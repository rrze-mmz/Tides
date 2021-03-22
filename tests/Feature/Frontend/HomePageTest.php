<?php

namespace Tests\Feature\Frontend;

use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function should_show_project_name()
    {
        $this->get('/')->assertStatus(200)->assertSee('Tides');
    }

    /** @test */
    public function should_not_display_clips_without_assets()
    {
        $clip = ClipFactory::create();

        $this->get('/')->assertDontSee($clip->title);
    }

    /** @test */
    public function should_display_clips_with_video_assets()
    {
        $clip = ClipFactory::withAssets(1)->create();

        $this->get('/')->assertSee($clip->title);
    }
}
