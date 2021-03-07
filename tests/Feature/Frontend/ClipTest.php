<?php

namespace Tests\Feature\Frontend;

use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_visitor_can_view_a_clip()
    {
        $clip = Clip::factory()->create();

        $this->get($clip->path())->assertSee($clip->title);
    }
}
