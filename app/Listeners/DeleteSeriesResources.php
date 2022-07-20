<?php

namespace App\Listeners;

use App\Events\SeriesDeleted;

class DeleteSeriesResources
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
     * @param  SeriesDeleted  $event
     * @return void
     */
    public function handle(SeriesDeleted $event): void
    {
        //delete all documents
        $event->series->documents->each(function ($document) {
            $document->delete();
        });
    }
}
