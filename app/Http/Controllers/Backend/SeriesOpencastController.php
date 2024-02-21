<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Services\OpencastService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

class SeriesOpencastController extends Controller
{
    /**
     * Creates an Openast series for the given tides series
     *
     *
     * @throws AuthorizationException
     */
    public function createSeries(Series $series, Request $request, OpencastService $opencastService): RedirectResponse
    {
        $this->authorize('administrate-portal-pages', $series);

        $opencastSeriesInfo = $opencastService->getSeriesInfo($series);

        if (isEmpty($opencastSeriesInfo->get('metadata'))) {
            $opencastSeriesId = $opencastService->createSeries($series);
            $series->updateOpencastSeriesId($opencastSeriesId);
        }

        session()->flash('flashMessage', 'Opencast series created successfully');

        return to_route('series.edit', $series);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateAcl(Series $series, Request $request, OpencastService $opencastService)
    {
        $this->authorize('administrate-admin-portal-pages');

        $opencastSeriesInfo = $opencastService->getSeriesInfo($series);

        //Opencast doesn't allow to update a series when a workflow is running
        if ($opencastSeriesInfo->get('running')->isNotEmpty()) {
            session()->flash('flashMessage', 'Opencast workflows running in this series. Try again later');

            return to_route('series.edit', $series);
        }

        $validated = $request->validate([
            'username' => ['required', 'exists:users'],
            'action' => ['required', Rule::in(['addUser', 'removeUser'])],
        ]);

        $response =
            $opencastService->updateSeriesAcl(
                $series,
                $opencastSeriesInfo,
                $validated['username'],
                $validated['action']
            );
        $opencastAcls = collect(json_decode((string) $response->getBody(), true));

        if ($opencastAcls->isNotEmpty()) {
            session()->flash('flashMessage', 'Opencast acls updated successfully');
        } else {
            session()->flash('flashMessage', 'There was a problem updating Opencast Acls');
        }

        return to_route('series.edit', $series);
    }

    public function updateEventsTitle(Series $series, Request $request, OpencastService $opencastService)
    {
        $this->authorize('administrate-admin-portal-pages');

        $request->validate([
            'opencastSeriesID' => [
                'required',
                function ($attribute, $value, $fail) use ($series) {
                    if ($value !== $series->opencast_series_id) {
                        return $fail($attribute.' must match the current series opencast series ID.');
                    }
                },
            ],
        ]);

        $events = $opencastService->getEventsBySeries($series);

        $events->each(function ($event) use ($opencastService) {
            $opencastService->updateEvent($event);
        });

        session()->flash('flashMessage', "{$events->count()} Opencast events updated successfully");

        return to_route('series.edit', $series);
    }
}
