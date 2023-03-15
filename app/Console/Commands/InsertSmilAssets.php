<?php

namespace App\Console\Commands;

use App\Models\Clip;
use App\Services\WowzaService;
use DOMException;
use Illuminate\Console\Command;

class InsertSmilAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smil:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert SMIL file paths to database. It does not create smil files';

    /**
     * Execute the console command.
     *
     *
     * @throws DOMException
     */
    public function handle(WowzaService $wowzaService): int
    {
        $this->info('Counting clips...');
        $bar = $this->output->createProgressBar(Clip::count());

        $bar->start();

        Clip::lazy()->each(function ($clip) use ($wowzaService) {
            $wowzaService->createSmilFile($clip);

            $this->info("Finish clip ID {$clip->id}");
            $this->newLine(2);
        });

        $bar->finish();

        $this->info('All smils generated!');

        return Command::SUCCESS;
    }
}
