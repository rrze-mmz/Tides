<?php

use App\Enums\Role;
use App\Jobs\IngestVideoFileToOpencast;
use App\Livewire\IngestOpencast;
use App\Services\OpencastService;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\Setup\WorksWithOpencastClient;

use function Pest\Laravel\get;

uses(WorksWithOpencastClient::class);
uses()->group('backend');

beforeEach(function () {
    app()->setLocale('en');
    $this->clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    $mockHandler = $this->swapOpencastClient();
    $this->opencastService = app(OpencastService::class);
    $mockHandler->append($this->mockHealthResponse());
});
it('contains a file upload livewire component in clip edit page', function () {
    get(route('clips.edit', $this->clip))->assertSeeLivewire('ingest-opencast');
});

it('dispatches an ingest job after video is uploaded', function () {
    Queue::fake();

    $file = UploadedFile::fake()->create('video.mp4', 1000);

    Livewire::test(IngestOpencast::class, ['clip' => $this->clip])
        ->set('videoFile', $file)
        ->call('submitForm');

    Queue::assertPushed(IngestVideoFileToOpencast::class);
});

it('does not dispatches an ingest job if a file is not a video', function () {
    Queue::fake();

    $file = UploadedFile::fake()->create('video.pdf', 1000);

    Livewire::test(IngestOpencast::class, ['clip' => $this->clip])
        ->set('videoFile', $file)
        ->call('submitForm')
        ->assertSee('The video file must be a file of type: video/mp4, video/mpeg, video/x-matroska, video/x-m4v.');

    Queue::assertNotPushed(IngestVideoFileToOpencast::class);
});

it('does not dispatches an ingest job if a file is bigger than 2 gigabytes', function () {
    Queue::fake();

    $file = UploadedFile::fake()->create('video.mp4', 9097152);

    Livewire::test(IngestOpencast::class, ['clip' => $this->clip])
        ->set('videoFile', $file)
        ->call('submitForm')
        ->assertSee('The video file field is required.');

    Queue::assertNotPushed(IngestVideoFileToOpencast::class);
});
