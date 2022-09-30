<?php

namespace Tests\Feature\Http\Controllers\Backend;

use App\Models\Series;
use App\Models\User;
use App\Notifications\SeriesMembershipAddUser;
use App\Notifications\SeriesOwnershipAddUser;
use App\Notifications\SeriesOwnershipRemoveUser;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    /** @test */
    public function an_assistant_cannot_see_change_series_ownership_button(): void
    {
        $series = Series::factory()->create(['owner_id' => null]);

        $this->signInRole('assistant');

        $this->get(route('series.edit', $series))->assertDontSee('Set series owner');
    }

    /** @test */
    public function an_admin_can_see_change_series_ownership_button(): void
    {
        $series = Series::factory()->create(['owner_id' => null]);

        $this->signInRole('admin');

        $this->get(route('series.edit', $series))->assertSee('Set series owner');
    }

    /** @test */
    public function an_assistant_is_not_allowed_to_change_series_ownership(): void
    {
        $series = Series::factory()->create(['owner_id' => null]);

        $this->signInRole('assistant');

        $this->post(route('series.ownership.change', $series))->assertForbidden();
    }

    /** @test */
    public function an_assistant_is_not_allowed_to_change_series_owner(): void
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $series = Series::factory()->create(['owner_id' => null]);

        $this->signInRole('assistant');

        $this->post(route('series.ownership.change', $series), ['userID' => $user->id])->assertForbidden();
    }

    /** @test */
    public function an_admin_is_allowed_to_change_series_ownership(): void
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $series = Series::factory()->create(['owner_id' => null]);

        $this->signInRole('admin');

        $this->post(route('series.ownership.change', $series), ['userID' => $user->id]);

        $series->refresh();

        $this->assertTrue($series->owner()->is($user));
    }

    /** @test */
    public function moderator_should_notified_for_ownership_change(): void
    {
        Notification::fake();

        $series = Series::factory()->create(['owner_id' => null]);

        $user = User::factory()->create();
        $user->assignRole('moderator');

        $this->signInRole('admin');

        $this->post(route('series.ownership.change', $series), ['userID' => $user->id]);

        Notification::assertSentTo(
            [$user],
            SeriesOwnershipAddUser::class
        );
    }

    /** @test */
    public function old_owner_should_be_notified_on_ownership_change(): void
    {
        Notification::fake();
        $firstOwner = User::factory()->create();

        $series = Series::factory()->create(['owner_id' => $firstOwner->id]);

        $user = User::factory()->create();
        $user->assignRole('moderator');

        $this->signInRole('admin');

        $this->post(route('series.ownership.change', $series), ['userID' => $user->id]);

        Notification::assertSentTo(
            [$firstOwner],
            SeriesOwnershipRemoveUser::class
        );

        Notification::assertSentTo(
            [$user],
            SeriesOwnershipAddUser::class
        );
    }
}
