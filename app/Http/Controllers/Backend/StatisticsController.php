<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;

class StatisticsController extends Controller
{
    public function series(Series $series)
    {
        return $series->toJson();
    }

    public function clip(Clip $clip)
    {
        return $clip->toJson();
    }
}
