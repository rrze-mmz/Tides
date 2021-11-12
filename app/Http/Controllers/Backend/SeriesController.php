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
use Log;

class SeriesController extends Controller
{
    /**
     * Index all series in admin portal. In case of simple user list only users series
     *
     * @return View
     */
    public function index(): View
    {
        return view('backend.series.index', [
            'series' =>
                (auth()->user()->isAdmin())
                    ? Series::orderByDesc('updated_at')->paginate(12)
                    : auth()->user()->series()->orderByDesc('updated_at')->paginate(12)
        ]);
    }

    /**
     * Create form for a series
     *
     * @return View
     */
    public function create(): View
    {
        return view('backend.series.create');
    }

    /**
     * Store a series in database
     *
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
     * Edit form for a series
     *
     * @param Series $series
     * @param OpencastService $opencastService
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Series $series, OpencastService $opencastService): View
    {
        $this->authorize('edit', $series);

        $opencastSeriesRunningWorkflows = $opencastService->getSeriesRunningWorkflows($series);

        return view('backend.series.edit', compact(['series', 'opencastSeriesRunningWorkflows']));
    }

    /**
     * Update a single series in the database
     *
     * @param Series $series
     * @param UpdateSeriesRequest $request
     * @param OpencastService $opencastService
     * @return RedirectResponse
     */
    public function update(
        Series              $series,
        UpdateSeriesRequest $request,
        OpencastService     $opencastService
    ): RedirectResponse {
        if (is_null($series->opencast_series_id)) {
            $opencastSeriesId = $opencastService->createSeries($series);

            $series->updateOpencastSeriesId($opencastSeriesId);
        }
        $series->update($request->validated());

        return redirect($series->adminPath());
    }

    /**
     * Delete a single series
     *
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
