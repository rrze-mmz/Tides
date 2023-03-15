<?php

namespace App\Observers;

use App\Models\Series;
use App\Services\ElasticsearchService;

class SeriesObserver
{
    public function __construct(private ElasticsearchService $elasticsearchService)
    {
    }

    /**
     * Handle the Series "created" event.
     *
     * @return void
     */
    public function created(Series $series)
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->createIndex($series);
    }

    /**
     * Handle the Series "updated" event.
     *
     * @return void
     */
    public function updated(Series $series)
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->updateIndex($series);
    }

    /**
     * Handle the Series "deleted" event.
     *
     * @return void
     */
    public function deleted(Series $series)
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->deleteIndex($series);
    }

    /**
     * Handle the Series "restored" event.
     *
     * @return void
     */
    public function restored(Series $series)
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');
    }

    /**
     * Handle the Series "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Series $series)
    {
        session()->flash('flashMessage', "{$series->title} ".__FUNCTION__.' successfully');
    }
}
