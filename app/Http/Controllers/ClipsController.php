<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use Illuminate\Support\Str;

class ClipsController extends Controller {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $clips = Clip::all();

        return view('clips.index', compact('clips'));
    }

    public function show(Clip $clip)
    {
        return view('clips.show', compact('clip'));
    }

    /**
     * @return \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function store(): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {

        $attributes = request()->validate([
                'title' => 'required',
                'description' => 'min:3|max:255'
        ]);

        $attributes['slug'] = $attributes['title'];

        auth()->user()->clips()->create($attributes);

        return redirect('/clips');
    }

    /**
     * @param Clip $clip
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Clip $clip)
    {
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'min:3|max:255'
        ]);

        $clip->setSlugAttribute($attributes['title']);

        $clip->update($attributes);

        return redirect($clip->path());
    }
}
