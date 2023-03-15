<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Series;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChaptersController extends Controller
{
    public function index(Series $series): Factory|View|Application
    {
        return view('backend.seriesChapters.index', compact('series'));
    }

    public function store(Series $series, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
            'title' => ['required', 'string'],
        ]);

        if ($series->chapters()->count() == 0) {
            $validated['default'] = true;
        }

        $series->chapters()->create($validated);

        return to_route('series.chapters.index', $series);
    }

    public function edit(Series $series, Chapter $chapter): Factory|View|Application
    {
        return view('backend.seriesChapters.edit', compact(['series', 'chapter']));
    }

    public function update(Series $series, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'chapters' => ['required', 'array'],
            'chapters.*.position' => ['integer', 'required', 'min:0'],
            'chapters.*.title' => ['string', 'required'],
        ]);

        $chapters = collect($validated['chapters']);

        $chapters->each(function ($ch, $id) {
            $chapter = Chapter::find($id);

            $chapter->position = $ch['position'];
            $chapter->title = $ch['title'];

            $chapter->save();
        });

        return to_route('series.chapters.index', $series);
    }

    public function destroy(Series $series, Chapter $chapter): RedirectResponse
    {
        $chapter->delete();

        return to_route('series.chapters.index', $series);
    }

    public function addClips(Series $series, Chapter $chapter, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'nullable'],
        ]);

        $chapter->addClips($validated['ids']);

        return to_route('series.chapters.edit', [$series, $chapter]);
    }

    public function removeClips(Series $series, Chapter $chapter, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'nullable'],
        ]);

        $chapter->removeClips($validated['ids']);

        return to_route('series.chapters.edit', [$series, $chapter]);
    }
}
