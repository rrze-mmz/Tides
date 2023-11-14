<?php

namespace App\Console\Commands;

use App\Services\OpenSearchService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;

class OpenSearchRebuildIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensearch:rebuild-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild OpenSearch indexes ';

    /**
     * Execute the console command.
     */
    public function handle(OpenSearchService $openSearchService)
    {

        $modelName = select(
            label: 'Which search index do you want to rebuild?',
            options: ['Series', 'Clip'],
            default: 'Series',
            hint: 'Clips may take longer as expected'
        );

        $modelClass = "App\\Models\\{$modelName}";
        $modelResource = "App\\Http\\Resources\\{$modelName}Resource";

        if (! class_exists($modelClass)) {
            $this->error("Model doesn't exists");

            return Command::FAILURE;
        }

        $openSearchService->deleteIndexes(Str::plural($modelName));

        $this->info($modelName.' Indexes deleted successfully');

        $modelClass::chunk(200, function (Collection $models) use ($openSearchService, $modelResource) {
            $models->each(function ($model) use ($openSearchService, $modelResource) {
                //create the necessary json resource
                if ($model) {
                    $openSearchService->createIndex(new $modelResource($model));
                }
            });
        });

        $this->info("{$modelClass::count()} ".Str::plural($modelName).' Indexes created successfully');

        return Command::SUCCESS;
    }
}
