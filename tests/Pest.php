<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use Illuminate\Support\Facades\Config;
use function Pest\Laravel\actingAs;

uses(
    Tests\TestCase::class,
)->beforeEach(function () {
    Config::set('logging.channels.single.path', storage_path('logs/laravel.log'));
})->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/*
* Sings in a user
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
* Sings in a user with a specific role
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
