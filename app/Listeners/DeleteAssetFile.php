<?php

namespace App\Listeners;

use App\Events\AssetDeleted;
use Illuminate\Support\Facades\Storage;

class DeleteAssetFile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Update clip poster file and delete the given video file
     */
    public function handle(AssetDeleted $event): void
    {
        //delete poster image file
        if (! is_null($event->asset->clip->posterImage)) {
            Storage::disk('thumbnails')->delete($event->asset->clip->posterImage);
            $event->asset->clip->updatePosterImage();
        }

        //delete the video file
        Storage::disk('videos')->delete($event->asset->path);
    }
}
