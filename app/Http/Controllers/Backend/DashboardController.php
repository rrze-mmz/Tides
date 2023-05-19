<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OpencastWorkflowState;
use App\Services\OpencastService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController
{
    /**
     * Show max 10 of user's series/clips and list dropzone files
     */
    public function __invoke(OpencastService $opencastService): Application|Factory|View
    {
        $opencastEvents = collect([]);

        if (auth()->user()->can('administrate-portal-pages') && $opencastService->getHealth()->contains('pass')) {
            $opencastEvents
                ->put('recording', $opencastService->getEventsByStatus(OpencastWorkflowState::RECORDING))
                ->put('running', $opencastService->getEventsByStatus(OpencastWorkflowState::RUNNING))
                ->put(
                    'scheduled',
                    $opencastService->getEventsByStatusAndByDate(OpencastWorkflowState::SCHEDULED, Carbon::now())
                )
                ->put('failed', $opencastService->getEventsByStatus(OpencastWorkflowState::FAILED))
                ->put('trimming', $opencastService->getEventsWaitingForTrimming());
        }

        return view('backend.dashboard.index', [
            'userSeries' => auth()->user()->getAllSeries()
                ->CurrentSemester()
                ->withLastPublicClip()
                ->orderByDesc('updated_at')
                ->simplePaginate(12),
            'userClips' => auth()->user()->clips()
                ->whereNull('series_id')
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get(),
            'files' => fetchDropZoneFiles(false),
            'opencastEvents' => $opencastEvents,
        ]);
    }
}
