<?php

namespace Tests\Feature\Backend;

use App\Services\OpencastService;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
