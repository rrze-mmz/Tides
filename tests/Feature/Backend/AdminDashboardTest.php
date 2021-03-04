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

    public function a_visitor_visiting_dashboard_should_redirect_to_login_page()
    {
        $this->get('/admin/dashboard')->assertRedirect('login');
    }
    /** @test */
    public function an_authenticated_user_can_access_dashboard_()
    {
        $this->actingAs(User::factory()->create());

        $this->get('admin/dashboard')->assertStatus(200);
    }

    /** @test */
    public function should_display_clips_if_existing()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get('/admin/dashboard')->assertSee($clip->title);
    }

    /** @test */
    public function should_display_string__if_no_clip_exist()
    {
        $this->signIn();

        $this->get('/admin/dashboard')->assertSee('No clips found');
    }
}
