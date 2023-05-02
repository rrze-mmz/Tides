<?php

use App\Enums\Role;
use function Pest\Laravel\get;

uses()->group('backend');

it('has a controller for user notifications', function () {
    signInRole(Role::MODERATOR);

    get(route('user.notifications'))->assertOk();
});
