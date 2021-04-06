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
    public function it_has_many_clips()
    {
        $series = Series::factory()->create();

        Clip::factory(2)->create(['series_id'=> $series->id]);

        $this->assertEquals(2, $series->clips()->count());
    }
}
