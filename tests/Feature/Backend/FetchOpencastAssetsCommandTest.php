<?php

namespace Tests\Feature\Backend;

use App\Services\OpencastService;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Support\Facades\Storage;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;
use Facades\Tests\Setup\FileFactory;

class FetchOpencastAssetsCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WorksWithOpencastClient;

    private OpencastService $opencastService;
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);
    }

    /** @test */
    public function it_fetches_opencast_events_for_a_given_series()
    {
        Storage::fake('videos');
        $fakeStorage = Storage::fake('opencast_archive');

        $archiveVersion = 2;
        $audioUID = $this->faker->uuid();
        $videoHD_UID = $this->faker->uuid();
        $opencastEventID = $this->faker->uuid();

        $seriesWithoutAssets = SeriesFactory::withClips(1)->withOpencastID()->create();

        $this->mockHandler->append($this->mockEventResponse($seriesWithoutAssets,
            'SUCCEEDED', 'EVENTS.EVENTS.STATUS.SUCCEDED', 4, $opencastEventID));

        $this->mockHandler->append($this->mockEventAssets($videoHD_UID, $audioUID));
        $this->mockHandler->append($this->mockEventByEventID($opencastEventID, 'SUCCEEDED', $archiveVersion));
        $this->mockHandler->append($this->mockEventAssets($videoHD_UID, $audioUID));


//        $this->mockHandler->append($this->mockEventAssets($videoHD_UID, $audioUID));

        $fakeStorage
            ->putFileAs(
                '',
                FileFactory::videoFile(), '/archive/mh_default_org/' .
                $opencastEventID . '/' . $archiveVersion . '/' . $audioUID . '.mp3'
            );
        $fakeStorage
            ->putFileAs(
                '',
                FileFactory::videoFile(), '/archive/mh_default_org/' .
                $opencastEventID . '/' . $archiveVersion . '/' . $videoHD_UID . '.m4v'
            );

        $this->artisan('opencast:finished-events')
            ->expectsOutput('Videos from Clip ' . $seriesWithoutAssets->clips()->first()->title . ' is online');
    }
}
