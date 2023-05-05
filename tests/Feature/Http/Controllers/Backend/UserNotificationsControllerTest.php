<?php

use App\Enums\ApplicationStatus;
use App\Enums\Role;
use App\Models\User;
use App\Notifications\NewAdminPortalNotification;
use Illuminate\Support\Facades\Notification;
use function Pest\Laravel\get;

uses()->group('backend');

it('has a controller for user notifications', function () {
    signInRole(Role::MODERATOR);

    get(route('user.notifications'))->assertOk();
});

it('displays all unread notifications for a user', function () {
    [$userA, $userB] = User::factory(2)->create();
    $userA->assignRole(Role::SUPERADMIN);
    $userA->refresh();
    Notification::send(User::role(Role::SUPERADMIN)->get(), new NewAdminPortalNotification($userB));

    signIn($userA);

    get(route('user.notifications'))->assertSee(ApplicationStatus::IN_PROGRESS);
});
