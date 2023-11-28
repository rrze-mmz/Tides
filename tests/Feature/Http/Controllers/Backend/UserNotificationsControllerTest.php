<?php

use App\Enums\ApplicationStatus;
use App\Enums\Role;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use App\Notifications\NewAdminPortalNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses()->group('backend');

it('has a controller for user notifications', function () {
    signInRole(Role::MODERATOR);

    get(route('user.notifications'))
        ->assertViewIs('backend.users.notifications.index')
        ->assertOk();
});

it('displays all unread notifications for a user', function () {
    [$userA, $userB] = User::factory(2)->create();
    $userA->assignRole(Role::SUPERADMIN);
    $userA->refresh();
    Notification::send(User::role(Role::SUPERADMIN)->get(), new NewAdminPortalNotification($userB));

    signIn($userA);

    get(route('user.notifications'))->assertSee(ApplicationStatus::IN_PROGRESS);
});

it('shows the application status for user application notifications', function () {
    $user = signInRole(Role::SUPERADMIN);
    Notification::send($user, new NewAdminPortalNotification(User::factory()->create()));

    $notification = NotificationModel::all()->first();
    get(route('user.notifications'))
        ->assertSee($notification->id)
        ->assertSee($notification->data['application_status']);
});

it('displays which superadmin processed the application in notifications index page', function () {
    [$superAdminA, $superadminB] = User::factory(2)
        ->create(['last_name' => 'LastnameUser']) // faker names like 0'Reilly fail the test
        ->each(fn ($user) => $user->assignRole(Role::SUPERADMIN));
    $memberUser = User::factory()->create()->assignRole(Role::MEMBER);

    signIn($memberUser);
    put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on']);
    put(route('frontend.admin.portal.accept.use.terms'), ['accept_use_terms' => 'on']);
    auth()->logout();

    signIn($superAdminA);
    post(route('admin.portal.application.grant', [
        'username' => $memberUser->username,
    ]));
    auth()->logout();

    signIn($superadminB);
    get(route('user.notifications'))
        ->assertSee('Application processed by '.$superAdminA->getFullNameAttribute(), false);
});

it('displays a delete button for notification', function () {
    $user = signInRole(Role::MODERATOR);
    Notification::send($user, new NewAdminPortalNotification(User::factory()->create()));

    get(route('user.notifications'))
        ->assertSee($user->notifications->first()->data)
        ->assertSee('Delete all selected notifications');
});

it('has an error if notification is selected to be deleted', function () {
    $user = signInRole(Role::MODERATOR);
    Notification::send($user, new NewAdminPortalNotification(User::factory()->create()));

    delete(route('user.notifications.delete', ['selected_notifications' => []]))->assertSessionHasErrors();
});

it('has an error if a user tries to delete a notification that belongs to another user', function () {
    [$superAdminA, $superadminB] = User::factory(2)
        ->create()
        ->each(fn ($user) => $user->assignRole(Role::SUPERADMIN));
    Notification::send($superAdminA, new NewAdminPortalNotification(User::factory()->create()));
    Notification::send($superadminB, new NewAdminPortalNotification(User::factory()->create()));

    $superAdminANotification = $superAdminA->notifications->first();

    signIn($superadminB);

    delete(route('user.notifications.delete', ['selected_notifications' => [$superAdminANotification->id]]))
        ->assertSessionHasErrors('selected_notifications.*');
});

it('the selected notifications for a user', function () {
    $user = User::factory()->create()->assignRole(Role::SUPERADMIN);
    Notification::send($user, new NewAdminPortalNotification(User::factory()->create()));
    Notification::send($user, new NewAdminPortalNotification(User::factory()->create()));

    signIn($user);

    delete(route('user.notifications.delete', [
        'selected_notifications' => $user->notifications->pluck('id')->toArray(),
    ]))->assertSessionDoesntHaveErrors();

    $user->refresh();

    expect($user->notifications->toArray())->toBeEmpty();
});
