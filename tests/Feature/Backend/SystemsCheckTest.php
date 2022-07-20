<?php

namespace Tests\Feature\Backend;

use App\Services\OpencastService;
use App\Services\WowzaService;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\WorksWithOpencastClient;
use Tests\Setup\WorksWithWowzaClient;
use Tests\TestCase;

class SystemsCheckTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WorksWithOpencastClient;
    use WorksWithWowzaClient;

    private OpencastService $opencastService;

    private WowzaService $wowzaService;

    private MockHandler $mockOpencastHandler;

    private MockHandler $mockWowzaHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockOpencastHandler = $this->swapOpencastClient();
        $this->mockWowzaHandler = $this->swapWowzaClient();

        $this->opencastService = app(OpencastService::class);
        $this->wowzaService = app(WowzaService::class);
    }

    /** @test */
    public function it_should_check_for_opencast_server(): void
    {
        $this->signInRole('admin');

        $this->mockOpencastHandler->append($this->mockServerNotAvailable());
        $this->mockWowzaHandler->append($this->mockServerNotAvailable());

        $this->get(route('systems.status'))->assertSee('Opencast version');
    }

    /** @test */
    public function it_shows_an_info_message_if_opencast_server_is_not_available(): void
    {
        $this->signInRole('admin');

        $this->mockOpencastHandler->append($this->mockServerNotAvailable());
        $this->mockWowzaHandler->append($this->mockCheckApiConnection());

        $this->get(route('systems.status'))
            ->assertOk()
            ->assertSee('unknown');
    }

    /** @test */
    public function it_should_check_for_opencast_status(): void
    {
        $this->signInRole('moderator');

        $this->mockOpencastHandler->append($this->mockHealthResponse());
        $this->mockWowzaHandler->append($this->mockCheckApiConnection());

        $this->get(route('systems.status'))->assertOk();
    }
}
