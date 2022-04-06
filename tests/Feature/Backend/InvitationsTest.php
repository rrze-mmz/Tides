<?php

namespace Tests\Feature\Backend;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\SeriesFactory;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_are_not_allowed_to_invite_users_in_a_series(): void
    {
        $nonSeriesUser = User::factory()->create();
        $series = SeriesFactory::create();

        $this->post(route('series.invitations', $series), [$nonSeriesUser->id])->assertRedirect(route('login'));
    }

    /** @test */
    public function a_moderator_is_not_allowed_to_invite_users_to_a_non_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole('moderator');

        $this->post(route('series.invitations', $series), ['userID' => auth()->user()->id])->assertForbidden();
    }

    /** @test */
    public function a_series_owner_cannot_invite_simple_users(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $user = User::factory()->create();

        $this->post(route('series.invitations', $series), ['userID' => $user->id])->assertSessionHasErrors('userID');
    }

    /** @test */
    public function a_series_owner_can_invite_user_with_moderator_role(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $this->post(route('series.invitations', $series), ['userID' => $user->id])
            ->assertRedirect(route('series.edit', $series));

        $this->get(route('series.edit', $series))->assertSee($user->getFullNameAttribute());
    }
}
