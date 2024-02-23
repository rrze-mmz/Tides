<?php

namespace App\Listeners;

use App\Events\SeriesTitleUpdated;
use App\Services\OpencastService;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateOpencastSeriesTitle
{
    /**
     * Create the event listener.
     */
    public function __construct(public OpencastService $opencastService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SeriesTitleUpdated $event): void
    {
        try {
            if ($this->opencastService->getHealth()['status'] === 'pass') {
                $this->opencastService->updateSeries($event->series);
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
