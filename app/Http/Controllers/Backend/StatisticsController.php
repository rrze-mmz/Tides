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
        $clipsViews = $series->clips()->where('is_livestream', false)->orderBy('episode')->get()->map(function ($clip) {
            $views = $clip->views();

            return [
                'name' => $clip->episode.'-'.$clip->title.'['.Carbon::parse($clip->recording_date)->format('Y-m-d').']',
                'count' => $views,
            ];
        });

        $obj = collect([
            'info' => $series,
            'geoLocationStats' => $series->sumGeoLocationDataGroupedByMonth(),
            'clipsViews' => $clipsViews,
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
