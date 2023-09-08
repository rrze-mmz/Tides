<?php

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;
use function Pest\Laravel\withoutVite;

uses(
    Tests\TestCase::class,
    RefreshDatabase::class
)->beforeEach(function () {
    withoutVite();
    Config::set('logging.channels.single.path', storage_path('logs/laravel.log'));
})->in('Feature', 'Unit');

expect()->extend('toBeOn1e', function () {
    return $this->toBe(1);
});

/*
* Sign's in a user
* @param User|null $user
* @return User
*/
function signIn(User $user = null): User
{
    $user = $user ?: User::factory()->create();
    actingAs($user);

    return $user;
}

/*
* Sign's in a user with a specific role
*
* @param string $role
* @return User
*/
function signInRole(Role $role): User
{

    $user = User::factory()->create();
    $user->assignRole($role);

    actingAs($user);

    return $user;
}

function acceptUseTerms(): void
{
    put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on']);
}

function acceptAdminPortalUseTerms(): void
{
    put(route('frontend.admin.portal.accept.use.terms'), ['accept_use_terms' => 'on']);
}
