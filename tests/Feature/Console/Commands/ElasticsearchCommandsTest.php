<?php

use App\Models\Series;
use App\Services\ElasticsearchService;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

uses(\Tests\Setup\WorksWithElasticsearchClient::class);

it('throws an error for rebuilding if model does not exists', function () {
    $this->artisan('elasticsearch:rebuild-indexes Ser')
        ->expectsOutput("Model doesn't exists");
});

it('shows a counter of models that are rebuild', function () {
    $this->withoutExceptionHandling();
    $series = Series::factory(10)->create();

    $this->mockSingleDocument();
    app(ElasticsearchService::class);

    $this->artisan('elasticsearch:rebuild-indexes Series')
        ->expectsOutput('Series Indexes deleted successfully')
        ->expectsOutput("{$series->count()} Series Indexes created successfully");
});
