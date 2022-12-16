<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;
use App\Models\Series;
use App\Models\User;
use App\Services\OpencastService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
            'series' => (auth()->user()->can('index-all-series'))
                    ? Series::orderByDesc('created_at')->paginate(12)
                    : auth()->user()->accessableSeries()->paginate(12),
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
     * @param  StoreSeriesRequest  $request
     * @param  OpencastService  $opencastService
     * @return RedirectResponse
     */
    public function store(StoreSeriesRequest $request, OpencastService $opencastService): RedirectResponse
    {
        $validated = $request->validated();

        $series = auth()->user()->series()->create(Arr::except($validated, ['presenters']));

        $opencastSeriesId = $opencastService->createSeries($series);

        $series->updateOpencastSeriesId($opencastSeriesId);
        $series->addPresenters(collect($validated['presenters']));

        session()->flash('flashMessage', 'Clip created successfully');

        return to_route('series.edit', $series);
    }

    /**
     * Edit form for a series
     *
     * @param  Series  $series
     * @param  OpencastService  $opencastService
     * @return View
     *
     * @throws AuthorizationException
     */
    public function edit(Series $series, OpencastService $opencastService): View
    {
        $this->authorize('edit', $series);

        $opencastSeriesInfo = $opencastService->getSeriesInfo($series);

        $assistants = User::role(Role::ASSISTANT)->get();
        //reject all assistants that are already in opencast series acl
        $availableAssistants = $assistants->reject(function ($admin) use ($opencastSeriesInfo) {
            if (! empty($opencastSeriesInfo->get('metadata'))) {
                foreach ($opencastSeriesInfo['metadata']['acl'] as $acl) {
                    //Opencast return Roles as ROLE_USER_USERNAME, so filter users based on this string
                    if (Str::contains($acl['role'], Str::of($admin->username)->upper())) {
                        return true;
                    }
                }
            }
        });

        return view('backend.series.edit', compact(['series', 'opencastSeriesInfo', 'availableAssistants']));
    }

    /**
     * Update a single series in the database
     *
     * @param  Series  $series
     * @param  UpdateSeriesRequest  $request
     * @param  OpencastService  $opencastService
     * @return RedirectResponse
     *
     * @throws AuthorizationException
     */
    public function update(
        Series $series,
        UpdateSeriesRequest $request,
        OpencastService $opencastService
    ): RedirectResponse {
        $this->authorize('update-series', $series);

        $validated = $request->validated();
        if (is_null($series->opencast_series_id)) {
            $opencastSeriesId = $opencastService->createSeries($series);

            $series->updateOpencastSeriesId($opencastSeriesId);
        }
        $series->update(Arr::except($validated, ['presenters']));
        $series->addPresenters(collect($validated['presenters']));

        return to_route('series.edit', $series);
    }

    /**
     * Delete a single series
     *
     * @param  Series  $series
     * @return RedirectResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Series $series): RedirectResponse
    {
        $this->authorize('delete', $series);

        $series->delete();

        return to_route('series.index');
    }
}
