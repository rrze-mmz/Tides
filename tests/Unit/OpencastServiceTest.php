<?php

namespace Tests\Unit;

use App\Models\Series;
use App\Services\OpencastService;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class OpencastServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker, WorksWithOpencastClient;

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
    public function it_creates_an_opencast_series(): void
    {
        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $series = SeriesFactory::create();

        $response = $this->opencastService->createSeries($series);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /** @test */
    public function it_ingest_a_media_package_to_opencast(): void
    {
        Storage::fake('videos');

        $this->mockHandler->append($this->mockIngestMediaPackageResponse());

        $series = SeriesFactory::withClips(1)->create();

        $file = UploadedFile::fake()->create('video.mp4', 1000);

        $response = $this->opencastService->ingestMediaPackage($series->clips()->first(),  $file);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_fetch_running_workflows_for_a_series(): void
    {
        $series = Series::factory()->create(['opencast_series_id'=> Str::uuid()]);

        $this->mockHandler->append($this->mockSeriesRunningWorkflowsResponse($series, true));

        $response = $this->opencastService->getSeriesRunningWorkflows($series);

        $this->assertEquals('RUNNING', $response['workflows']['workflow']['0']['state']);

        $this->assertEquals($series->opencast_series_id, $response['workflows']['workflow']['0']['mediapackage']['series']);
    }

    /** @test */
    public function it_formats_data_for_opencast_create_series_post_request(): void
    {
        $series = SeriesFactory::create();

        $data = [
            'headers'  => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
            'form_params' => [
                "metadata" => '[{"flavor": "dublincore/series","title": "EVENTS.EVENTS.DETAILS.CATALOG.EPISODE",
                        "fields": [
                        {
                             "id": "title",
                             "value": "'.$series->title.'",
                         },
                         {
                             "id": "creator",
                             "value": ["'. $series->owner->name.'"],
                         },
                         ]
                    }]',
                "acl" => '[
					{"allow": true,"role": "ROLE_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_ADMIN","action": "write"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "write"},
			    ]',
                "theme" => '601'
            ]
        ];

        $this->assertEquals($data, $this->opencastService->createOpencastSeriesFormData($series));
    }

    /** @test */
    public function it_formats_data_for_opencast_ingest_media_package_request(): void
    {
        Storage::fake('videos');

        $series = SeriesFactory::withClips(2)->create(['opencast_series_id'=> Str::uuid()]);


        $file = UploadedFile::fake()->create('video.mp4', 1000);

        $data = [
            'headers'   => [
                'Content-Type: multipart/form-data',
            ],
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
