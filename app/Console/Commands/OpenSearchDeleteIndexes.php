<?php

namespace App\Console\Commands;

use App\Services\OpenSearchService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class OpenSearchDeleteIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensearch:delete-indexes {model : The model index}';

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
     *
     * @throws GuzzleException
     */
    public function handle(OpenSearchService $openSearchService): int
    {
        $modelName = Str::singular($this->argument('model'));

        $openSearchService->deleteIndexes(Str::plural($this->argument('model')));

        $this->info("{$modelName} Indexes deleted successfully");

        return Command::SUCCESS;
    }
}
