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
            if (! is_null($podcast->image_id)) {
                $oldFilePath = 'podcast-files/courseID_'.$podcast->old_podcast_id.'/'.$podcast->cover->file_name;
                if (Storage::exists($oldFilePath)) {
                    Storage::move($oldFilePath, 'images/'.$podcast->cover->file_name);
                    $this->info('Moving file from podcast folder to images folder');
                }
            }
            $podcast->episodes->each(function ($episode) {

                if (! is_null($episode->image_id)) {
                    $oldFilePath = 'podcast-files/clipID_'.$episode->old_episode_id.'/'.$episode->cover->file_name;
                    if (Storage::exists($oldFilePath)) {
                        Storage::move($oldFilePath, 'images/'.$episode->cover->file_name);
                        $this->info('Moving file from podcast episode folder to images folder');
                    }
                }
            });
        });
    }
}
