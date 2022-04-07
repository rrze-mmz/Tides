<?php

namespace Tests\Feature\Backend;

use App\Models\User;
use App\Notifications\SeriesMembershipAddUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SeriesMembershipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_are_not_allowed_to_add_users_in_a_series(): void
    {
        $nonSeriesUser = User::factory()->create();
        $series = SeriesFactory::create();

        $this->post(route('series.membership.addUser', $series), [$nonSeriesUser->id])->assertRedirect(route('login'));
    }

    /** @test */
    public function a_moderator_is_not_allowed_to_add_users_to_a_non_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole('moderator');

        $this->post(route('series.membership.addUser', $series), ['userID' => auth()->user()->id])->assertForbidden();
    }

    /** @test */
    public function a_series_owner_cannot_add_simple_users(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $user = User::factory()->create();

        $this->post(route('series.membership.addUser', $series), ['userID' => $user->id])
            ->assertSessionHasErrors('userID');
    }

    /** @test */
    public function a_series_owner_can_add_a_user_with_moderator_role(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $this->post(route('series.membership.addUser', $series), ['userID' => $user->id])
            ->assertRedirect(route('series.edit', $series));

        $this->get(route('series.edit', $series))->assertSee($user->getFullNameAttribute());
    }

    /** @test */
    public function a_series_member_cannot_add_users_to_series(): void
    {
        $series = SeriesFactory::create();

        $user = $series->addMember(User::factory()->create()->assignRole('moderator'));

        $this->signIn($user);

        $this->get(route('series.edit', $series))->assertDontSee('Add a moderator as series member');
    }

    /** @test */
    public function it_sends_an_email_to_user_after_being_added_in_series(): void
    {
        Notification::fake();

        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $this->post(route('series.membership.addUser', $series), ['userID' => $user->id]);

        Notification::assertSentTo(
            [$user],
            SeriesMembershipAddUser::class
        );
    }

    /** @test */
    public function a_series_owner_can_remove_a_member_from_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();
        $this->assertEquals(0, $series->members()->count());

        $user = $series->addMember(User::factory()->create()->assignRole('moderator'));

        $this->assertEquals(1, $series->members()->count());

        $this->post(route('series.membership.removeUser', $series), ['userID' => $user->id]);

        $this->assertEquals(0, $series->members()->count());
    }
}
