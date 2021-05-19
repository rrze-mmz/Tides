<?php

namespace Tests\Feature\Backend;

use App\Jobs\IngestVideoFileToOpencast;
use App\Models\Clip;
use App\Models\Series;
use App\Services\OpencastService;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class OpencastTest extends TestCase {
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
    public function it_shows_an_info_message_if_opencast_server_is_not_available(): void
    {
        $this->signInAdmin();

        $this->mockHandler->append(new Response(200, [], json_encode([])));

        $this->get(route('opencast.status'))
            ->assertStatus(200)
            ->assertSee('Opencast connection is not available');
    }

    /** @test */
    public function it_should_check_for_opencast_status(): void
    {
        $this->signIn();

        $this->mockHandler->append($this->mockHealthResponse());

        $this->get(route('opencast.status'))->assertStatus(200);
    }

    /** @test */
    public function it_should_dispatch_an_ingest_to_opencast_only_if_opencast_series_id_exists_in_series(): void
    {
        Queue::fake();

        Storage::fake('videos');

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('opencast.ingestMediaPackage', $clip), ['videoFile' => FileFactory::videoFile()]);

        Queue::assertNotPushed(IngestVideoFileToOpencast::class);
    }

    /** @test */
    public function it_should_dispatch_an_ingest_to_opencast_job_after_video_upload(): void
    {
        Queue::fake();

        Storage::fake('videos');

        $this->signIn();

        $series = Series::factory()->create(['opencast_series_id' => Str::uuid()]);

        $series->clips()->save(ClipFactory::create());

        $this->post(route('opencast.ingestMediaPackage', $series->clips()->first() ), ['videoFile' => FileFactory::videoFile()]);

        Queue::assertPushed(IngestVideoFileToOpencast::class);
    }
}
