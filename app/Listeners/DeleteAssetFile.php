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
     * Handle the event.
     *
     * @param  AssetDeleted  $event
     * @return void
     */
    public function handle(AssetDeleted $event)
    {
       Storage::delete($event->asset->uploadedFile);
    }
}
