<?php

use App\Enums\Role;
use App\Models\User;

uses()->group('frontend');

it('will send a notification to all superadmins if a user applies for admin portal', function () {
    [$userA, $userB] = User::factory(2)->create();

    $userA->assignRole(Role::SUPERADMIN);
    $userB->assignRole(Role::SUPERADMIN);

    signInRole(Role::MEMBER);
    acceptUseTerms();

    expect($userA->notifications()->count())->toBe(0);
    acceptAdminPortalUseTerms();
    expect($userA->notifications()->count())->toBe(1);
    expect($userB->notifications()->count())->toBe(1);
});
