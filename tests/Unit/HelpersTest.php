<?php


namespace Tests\Unit;

use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HelpersTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_generic_poster_image_when_poster_file_path_is_null(): void
    {
        $this->assertEquals('/images/generic_clip_poster_image.png', fetchClipPoster());
    }

    /** @test */
    public function it_fetches_a_clip_poster_image_when_poster_file_path_is_not_null(): void
    {
        $this->assertEquals('/thumbnails/1_poster.png', fetchClipPoster('1_poster.png'));
    }

    /** @test */
    public function it_returns_a_date_path(): void
    {
        $this->assertEquals('/2021/01/13/TIDES_Clip_ID_1',
            getClipStoragePath(Clip::factory()->create(['created_at' => '2021-01-13 15:38:51'])));
        $this->assertEquals('/2021/01/01/TIDES_Clip_ID_2',
            getClipStoragePath(Clip::factory()->create(['created_at' => '2021-01-01 15:38:51'])));
        $this->assertEquals('/2021/12/27/TIDES_Clip_ID_3',
            getClipStoragePath(Clip::factory()->create(['created_at' => '2021-12-27 15:38:51'])));
    }

    /** @test */
    public function it_returns_a_collection_with_all_dropzone_files(): void
    {
        $disk = Storage::fake('video_dropzone');

        $disk->putFileAs('', File::create('export_video.mp4', 1000), 'export_video.mp4');

        $collection = fetchDropZoneFiles();

        $this->assertInstanceOf('Illuminate\Support\Collection', $collection);

        $this->assertTrue($collection->contains('name','export_video.mp4'));
    }
}
