<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SeriesClipsController extends Controller
{

    /**
     *  Show create clip form to assign a clip to series
     *
     * @param Series $series
     * @return View
     * @throws AuthorizationException
     */
    public function create(Series $series): View
    {
        $this->authorize('edit-series', $series);

        return view('backend.seriesClips.create', compact('series'));
    }

    /**
     * Store a clip in a series
     *
     * @param Series $series
     * @param StoreClipRequest $request
     * @return RedirectResponse
     */
    public function store(Series $series, StoreClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $clip = $series->addClip($validated);

        $clip->addTags(collect($validated['tags']));

        $clip->addAcls(collect($validated['acls']));

        $request->session()->flash('clip_created_successfully', 'Clip created successfully');

        return redirect(route('clips.edit', $clip));
    }
}
