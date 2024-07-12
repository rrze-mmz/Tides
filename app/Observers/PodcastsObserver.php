<?php

namespace App\Observers;

use App\Http\Resources\PodcastResource;
use App\Models\Podcast;
use App\Services\OpenSearchService;

class PodcastsObserver
{
    public function __construct(readonly private OpenSearchService $openSearchService) {}

    /**
     * Handle the Podcast "created" event.
     */
    public function created(Podcast $podcast): void
    {
        session()->flash('flashMessage', "{$podcast->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->createIndex(new PodcastResource($podcast));
    }

    /**
     * Handle the Podcast "updated" event.
     */
    public function updated(Podcast $podcast): void
    {
        session()->flash('flashMessage', "{$podcast->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->updateIndex(new PodcastResource($podcast));
    }

    /**
     * Handle the Podcast "deleted" event.
     */
    public function deleted(Podcast $podcast): void
    {
        //
    }

    /**
     * Handle the Podcast "restored" event.
     */
    public function restored(Podcast $podcast): void
    {
        //
    }

    /**
     * Handle the Podcast "force deleted" event.
     */
    public function forceDeleted(Podcast $podcast): void
    {
        //
    }
}
