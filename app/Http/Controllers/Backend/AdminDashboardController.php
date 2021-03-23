<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;


class AdminDashboardController extends Controller {

    /**
     * Dashboard for the logged in user
     *
     * @return View
     */
    public function __invoke(): View
    {
        //fetch the Drop zone file and list them
        $files = collect(Storage::disk('video_dropzone')->files())->map(function ($file) {
            return [
                'date_modified' => Carbon::createFromTimestamp(Storage::disk('video_dropzone')->lastModified($file))->format('Y-m-d H:i:s'),
                'name'          => $file
            ];
        });

        return view('backend.dashboard.index', [
            'clips' => auth()->user()->clips()
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get(),
            'files' => $files->sortByDesc('date_modified')
        ]);
    }
}
