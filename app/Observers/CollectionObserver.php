<?php

namespace App\Observers;

use App\Models\Collection;

class CollectionObserver
{
    /**
     * Handle the Collection "created" event.
     *
     * @param Collection $collection
     * @return void
     */
    public function created(Collection $collection)
    {
        session()->flash('flashMessage', $collection->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Collection "updated" event.
     *
     * @param Collection $collection
     * @return void
     */
    public function updated(Collection $collection)
    {
        session()->flash('flashMessage', $collection->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Collection "deleted" event.
     *
     * @param Collection $collection
     * @return void
     */
    public function deleted(Collection $collection)
    {
        session()->flash('flashMessage', $collection->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Collection "restored" event.
     *
     * @param Collection $collection
     * @return void
     */
    public function restored(Collection $collection)
    {
        session()->flash('flashMessage', $collection->title . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the Collection "force deleted" event.
     *
     * @param Collection $collection
     * @return void
     */
    public function forceDeleted(Collection $collection)
    {
        session()->flash('flashMessage', $collection->title . ' ' . __FUNCTION__ . ' successfully');
    }
}
