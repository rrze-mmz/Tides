<?php


namespace App\Http\Controllers\Backend;

use App\Enums\OpencastWorkflowState;
use App\Services\OpencastService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class DashboardController
{
    /**
     * Show max 10 of user's series/clips and list dropzone files
     *
     * @param OpencastService $opencastService
     * @return Application|Factory|View
     */
    public function __invoke(OpencastService $opencastService): Application|Factory|View
    {
        $opencastWorkflows = collect([]);

        if (auth()->user()->can('view-opencast-workflows') && $opencastService->getHealth()->isNotEmpty()) {
            $opencastWorkflows->put('running', $opencastService->getAllRunningWorkflows())
                ->put('failed', $opencastService->getEventsByStatus(OpencastWorkflowState::FAILED));
        }

        return view('backend.dashboard.index', [
            'userSeries'        => auth()->user()->series()
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get(),
            'userClips'         => auth()->user()->clips()
                ->whereNull('series_id')
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get(),
            'files'             => fetchDropZoneFiles(false),
            'opencastWorkflows' => $opencastWorkflows
        ]);
    }
}
