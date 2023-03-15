<?php

namespace App\Observers;

use App\Models\Collection;

class CollectionObserver
{
    /**
     * Handle the Collection "created" event.
     *
     * @return void
     */
    public function created(Collection $collection)
    {
        session()->flash('flashMessage', "{$collection->title} ".__FUNCTION__.'successfully');
    }

    /**
     * Handle the Collection "updated" event.
     *
     * @return void
     */
    public function updated(Collection $collection)
    {
        session()->flash('flashMessage', "{$collection->title} ".__FUNCTION__.'successfully');
    }

    /**
     * Handle the Collection "deleted" event.
     *
     * @return void
     */
    public function deleted(Collection $collection)
    {
        session()->flash('flashMessage', "{$collection->title} ".__FUNCTION__.'successfully');
    }

    /**
     * Handle the Collection "restored" event.
     *
     * @return void
     */
    public function restored(Collection $collection)
    {
        session()->flash('flashMessage', "{$collection->title} ".__FUNCTION__.'successfully');
    }

    /**
     * Handle the Collection "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Collection $collection)
    {
        session()->flash('flashMessage', "{$collection->title} ".__FUNCTION__.'successfully');
    }
}
