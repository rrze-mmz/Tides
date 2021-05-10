<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Http\Requests\UpdateClipRequest;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ClipsController extends Controller
{


    /**
     * @return View
     */
    public function index(): View
    {
        return view(
            'backend.clips.index',
            [
                'clips' => auth()->user()->clips()->orderBy('title')->limit(10)->get(),
            ]
        );

    }//end index()


    /**
     * Create form for a single clip
     *
     * @return View
     */
    public function create(): View
    {
        return view('backend.clips.create');

    }//end create()


    /**
     * Store a clip in database
     *
     * @param  StoreClipRequest $request
     * @return RedirectResponse
     */
    public function store(StoreClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $clip = auth()->user()->clips()->create(Arr::except($request->validated(), 'tags'));

        $clip->addTags(collect($validated['tags']));

        return redirect($clip->adminPath());

    }//end store()

    /**
     * Edit form for a single clip
     *
     * @param Clip $clip
     * @param OpencastService $opencastService
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Clip $clip, OpencastService $opencastService): View
    {
        $this->authorize('edit', $clip);

        \Debugbar::info($clip->series()->first()->opencast_series_id);

        return view(
            'backend.clips.edit',
            [
                'clip'                         => $clip,
                'opencastConnectionCollection' => $opencastService->getHealth(),
            ]
        );

    }//end edit()


    /**
     * Update a clip in the database
     *
     * @param  Clip              $clip
     * @param  UpdateClipRequest $request
     * @return RedirectResponse
     */
    public function update(Clip $clip, UpdateClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $clip->update(Arr::except($request->validated(), 'tags'));

        $clip->addTags(collect($validated['tags']));

        return redirect($clip->adminPath());

    }//end update()


    /**
     * @param  Clip $clip
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Clip $clip): RedirectResponse
    {
        $this->authorize('edit', $clip);

        $clip->delete();

        return redirect(route('clips.index'));

    }//end destroy()


}//end class
