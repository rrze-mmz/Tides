<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class ShowSeriesController extends Controller
{
    /**
     * Series main page
     *
     * @param Series $series
     * @return View
     * @throws AuthorizationException
     */
    public function show(Series $series): View
    {
        $this->authorize('view-series', $series);

        /*
         * for visitors fetch only clips that containing a video asset
         */
        $series->clips = (auth()->user()?->id === $series->owner_id || auth()->user()?->isAdmin())
            ? $series->clips
            : $series->clips->filter(fn($clip) => $clip->assets()->count() && $clip->isPublic);

        return view('frontend.series.show', compact('series'));
    }
}
