<?php


namespace Tests\Feature\Backend;

use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_should_not_be_accessed_by_a_visitor(): void
    {
        $this->get(route('dashboard'))->assertRedirect('login');
    }

    /** @test */
    public function it_should_be_accessed_by_authenticated_user(): void
    {
        $this->signIn();

        $this->get('admin/dashboard')
            ->assertStatus(200)
            ->assertViewIs('backend.dashboard.index');
    }

    /** @test */
    public function it_should_display_an_add_series_button(): void
    {
        $this->signIn();

        $this->get(route('dashboard'))->assertSee('New series');
    }

    /** @test */
    public function it_should_display_an_add_clip_button(): void
    {
        $this->signIn();

        $this->get(route('dashboard'))->assertSee('New clip');
    }

    /** @test */
    public function it_should_display_info_if_no_series_exist(): void
    {
        $this->signIn();

        $this->get(route('dashboard'))->assertSee('No series found');
    }

    /** @test */
    public function it_should_display_info_if_no_clip_exist(): void
    {
        $this->signIn();

        $this->get(route('dashboard'))->assertSee('No clips found');
    }

    /** @test */
    public function it_display_user_series(): void
    {
        $user = $this->signIn();

        SeriesFactory::create();

        $this->get(route('dashboard'))->assertSee('No series found');

        $userSeries = SeriesFactory::ownedBy($user)->create();

        $this->get(route('dashboard'))->assertSee($userSeries->title);
    }

    /** @test */
    public function it_display_user_clips_(): void
    {
        $user = $this->signIn();

        ClipFactory::create();

        $this->get(route('dashboard'))->assertSee('No clips found');

        $userClip = ClipFactory::ownedBy($user)->create();

        $this->get(route('dashboard'))->assertSee($userClip->title);
    }

    /** @test */
    public function it_should_list_all_files_in_drop_zone(): void
    {
        $this->signIn();

        Storage::fake('video_dropzone');

        Storage::disk('video_dropzone')->put('test.pdf', 'some non-pdf content');

        $this->get(route('dashboard'))->assertSee('test.pdf');
    }

    /** @test */
    public function it_show_sidebar_menu_items_for_admins()
    {
        $this->signInAdmin();

        $this->get(route('dashboard'))->assertSee('Opencast')
        ->assertSee('Users');
    }

    /** @test */
    public function it_hides_sidebar_menu_items_for_logged_in_users()
    {
        $this->signIn();

        $this->get(route('dashboard'))->assertDontSee('Opencast')
        ->assertDontSee('Users');
    }
}
