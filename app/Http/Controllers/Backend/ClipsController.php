<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Content;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Http\Requests\UpdateClipRequest;
use App\Models\Acl;
use App\Models\Clip;
use App\Services\OpencastService;
use App\Services\WowzaService;
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
     */
    public function edit(Clip $clip, OpencastService $opencastService, WowzaService $wowzaService): Application|Factory|View
    {
        $this->authorize('edit', $clip);
        $assetsResolutions = $clip->assets->map(function ($asset) {
            return match (true) {
                $asset->width >= 1920 => 'QHD',
                $asset->width >= 720 && $asset->width < 1920 => 'HD',
                $asset->width >= 10 && $asset->width < 720 => 'SD',
                $asset->type == Content::AUDIO() => 'Audio',
                default => 'PDF/CC'
            };
        })
            ->unique()
            ->filter(function ($value, $key) {
                return $value !== 'PDF/CC';
            });
        $wowzaStatus = $wowzaService->getHealth();
        $urls = collect([]);
        $defaultPlayerUrl = '';
        if ($wowzaStatus) {
            $urls = $wowzaService->vodSecureUrls($clip);

            $defaultPlayerUrl = match (true) {
                empty($urls) => [],
                $urls->has('composite') => $urls['composite'],
                $urls->has('presenter') => $urls['presenter'],
                $urls->has('presentation') => $urls['presentation'],
                default => []
            };
        }

        return view(
            'backend.clips.edit',
            [
                'clip' => $clip,
                'acls' => Acl::all(),
                'previousNextClipCollection' => $clip->previousNextClipCollection(),
                'opencastConnectionCollection' => $opencastService->getHealth(),
                'wowzaStatus' => $wowzaService->getHealth(),
                'defaultVideoUrl' => $defaultPlayerUrl,
                'assetsResolutions' => $assetsResolutions,
                'alternativeVideoUrls' => $urls,
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
