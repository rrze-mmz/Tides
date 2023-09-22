<?php

use App\Enums\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered only for superadmin', function () {
    $this->signInRole(Role::SUPERADMIN);

    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register only for superadmins', function () {
    $this->signInRole(Role::SUPERADMIN);

    $response = $this->post('/register', [
        'first_name' => 'Test User',
        'last_name' => 'User',
        'username' => 'test_user',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home'));
});
