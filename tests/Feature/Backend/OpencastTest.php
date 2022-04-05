<?php

namespace Tests\Feature\Backend;

use App\Services\OpencastService;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class OpencastTest extends TestCase
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
    public function it_shows_an_info_message_if_opencast_server_is_not_available(): void
    {
        $this->signInRole('admin');

        $this->mockHandler->append(new Response(200, [], json_encode([])));

        $this->get(route('opencast.status'))
            ->assertOk()
            ->assertSee('Opencast connection is not available');
    }

    /** @test */
    public function it_should_check_for_opencast_status(): void
    {
        $this->signInRole('moderator');

        $this->mockHandler->append($this->mockHealthResponse());

        $this->get(route('opencast.status'))->assertOk();
    }
}
