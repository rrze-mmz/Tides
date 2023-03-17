<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScanImagesAndUpdateFileSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:update-file-size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan file and updates  it\'s  file size in database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $images = Image::whereNull('file_size');
        $this->info('Starting to update images file size in database');
        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $images->each(function ($image) use ($bar) {
            if (Storage::disk('images')->exists($image->file_name)) {
                $size = Storage::disk('images')->size($image->file_name);
                $this->info("File size for file {$image->description} updated");
                $image->file_size = $size;
                $image->saveQuietly();
            } else {
                $this->info("File not found for image {$image->description}");
            }
            $bar->advance();
            $this->newLine(2);
        });

        $bar->finish();
        $this->info('All files copied!');

        return Command::SUCCESS;
    }
}
