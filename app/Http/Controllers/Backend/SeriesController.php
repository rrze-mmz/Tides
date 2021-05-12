<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;
use App\Models\Series;
use App\Services\OpencastService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SeriesController extends Controller
{

    /**
     * @return View
     */
    public function index(): View
    {
        return view('backend.series.index', [
            'series' =>
                (auth()->user()->isAdmin())
                    ? Series::orderByDesc('updated_at')->paginate(20)
                    : auth()->user()->series()->orderBy('updated_at')->limit(20)->get(),
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

        session()->flash('flashMessage', 'Clip created successfully');

        return redirect($series->adminPath());
    }

    /**
     * @param Series $series
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Series $series, OpencastService $opencastService): View
    {
        $this->authorize('edit', $series);

        return view('backend.series.edit', [
            'series'    =>  $series,
            'opencastSeriesRunningWorkflows'    =>  $opencastService->getSeriesRunningWorkflows($series)
        ]);
    }

    /**
     * @param Series $series
     * @param UpdateSeriesRequest $request
     * @param OpencastService $opencastService
     * @return RedirectResponse
     */
    public function update(
        Series $series,
        UpdateSeriesRequest $request,
        OpencastService $opencastService
    ): RedirectResponse {
        if (is_null($series->opencast_series_id)) {
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
