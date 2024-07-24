<?php

namespace App\Observers;

use App\Http\Resources\PodcastResource;
use App\Models\Podcast;
use App\Services\OpenSearchService;

class PodcastsObserver
{
    public function __construct(readonly private OpenSearchService $openSearchService) {}

    public function created(Podcast $podcast): void
    {
        session()->flash('flashMessage', "{$podcast->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->createIndex(new PodcastResource($podcast));
    }

    public function updated(Podcast $podcast): void
    {
        session()->flash('flashMessage', "{$podcast->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->updateIndex(new PodcastResource($podcast));
    }

    public function deleted(Podcast $podcast): void
    {
        session()->flash('flashMessage', "{$podcast->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->deleteIndex($podcast);

        $podcast->episodes->each(function ($episode) {
            $episode->delete();
        });
    }
}
