<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Clip;
use App\Services\WowzaService;
use Facades\Tests\Setup\ClipFactory;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Setup\WorksWithWowzaClient;

class WowzaServiceTest extends TestCase
{
    use RefreshDatabase, WorksWithWowzaClient;

    private WowzaService $wowzaService;

    private MockHandler $mockHandler;

    private Asset $asset;

    private Clip $clip;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler =  $this->swapWowzaClient();

        $this->wowzaService = app(WowzaService::class);

        $this->clip = ClipFactory::create();

        $this->asset = Asset::create([
            'disk'  => 'videos',
            'clip_id' => $this->clip->id,
            'original_file_name' => 'test.mp4',
            'path'  => '/2021/01/01/TEST/',
            'duration' => 300,
            'width' => 1920,
            'height'  => 1080,
            'type'  => 'video'
        ]);
    }

    /** @test */
    public function it_creates_a_wowza_smil_file(): void
    {
        Storage::fake('videos');

        $this->wowzaService->createSmilFiles($this->clip);

        Storage::disk('videos')->assertExists(getClipStoragePath($this->clip).'/camera.smil');
    }

    /** @test */
    public function it_fetch_wowza_server_status(): void
    {
        $this->mockHandler->append($this->mockCheckApiConnection());

        $results = $this->wowzaService->checkApiConnection();

        $this->assertStringContainsString('Wowza Streaming Engine', $results->get(0));
    }

    /** @test */
    public function it_generates_array_for_smil_file_based_on_asset(): void
    {
        $expectedArray =  [
            'video' => [
                '_attributes' => [
                    'src'            => 'mp4:test.mp4',
                    'system-bitrate' => 1500000,
                    'width'          => 1920,
                    'height'         => 1080
                ],
                'param1'       => [
                    '_attributes' => [
                        'name'      => 'videoBitrate',
                        'value'     => 1500000,
                        'valuetype' => 'data',
                    ]
                ],
                'param2'       => [
                    '_attributes' => [
                        'name'      => 'audioBitrate',
                        'value'     => '44100',
                        'valuetype' => 'data',
                    ]
                ],
                'param3'       => [
                    '_attributes' => [
                        'name'      => 'videoCodecId',
                        'value'     => 'avc1.4d401f',
                        'valuetype' => 'data',
                    ]
                ],
                'param4'       => [
                    '_attributes' => [
                        'name'      => 'audioCodecId',
                        'value'     => 'mp4a.40.2',
                        'valuetype' => 'data',
                    ]
                ],
            ]
        ];

        $this->assertEquals($expectedArray, $this->wowzaService->createSmilFileArray($this->asset));
    }

    /** @test */
    public function it_returns_wowza_smil_file_bitrates(): void
    {
        $this->assertEquals('1500000', $this->wowzaService->findWowzaAssetBitrate((int)$this->asset->height));

        $this->asset->height = 720;
        $this->asset->update();

        $this->assertEquals('1100000', $this->wowzaService->findWowzaAssetBitrate((int)$this->asset->height));

        $this->asset->height = 360;
        $this->asset->update();

        $this->assertEquals('450000', $this->wowzaService->findWowzaAssetBitrate((int)$this->asset->height));
    }
}
