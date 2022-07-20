<?php

namespace App\Listeners;

use App\Events\DocumentDeleted;
use Illuminate\Support\Facades\Storage;

class DeleteDocumentFile
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
     * @param  DocumentDeleted  $event
     * @return void
     */
    public function handle(DocumentDeleted $event): void
    {
        Storage::disk('documents')->delete($event->document->save_path);
    }
}
