<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeriesRequest;
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

    public function store(StoreSeriesRequest $request): RedirectResponse
    {
        $series = auth()->user()->series()->create( $request->validated());

        return redirect($series->adminPath());
    }
}
