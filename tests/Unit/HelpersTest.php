<?php

namespace Tests\Unit;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function get_generic_poster_image_when_poster_file_path_is_null()
    {
        $this->assertEquals('/images/generic_clip_poster_image.png', fetchClipPoster(null));
    }

    /** @test */
    public function get_generic_poster_image_when_poster_file_path_is_not_null()
    {
        $this->assertEquals('/thumbnails/1_poster.png', fetchClipPoster('1_poster.png'));
    }
}
