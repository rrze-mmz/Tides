<?php

use App\Services\OpencastService;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Support\Carbon;
use Tests\Setup\WorksWithOpencastClient;

use function Pest\Laravel\artisan;

uses()->group('backend');
uses(WorksWithOpencastClient::class);

beforeEach(function () {
    $this->mockHandler = $this->swapOpencastClient();
    $this->opencastService = app(OpencastService::class);
});

it('outputs a message and skip checks if Opencast server is not available', function () {
    $this->mockHandler->append($this->mockServerNotAvailable());

    artisan('app:enable-livestreams')->expectsOutput('No Opencast server found or server is offline!');
});

it('outputs a message and skip checks if no Opencast scheduled events found for the next 10 minutes', function () {
    $this->mockHandler->append($this->mockHealthResponse(), $this->mockNoResultsResponse());

    artisan('app:enable-livestreams')->expectsOutput('No Opencast scheduled events found for the next 10 minutes');
});

it('outputs a message if Opencast scheduled events found for the next 10 minutes', function () {
    $series = SeriesFactory::withClips(3)->withOpencastID()->create();
    $livestreamClip = $series->clips()->first();
    $livestreamClip->is_livestream = true;
    $livestreamClip->save();

    $this->mockHandler->append(
        $this->mockHealthResponse(),
        $this->mockScheduledEvents($series, 1, Carbon::now()->addMinutes(6), Carbon::now()->addMinutes(26))
    );

    artisan('app:enable-livestreams')->expectsOutput("Series '{$series->title}' has a livestream clip now try to enable"
        .' wowza app test-lecture-hall for this clip');
});
