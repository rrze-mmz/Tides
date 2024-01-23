<?php

use App\Enums\Role;

use function Pest\Laravel\get;

uses()->group('backend');

it('denies access to simple user to create a channel', function () {
    get(route('channels.index'))->assertRedirect(route('login'));
    signInRole(Role::USER);
    get(route('channels.index'))->assertForbidden();
});

it('allows access to moderators to create a channel', function () {
    signInRole(Role::MODERATOR);

    get(route('channels.index'))->assertOk()
        ->assertViewIs('backend.channels.index')
        ->assertViewHas(['channels']);
});

it('asks to enable channels if moderator has no active channel', function () {
    signInRole(Role::MODERATOR);

    get(route('channels.index'))->assertSee(__('cha'));
});
