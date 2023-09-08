<?php

namespace Tests\Unit\Services;

use App\Enums\Content;
use App\Models\Asset;
use App\Models\Clip;
use App\Services\WowzaService;
use Facades\Tests\Setup\ClipFactory;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;
use Tests\Setup\WorksWithWowzaClient;
use Tests\TestCase;

class WowzaServiceTest extends TestCase
{
    use RefreshDatabase;
    use WorksWithOpencastClient;
    use WorksWithWowzaClient;

    private WowzaService $wowzaService;

    private MockHandler $mockHandler;

    private Asset $asset;

    private Clip $clip;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->swapWowzaClient();

        $this->wowzaService = app(WowzaService::class);

        $this->clip = ClipFactory::create();
    }

    /** @test */
    public function it_returns_default_values_if_guzzle_response_is_empty(): void
    {
        $this->mockHandler->append($this->mockServerNotAvailable());

        $results = $this->wowzaService->getHealth();

        $this->assertEquals('failed', $results['status']);
    }

    /** @test */
    public function it_creates_a_wowza_presenter_smil_file(): void
    {
        Storage::fake('videos');

        $this->clip->addAsset([
            'disk' => 'videos',
            'original_file_name' => 'test.mp4',
            'path' => '/2021/01/01/TEST/',
            'duration' => 300,
            'guid' => Str::uuid(),
            'width' => 1920,
            'height' => 1080,
            'type' => Content::PRESENTER(),
        ]);

        $this->wowzaService->createSmilFile($this->clip);

        Storage::disk('videos')->assertExists(getClipStoragePath($this->clip).'/presenter.smil');
        Storage::disk('videos')->assertMissing(getClipStoragePath($this->clip).'/composite.smil');

        $this->assertDatabaseHas('assets', ['type' => Content::SMIL(), 'original_file_name' => 'presenter.smil']);
    }

    /** @test */
    public function it_creates_a_wowza_presentation_smil_file(): void
    {
        Storage::fake('videos');

        $this->clip->addAsset([
            'disk' => 'videos',
            'original_file_name' => 'presentation.mp4',
            'path' => '/2021/01/01/TEST/',
            'duration' => 300,
            'width' => 1920,
            'guid' => Str::uuid(),
            'height' => 1080,
            'type' => Content::PRESENTATION(),
        ]);

        $this->wowzaService->createSmilFile($this->clip);

        Storage::disk('videos')->assertExists(getClipStoragePath($this->clip).'/presentation.smil');

        $this->assertDatabaseHas('assets', [
            'type' => Content::SMIL(),
            'original_file_name' => 'presentation.smil',
        ]);
    }

    /** @test */
    public function it_creates_a_wowza_composite_smil_file(): void
    {
        Storage::fake('videos');

        $this->clip->addAsset([
            'disk' => 'videos',
            'original_file_name' => 'composite.mp4',
            'path' => '/2021/01/01/TEST/',
            'duration' => 300,
            'width' => 1920,
            'guid' => Str::uuid(),
            'height' => 1080,
            'type' => Content::COMPOSITE(),
        ]);

        $this->wowzaService->createSmilFile($this->clip);

        Storage::disk('videos')->assertExists(getClipStoragePath($this->clip).'/composite.smil');

        $this->assertDatabaseHas('assets', [
            'type' => Content::SMIL(),
            'original_file_name' => 'composite.smil',
        ]);
    }

    /** @test */
    public function it_fetch_wowza_server_status(): void
    {
        $this->mockHandler->append($this->mockCheckApiConnection());

        $results = $this->wowzaService->getHealth();

        $this->assertStringContainsString('Wowza Streaming Engine', $results->get('releaseId'));
    }

    /** @test */
    public function it_generates_array_for_smil_file_based_on_asset(): void
    {
        $this->clip->addAsset([
            'disk' => 'videos',
            'original_file_name' => 'test.mp4',
            'path' => '/2021/01/01/TEST/',
            'duration' => 300,
            'width' => 1920,
            'guid' => Str::uuid(),
            'height' => 1080,
            'type' => Content::PRESENTER(),
        ]);

        $expectedArray = [
            'video' => [
                '_attributes' => [
                    'src' => 'mp4:test.mp4',
                    'system-bitrate' => 1500000,
                    'width' => 1920,
                    'height' => 1080,
                ],
                'paramVideoBR' => [
                    '_attributes' => [
                        'name' => 'videoBitrate',
                        'value' => 1500000,
                        'valuetype' => 'data',
                    ],
                ],
                'paramAudioBR' => [
                    '_attributes' => [
                        'name' => 'audioBitrate',
                        'value' => '44100',
                        'valuetype' => 'data',
                    ],
                ],
                'paramVideoCodecID' => [
                    '_attributes' => [
                        'name' => 'videoCodecId',
                        'value' => 'avc1.4d401f',
                        'valuetype' => 'data',
                    ],
                ],
                'paramAudioCodecID' => [
                    '_attributes' => [
                        'name' => 'audioCodecId',
                        'value' => 'mp4a.40.2',
                        'valuetype' => 'data',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $this->wowzaService->createSmilFileArray($this->clip->assets()->first()));
    }

    /** @test */
    public function it_returns_wowza_smil_file_bitrate(): void
    {
        $this->assertEquals(1500000, $this->wowzaService->findWowzaAssetBitrate(1200));
        $this->assertEquals(1500000, $this->wowzaService->findWowzaAssetBitrate(1080));
        $this->assertEquals(1100000, $this->wowzaService->findWowzaAssetBitrate(720));
        $this->assertEquals(450000, $this->wowzaService->findWowzaAssetBitrate(360));
    }
}
