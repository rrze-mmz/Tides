<?php

namespace Tests\Unit;

use App\Enums\Acl;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Series;
use App\Models\User;
use App\Services\WowzaService;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $clip = ClipFactory::withAssets(2)->create();

        $this->assertEquals(
            "/thumbnails/previews-ng/{$clip->assets()->first()->player_preview}",
            fetchClipPoster($clip)
        );
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

        $this->assertEquals('active-nav-link opacity-100', setActiveLink(route('dashboard')));
    }

    /** @test */
    public function it_has_a_get_access_token_function(): void
    {
        $time = dechex(time());
        $client = Acl::LMS->lower();
        $token = md5('clip'.'1'.'1234qwER'.'127.0.0.1'.$time.$client);

        $this->assertEquals($token, getAccessToken(ClipFactory::create(['password' => '1234qwER']), $time, $client));
    }

    /** @test */
    public function it_has_a_get_url_token_type_function(): void
    {
        $this->assertEquals('series', getUrlTokenType('course'));
        $this->assertEquals('series', getUrlTokenType('series'));
        $this->assertEquals('clip', getUrlTokenType('clip'));

        $this->expectException(NotFoundHttpException::class);
        getUrlTokenType('test');
    }

    /** @test */
    public function it_has_a_get_url_client_type_function(): void
    {
        $this->assertEquals(Acl::LMS->lower(), getUrlClientType('studon'));
        $this->assertEquals(Acl::LMS->lower(), getUrlClientType('lms'));
        $this->assertEquals(Acl::PASSWORD->lower(), getUrlClientType('password'));

        $this->expectException(NotFoundHttpException::class);
        getUrlClientType('test');
    }

    /** @test */
    public function it_has_a_set_session_access_token_function(): void
    {
        $time = dechex(time());
        $client = Acl::LMS->lower();
        $token = md5('clip'.'1'.'1234qwER'.'127.0.0.1'.$time.$client);
        $clip = Clip::factory()->create();
        setSessionAccessToken($clip, $token, $time, $client);

        $this->get(route('frontend.clips.show', $clip))
            ->assertSessionHas([
                "clip_{$clip->id}_token" => $token,
                "clip_{$clip->id}_time" => $time,
                "clip_{$clip->id}_client" => $client,
            ]);
    }

    /** @test */
    public function it_compares_token(): void
    {
        $time = dechex(time());
        $client = Acl::LMS->lower();
        $token = md5('clip'.'1'.'1234qwER'.'127.0.0.1'.$time.$client);
        $clip = Clip::factory()->create();

        //session token doesn't exist
        $this->assertFalse(checkAccessToken($clip));
        session()->put([
            "clip_{$clip->id}_token" => $token,
            "clip_{$clip->id}_time" => $time,
            "clip_{$clip->id}_client" => $client,
        ]);
        //session token exists but clip has no password
        $this->assertFalse(checkAccessToken($clip));

        $clip->password = '1234qwER';
        $clip->save();

        //session token exists and clip has password
        $this->assertTrue(checkAccessToken($clip));

        session()->flush();

        //test again clip series token
        $time = dechex(time());
        $client = Acl::LMS->lower();
        $seriesToken = md5('series'.'1'.'1234QWer'.'127.0.0.1'.$time.$client);
        $series = Series::factory()->create(['password' => '1234QWer']);

        session()->put([
            "series_{$series->id}_token" => $seriesToken,
            "series_{$series->id}_time" => $time,
            "series_{$series->id}_client" => $client,
        ]);

        $this->assertFalse(checkAccessToken($clip));

        $clip->series_id = $series->id;
        $clip->save();

        $clip->refresh();
        $this->assertTrue(checkAccessToken($clip));
    }

    /** @test */
    public function it_has_a_token_function_with_url_option_as_parameter(): void
    {
        $time = dechex(time());
        $client = Acl::LMS->lower();
        $token = md5('clip'.'1'.'1234qwER'.'127.0.0.1'.$time.$client);
        $clip = ClipFactory::create(['password' => '1234qwER']);

        $url = "/protector/link/clip/1/{$token}/{$time}/{$client}";

        $this->assertNotEquals($url, getAccessToken($clip, $time, $client));
        $this->assertEquals($url, getAccessToken($clip, $time, $client, true));
    }

    /** @test */
    public function it_has_an_opencast_workflow_operation_percentage_step(): void
    {
        $this->assertEquals('24', opencastWorkflowOperationPercentage('Generating waveform'));
    }

    /** @test */
    public function it_has_a_helper_for_returning_an_assets_extension(): void
    {
        $this->assertEquals('mp4', getFileExtension(Asset::factory()->create(['original_file_name' => 'test.mp4'])));
        $this->assertEquals(
            'mp4',
            getFileExtension(Asset::factory()->create(['original_file_name' => 'test.340.mp4']))
        );
    }

    /** @test */
    public function it_returns_a_users_name_for_an_opencast_role(): void
    {
        $user = User::factory()->create();
        $opencastUserRole = 'ROLE_USER_'.strtoupper($user->username);

        $this->assertEquals(
            $user->getFullNameAttribute(),
            findUserByOpencastRole($opencastUserRole)->getFullNameAttribute()
        );
    }
}
