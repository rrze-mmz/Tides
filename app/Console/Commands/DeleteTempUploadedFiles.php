<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DeleteTempUploadedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-temp-uploaded-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary uploaded files older than 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $directories = Storage::directories('tmp');

        foreach ($directories as $directory) {
            $files = Storage::allFiles($directory);
            $directoryLastModified = collect($files)->reduce(function ($carry, $file) {
                $fileModified = Storage::lastModified($file);

                return $fileModified > $carry ? $fileModified : $carry;
            }, 0);

            $directoryLastModified = Carbon::createFromTimestamp($directoryLastModified);
            $hoursDifference = floor(abs(now()->floatDiffInHours($directoryLastModified)));

            if ($hoursDifference > 24) {
                Storage::deleteDirectory($directory);
            }
        }
    }
}
