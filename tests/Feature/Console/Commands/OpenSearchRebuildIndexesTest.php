<?php

use Tests\Setup\WorksWithOpenSearchClient;

use function Pest\Laravel\artisan;

uses(WorksWithOpenSearchClient::class);

it('throws an error for rebuilding if model does not exists', function () {
    artisan('opensearch:rebuild-indexes NotExistingModelName')
        ->expectsOutput("Model doesn't exists");
});
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
