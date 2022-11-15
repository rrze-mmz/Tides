<?php

namespace App\Observers;

use App\Models\Clip;
use App\Services\ElasticsearchService;

class ClipObserver
{
    public function __construct(private ElasticsearchService $elasticsearchService)
    {
    }

    /**
     * Handle the Clip "created" event.
     *
     * @param  Clip  $clip
     * @return void
     */
    public function created(Clip $clip): void
    {
        $clip->refresh();

        $clip->folder_id = 'TIDES_ClipID_'.$clip->id;
        if (auth()->user()?->isAdmin()) {
            $clip->supervisor_id = auth()->user()->id;
        }
        $clip->save();

        session()->flash('flashMessage', $clip->title.' '.__FUNCTION__.' successfully');

        $this->elasticsearchService->createIndex($clip);
    }

    /**
     * Handle the Clip "updated" event.
     *
     * @param  Clip  $clip
     * @return void
     */
    public function updated(Clip $clip): void
    {
        if (auth()->user()?->isAdmin() && $clip->supervisor_id !== auth()->user()->id) {
            $clip->supervisor_id = auth()->user()->id;
            $clip->save();
        }

        session()->flash('flashMessage', "{$clip->title} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->updateIndex($clip);
    }

    /**
     * Handle the Clip "deleted" event.
     *
     * @param  Clip  $clip
     * @return void
     */
    public function deleted(Clip $clip): void
    {
        session()->flash('flashMessage', "{$clip->title} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->deleteIndex($clip);
    }

    /**
     * Handle the Clip "restored" event.
     *
     * @param  Clip  $clip
     * @return void
     */
    public function restored(Clip $clip): void
    {
        session()->flash('flashMessage', "{$clip->title} ".__FUNCTION__.' successfully');
    }

    /**
     * Handle the Clip "force deleted" event.
     *
     * @param  Clip  $clip
     * @return void
     */
    public function forceDeleted(Clip $clip): void
    {
        session()->flash('flashMessage', "{$clip->title} ".__FUNCTION__.' successfully');
    }
}
