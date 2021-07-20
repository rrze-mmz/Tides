<?php


namespace App\Http\Controllers\Backend;

use Illuminate\View\View;

class DashboardController
{
    /**
     * Show max 10 of user's series/clips and list dropzone files
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('backend.dashboard.index', [
            'series' => auth()->user()->series()
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get(),
            'clips'  => auth()->user()->clips()
                ->whereNull('series_id')
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get(),
            'files'  => fetchDropZoneFiles()
        ]);
    }
}
