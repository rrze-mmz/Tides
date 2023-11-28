<?php

use Tests\Setup\WorksWithOpenSearchClient;

use function Pest\Laravel\artisan;

uses(WorksWithOpenSearchClient::class);

it('throws an error for rebuilding if model does not exists', function () {
    artisan('opensearch:rebuild-indexes')
        ->expectsQuestion('Which search index do you want to rebuild?', 'Series');
});
