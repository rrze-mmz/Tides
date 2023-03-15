<?php

namespace App\Console\Commands;

use App\Services\ElasticsearchService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ElasticsearchRebuildIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:rebuild-indexes {model : The model index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild indexes for a given model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     *
     * @throws GuzzleException
     */
    public function handle(ElasticsearchService $elasticsearchService): int
    {
        $modelName = Str::singular($this->argument('model'));

        $modelClass = "App\\Models\\{$modelName}";

        if (! class_exists($modelClass)) {
            $this->error("Model doesn't exists");

            return Command::FAILURE;
        }

        $modelCollection = $modelClass::all();

        $elasticsearchService->deleteIndexes(Str::plural($this->argument('model')));

        $this->info($modelName.' Indexes deleted successfully');

        $modelCollection->each(function ($series) use ($elasticsearchService) {
            $elasticsearchService->createIndex($series);
        });

        $this->info("{$modelCollection->count()} ".Str::plural($modelName).' Indexes created successfully');

        return Command::SUCCESS;
    }
}
