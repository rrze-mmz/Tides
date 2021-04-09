<?php

namespace Tests\Unit;

use App\Models\Clip;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $series = Series::factory()->create();

        $this->assertEquals('/series/'.$series->slug, $series->path());
    }

    /** @test */
    public function it_has_an_admin_path()
    {
        $series = Series::factory()->create();

        $this->assertEquals('/admin/series/'.$series->slug, $series->adminPath());
    }

    /** @test */
    public function it_has_a_slug_route()
    {
        $series = Series::factory()->create();

        $this->get($series->path())->assertStatus(200);

    }

    /** @test */
    public function it_has_a_unique_slug()
    {
        $seriesA = Series::factory()->create(['title'=>'A test title','slug'=>'A test title']);

        $seriesB = Series::factory()->create(['title'=>'A test title','slug'=>'A test title']);

        $this->assertNotEquals($seriesA->slug, $seriesB->slug);
    }

    /** @test */
    public function it_has_many_clips()
    {
        $series = Series::factory()->create();

        Clip::factory(2)->create(['series_id'=> $series->id]);

        $this->assertEquals(2, $series->clips()->count());
    }

}
