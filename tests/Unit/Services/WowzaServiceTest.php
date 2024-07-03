<?php

use App\Enums\Content;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Livestream;
use App\Services\WowzaService;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;
use Tests\Setup\WorksWithWowzaClient;

use function Pest\Laravel\assertDatabaseHas;

uses(WorksWithOpencastClient::class);
uses(WorksWithWowzaClient::class);
uses()->group('unit');

beforeEach(function () {
    $this->mockHandler = $this->swapWowzaClient();
    $this->wowzaService = app(WowzaService::class);
    $this->clip = ClipFactory::create();
});

it('returns default values if guzzle response is empty', function () {
    $this->mockHandler->append($this->mockServerNotAvailable());
    $results = $this->wowzaService->getHealth();

    expect($results['status'])->toEqual('failed');
});

it('creates a wowza presenter smil file', function () {
    Storage::fake('videos');
    $this->clip->addAsset(Asset::create([
        'disk' => 'videos',
        'original_file_name' => 'test.mp4',
        'path' => '/2021/01/01/TEST/',
        'duration' => 300,
        'guid' => Str::uuid(),
        'width' => 1920,
        'height' => 1080,
        'type' => Content::PRESENTER(),
    ]));
    $this->wowzaService->createSmilFile($this->clip);
    Storage::disk('videos')->assertExists(getClipStoragePath($this->clip).'/presenter.smil');
    Storage::disk('videos')->assertMissing(getClipStoragePath($this->clip).'/composite.smil');

    assertDatabaseHas('assets', ['type' => Content::SMIL(), 'original_file_name' => 'presenter.smil']);
});

it('creates a wowza presentation smil file', function () {
    Storage::fake('videos');
    $this->clip->addAsset(Asset::create([
        'disk' => 'videos',
        'original_file_name' => 'presentation.mp4',
        'path' => '/2021/01/01/TEST/',
        'duration' => 300,
        'width' => 1920,
        'guid' => Str::uuid(),
        'height' => 1080,
        'type' => Content::PRESENTATION(),
    ]));
    $this->wowzaService->createSmilFile($this->clip);
    Storage::disk('videos')->assertExists(getClipStoragePath($this->clip).'/presentation.smil');

    assertDatabaseHas('assets', [
        'type' => Content::SMIL(),
        'original_file_name' => 'presentation.smil',
    ]);
});

it('creates a wowza composite smil file', function () {
    Storage::fake('videos');
    $this->clip->addAsset(Asset::create([
        'disk' => 'videos',
        'original_file_name' => 'composite.mp4',
        'path' => '/2021/01/01/TEST/',
        'duration' => 300,
        'width' => 1920,
        'guid' => Str::uuid(),
        'height' => 1080,
        'type' => Content::COMPOSITE(),
    ]));
    $this->wowzaService->createSmilFile($this->clip);

    Storage::disk('videos')->assertExists(getClipStoragePath($this->clip).'/composite.smil');
    assertDatabaseHas('assets', [
        'type' => Content::SMIL(),
        'original_file_name' => 'composite.smil',
    ]);
});

it('fetch wowza server status', function () {
    $this->mockHandler->append($this->mockCheckApiConnection());
    $results = $this->wowzaService->getHealth();

    $this->assertStringContainsString('Wowza Streaming Engine', $results->get('releaseId'));
});

it('generates array for smil file based on asset', function () {
    $this->clip->addAsset(Asset::create([
        'disk' => 'videos',
        'original_file_name' => 'test.mp4',
        'path' => '/2021/01/01/TEST/',
        'duration' => 300,
        'width' => 1920,
        'guid' => Str::uuid(),
        'height' => 1080,
        'type' => Content::PRESENTER(),
    ]));
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

    expect($this->wowzaService->createSmilFileArray($this->clip->assets()->first()))->toEqual($expectedArray);
});

it('returns wowza smil file bitrate', function () {
    expect($this->wowzaService->findWowzaAssetBitrate(1200))->toEqual(1500000);
    expect($this->wowzaService->findWowzaAssetBitrate(1080))->toEqual(1500000);
    expect($this->wowzaService->findWowzaAssetBitrate(720))->toEqual(1100000);
    expect($this->wowzaService->findWowzaAssetBitrate(360))->toEqual(450000);
});

it('generates secure VOD URLs for a clip', function () {
    $clip = ClipFactory::withAssets(3)->create();
    expect($this->wowzaService->vodSecureUrls($clip))->toBeInstanceOf(Collection::class);
    expect($this->wowzaService->vodSecureUrls($clip))->toHaveKey('presenter');
    expect($this->wowzaService->vodSecureUrls($clip)->first())
        ->toContain(config('settings.streaming.wowza.server1.engine_url'));
});

it('generates secure livestream URLs for a livestream room', function () {
    $livestream = Livestream::factory()->create();

    expect($this->wowzaService->livestreamSecureUrls($livestream))->toBeInstanceOf(Collection::class);
    expect($this->wowzaService->livestreamSecureUrls($livestream)->first())
        ->toContain(config('settings.streaming.wowza.server2.engine_url'));
});

it('reserves a livestream room for a given Opencast agent ID', function () {
    $livestream = Livestream::factory()->create();
    expect($livestream->refresh()->active)->toBe(0);

    $this->wowzaService->reserveLivestreamRoom($livestream->opencast_location_name);
    expect($livestream->refresh()->active)->toBe(1);
});

it('reserves a livestream for a given location', function () {
    $livestream = Livestream::factory()->create();
    $clip = Clip::factory()->create(['is_livestream' => true]);
    expect($livestream->refresh()->active)->toBe(0);
    expect($livestream->refresh()->clip_id)->toBeNull();

    $this->wowzaService->reserveLivestreamRoom('', $clip, null, $livestream->name);

    expect($livestream->refresh()->active)->toBe(1);
    expect($livestream->refresh()->clip_id)->toBe($clip->id);
});
