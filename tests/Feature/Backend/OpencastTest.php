<?php

namespace Tests\Feature\Backend;

use App\Jobs\IngestVideoFileToOpencast;
use App\Services\OpencastService;
use Facades\Tests\Setup\FileFactory;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Support\Facades\Queue;
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
    public function it_should_check_for_opencast_status(): void
    {
        $this->signIn();

        $this->mockHandler->append($this->mockHealthResponse());

        $this->get(route('opencast.status'))->assertStatus(200);
    }

    /** @test */
    public function it_should_dispatch_an_ingest_to_opencast_job_after_video_upload()
    {
        $this->withoutExceptionHandling();
        Queue::fake();

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('opencast.ingestMediaPackage', $clip), ['videoFile' =>  FileFactory::videoFile()]);

        Queue::assertPushed(IngestVideoFileToOpencast::class);
    }
}
