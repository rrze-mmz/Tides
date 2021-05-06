<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;
use App\Models\Series;
use App\Services\OpencastService;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SeriesController extends Controller {


    /**
     * @return View
     */
    public function index(): View
    {
        return view('backend.series.index', [
            'series' => auth()->user()->series()->orderByDesc('updated_at')->limit(20)->get()
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('backend.series.create');
    }

    /**
     * @param StoreSeriesRequest $request
     * @param OpencastService $opencastService
     * @return RedirectResponse
     */
    public function store(StoreSeriesRequest $request, OpencastService $opencastService): RedirectResponse
    {
        $series = auth()->user()->series()->create($request->validated());

        $opencastSeriesId = $opencastService->createSeries($series);

        $series->updateOpencastSeriesId($opencastSeriesId);

        return redirect($series->adminPath());
    }

    /**
     * @param Series $series
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Series $series): View
    {
        $this->authorize('edit', $series);

        return view('backend.series.edit', compact('series'));
    }

    /**
     * @param Series $series
     * @param UpdateSeriesRequest $request
     * @param OpencastService $opencastService
     * @return RedirectResponse
     */
    public function update(Series $series, UpdateSeriesRequest $request, OpencastService $opencastService): RedirectResponse
    {
        if(is_null($series->opencast_series_id))
        {
            $opencastSeriesId = $opencastService->createSeries($series);

            $series->updateOpencastSeriesId($opencastSeriesId);
        }

        $series->update($request->validated());

        return redirect($series->adminPath());
    }

    /**
     * @param Series $series
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Series $series): RedirectResponse
    {
        $this->authorize('edit', $series);

        $series->delete();

        return redirect(route('series.index'));
    }
}
