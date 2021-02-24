<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{

    /** @test */
    public function start_page_should_show_project_name()
    {
        $this->get('/')->assertSee('Tides');
    }
}
