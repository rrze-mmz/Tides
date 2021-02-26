<?php

namespace Tests\Feature;

use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_page_should_show_project_name()
    {

        $this->get('/')->assertStatus(200)->assertSee('Tides');
    }

    /** @test */
    public function home_page_should_display_clip_if_any()
    {
        $this->get('/')->assertSee('No clips found');

        $clip = Clip::factory()->create();

       $this->get('/')->assertSee($clip->title);

    }
}
