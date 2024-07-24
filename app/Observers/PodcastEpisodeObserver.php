<?php

namespace App\Observers;

use App\Http\Resources\PodcastEpisodeResource;
use App\Models\PodcastEpisode;
use App\Services\OpenSearchService;
use Illuminate\Support\Str;

class PodcastEpisodeObserver
{
    public function __construct(readonly private OpenSearchService $openSearchService) {}

    public function created(PodcastEpisode $podcastEpisode): void
    {
        session()->flash('flashMessage', "{$podcastEpisode->title} ".__FUNCTION__.' successfully');
        $podcastEpisode->refresh();
        $podcastEpisode->folder_id = Str::uuid()->toString();
        $podcastEpisode->save();
        $this->openSearchService->createIndex(new PodcastEpisodeResource($podcastEpisode));
    }

    public function updated(PodcastEpisode $podcastEpisode): void
    {
        session()->flash('flashMessage', "{$podcastEpisode->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->updateIndex(new PodcastEpisodeResource($podcastEpisode));
    }

    public function deleted(PodcastEpisode $podcastEpisode): void
    {
        session()->flash('flashMessage', "{$podcastEpisode->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->deleteIndex($podcastEpisode);
    }
}
