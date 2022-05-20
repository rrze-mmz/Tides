<?php

namespace App\Listeners;

use App\Events\ClipDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteClipResources
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
     * @param ClipDeleted $event
     * @return void
     */
    public function handle(ClipDeleted $event): void
    {
        $event->clip->documents->each(function ($document) {
            $document->delete();
        });
    }
}
