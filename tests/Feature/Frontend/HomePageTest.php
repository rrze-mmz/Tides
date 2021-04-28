<?php


namespace Tests\Feature\Frontend;

use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /** @test */
    public function should_show_project_name()
    {
        $this->get(route('home'))->assertSee('Tides');
    }

    /** @test */
    public function should_not_display_series_with_clips_without_assets()
    {
        $series = SeriesFactory::withClips(1)->create();

        $this->get(route('home'))->assertDontSee($series->title);
    }
    /** @test */
    public function should_display_latest_series_with_clips_that_have_assets()
    {
        $series = SeriesFactory::create();

        $clip = ClipFactory::withAssets(1)->create();

        $series->clips()->save($clip);

        $this->get(route('home'))->assertSee($series->title);
    }

    /** @test */
    public function should_not_display_clips_without_assets()
    {
        $clip = ClipFactory::create();

        $this->get(route('home'))->assertDontSee($clip->title);
    }

    /** @test */
    public function should_not_display_clips_that_belong_to_a_series()
    {
        $series = SeriesFactory::withClips(1)->create();

        $this->get(route('home'))->assertDontSee($series->clips()->first()->title);
    }


    /** @test */
    public function should_display_clips_with_video_assets()
    {
        $clip = ClipFactory::withAssets(1)->create();

        $this->get(route('home'))->assertSee($clip->title);
    }
}
