<?php


namespace App\Http\Controllers\Backend;

use App\Services\OpencastService;
use Illuminate\View\View;

class DashboardController
{
    /**
     * Show max 10 of user's series/clips and list dropzone files
     *
     * @param OpencastService $opencastService
     * @return View
     */
    public function __invoke(OpencastService $opencastService): View
    {
        $opencastWorkflows = [];
        if (auth()->user()->can('view-opencast-workflows')) {
            $opencastWorkflows = [
                'running' => $opencastService->getAllRunningWorkflows(),
                'failed'  => $opencastService->getEventsByStatus('failed')
            ];

        }
        return view('backend.dashboard.index', [
            'userSeries'               => auth()->user()->series()
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get(),
            'userClips'                => auth()->user()->clips()
                ->whereNull('series_id')
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get(),
            'files'                    => fetchDropZoneFiles(),
            'opencastRunningWorkflows' => $opencastWorkflows
        ]);
    }
}
