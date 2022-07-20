<?php

namespace App\Console\Commands;

use App\Services\ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ElasticsearchDeleteIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:delete-indexes {model : The model index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete indexes for a given model';

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
     * @param  ElasticsearchService  $elasticsearchService
     * @return int
     */
    public function handle(ElasticsearchService $elasticsearchService): int
    {
        $modelName = Str::singular($this->argument('model'));

        $elasticsearchService->deleteIndexes(Str::plural($this->argument('model')));

        $this->info($modelName.' Indexes deleted successfully');

        return Command::SUCCESS;
    }
}
