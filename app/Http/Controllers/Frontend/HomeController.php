<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the latest series and clips for the home page
     */
    public function __invoke(): View
    {
        return view('frontend.homepage.index', [
            'series' => Series::whereHas('clips', function (Builder $query) {
                $query->has('assets');
            })->isPublic()
                ->with(['owner', 'presenters', 'clips'])
                ->withLastPublicClip()
                ->orderByDesc('updated_at')
                ->limit(16)
                ->get(),
            'clips' => Clip::with(['assets', 'presenters'])
                ->public()
                ->whereHas('assets')
                ->single()
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get(),
        ]);
    }
}
