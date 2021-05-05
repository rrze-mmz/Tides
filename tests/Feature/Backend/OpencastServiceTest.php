<?php

namespace Tests\Feature\Backend;

use App\Services\OpencastService;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\SeriesFactory;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class OpencastServiceTest extends TestCase {

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
    public function it_should_fetch_opencast_health_status(): void
    {
        $this->mockHandler->append($this->mockHealthResponse());

        $results = $this->opencastService->getHealth();

        $this->assertEquals('Opencast node\'s health status', $results['description']);
    }

    /** @test */
    public function it_should_create_an_opencast_series(): void
    {
        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $series = SeriesFactory::create();

        $response = $this->opencastService->createSeries($series);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /** @test */
    public function it_formats_data_for_opencast_create_series_post_request()
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
			    ]'
            ]
        ];

        $this->assertEquals($data, $this->opencastService->createOpencastSeriesFormData($series));
    }
}
