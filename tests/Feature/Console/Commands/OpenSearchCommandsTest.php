<?php

use App\Models\Series;
use App\Services\OpenSearchService;
use Tests\Setup\WorksWithOpenSearchClient;

uses(WorksWithOpenSearchClient::class);

it('throws an error for rebuilding if model does not exists', function () {
    $this->artisan('opensearch:rebuild-indexes Ser')
        ->expectsOutput("Model doesn't exists");
});

it('shows a counter of models that are rebuild', function () {
    $this->withoutExceptionHandling();
    $series = Series::factory(10)->create();

    $this->mockSingleDocument();
    app(OpenSearchService::class);

    $this->artisan('opensearch:rebuild-indexes Series')
        ->expectsOutput('Series Indexes deleted successfully')
        ->expectsOutput("{$series->count()} Series Indexes created successfully");
});
