<?php


namespace Tests\Feature\Backend;

use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_should_not_be_accessed_by_a_user()
    {
        $this->get(route('dashboard'))->assertRedirect('login');
    }

    /** @test */
    public function it_should_be_accessed_by_authenticated_user()
    {
        $this->signIn();

        $this->get('admin/dashboard')
            ->assertStatus(200)
            ->assertViewIs('backend.dashboard.index');
    }

    /** @test */
    public function it_should_display_clips_if_existing()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get(route('dashboard'))->assertSee($clip->title);
    }

    /** @test */
    public function it_should_display_string__if_no_clip_exist()
    {
        $this->signIn();

        $this->get(route('dashboard'))->assertSee('No clips found');
    }

    /** @test */
    public function it_display_only_clips_created_by_the_logged_in_user()
    {
        $bob = $this->signIn();

        ClipFactory::create();

        $this->get(route('dashboard'))->assertSee('No clips found');

        $newClip = ClipFactory::ownedBy($bob)->create();

        $this->get(route('dashboard'))->assertSee($newClip->title);
    }

    /** @test */
    public function it_should_list_all_files_in_drop_zone()
    {
        $this->signIn();

        Storage::fake('video_dropzone');

        Storage::disk('video_dropzone')->put('test.pdf', 'some non-pdf content');

        $this->get(route('dashboard'))->assertSee('test.pdf');
    }
}
