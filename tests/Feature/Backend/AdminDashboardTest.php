<?php

namespace Tests\Feature;

use App\Models\Clip;
use App\Models\User;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_authenticated_user_can_view_the_dashboard()
    {
        $this->get('/admin/dashboard')->assertRedirect('login');

        $this->actingAs(User::factory()->create());

        $this->get('admin/dashboard')->assertStatus(200);
    }

    /** @test */
    public function should_display_clips()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get('/admin/dashboard')->assertSee($clip->title);

    }
}
