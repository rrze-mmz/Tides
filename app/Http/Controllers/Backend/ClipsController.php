<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreClipRequest;
use App\Http\Requests\UpdateClipRequest;
use App\Http\Controllers\Controller;
use App\Models\Clip;

class ClipsController extends Controller {


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('clips.index',[
            'clips' => Clip::orderByDesc('updated_at')->limit(18)->get(),
        ]);
    }

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
     * @param UpdateClipRequest $request
     * @return Illuminate\Routing\Redirector\Illuminate\Contracts\Foundation\Application\Illuminate\Http\RedirectResponse
     */
    public function store(StoreClipRequest $request)
    {
        $project = auth()->user()->clips()->create($request->validated());

        return redirect($project->adminPath());
    }

    /**
     * Edit form for a single clip
     *
     * @param Clip $clip
     * @return \Illuminate\Contracts\View\actory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function edit(Clip $clip): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
       $this->authorize('edit', $clip);

        return view('clips.edit', compact('clip'));
    }

    /**
     * Update a clip in the databse
     *
     * @param Clip $clip
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Clip $clip, UpdateClipRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {

        $clip->update($request->validated());

        $clip->refresh();

        return redirect($clip->adminPath());
    }

    public function destroy(Clip $clip)
    {
        $this->authorize('edit', $clip);

        $clip->delete();

        return redirect(route('clips.index'));
    }
}
