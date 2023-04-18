<?php

namespace App\Console\Commands;

use App\Models\Clip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReAssignClipPosterImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clip:posterImage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create player  poster image directories and update DB';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Counting clips...');
        $bar = $this->output->createProgressBar(Clip::count());
        $bar->start();

        Clip::lazy()->each(function ($clip) use ($bar) {
            $asset = $clip->assets()->orderBy('width', 'desc')->limit(1)->get()->first();

            if ($asset) {
                $clip->posterImage = $asset->player_preview;
                $clip->save();

                Log::info("CLIP POSTER for ID :{$clip->id} IS {$clip->posterImage}");
                $bar->advance();
            } else {
                $this->info("Assets not found for Clip ID {$clip->id}! Skipping...");
                $bar->advance();
                $this->newLine(2);
            }

            $this->info("Finish clip ID {$clip->id}");
            $this->newLine(2);
        });

        $bar->finish();

        $this->info('All rows updated!');

        return Command::SUCCESS;
    }
}
