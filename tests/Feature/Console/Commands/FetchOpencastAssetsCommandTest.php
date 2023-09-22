<?php

use App\Enums\OpencastWorkflowState;
use App\Models\Series;
use App\Services\OpencastService;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

uses(\Illuminate\Foundation\Testing\WithFaker::class);

uses(\Tests\Setup\WorksWithOpencastClient::class);

beforeEach(function () {
    $this->mockHandler = $this->swapOpencastClient();

    $this->opencastService = app(OpencastService::class);
});

it('searches for clips with no assets', function () {
    SeriesFactory::withClips(1)->withAssets(2)->withOpencastID()->create();

    $this->artisan('opencast:finished-events')
        ->expectsOutput('No empty clips found');
});

it('searches for clips with series', function () {
    ClipFactory::create();

    $this->artisan('opencast:finished-events')
        ->expectsOutput('No empty clips found');
});

it('searched for clips with opencast series id', function () {
    SeriesFactory::withClips(1)->create();
    expect(Series::hasOpencastSeriesID()->count())->toEqual(0);

    $this->artisan('opencast:finished-events')
        ->expectsOutput('No empty clips found');
});

it('fetches opencast events for a given series', function () {
    Storage::fake('videos');
    $fakeStorage = Storage::fake('opencast_archive');

    $archiveVersion = 2;
    $audioUID = $this->faker->uuid();
    $videoHD_UID = $this->faker->uuid();
    $opencastEventID = $this->faker->uuid();

    $seriesWithoutAssets = SeriesFactory::withClips(1)->withOpencastID()->create();

    $this->mockHandler->append(
        $this->mockEventResponse($seriesWithoutAssets, OpencastWorkflowState::SUCCEEDED, 4, $opencastEventID),
        $this->mockEventAssets($videoHD_UID, $audioUID),
        $this->mockEventByEventID($opencastEventID, OpencastWorkflowState::SUCCEEDED, $archiveVersion),
        $this->mockEventAssets($videoHD_UID, $audioUID),
    );

    $fakeStorage
        ->putFileAs(
            '',
            FileFactory::videoFile(),
            "/archive/mh_default_org/{$opencastEventID}/{$archiveVersion}/{$audioUID}.mp3"
        );
    $fakeStorage
        ->putFileAs(
            '',
            FileFactory::videoFile(),
            "/archive/mh_default_org/{$opencastEventID}/{$archiveVersion}/{$videoHD_UID}.m4v"
        );

    $this->artisan('opencast:finished-events')
        ->expectsOutput("Videos from Clip {$seriesWithoutAssets->clips()->first()->title} is online");
});
