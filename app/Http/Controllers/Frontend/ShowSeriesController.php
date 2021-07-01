<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowSeriesController extends Controller
{

    /**
     * Series main page
     *
     * @param Series $series
     * @return View
     */
    public function show(Series $series): View
    {
        $this->authorize('view-series', $series);

        /*
         * modify series clips and if user is not owner fetch only clips that has video assets
         */
        $series->clips = (auth()->user()?->id === $series->owner_id)
            ? $series->clips
            :  $series->clips->filter(fn($clip)=> $clip->assets()->count());

        return view('frontend.series.show', compact('series'));
    }
}
