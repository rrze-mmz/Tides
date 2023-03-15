<?php

namespace App\Listeners;

use App\Events\ChapterDeleted;
use App\Models\Clip;

class UpdateClipChapter
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
     * @return void
     */
    public function handle(ChapterDeleted $event)
    {
        Clip::where('chapter_id', $event->chapter->id)->update(['chapter_id' => null]);
    }
}
