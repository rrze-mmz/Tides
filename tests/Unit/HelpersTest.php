<?php

namespace Tests\Unit;

use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_generic_poster_image_when_poster_file_path_is_null()
    {
        $this->assertEquals('/images/generic_clip_poster_image.png', fetchClipPoster());
    }

    /** @test */
    public function it_fetches_a_clip_poster_image_when_poster_file_path_is_not_null()
    {
        $this->assertEquals('/thumbnails/1_poster.png', fetchClipPoster('1_poster.png'));
    }

    /** @test */
    public function it_returns_a_date_path()
    {
        $this->assertEquals('/2021/01/13/TIDES_Clip_ID_1',getClipStoragePath(Clip::factory()->create(['created_at' => '2021-01-13 15:38:51'])));
        $this->assertEquals('/2021/01/01/TIDES_Clip_ID_2',getClipStoragePath(Clip::factory()->create(['created_at' => '2021-01-01 15:38:51'])));
        $this->assertEquals('/2021/12/27/TIDES_Clip_ID_3',getClipStoragePath(Clip::factory()->create(['created_at' => '2021-12-27 15:38:51'])));
    }
}
