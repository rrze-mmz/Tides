<?php

namespace App\Observers;

use App\Models\Channel;

class ChannelObserver
{
    /**
     * Handle the Channel "created" event.
     */
    public function created(Channel $channel): void
    {
        //
    }

    /**
     * Handle the Channel "updated" event.
     */
    public function updated(Channel $channel): void
    {
        session()->flash('flashMessage', "{$channel->url_handle} Channel ".__FUNCTION__.' successfully');
    }

    /**
     * Handle the Channel "deleted" event.
     */
    public function deleted(Channel $channel): void
    {
        //
    }

    /**
     * Handle the Channel "restored" event.
     */
    public function restored(Channel $channel): void
    {
        //
    }

    /**
     * Handle the Channel "force deleted" event.
     */
    public function forceDeleted(Channel $channel): void
    {
        //
    }
}
