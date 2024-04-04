<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{
    public function series(Series $series)
    {
        $statistics = ['another' => 'one'];
        $clipViews = $series->clips()->orderBy('episode')->get()->mapWithKeys(function ($clip) {
            $views = $clip->views();

            return [
                $clip->episode.'-'.$clip->title.'['.Carbon::parse($clip->recording_date)->format('Y-m-d').']' => $views,
            ];
        });
        $obj = collect([
            'info' => $series,
            'geoLocationStats' => $series->sumGeoLocationDataGroupedByMonth(),
            'clipsViews' => $clipViews,
        ]);

        return view('backend.statistics.index', [
            'statistics' => $statistics,
            'obj' => $obj,
        ]);
    }

    public function clip(Clip $clip)
    {
        return $clip->toJson();
    }
}
