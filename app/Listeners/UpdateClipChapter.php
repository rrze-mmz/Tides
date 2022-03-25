<?php

namespace App\Listeners;

use App\Events\ChapterDeleted;
use App\Models\Clip;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param ChapterDeleted $event
     * @return void
     */
    public function handle(ChapterDeleted $event)
    {
        Clip::where('chapter_id', $event->chapter->id)->update(['chapter_id' => null]);
    }
}
