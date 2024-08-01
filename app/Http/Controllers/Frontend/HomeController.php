<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the latest series and clips for the home page
     */
    public function __invoke(): View
    {
        $series = Series::select('id', 'slug', 'title', 'owner_id')
            ->isPublic()
            ->hasClipsWithAssets()
            ->with(['owner', 'presenters:id'])
            ->withLastPublicClip()
            ->orderByDesc(Clip::select('recording_date')
                ->where('has_video_assets', 1)
                ->where('is_public', 1)
                ->whereColumn('series_id', 'series.id')
                ->limit(1)
                ->orderByDesc('recording_date'))
            ->limit(16)
            ->get();

        return view('frontend.homepage.index', [
            'series' => $series,
            'clips' => Clip::select('id', 'slug', 'title', 'recording_date', 'owner_id')
                ->with(['presenters:id'])
                ->public()
                ->withVideoAssets()
                ->single()
                ->orderByDesc('recording_date')
                ->limit(12)
                ->get(),
        ]);
    }
}
