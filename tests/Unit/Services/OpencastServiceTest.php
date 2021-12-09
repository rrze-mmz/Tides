<?php

namespace Tests\Unit\Services;

use App\Models\Series;
use App\Services\OpencastService;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class OpencastServiceTest extends TestCase
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
    public function it_fetch_opencast_health_status(): void
    {
        $this->mockHandler->append($this->mockHealthResponse());

        $results = $this->opencastService->getHealth();

        $this->assertEquals('Opencast node\'s health status', $results['description']);
    }

    /** @test */
    public function it_fetches_an_empty_collection_for_opencast_health_if_no_opencast_server_is_available(): void
    {
        $this->mockHandler->append($this->mockServerNotAvailable());

        $results = $this->opencastService->getHealth();

        $this->assertTrue($results->isEmpty());
    }

    /** @test */
    public function it_fetches_opencast_series_info_for_a_given_series(): void
    {
        $series = SeriesFactory::create();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesRunningWorkflowsResponse($series),
            $this->mockEventResponse($series)
        );

        $seriesInfo = $this->opencastService->getSeriesInfo($series);

        $this->assertTrue($seriesInfo->isNotEmpty());
        $this->assertTrue($seriesInfo['health']);
        $this->assertArrayHasKey('running', $seriesInfo);
        $this->assertArrayHasKey('failed', $seriesInfo);
    }

    /** @test */
    public function it_fetches_an_empty_collection_for_opencast_series_info_if_no_opencast_server_is_available(): void
    {
        $series = SeriesFactory::create();

        $this->mockHandler->append(
            $this->mockServerNotAvailable()
        );

        $seriesInfo = $this->opencastService->getSeriesInfo($series);

        $this->assertTrue($seriesInfo->isEmpty());
    }

    /** @test */
    public function it_creates_an_opencast_series(): void
    {
        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $series = SeriesFactory::create();

        $response = $this->opencastService->createSeries($series);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /** @test */
    public function it_does_not_create_an_opencast_series_if_server_is_unavailable(): void
    {
        $this->mockHandler->append($this->mockServerNotAvailable());

        $series = SeriesFactory::create();

        $response = $this->opencastService->createSeries($series);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_ingest_a_media_package_to_opencast(): void
    {
        Storage::fake('videos');

        $this->mockHandler->append($this->mockIngestMediaPackageResponse());

        $series = SeriesFactory::withClips(1)->create();

        $file = UploadedFile::fake()->create('video.mp4', 1000);

        $response = $this->opencastService->ingestMediaPackage($series->clips()->first(), $file);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_fetch_running_workflows_for_a_given_series(): void
    {
        $series = Series::factory()->create(['opencast_series_id' => Str::uuid()]);

        $this->mockHandler->append($this->mockSeriesRunningWorkflowsResponse($series, true));

        $response = $this->opencastService->getSeriesRunningWorkflows($series);

        $this->assertEquals('RUNNING', $response['workflows']['workflow']['0']['state']);

        $this->assertEquals(
            $series->opencast_series_id, $response['workflows']['workflow']['0']['mediapackage']['series']
        );
    }

    /** @test */
    public function it_fetches_an_empty_collection_for_running_workflows_if_no_opencast_server_is_available(): void
    {
        $series = SeriesFactory::create();

        $this->mockHandler->append($this->mockServerNotAvailable());

        $seriesInfo = $this->opencastService->getSeriesRunningWorkflows($series);

        $this->assertTrue($seriesInfo->isEmpty());
    }

    /** @test */
    public function it_fetch_failed_events_for_a_given_series(): void
    {
        $series = Series::factory()->create(['opencast_series_id' => Str::uuid()]);

        $this->mockHandler->append($this->mockEventResponse(
            $series, 'FAILED', 'EVENTS.EVENTS.STATUS.PROCESSING_FAILURE'
        ));

        $response = $this->opencastService->getFailedEventsBySeries($series);

        $this->assertTrue($response->pluck('processing_state')->contains('FAILED'));

        $this->assertEquals($series->opencast_series_id, $response->first()['is_part_of']);
    }

    /** @test */
    public function it_fetches_an_empty_collection_for_failed_events_if_no_opencast_server_is_available(): void
    {
        $series = SeriesFactory::create();

        $this->mockHandler->append($this->mockServerNotAvailable());

        $seriesInfo = $this->opencastService->getFailedEventsBySeries($series);

        $this->assertTrue($seriesInfo->isEmpty());
    }

    /** @test */
    public function if_fetches_a_collection_of_all_finished_events_for_a_given_series(): void
    {
        $series = SeriesFactory::withClips(1)->withOpencastID()->create();

        $this->mockHandler->append($this->mockEventResponse($series));
        $this->mockHandler->append($this->mockEventResponse($series, 'STOPPED', 'EVENTS.EVENTS.STATUS.STOPPED'));

        $response = $this->opencastService->getProcessedEventsBySeriesID($series->opencast_series_id);

        $this->assertInstanceOf(Collection::class, $response);

        $this->assertTrue($response->pluck('processing_state')->contains('STOPPED'));

        $this->assertTrue($response->pluck('processing_state')->contains('SUCCEEDED'));

        $this->assertEquals($series->opencast_series_id, $response->first()['is_part_of']);

    }

    /** @test */
    public function it_fetches_a_collection_of_all_assets_for_a_given_event(): void
    {
        $this->mockHandler->append($this->mockEventAssets($this->faker->uuid(), $this->faker->uuid()));
        $this->mockHandler->append($this->mockEventAssets($this->faker->uuid(), $this->faker->uuid()));

        $response = $this->opencastService->getAssetsByEventID($this->faker->uuid);

        $this->assertInstanceOf(Collection::class, $response);
    }

    /** @test */
    public function it_fetches_all_metadata_for_a_single_event(): void
    {
        $series = Series::factory()->create(['opencast_series_id' => Str::uuid()]);

        $this->mockHandler->append($this->mockEventResponse($series));

        $response = $this->opencastService->getEventByEventID($this->faker->uuid());

        $this->assertInstanceOf(Collection::class, $response);
    }

    /** @test */
    public function it_formats_data_for_opencast_create_series_post_request(): void
    {
        $series = SeriesFactory::create();

        $data = [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                "metadata" => '[{"flavor": "dublincore/series","title": "EVENTS.EVENTS.DETAILS.CATALOG.EPISODE",
                        "fields": [
                        {
                             "id": "title",
                             "value": "' . $series->title . '",
                         },
                         {
                             "id": "creator",
                             "value": ["' . $series->owner->name . '"],
                         },
                         ]
                    }]',
                "acl"      => '[
					{"allow": true,"role": "ROLE_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_ADMIN","action": "write"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "write"},
			    ]',
                "theme"    => '601'
            ]
        ];

        $this->assertEquals($data, $this->opencastService->createOpencastSeriesFormData($series));
    }

    /** @test */
    public function it_formats_data_for_opencast_ingest_media_package_request(): void
    {
        Storage::fake('videos');

        $series = SeriesFactory::withClips(2)->create(['opencast_series_id' => Str::uuid()]);


        $file = UploadedFile::fake()->create('video.mp4', 1000);

        $data = [
            'multipart' => [
                [
                    'name'     => 'flavor',
                    'contents' => 'presenter/source'
                ],
                [
                    'name'     => 'title',
                    'contents' => $series->clips()->first()->title
                ],
                [
                    'name'     => 'description',
                    'contents' => $series->clips()->first()->id
                ],
                [
                    'name'     => 'publisher',
                    'contents' => $series->clips()->first()->owner->email
                ],
                [
                    'name'     => 'isPartOf',
                    'contents' => $series->opencast_series_id
                ],
                [
                    'name'     => 'file',
                    'contents' => file_get_contents($file),
                    'filename' => basename($file)
                ]
            ]
        ];

        $this->assertEquals($data, $this->opencastService
            ->ingestMediaPackageFormData($series->clips()->first(), $file));
    }
}
