<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use function Pest\Laravel\actingAs;

uses(
    Tests\TestCase::class,
    RefreshDatabase::class
)->beforeEach(function () {
    Config::set('logging.channels.single.path', storage_path('logs/laravel.log'));
})->in('Feature', 'Unit');

expect()->extend('toBeOne', function () {
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
function signInRole(string $role = ''): User
{
    $user = User::factory()->create();
    $user->assignRole($role);
    actingAs($user);

    return $user;
}
