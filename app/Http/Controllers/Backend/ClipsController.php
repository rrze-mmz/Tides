<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreUpdateClipRequest;
use App\Http\Controllers\Controller;
use App\Models\Clip;

class ClipsController extends Controller {

    /**
     * Create form for a single clip
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('clips.create');
    }


    /**
     * Store a clip in database
     *
     * @param StoreUpdateClipRequest $request
     * @return Illuminate\Routing\Redirector\Illuminate\Contracts\Foundation\Application\Illuminate\Http\RedirectResponse
     */
    public function store(StoreUpdateClipRequest $request)
    {
        $attributes = $request->validated();

        $attributes['slug'] = $attributes['title'];

        $project = auth()->user()->clips()->create($attributes);

        return redirect($project->adminPath());
    }

    /**
     * Edit form for a single clip
     *
     * @param Clip $clip
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function edit(Clip $clip): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('clips.edit', compact('clip'));
    }

    /**
     * Update a clip in the databse
     *
     * @param Clip $clip
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Clip $clip, StoreUpdateClipRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $attributes = $request->validated();

        $clip->setSlugAttribute($attributes['title']);

        $clip->update($attributes);

        return redirect($clip->adminPath());
    }
}
