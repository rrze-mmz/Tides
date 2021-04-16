<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SeriesController extends Controller
{

    /**
     * @return View
     */
    public function create(): View
    {
        return view('backend.series.create');
    }

    /**
     * @param  StoreSeriesRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreSeriesRequest $request): RedirectResponse
    {
        $series = auth()->user()->series()->create( $request->validated());

        return redirect($series->adminPath());
    }

    /**
     * @param  Series  $series
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Series $series): View
    {
        $this->authorize('edit', $series);

        return view('backend.series.edit', compact('series'));
    }

    /**
     * @param  Series  $series
     * @param  UpdateSeriesRequest  $request
     * @return RedirectResponse
     */
    public function update(Series $series, UpdateSeriesRequest $request): RedirectResponse
    {
        $series->update($request->validated());

        return redirect($series->adminPath());

    }
}
