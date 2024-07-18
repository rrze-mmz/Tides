<?php

namespace App\Observers;

use App\Http\Resources\PodcastEpisodeResource;
use App\Models\PodcastEpisode;
use App\Services\OpenSearchService;

class PodcastEpisodeObserver
{
    public function __construct(readonly private OpenSearchService $openSearchService) {}

    public function created(PodcastEpisode $podcastEpisode): void
    {
        session()->flash('flashMessage', "{$podcastEpisode->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->createIndex(new PodcastEpisodeResource($podcastEpisode));
    }

    public function updated(PodcastEpisode $podcastEpisode): void
    {
        session()->flash('flashMessage', "{$podcastEpisode->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->updateIndex(new PodcastEpisodeResource($podcastEpisode));
    }

    public function deleted(PodcastEpisode $podcastEpisode): void
    {
        session()->flash('flashMessage', "{$podcastEpisode} ".__FUNCTION__.' successfully');

        $this->openSearchService->deleteIndex($podcastEpisode);
    }
}
