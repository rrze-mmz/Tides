<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Series;
use App\Services\ElasticsearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\WorksWithElasticsearchClient;
use Tests\TestCase;

class ElasticsearchCommandsTest extends TestCase
{
    use RefreshDatabase;
    use WorksWithElasticsearchClient;

    /** @test */
    public function it_throws_an_error_for_rebuilding_if_model_does_not_exists(): void
    {
        $this->artisan('elasticsearch:rebuild-indexes Ser')
            ->expectsOutput("Model doesn't exists");
    }

    /** @test */
    public function it_shows_a_counter_of_models_that_are_rebuild(): void
    {
        $this->withoutExceptionHandling();
        $series = Series::factory(10)->create();

        $this->mockSingleDocument();
        app(ElasticsearchService::class);

        $this->artisan('elasticsearch:rebuild-indexes Series')
            ->expectsOutput('Series Indexes deleted successfully')
            ->expectsOutput("{$series->count()} Series Indexes created successfully");
    }
}
