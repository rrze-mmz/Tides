<?php

namespace Tests\Feature\Backend;

use App\Services\OpencastService;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class OpencastTest extends TestCase
{
    use RefreshDatabase, WithFaker, WorksWithOpencastClient;

    private OpencastService $opencastService;

    /*
     * Mock api results
     */
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler  = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);
    }

    /** @test */
    public function it_should_check_for_opencast_status(): void
    {
        $this->signIn();

        $this->mockHandler->append($this->mockHealthResponse());

        $this->get(route('opencast'))->assertStatus(200);
    }
}
