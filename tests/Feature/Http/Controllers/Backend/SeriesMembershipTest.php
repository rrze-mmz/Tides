<?php

use App\Enums\Role;
use App\Models\Series;
use App\Models\User;
use App\Notifications\SeriesMembershipAddUser;
use App\Notifications\SeriesOwnershipAddUser;
use App\Notifications\SeriesOwnershipRemoveUser;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('guests are not allowed to add users in a series', function () {
    $nonSeriesUser = User::factory()->create();
    $series = SeriesFactory::create();

    post(route('series.membership.addUser', $series), [$nonSeriesUser->id])->assertRedirect(route('login'));
});

test('a moderator is not allowed to add users to a non owned series', function () {
    $series = SeriesFactory::create();
    signInRole(Role::MODERATOR);

    post(route('series.membership.addUser', $series), ['userID' => auth()->user()->id])->assertForbidden();
});

test('a series owner cannot add simple users', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    $user = User::factory()->create();

    post(route('series.membership.addUser', $series), ['userID' => $user->id])
        ->assertSessionHasErrors('userID');
});

test('a series owner can add a user with moderator role', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);

    post(route('series.membership.addUser', $series), ['userID' => $user->id])
        ->assertRedirect(route('series.edit', $series));
    get(route('series.edit', $series))->assertSee($user->getFullNameAttribute());
});

test('a series member cannot add users to series', function () {
    $series = SeriesFactory::create();
    $user = $series->addMember(User::factory()->create()->assignRole(Role::MODERATOR));
    $this->signIn($user);

    get(route('series.edit', $series))->assertDontSee('Add a moderator as series member');
});

it('sends an email to user after being added in series', function () {
    Notification::fake();
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    post(route('series.membership.addUser', $series), ['userID' => $user->id]);

    Notification::assertSentTo(
        [$user],
        SeriesMembershipAddUser::class
    );
});

test('a series owner can remove a member from series', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    expect($series->members()->count())->toEqual(0);

    $user = $series->addMember(User::factory()->create()->assignRole(Role::MODERATOR));
    expect($series->members()->count())->toEqual(1);

    post(route('series.membership.removeUser', $series), ['userID' => $user->id]);
    expect($series->members()->count())->toEqual(0);
});

test('an assistant cannot see change series ownership button', function () {
    $series = Series::factory()->create(['owner_id' => null]);
    signInRole(Role::ASSISTANT);

    get(route('series.edit', $series))->assertDontSee('Set series owner');
});

test('an admin can see change series ownership button', function () {
    $series = Series::factory()->create(['owner_id' => null]);
    signInRole(Role::ADMIN);

    get(route('series.edit', $series))->assertSee('Set series owner');
});

test('an assistant is not allowed to change series ownership', function () {
    $series = Series::factory()->create(['owner_id' => null]);
    signInRole(Role::ASSISTANT);

    post(route('series.ownership.change', $series))->assertForbidden();
});

test('an assistant is not allowed to change series owner', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    $series = Series::factory()->create(['owner_id' => null]);
    signInRole(Role::ASSISTANT);

    post(route('series.ownership.change', $series), ['userID' => $user->id])->assertForbidden();
});

test('an admin is allowed to change series ownership', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    $series = Series::factory()->create(['owner_id' => null]);
    signInRole(Role::ADMIN);
    post(route('series.ownership.change', $series), ['userID' => $user->id]);
    $series->refresh();

    expect($series->owner()->is($user))->toBeTrue();
});

test('moderator should notified for ownership change', function () {
    Notification::fake();
    $series = Series::factory()->create(['owner_id' => null]);
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    signInRole(Role::ADMIN);
    post(route('series.ownership.change', $series), ['userID' => $user->id]);

    Notification::assertSentTo(
        [$user],
        SeriesOwnershipAddUser::class
    );
});

test('old owner should be notified on ownership change', function () {
    Notification::fake();
    $firstOwner = User::factory()->create();
    $series = Series::factory()->create(['owner_id' => $firstOwner->id]);
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    signInRole(Role::ADMIN);
    post(route('series.ownership.change', $series), ['userID' => $user->id]);

    Notification::assertSentTo(
        [$firstOwner],
        SeriesOwnershipRemoveUser::class
    );
    Notification::assertSentTo(
        [$user],
        SeriesOwnershipAddUser::class
    );
});
