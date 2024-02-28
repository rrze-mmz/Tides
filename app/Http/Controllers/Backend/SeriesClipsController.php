<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Models\Clip;
use App\Models\Semester;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SeriesClipsController extends Controller
{
    /**
     *  Show create clip form to assign a clip to series
     *
     *
     * @throws AuthorizationException
     */
    public function create(Series $series): View
    {
        $this->authorize('edit-series', $series);

        return view('backend.seriesClips.create', compact('series'));
    }

    /**
     * Store a clip in a series
     */
    public function store(Series $series, StoreClipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $clip = $series->addClip($validated);

        $clip->addTags(collect($validated['tags']));
        $clip->addAcls(collect($validated['acls']));

        $request->session()->flash('clip_created_successfully', 'Clip created successfully');

        return to_route('clips.edit', $clip);
    }

    /**
     * @throws AuthorizationException
     */
    public function listSeries(Clip $clip): View
    {
        $this->authorize('edit', $clip);

        return view('backend.seriesClips.listSeries', [
            'clip' => $clip,
            'series' => (auth()->user()->isAdmin())
                    ? Series::orderByDesc('updated_at')->paginate(12)
                    : auth()->user()->accessableSeries()->paginate(12),
        ]);
    }

    /**
     * Assign the given series to the given clip
     *
     *
     * @throws AuthorizationException
     */
    public function assign(Series $series, Clip $clip): RedirectResponse
    {
        $this->authorize('edit-series', $series);

        $clip->episode = $series->clips()->count() + 1;
        $clip->series()->associate($series);
        $clip->save();

        return to_route('clips.edit', $clip);
    }

    /**
     * @throws AuthorizationException
     */
    public function remove(Clip $clip): RedirectResponse
    {
        $this->authorize('edit-clips', $clip);

        $clip->episode = 1;
        $clip->series()->dissociate();
        $clip->save();

        return to_route('clips.edit', $clip);
    }

    public function listClips(Series $series): Factory|View|Application
    {
        $clips = Clip::select('id', 'title', 'slug', 'episode', 'is_public')
            ->where('series_id', $series->id)
            ->addSelect(
                [
                    'semester' => Semester::select('name')
                        ->whereColumn('id', 'clips.semester_id')
                        ->take(1),
                ]
            )
            ->with('acls')
            ->orderBy('episode')->get();

        return view('backend.seriesClips.reorder', compact('series', 'clips'));
    }

    /**
     * Changes clips episodes for a series
     */
    public function reorder(Series $series, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'episodes' => ['required', 'array'],
            'episodes.*' => ['integer'],
        ]);

        $series->reorderClips(collect($validated['episodes']));

        return to_route('series.edit', $series);
    }
}
