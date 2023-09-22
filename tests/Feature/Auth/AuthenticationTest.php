<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Str;
use Slides\Saml2\Models\Tenant;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('has a login selection page with options', function () {
    Tenant::create([
        'key' => 'test',
        'uuid' => Str::uuid(),
        'idp_entity_id' => 'WebSSO',
        'idp_login_url' => 'test.com/login',
        'idp_logout_url' => 'test.com/logout',
        'idp_x509_cert' => 'JDKJGkljdfWKJSDFjkj',
        'metadata' => 'test',
    ]);

    $this->get(route('login'))
        ->assertOk()
        ->assertSee('WebSSO');
});

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'username' => $user->username,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'username' => $user->username,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
