<?php

use App\Enums\OpencastWorkflowState;
use App\Enums\Role;
use App\Services\OpencastService;
use Facades\Tests\Setup\SeriesFactory;
use Tests\Setup\WorksWithOpencastClient;

use function Pest\Laravel\post;
use function PHPUnit\Framework\assertNotSame;

uses(WorksWithOpencastClient::class);

beforeEach(function () {
    // TODO: Change the autogenerated stub
    $mockHandler = $this->swapOpencastClient();
    $this->opencastService = app(OpencastService::class);
    $this->series = SeriesFactory::withOpencastID()->create();

    $mockHandler->append(
        $this->mockHealthResponse(),
        $this->mockSeriesMetadata($this->series),
        $this->mockSeriesRunningWorkflowsResponse($this->series),
        $this->mockEventResponse($this->series, OpencastWorkflowState::RUNNING),
        $this->mockCreateSeriesResponse()
    );
});

it('allows create opencast series only for portal admins', function () {
    $mockHandler = $this->swapOpencastClient();
    $this->opencastService = app(OpencastService::class);
    auth()->logout();

    post(route('series.opencast.createSeries', $this->series))->assertRedirectToRoute('login');

    signInRole(Role::MODERATOR);
    post(route('series.opencast.createSeries', $this->series))->assertForbidden();

    auth()->logout();
    $ownedSeries = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    post(route('series.opencast.createSeries', $ownedSeries))->assertForbidden();
    auth()->logout();

    signInRole(Role::ADMIN);
    $mockHandler->append(
        $this->mockHealthResponse(), //health
        $this->mockNoResultsResponse(), // seriesInfo
        $this->mockNoResultsResponse(), //recording
        $this->mockNoResultsResponse(), //running
        $this->mockNoResultsResponse(), //scheduled
        $this->mockNoResultsResponse(), //failed
        $this->mockNoTrimmingResultsResponse(), //trimming
        $this->mockNoResultsResponse(), //upcoming
        $this->mockCreateSeriesResponse(),
    );

    post(route('series.opencast.createSeries', $ownedSeries))->assertRedirect();
});

it('updates opencast series id for the given series', function () {
    $mockHandler = $this->swapOpencastClient();
    $this->opencastService = app(OpencastService::class);
    $oldSeriesId = $this->series->opencast_series_id;

    signInRole(Role::ADMIN);

    $mockHandler->append(
        $this->mockHealthResponse(), //health
        $this->mockNoResultsResponse(), // seriesInfo
        $this->mockNoResultsResponse(), //recording
        $this->mockNoResultsResponse(), //running
        $this->mockNoResultsResponse(), //scheduled
        $this->mockNoResultsResponse(), //failed
        $this->mockNoTrimmingResultsResponse(), //trimming
        $this->mockNoResultsResponse(), //upcoming
        $this->mockCreateSeriesResponse(),
    );
    post(route('series.opencast.createSeries', $this->series));
    $this->series->refresh();
    assertNotSame($oldSeriesId, $this->series->opencast_series_id);
});

test('an admin can update opencast acl for a series', function () {
    $mockHandler = $this->swapOpencastClient();
    $this->opencastService = app(OpencastService::class);
    auth()->logout();

    signInRole(Role::MODERATOR);
    post(route('series.opencast.updateSeriesAcl', $this->series))->assertForbidden();

    auth()->logout();
    $mockHandler->append(
        $this->mockHealthResponse(), //health
        $this->mockNoResultsResponse(), // seriesInfo
        $this->mockNoResultsResponse(), //recording
        $this->mockNoResultsResponse(), //running
        $this->mockNoResultsResponse(), //scheduled
        $this->mockNoResultsResponse(), //failed
        $this->mockNoTrimmingResultsResponse(), //trimming
        $this->mockNoResultsResponse(), //upcoming
        $this->mockCreateSeriesResponse(),
    );

    signInRole(Role::ADMIN);
    post(route('series.opencast.updateSeriesAcl', $this->series))->assertRedirect();
});

afterEach(function () {
    // TODO: Change the autogenerated stub
});
