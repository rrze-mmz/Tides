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
        $images = Image::all();
        $this->info('Starting to update images file size in database');
        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $images->each(function ($image) use ($bar) {
            if (Storage::disk('images')->exists($image->file_name)) {
                $size = Storage::disk('images')->size($image->file_name);
                $mime = Storage::disk('images')->mimeType($image->file_name);
                $image->file_size = $size;
                $image->mime_type = $mime;
                $image->saveQuietly();
                $this->info("File size for file {$image->description} updated");
            } else {
                $this->info("File not found for image {$image->description}");
            }
            $bar->advance();
            $this->newLine(2);
        });

        $bar->finish();
        $this->info('All image rows updated!');

        return Command::SUCCESS;
    }
}
