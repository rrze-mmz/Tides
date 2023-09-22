<?php

use App\Enums\OpencastWorkflowState;
use App\Models\Series;
use App\Models\User;
use App\Services\OpencastService;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;

uses(WithFaker::class);
uses(WorksWithOpencastClient::class);
uses()->group('unit');

it('returns default values if guzzle response is empty', function () {
    $this->mockHandler->append($this->mockServerNotAvailable());
    $results = $this->opencastService->getHealth();

    expect($results['status'])->toEqual('failed');
});

it('fetch opencast health status', function () {
    $this->mockHandler->append($this->mockHealthResponse());
    $results = $this->opencastService->getHealth();

    expect($results['description'])->toEqual('Opencast node\'s health status');
});

it('fetches opencast series info for a given series', function () {
    $series = SeriesFactory::create();

    $this->mockHandler->append(
        $this->mockHealthResponse(), //health
        $this->mockSeriesMetadata($series), // seriesInfo
        $this->mockNoResultsResponse(), //recording
        $this->mockNoResultsResponse(), //running
        $this->mockNoResultsResponse(), //scheduled
        $this->mockNoResultsResponse(), //failed
        $this->mockNoTrimmingResultsResponse(), //trimming
        $this->mockNoResultsResponse(), //upcoming
    );

    $seriesInfo = $this->opencastService->getSeriesInfo($series);

    expect($seriesInfo->isNotEmpty())->toBeTrue();
    expect($seriesInfo['health'])->toBeTrue();
    expect($seriesInfo)->toHaveKey('metadata');
    expect($seriesInfo)->toHaveKey(OpencastWorkflowState::RECORDING->lower());
    expect($seriesInfo)->toHaveKey(OpencastWorkflowState::RUNNING->lower());
    expect($seriesInfo)->toHaveKey(OpencastWorkflowState::SCHEDULED->lower());
    expect($seriesInfo)->toHaveKey(OpencastWorkflowState::FAILED->lower());
    expect($seriesInfo)->toHaveKey(OpencastWorkflowState::TRIMMING->lower());
    expect($seriesInfo)->toHaveKey('upcoming');
});

it('fetches an empty collection for opencast series info if no opencast server is available', function () {
    $series = SeriesFactory::create();

    $this->mockHandler->append(
        $this->mockServerNotAvailable()
    );

    $seriesInfo = $this->opencastService->getSeriesInfo($series);

    expect($seriesInfo->isEmpty())->toBeTrue();
});

it('creates an opencast series', function () {
    $this->mockHandler->append($this->mockCreateSeriesResponse());

    $response = $this->opencastService->createSeries(SeriesFactory::create());

    expect($response->getStatusCode())->toEqual(201);
});

it('throws an exception if create opencast series has no arguments', function () {
    $this->expectException(ArgumentCountError::class);
    $this->opencastService->createSeries();
});

test('create opencast series must have a portal series eloquent model', function () {
    $this->mockHandler->append($this->mockCreateAdminUserResponse());
    expect($this->opencastService->createSeries(SeriesFactory::create()))->toBeInstanceOf(Response::class);
});

it('creates an opencast admin user', function () {
    $this->mockHandler->append($this->mockCreateAdminUserResponse());

    $response = $this->opencastService->createUser(User::factory()->create());

    expect($response->getStatusCode())->toEqual(201);
});

it('throws an exception if create user has no arguments', function () {
    $this->expectException(ArgumentCountError::class);
    $this->opencastService->createUser();
});

it('throws an exception if create user argument is no eloquent model', function () {
    $this->expectException(TypeError::class);
    $this->opencastService->createUser('test');
});

test('create user must have an user eloquent model', function () {
    $this->mockHandler->append($this->mockCreateAdminUserResponse());
    expect($this->opencastService->createUser(User::factory()->create()))->toBeInstanceOf(Response::class);
});

it('does not create an opencast series if server is unavailable', function () {
    $this->mockHandler->append($this->mockServerNotAvailable());
    $series = SeriesFactory::create();
    $response = $this->opencastService->createSeries($series);

    expect($response->getStatusCode())->toEqual(200);
});

it('ingest a media package to opencast', function () {
    Storage::fake('videos');
    $this->mockHandler->append($this->mockIngestMediaPackageResponse());
    $series = SeriesFactory::withClips(1)->create();
    $file = UploadedFile::fake()->create('video.mp4', 1000);
    $response = $this->opencastService->ingestMediaPackage($series->clips()->first(), $file);

    expect($response->getStatusCode())->toEqual(200);
});

it('fetch running workflows for a given series', function () {
    $series = Series::factory()->create(['opencast_series_id' => Str::uuid()]);
    $this->mockHandler->append($this->mockSeriesRunningWorkflowsResponse($series, true));
    $response = $this->opencastService->getEventsByStatus(OpencastWorkflowState::RUNNING, $series);

    expect($response[0]['processing_state'])->toEqual('RUNNING');
    expect($response[0]['is_part_of'])->toEqual($series->opencast_series_id);
});

it('fetches an empty collection for running workflows if no opencast server is available', function () {
    $series = SeriesFactory::create();
    $this->mockHandler->append($this->mockServerNotAvailable());
    $seriesInfo = $this->opencastService->getEventsByStatus(OpencastWorkflowState::RUNNING);

    expect($seriesInfo->isEmpty())->toBeTrue();
});

it('fetch failed events for a given series', function () {
    $series = Series::factory()->create(['opencast_series_id' => Str::uuid()]);
    $this->mockHandler->append($this->mockEventResponse(
        $series,
        OpencastWorkflowState::FAILED
    ));
    $response = $this->opencastService->getFailedEventsBySeries($series);

    expect($response->pluck('processing_state')->contains('FAILED'))->toBeTrue();
    expect($response->first()['is_part_of'])->toEqual($series->opencast_series_id);
});

it('fetches an empty collection for failed events if no opencast server is available', function () {
    $series = SeriesFactory::create();
    $this->mockHandler->append($this->mockServerNotAvailable());
    $seriesInfo = $this->opencastService->getFailedEventsBySeries($series);

    expect($seriesInfo->isEmpty())->toBeTrue();
});

test('if fetches a collection of all finished events for a given series', function () {
    $series = SeriesFactory::withClips(1)->withOpencastID()->create();
    $this->mockHandler->append(
        $this->mockEventResponse($series, OpencastWorkflowState::SUCCEEDED),
        $this->mockEventResponse($series, OpencastWorkflowState::STOPPED)
    );
    $response = $this->opencastService->getProcessedEventsBySeriesID($series->opencast_series_id);

    expect($response)->toBeInstanceOf(Collection::class);
    expect($response->pluck('processing_state')->contains('STOPPED'))->toBeTrue();
    expect($response->pluck('processing_state')->contains('SUCCEEDED'))->toBeTrue();
    expect($response->first()['is_part_of'])->toEqual($series->opencast_series_id);
});

it('fetches a collection of all assets for a given event', function () {
    $this->mockHandler->append(
        $this->mockEventAssets($this->faker->uuid(), $this->faker->uuid()),
        $this->mockEventAssets($this->faker->uuid(), $this->faker->uuid())
    );
    $response = $this->opencastService->getAssetsByEventID($this->faker->uuid);

    expect($response)->toBeInstanceOf(Collection::class);
});

it('fetches all metadata for a single event', function () {
    $series = Series::factory()->create(['opencast_series_id' => Str::uuid()]);
    $this->mockHandler->append($this->mockEventResponse($series, OpencastWorkflowState::SUCCEEDED));
    $response = $this->opencastService->getEventByEventID($this->faker->uuid());

    expect($response)->toBeInstanceOf(Collection::class);
});

it('formats data for opencast create user post request', function () {
    $user = User::factory()->create();
    $data = [
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => [
            'username' => $user->username,
            'password' => $password = Str::random(10),
            'name' => $user->getFullNameAttribute(),
            'email' => $user->email,
            'roles' => "[{'name': 'ROLE_GROUP_MMZ_HIWIS', 'type': 'INTERNAL'}]",
        ],
    ];

    expect($this->opencastService->createAdminUserFormData($user, $password))->toEqual($data);
});

it('formats data for opencast create series post request', function () {
    $series = SeriesFactory::create();
    $title = "{$series->title} / tidesSeriesID: {$series->id}";
    $data = [
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => [
            'metadata' => '[{"flavor": "dublincore/series","title": "EVENTS.EVENTS.DETAILS.CATALOG.EPISODE",
                        "fields": [
                        {
                             "id": "title",
                             "value": "'.$title.'",
                         },
                         {
                             "id": "creator",
                             "value": ["'.$series->owner->name.'"],
                         },
                         ]
                    }]',
            'acl' => '[
					{"allow": true,"role": "ROLE_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_ADMIN","action": "write"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "write"},
			    ]',
            'theme' => config('opencast.default_theme_id'),
        ],
    ];

    expect($this->opencastService->createOpencastSeriesFormData($series))->toEqual($data);
});

it('formats data for opencast ingest media package request', function () {
    Storage::fake('videos');
    $series = SeriesFactory::withClips(2)->create(['opencast_series_id' => Str::uuid()]);
    $file = UploadedFile::fake()->create('video.mp4', 1000);
    $data = [
        'multipart' => [
            [
                'name' => 'flavor',
                'contents' => 'presenter/source',
            ],
            [
                'name' => 'title',
                'contents' => $series->clips()->first()->title,
            ],
            [
                'name' => 'description',
                'contents' => $series->clips()->first()->id,
            ],
            [
                'name' => 'publisher',
                'contents' => $series->clips()->first()->owner->email,
            ],
            [
                'name' => 'isPartOf',
                'contents' => $series->opencast_series_id,
            ],
            [
                'name' => 'file',
                'contents' => file_get_contents($file),
                'filename' => basename($file),
            ],
        ],
    ];

    expect($this->opencastService
        ->ingestMediaPackageFormData($series->clips()->first(), $file))->toEqual($data);
});

beforeEach(function () {
    $this->mockHandler = $this->swapOpencastClient();
    $this->opencastService = app(OpencastService::class);
});
