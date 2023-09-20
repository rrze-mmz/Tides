<?php

use App\Enums\Role;
use App\Models\Activity;

use function Pest\Laravel\get;

uses()->group('backend');

it('denies access to activities index to visitors', function () {
    get(route('activities.index'))->assertRedirect(route('login'));
});

it('denies access to activities index to logged in users', function () {
    signInRole(Role::USER);
    get(route('activities.index'))->assertForbidden();
});

it('denies access to activities index to moderators', function () {
    signInRole(Role::MODERATOR);
    get(route('activities.index'))->assertForbidden();
});

it('allows access to activities index to admins', function () {
    Activity::factory(3)->create();
    signInRole(Role::ADMIN);

    get(route('activities.index'))->assertOk();
});
