<?php

namespace App\Console\Commands;

use App\Models\Podcast;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RenamePodcastFolders extends Command
{
    /**
     *  After migration is finished this command and also the corresponding test can be safely deleted
     *
     * @var string
     */
    protected $signature = 'podcasts:rename-folders';

    protected $description = 'Rename folders in storage/app/podcast-files from courseID_oldID to podcastID_newID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $podcasts = Podcast::all();

        $this->info('Looking for podcast files...');

        $podcasts->each(function ($podcast) {
            if (Storage::exists('podcasts-files/covers/courseID_'.$podcast->old_podcast_id)) {
                $oldFolderPath = 'podcasts-files/covers/courseID_'.$podcast->old_podcast_id;
                $newFolderPath = 'podcasts-files/covers/podcastID_'.$podcast->id;
                $this->info("Renamed {$oldFolderPath} to {$newFolderPath}");
                Storage::move($oldFolderPath, $newFolderPath);
            }
            $podcast->episodes->each(function ($episode) {
                if (Storage::exists('podcasts-files/covers/clipID_'.$episode->old_episode_id)) {
                    $oldFolderPath = 'podcasts-files/covers/clipID_'.$episode->old_episode_id;
                    $newFolderPath =
                        'podcasts-files/covers/podcastID_'.$episode->podcast->id.'/episodeID_'.$episode->id;
                    $this->info("Renamed {$oldFolderPath} to {$newFolderPath}");
                    Storage::move($oldFolderPath, $newFolderPath);
                }
            });
        });
    }
}
