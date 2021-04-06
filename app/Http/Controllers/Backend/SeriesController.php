<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class SeriesController extends Controller
{

    /**
     * @return View
     */
    public function create(): View
    {
        return view('backend.series.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'min:3|max:255',
            'slug'        => 'required',
        ]);

        $series = auth()->user()->series()->create($validated);

        return redirect($series->adminPath());
    }
}
