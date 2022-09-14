<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Slides\Saml2\Models\Tenant;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_login_selection_page_with_options(): void
    {
        Tenant::create([
            'key'            => 'test',
            'uuid'           => Str::uuid(),
            'idp_entity_id'  => 'WebSSO',
            'idp_login_url'  => 'test.com/login',
            'idp_logout_url' => 'test.com/logout',
            'idp_x509_cert'  => 'JDKJGkljdfWKJSDFjkj',
            'metadata'       => 'test',
        ]);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('WebSSO');
    }

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
