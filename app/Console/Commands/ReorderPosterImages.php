<?php

namespace App\Console\Commands;

use App\Models\Clip;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ReorderPosterImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:reorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create player  poster image directories and update DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Counting clips...');

        $bar = $this->output->createProgressBar(Clip::count());

        $bar->start();

        Clip::lazy()->each(function ($clip) use ($bar) {
            Storage::disk('thumbnails')->makeDirectory('clip_'.$clip->id);
            $assets = $clip->assets;

            if ($assets->count() > 0) {
                $assets->each(function ($asset) use ($clip) {
                    if (Storage::exists('player_previews/'.$asset->id.'_preview.img')) {
                        $path = Storage::disk('thumbnails')->putFile(
                            'clip_'.$clip->id,
                            new File(storage_path('app/player_previews/').$asset->id.'_preview.img')
                        );
                        $clip->posterImage = $path;
                        $clip->save();
                    }
                });
                $bar->advance();
            } else {
                $this->info('Assets not found for Clip ID '.$clip->id.'! Skipping...');
                $bar->advance();
                $this->newLine(2);
            }

            $this->info('Finish clip ID '.$clip->id);
            $this->newLine(2);
        });

        $bar->finish();

        $this->info('All files copied!');

        return Command::SUCCESS;
    }
}