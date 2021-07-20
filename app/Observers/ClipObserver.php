<?php

namespace App\Observers;

use App\Models\Clip;

class ClipObserver
{
    /**
     * Handle the Clip "created" event.
     *
     * @param Clip $clip
     * @return void
     */
    public function created(Clip $clip)
    {
        session()->flash('flashMessage', $clip->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Clip "updated" event.
     *
     * @param Clip $clip
     * @return void
     */
    public function updated(Clip $clip)
    {
        session()->flash('flashMessage', $clip->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Clip "deleted" event.
     *
     * @param Clip $clip
     * @return void
     */
    public function deleted(Clip $clip)
    {
        session()->flash('flashMessage', $clip->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Clip "restored" event.
     *
     * @param Clip $clip
     * @return void
     */
    public function restored(Clip $clip)
    {
        session()->flash('flashMessage', $clip->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Clip "force deleted" event.
     *
     * @param Clip $clip
     * @return void
     */
    public function forceDeleted(Clip $clip)
    {
        session()->flash('flashMessage', $clip->title . ' ' . __FUNCTION__ . ' successfully');
    }
}
