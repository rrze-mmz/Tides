<?php

namespace App\Observers;

use App\Models\Presenter;
use App\Services\ElasticsearchService;

class PresenterObserver
{
    public function __construct(private ElasticsearchService $elasticsearchService)
    {
    }

    /**
     * Handle the Presenter "created" event.
     *
     * @return void
     */
    public function created(Presenter $presenter)
    {
        session()->flash('flashMessage', "{$presenter->getFullNameAttribute()} ".__FUNCTION__.' successfully');
        $this->elasticsearchService->createIndex($presenter);
    }

    /**
     * Handle the Presenter "updated" event.
     *
     * @return void
     */
    public function updated(Presenter $presenter)
    {
        session()->flash('flashMessage', "{$presenter->getFullNameAttribute()} ".__FUNCTION__.' successfully');
        $this->elasticsearchService->updateIndex($presenter);
    }

    /**
     * Handle the Presenter "deleted" event.
     *
     * @return void
     */
    public function deleted(Presenter $presenter)
    {
        session()->flash('flashMessage', "{$presenter->getFullNameAttribute()} ".__FUNCTION__.' successfully');
        $this->elasticsearchService->deleteIndex($presenter);
    }
}
