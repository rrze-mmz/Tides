<?php

namespace App\Console\Commands;

use App\Enums\Acl;
use App\Models\Asset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdateAssetSymbolicLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:update-assets-symbolic-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all symbolic links for open clips assets';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $assets = Asset::formatVideo()
            ->orWhere->formatAudio();

        $bar = $this->output->createProgressBar($count = $assets->count());
        $bar->start();

        $this->info("Processing {$count} Audio/Video assets");

        $assets->each(function ($asset) use ($bar) {
            if (Storage::disk('assetsSymLinks')->exists("{$asset->guid}.".getFileExtension($asset))
                && (! $asset->clip->is_public || $asset->clip->acls->pluck('id')->doesntContain(Acl::PUBLIC()))) {
                unlink(Storage::disk('assetsSymLinks')->path("{$asset->guid}.".getFileExtension($asset)));
                $this->info('Clip Acl changed. Deleting symbolic link...');
                $this->newLine(2);
                $bar->advance();
            } elseif ($asset->clip->is_public && $asset->clip->acls->pluck('id')->contains(Acl::PUBLIC())) {
                symlink(
                    Storage::disk('videos')->path($asset->path),
                    Storage::disk('assetsSymLinks')->path("{$asset->guid}.".getFileExtension($asset))
                );
                $this->info("Symbolik link for clip:{$asset->clip->title} created successfully");
                $this->newLine(2);
                $bar->advance();
            } else {
                $this->info("Clip:{$asset->clip->title} is protected. Moving to the next one");
                $this->newLine(2);
                $bar->advance();
            }
        });
        $bar->finish();

        $this->info('All links created');

        return Command::SUCCESS;
    }
}
