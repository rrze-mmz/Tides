<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;
use App\Models\Series;
use App\Services\OpencastService;
use GuzzleHttp\Exception\GuzzleException;
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
     * @return RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(StoreSeriesRequest $request, OpencastService $opencastService): RedirectResponse
    {
        $series = auth()->user()->series()->create($request->validated());

        try{
            $response = $opencastService->createSeries($series);

            $series->opencast_series_id = Str::afterLast($response->getHeaders()['Location'][0], 'api/series/');

            $series->update();
        } catch (GuzzleException $exception)
        {
            Log::error($exception);
        }


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
     * @return RedirectResponse
     */
    public function update(Series $series, UpdateSeriesRequest $request): RedirectResponse
    {
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
