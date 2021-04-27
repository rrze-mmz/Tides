<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowSeriesController extends Controller
{

    /**
     * Series page
     *
     * @param Series $series
     * @return View
     */
    public function show(Series $series): View
    {
        return view('frontend.series.show',compact('series'));
    }
}
