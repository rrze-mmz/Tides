<?php

namespace Tests\Feature\Backend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_not_authorized_for_logged_in_users(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');

        $this->signIn();

        $this->get('admin/users')->assertStatus(403);
    }

    /** @test */
    public function it_renders_a_datatable_for_users_with_admin_role(): void
    {
        $this->signInAdmin();

        $this->get('admin/users')->assertStatus(200);
    }
}
