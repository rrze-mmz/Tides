<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Series;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SeriesChaptersController extends Controller
{
    /**
     * @param Series $series
     * @return Factory|View|Application
     */
    public function index(Series $series): Factory|View|Application
    {
        return view('backend.seriesChapters.index', compact('series'));
    }

    /**
     * @param Series $series
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Series $series, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
            'title'    => ['required', 'string']
        ]);

        $series->chapters()->create($validated);

        return to_route('series.chapters.index', $series);
    }

    /**
     * @param Series $series
     * @param Chapter $chapter
     * @return Factory|View|Application
     */
    public function edit(Series $series, Chapter $chapter): Factory|View|Application
    {
        return view('backend.seriesChapters.edit', compact(['series', 'chapter']));
    }

    /**
     * @param Series $series
     * @param Chapter $chapter
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Series $series, Chapter $chapter, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'nullable']
        ]);

        $chapter->addClips($validated);

        return to_route('series.chapters.edit', [$series, $chapter]);
    }
}
