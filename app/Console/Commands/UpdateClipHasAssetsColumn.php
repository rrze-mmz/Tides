<?php

namespace App\Console\Commands;

use App\Models\Clip;
use Illuminate\Console\Command;

class UpdateClipHasAssetsColumn extends Command
{
    protected $signature = 'clip:update-clip-has-assets-column';

    protected $description = 'Checks if a clip has video assets an updates the column in the clips table';

    public function handle()
    {
        $this->info('Counting clips...');
        $bar = $this->output->createProgressBar(Clip::count());
        $bar->start();

        Clip::lazy()->each(function ($clip) {
            $clip->has_video_assets = $clip->hasVideoAsset();
            $clip->save();
            $this->info("Finish clip ID {$clip->id}");
            $this->newLine(2);
        });
        $bar->finish();

        $this->info('All rows updated!');

        return Command::SUCCESS;
    }
}
