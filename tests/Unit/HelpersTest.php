<?php


namespace Tests\Unit;

use App\Models\Clip;
use App\Services\WowzaService;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Support\Str;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_generic_poster_image_when_poster_file_path_is_null(): void
    {
        $clip = Clip::factory()->create();
        $this->assertEquals('/images/generic_clip_poster_image.png', fetchClipPoster($clip));
    }

    /** @test */
    public function it_fetches_a_clip_poster_image_when_poster_file_path_is_not_null(): void
    {
        $clip = Clip::factory()->create();

        $clip->posterImage = 'clip_'.$clip->id.'/2nUGJry2p8uPY9wjctep4PtlY9ABXLdJuCrbfMHS.jpg';
        $clip->save();

        $this->assertEquals('/thumbnails/'.$clip->posterImage, fetchClipPoster($clip));
    }

    /** @test */
    public function it_returns_clip_smil_file(): void
    {
        Storage::fake('videos');
        $wowzaService = app(WowzaService::class);

        $wowzaService->createSmilFile($clip = ClipFactory::withAssets(2)->create(['created_at' => '01.01.2022']));

        $this->assertEquals(
            'http://172.17.0.2:1935/vod/_definst_/2022/01/01/TIDES_ClipID_1/presenter.smil/playlist.m3u8',
            getClipSmilFile($clip, false)
        );
    }

    /** @test */
    public function it_returns_a_date_path(): void
    {
        $this->assertEquals(
            '/2021/01/13/TIDES_ClipID_1/',
            getClipStoragePath(Clip::factory()->create(['created_at' => '2021-01-13 15:38:51']))
        );
        $this->assertEquals(
            '/2021/01/01/TIDES_ClipID_2/',
            getClipStoragePath(Clip::factory()->create(['created_at' => '2021-01-01 15:38:51']))
        );
        $this->assertEquals(
            '/2021/12/27/TIDES_ClipID_3/',
            getClipStoragePath(Clip::factory()->create(['created_at' => '2021-12-27 15:38:51']))
        );
    }

    /** @test */
    public function it_returns_a_collection_with_all_dropzone_files(): void
    {
        $disk = Storage::fake('video_dropzone');

        $disk->putFileAs('', FileFactory::videoFile(), 'export_video_1080.mp4');

        $collection = fetchDropZoneFiles();

        $this->assertInstanceOf('Illuminate\Support\Collection', $collection);

        $this->assertTrue($collection->contains('name', 'export_video_1080.mp4'));
    }

    /** @test */
    public function it_ignores_hidden_files_in_dropzone(): void
    {
        $disk = Storage::fake('video_dropzone');

        $disk->putFileAs('', FileFactory::videoFile(), 'export_video_1080.mp4');
        $disk->putFileAs('', FileFactory::simpleFile(), '.DS_Store');

        $collection = fetchDropZoneFiles();

        $this->assertInstanceOf('Illuminate\Support\Collection', $collection);

        $this->assertTrue($collection->contains('name', 'export_video_1080.mp4'));
    }

    /** @test */
    public function it_returns_active_class_if_current_url_matches_href()
    {
        $this->get(route('dashboard'));

        $this->assertEquals('border-b-2', setActiveLink(route('dashboard')));
    }

    /** @test */
    public function it_has_a_generate_token_function(): void
    {
        $time = dechex(time());

        $token = md5('clip'.'1'.'1234qwER'.'127.0.0.1'.$time.'studon');

        $this->assertEquals($token, generateLMSToken(ClipFactory::create(['password' => '1234qwER']), $time));
    }

    /** @test */
    public function it_has_a_token_function_with_url_option_as_parameter(): void
    {
        $time = dechex(time());

        $token = md5('clip'.'1'.'1234qwER'.'127.0.0.1'.$time.'studon');

        $clip = ClipFactory::create(['password' => '1234qwER']);

        $url = '/protector/link/clip/1/'.$token.'/'.$time.'/studon';

        $this->assertNotEquals($url, generateLMSToken($clip, $time));
        $this->assertEquals($url, generateLMSToken($clip, $time, true));
    }

    /** @test */
    public function it_has_an_opencast_workflow_operation_percentage_step(): void
    {
        $this->assertEquals('24', opencastWorkflowOperationPercentage('Generating waveform'));
    }
}
