<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Http\Request;

class ShowSeriesController extends Controller
{

    public function show(Series $series)
    {
        return view('frontend.series.show',compact('series'));
    }
}
