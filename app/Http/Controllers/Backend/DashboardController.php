<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OpencastWorkflowState;
use App\Models\Livestream;
use App\Models\Setting;
use App\Services\OpencastService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController
{
    /**
     * Show max 10 of user's series/clips, list dropzone files and opencast events
     */
    public function __invoke(OpencastService $opencastService): Application|Factory|View
    {
        $opencastEvents = collect();
        $opencastSettings = Setting::opencast();

        //fetch all available opencast events
        if ($opencastService->getHealth()->contains('pass')) {
            $opencastEvents
                ->put(
                    OpencastWorkflowState::RECORDING->lower(),
                    $opencastService->getEventsByStatus(OpencastWorkflowState::RECORDING)
                )
                ->put(
                    OpencastWorkflowState::RUNNING->lower(),
                    $opencastService->getEventsByStatus(OpencastWorkflowState::RUNNING)
                )
                ->put(
                    OpencastWorkflowState::SCHEDULED->lower(),
                    $opencastService->getEventsByStatusAndByDate(
                        OpencastWorkflowState::SCHEDULED,
                        null,
                        Carbon::now()->startOfDay(),
                        Carbon::now()->endOfDay(),
                    )
                )
                ->put(
                    OpencastWorkflowState::FAILED->lower(),
                    $opencastService->getEventsByStatus(OpencastWorkflowState::FAILED)
                )
                ->put(OpencastWorkflowState::TRIMMING->lower(), $opencastService->getEventsWaitingForTrimming());

            //if the logged-in user is a moderator then filter all opencast events
            if (auth()->user()->cannot('administrate-admin-portal-pages')) {
                //a collection for all user series opencast ids
                $series = auth()->user()->accessableSeries();
                $userOpencastSeriesIDs = $series->get()->pluck('opencast_series_id');

                //create a new collection with filtered events
                $opencastEvents = $opencastEvents->map(function ($events, $key) use ($userOpencastSeriesIDs) {
                    if ($events->isNotEmpty()) {
                        return $events->filter(function ($event) use ($key, $userOpencastSeriesIDs) {
                            //trimming endpoint results are different from all others
                            if ($key === OpencastWorkflowState::TRIMMING->lower()) {
                                return isset($event['series']['id'])
                                    && $userOpencastSeriesIDs->contains($event['series']['id']);
                            } elseif (isset($event['is_part_of'])) {
                                return $userOpencastSeriesIDs->contains($event['is_part_of']);
                            }

                            return false;
                        });
                    }

                    return $events;
                });

                $upcomingEvents = collect();
                $series->each(function ($series) use ($upcomingEvents, $opencastService) {
                    $opencastService->getEventsByStatus(OpencastWorkflowState::SCHEDULED, $series, 3)
                        ->each(function ($event) use ($upcomingEvents) {
                            $upcomingEvents->push($event);
                        });
                });
                $opencastEvents->put('upcoming', $upcomingEvents);
            }
        }

        $livestreams = Livestream::active()->orderBy('clip_id');

        return view('backend.dashboard.index', [
            'userSeries' => auth()->user()
                ->getAllSeries()
                ->with('owner')
                ->withLastPublicClip()
                ->CurrentSemester()
                ->orderBy('title')
                ->paginate(12),
            'userClips' => auth()->user()->clips()
                ->whereNull('series_id')
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get(),
            'files' => fetchDropZoneFiles(false),
            'opencastEvents' => $opencastEvents,
            'opencastSettings' => $opencastSettings->data,
            'activeLivestreams' => $livestreams,
        ]);
    }
}
