<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Http\Requests\UpdateClipRequest;
use App\Models\Acl;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ClipsController extends Controller
{
    /**
     * Index all clips in admin portal. In case of simple user list only users clips
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function index(): Application|Factory|\Illuminate\Contracts\View\View
    {
        return view(
            'backend.clips.index',
            [
                'clips' => (auth()->user()->can('index-all-clips'))
                    ? Clip::orderBy('title')->paginate(12)
                    : auth()->user()->clips()->orderBy('updated_at')->paginate(12),
            ]
        );
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
    public function store(StoreClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();


        $clip = auth()->user()->clips()->create(Arr::except($validated, ['tags', 'acls', 'presenters']));

        $clip->addTags(collect($validated['tags']));
        $clip->addPresenters(collect($validated['presenters']));
        $clip->addAcls(collect($validated['acls']));

        return to_route('clips.edit', $clip);
    }

    /**
     * Edit form for a single clip
     *
     * @param Clip $clip
     * @param OpencastService $opencastService
     * @return View|Application|Factory
     * @throws AuthorizationException
     */
    public function edit(Clip $clip, OpencastService $opencastService): Application|Factory|View
    {
        $this->authorize('edit', $clip);

        return view(
            'backend.clips.edit',
            [
                'clip'                         => $clip,
                'acls'                         => Acl::all(),
                'previousNextClipCollection'   => $clip->previousNextClipCollection(),
                'opencastConnectionCollection' => $opencastService->getHealth(),
            ]
        );
    }

    /**
     * Update a single clip in the database
     *
     * @param Clip $clip
     * @param UpdateClipRequest $request
     * @return RedirectResponse
     */
    public function update(Clip $clip, UpdateClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $clip->update(Arr::except($validated, ['tags', 'acls', 'presenters']));

        $clip->addTags(collect($validated['tags']));
        $clip->addPresenters(collect($validated['presenters']));
        $clip->addAcls(collect($validated['acls']));

        return to_route('clips.edit', $clip);
    }

    /**
     * Delete a single clip
     *
     * @param Clip $clip
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Clip $clip): RedirectResponse
    {
        $this->authorize('edit', $clip);

        $clip->delete();

        if ($clip->series_id) {
            return redirect(route('series.edit', $clip->series));
        }

        return to_route('clips.index');
    }
}
