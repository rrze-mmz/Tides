<?php

namespace Tests\Feature\Auth;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered_only_for_superadmin()
    {
        $this->signInRole(Role::SUPERADMIN);

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_only_for_superadmins()
    {
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
    }
}
