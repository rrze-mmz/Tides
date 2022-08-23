<?php

namespace Tests\Feature\Backend;

use App\Services\ElasticsearchService;
use App\Services\OpencastService;
use App\Services\WowzaService;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\WorksWithElasticsearchClient;
use Tests\Setup\WorksWithOpencastClient;
use Tests\Setup\WorksWithWowzaClient;
use Tests\TestCase;

class SystemsCheckTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WorksWithOpencastClient;
    use WorksWithWowzaClient;
    use WorksWithElasticsearchClient;

    private OpencastService $opencastService;

    private WowzaService $wowzaService;

    private ElasticsearchService $elasticsearchService;

    private MockHandler $mockOpencastHandler;

    private MockHandler $mockWowzaHandler;

    private MockHandler $mockElasticsearchHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockOpencastHandler = $this->swapOpencastClient();
        $this->mockWowzaHandler = $this->swapWowzaClient();
        $this->mockElasticsearchHandler = $this->swapElasticsearchGuzzleClient();

        $this->opencastService = app(OpencastService::class);
        $this->wowzaService = app(WowzaService::class);
        $this->elasticsearchService = app(ElasticsearchService::class);
    }

    /** @test */
    public function it_should_check_for_opencast_server(): void
    {
        $this->signInRole('admin');

        $this->mockOpencastHandler->append($this->mockServerNotAvailable());
        $this->mockWowzaHandler->append($this->mockServerNotAvailable());
        $this->mockElasticsearchHandler->append(($this->mockClusterHealthResponse()));

        $this->get(route('systems.status'))->assertSee('Opencast version');
    }

    /** @test */
    public function it_shows_an_info_message_if_opencast_server_is_not_available(): void
    {
        $this->signInRole('admin');

        $this->mockOpencastHandler->append($this->mockServerNotAvailable());
        $this->mockWowzaHandler->append($this->mockCheckApiConnection());
        $this->mockElasticsearchHandler->append(($this->mockClusterNotAvailable()));

        $this->get(route('systems.status'))
            ->assertOk()
            ->assertSee('Opencast server not available');
    }

    /** @test */
    public function it_should_check_for_opencast_status(): void
    {
        $this->signInRole('moderator');

        $this->mockOpencastHandler->append($this->mockHealthResponse());
        $this->mockWowzaHandler->append($this->mockCheckApiConnection());
        $this->mockElasticsearchHandler->append(($this->mockClusterHealthResponse()));

        $this->get(route('systems.status'))->assertOk()->assertSee('8.10.0');
    }

    /** @test */
    public function it_should_check_for_wowza_server(): void
    {
        $this->signInRole('admin');

        $this->mockOpencastHandler->append($this->mockServerNotAvailable());
        $this->mockWowzaHandler->append($this->mockServerNotAvailable());
        $this->mockElasticsearchHandler->append(($this->mockClusterNotAvailable()));

        $this->get(route('systems.status'))->assertSee('Wowza description');
    }

    /** @test */
    public function it_shows_an_info_message_if_wowza_server_is_not_available(): void
    {
        $this->signInRole('admin');

        $this->mockOpencastHandler->append($this->mockServerNotAvailable());
        $this->mockWowzaHandler->append($this->mockServerNotAvailable());
        $this->mockElasticsearchHandler->append(($this->mockClusterNotAvailable()));

        $this->get(route('systems.status'))
            ->assertOk()
            ->assertSee('Wowza server not available');
    }

    /** @test */
    public function it_should_check_for_wowza_status(): void
    {
        $this->signInRole('moderator');

        $this->mockOpencastHandler->append($this->mockHealthResponse());
        $this->mockWowzaHandler->append($this->mockCheckApiConnection());
        $this->mockElasticsearchHandler->append(($this->mockClusterHealthResponse()));

        $this->get(route('systems.status'))
            ->assertOk()
            ->assertSee('Wowza Streaming Engine X Perpetual Edition X.X.X.xxx buildYYYVERSION');
    }

    /** @test */
    public function it_should_check_for_elasticsearch_server(): void
    {
        $this->signInRole('admin');

        $this->mockOpencastHandler->append($this->mockServerNotAvailable());
        $this->mockWowzaHandler->append($this->mockServerNotAvailable());
        $this->mockElasticsearchHandler->append($this->mockClusterNotAvailable());

        $this->get(route('systems.status'))->assertSee('Elasticsearch');
    }
}
