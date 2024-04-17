<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function series(Series $series): View
    {
        $clipsViews = $series->clips()->where('is_livestream', false)->orderBy('episode')->get()->map(function ($clip) {
            $views = $clip->views();

            return [
                'name' => $clip->episode.'-'.$clip->title.'['.Carbon::parse($clip->recording_date)->format('Y-m-d').']',
                'count' => $views,
            ];
        });

        $info = [];
        $info['type'] = 'series';
        $info['info'] = $series;
        $info['clipsViews'] = ($clipsViews->pluck('count')->max() == 0) ? [] : $clipsViews;
        $info['geoLocationStats'] = ($clipsViews->pluck('count')->max() == 0)
            ? [] : $series->sumGeoLocationDataGroupedByMonth();

        return view('backend.statistics.index', ['obj' => collect($info)]);
    }

    public function clip(Clip $clip): View
    {
        $info = [];
        $info['type'] = 'clip';
        $info['info'] = $clip;
        $info['clipsViews'] = ($clip->views() > 0) ? $clip->views() : [];
        $info['clipStats'] = ($clip->views() > 0) ? $clip->sumViewsDataGroupedByMonth() : [];
        $info['geoLocationStats'] = ($clip->views() > 0) ? $clip->sumGeoLocationDataGroupedByMonth() : [];

        return view('backend.statistics.index', ['obj' => collect($info)]);
    }
}
