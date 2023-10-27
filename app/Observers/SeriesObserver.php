<?php

namespace App\Observers;

use App\Models\Series;
use App\Services\OpenSearchService;

class SeriesObserver
{
    public function __construct(
        readonly private OpenSearchService $openSearchService,
    ) {
    }

    /**
     * Handle the Series "created" event.
     */
    public function created(Series $series): void
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->createIndex($series);
    }

    /**
     * Handle the Series "updated" event.
     */
    public function updated(Series $series): void
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->updateIndex($series);
    }

    /**
     * Handle the Series "deleted" event.
     */
    public function deleted(Series $series): void
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');

        $this->openSearchService->deleteIndex($series);
    }

    /**
     * Handle the Series "restored" event.
     */
    public function restored(Series $series): void
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');
    }

    /**
     * Handle the Series "force deleted" event.
     */
    public function forceDeleted(Series $series): void
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');
    }
}
