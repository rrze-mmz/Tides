<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateClipRequest;
use App\Models\Clip;

class ClipsController extends Controller {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function index()
    {
        $clips = Clip::all();

        return view('clips.index', compact('clips'));
    }

    public function create()
    {
        return view('clips.create');
    }

    /**
     * @param Clip $clip
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function show(Clip $clip): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('clips.show', compact('clip'));
    }

    /**
     * @param StoreUpdateClipRequest $request
     * @return Illuminate\Routing\Redirector\Illuminate\Contracts\Foundation\Application\Illuminate\Http\RedirectResponse
     */
    public function store(StoreUpdateClipRequest $request)
    {
        $attributes = $request->validated();

        $attributes['slug'] = $attributes['title'];

        auth()->user()->clips()->create($attributes);

        return redirect('/clips');
    }

    /**
     * @param Clip $clip
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Clip $clip, StoreUpdateClipRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $attributes = $request->validated();

        $clip->setSlugAttribute($attributes['title']);

        $clip->update($attributes);

        return redirect($clip->path());
    }
}
