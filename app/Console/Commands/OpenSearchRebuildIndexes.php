<?php

namespace App\Console\Commands;

use App\Services\OpenSearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class OpenSearchRebuildIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensearch:rebuild-indexes {model : the model to be indexed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild OpenSearch indexes for the given model';

    /**
     * Execute the console command.
     */
    public function handle(OpenSearchService $openSearchService)
    {
        $modelName = Str::singular($this->argument('model'));

        $modelClass = "App\\Models\\{$modelName}";

        if (! class_exists($modelClass)) {
            $this->error("Model doesn't exists");

            return Command::FAILURE;
        }

        $modelCollection = $modelClass::all();

        $openSearchService->deleteIndexes(Str::plural($this->argument('model')));

        $this->info($modelName.' Indexes deleted successfully');

        $modelCollection->each(function ($series) use ($openSearchService) {
            $openSearchService->createIndex($series);
        });

        $this->info("{$modelCollection->count()} ".Str::plural($modelName).' Indexes created successfully');

        return Command::SUCCESS;
    }
}
