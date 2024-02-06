<?php

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

use function Pest\Laravel\post;
use function Pest\Laravel\withoutExceptionHandling;

uses()->group('backend');

test('only users with role superadmin allow to activate a user channel ', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    $attributes = [
        'username' => $user->username,
    ];
    signInRole(Role::MODERATOR);
    post(route('channels.activate'), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    post(route('channels.activate'), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    post(route('channels.activate'), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::SUPERADMIN);
    post(route('channels.activate'), $attributes)->assertRedirect();
});

it('requires a username to activate a user channel', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    $attributes = ['username' => ''];
    signInRole(Role::SUPERADMIN);

    post(route('channels.activate'), $attributes)->assertSessionHasErrors(['username']);
});

it('requires the user to has a moderator role to activate his channel', function () {
    withoutExceptionHandling();
    $user = User::factory()->create();
    $attributes = ['username' => $user->username];
    signInRole(Role::SUPERADMIN);

    post(route('channels.activate'), $attributes);
})->throws(AuthorizationException::class, 'The user does not have a role: moderator');

it('a moderator can create/activate a channel for a user', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::MODERATOR);
    $attributes = ['username' => $user->username];
    signInRole(Role::SUPERADMIN);

    post(route('channels.activate'), $attributes)->assertRedirectToRoute('users.edit', $user);

    expect($user->channels()->count())->toBeGreaterThan(0);
});
