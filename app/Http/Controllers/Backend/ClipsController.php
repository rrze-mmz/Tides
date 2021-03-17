<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreClipRequest;
use App\Http\Requests\UpdateClipRequest;
use App\Http\Controllers\Controller;
use App\Models\Clip;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClipsController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('backend.clips.index',[
            'clips' => Clip::orderByDesc('updated_at')->limit(18)->get(),
        ]);
    }

    /**
     * Create form for a single clip
     *
     * @return View
     */
    public function create(): View
    {
        return view('backend.clips.create');
    }

    /**
     * Store a clip in database
     *
     * @param StoreClipRequest $request
     * @return RedirectResponse
     */
    public function store(StoreClipRequest $request) : RedirectResponse
    {
        $project = auth()->user()->clips()->create($request->validated());

        return redirect($project->adminPath());
    }

    /**
     * Edit form for a single clip
     *
     * @param Clip $clip
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Clip $clip):View
    {
       $this->authorize('edit', $clip);

        return view('backend.clips.edit', compact('clip'));
    }

    /**
     * Update a clip in the databse
     *
     * @param Clip $clip
     * @param UpdateClipRequest $request
     * @return RedirectResponse
     */
    public function update(Clip $clip, UpdateClipRequest $request):RedirectResponse
    {
        $clip->update($request->validated());

        $clip->refresh();

        return redirect($clip->adminPath());
    }

    /**
     * @param Clip $clip
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Clip $clip): RedirectResponse
    {
        $this->authorize('edit', $clip);

        $clip->delete();

        return redirect(route('clips.index'));
    }
}
