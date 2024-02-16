<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;
use Illuminate\Contracts\Database\Eloquent\Builder as ContractsBuilder;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the latest series and clips for the home page
     */
    public function __invoke(): View
    {
        $series = Series::whereHas('clips.assets')->isPublic()
            ->with(['owner', 'presenters', 'clips' => function (ContractsBuilder $query) {
                $query->whereHas('assets');
            }, 'clips.assets'])
            ->withLastPublicClip()
            ->orderByDesc(Clip::select('recording_date')
                ->whereColumn('series_id', 'series.id')
                ->has('assets')
                ->limit(1)
                ->orderByDesc('recording_date'))
            ->limit(16)
            ->get();

        return view('frontend.homepage.index', [
            'series' => $series,
            'clips' => Clip::with(['assets', 'presenters'])
                ->public()
                ->whereHas('assets')
                ->single()
                ->orderByDesc('recording_date')
                ->limit(12)
                ->get(),
        ]);
    }
}
