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

class SeriesChaptersController extends Controller
{
    public function index(Series $series): Factory|View|Application
    {
        return view('backend.seriesChapters.index', compact('series'));
    }

    public function store(Series $series, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
            'title'    => ['required', 'string']
        ]);

        $series->chapters()->create($validated);

        return to_route('series.chapters.index', $series);
    }
}
