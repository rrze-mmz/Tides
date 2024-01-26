<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Http\Requests\UpdateClipRequest;
use App\Models\Acl;
use App\Models\Clip;
use App\Services\OpencastService;
use Carbon\Carbon;
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
     */
    public function index(): Application|Factory|\Illuminate\Contracts\View\View
    {
        //        return view(
        //            'backend.clips.index',
        //            [
        //                'clips' => (auth()->user()->can('index-all-clips'))
        //                    ? Clip::orderBy('title')->paginate(24)
        //                    : auth()->user()->clips()->orderBy('updated_at')->paginate(12),
        //            ]
        //        );
        return view('backend.clips.index');
    }

    /**
     * Store a clip in database
     */
    public function store(StoreClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['has_time_availability'] && $validated['time_availability_start']->isFuture()) {
            $validated['is_public'] = false;
        }
        $clip = auth()->user()->clips()->create(Arr::except($validated, ['tags', 'acls', 'presenters']));

        $clip->addTags(collect($validated['tags']));
        $clip->addPresenters(collect($validated['presenters']));
        $clip->addAcls(collect($validated['acls']));

        return to_route('clips.edit', $clip);
    }

    /**
     * Create form for a single clip
     */
    public function create(): View
    {
        return view('backend.clips.create');
    }

    /**
     * Edit form for a single clip
     *
     *
     * @throws AuthorizationException
     */
    public function edit(Clip $clip, OpencastService $opencastService): Application|Factory|View
    {
        $this->authorize('edit', $clip);

        return view(
            'backend.clips.edit',
            [
                'clip' => $clip,
                'acls' => Acl::all(),
                'previousNextClipCollection' => $clip->previousNextClipCollection(),
                'opencastConnectionCollection' => $opencastService->getHealth(),
            ]
        );
    }

    /**
     * Update a single clip in the database
     */
    public function update(Clip $clip, UpdateClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['has_time_availability'] && Carbon::parse($validated['time_availability_start'])->isFuture()) {
            $validated['is_public'] = false;
        }
        $clip->update(Arr::except($validated, ['tags', 'acls', 'presenters']));

        $clip->addTags(collect($validated['tags']));
        $clip->addPresenters(collect($validated['presenters']));
        $clip->addAcls(collect($validated['acls']));

        return to_route('clips.edit', $clip);
    }

    /**
     * Delete a single clip
     *
     *
     * @throws AuthorizationException
     */
    public function destroy(Clip $clip): RedirectResponse
    {
        $this->authorize('edit', $clip);

        //deleting a clip will fire an event and trigger a listener
        $clip->delete();

        return ($clip->series_id) ? to_route('series.edit', $clip->series) : to_route('clips.index');
    }
}
