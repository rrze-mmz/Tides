<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Models\Series;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class SeriesClipsController extends Controller {

    /**
     *  Show clip form for a series
     *
     * @param Series $series
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Series $series): View
    {
        $this->authorize('edit', $series);

        return view('backend.seriesClips.create', compact('series'));
    }

    /**
     * Store a clip with a series id
     *
     * @param Series $series
     * @param StoreClipRequest $request
     * @return View
     */
    public function store(Series $series, StoreClipRequest $request): View
    {
//        $request = Arr::add($request->validated(), 'owner_id', auth()->id());
        $validated = $request->validated();

        $series->addClip($validated);

        return view('backend.series.edit', compact('series'));
    }
}
