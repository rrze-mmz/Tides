<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $clip = Clip::factory()->create();

        $this->assertEquals('/clips/'.$clip->slug, $clip->path());
    }

    /** @test */
    public function it_has_a_slug_route()
    {
        $clip = Clip::factory()->create();

        $this->get($clip->path())->assertStatus(200);
    }

    /** @test */
    public function it_has_a_set_slug_fuction()
    {
        $clip = Clip::factory()->create();

        $this->assertEquals($clip->slug, Str::slug($clip->title));
    }

    /** @test */
    public function it_has_many_assets()
    {
        $clip = Clip::factory()->create();

        $assets = Asset::factory(2)->create(['clip_id'=> $clip->id]);

        $this->assertEquals(2, $clip->assets()->count());
    }
}
