<?php

namespace App\Console\Commands;

use App\Enums\OpencastWorkflowState;
use App\Models\Livestream;
use App\Models\Series;
use App\Services\OpencastService;
use App\Services\WowzaService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class EnableLivestreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enable-livestreams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for planned opencast events and livestream clips to enable the livestream app';

    /**
     * Execute the console command.
     */
    public function handle(OpencastService $opencastService, WowzaService $wowzaService)
    {
        if ($opencastService->getHealth()->get('status') === 'failed') {
            $this->info('No Opencast server found or server is offline!');

            return Command::SUCCESS;
        }

        $startDate = (Carbon::now()->isDST()) ? Carbon::now()->subMinutes(120) : Carbon::now()->subMinutes(60);
        $endDate = (Carbon::now()->isDST()) ? Carbon::now()->subMinutes(110) : Carbon::now()->subMinutes(50);
        //        $endDate = (Carbon::now()->isDST()) ? Carbon::now()->addMinutes(110) : Carbon::now()->subMinutes(50);
        Log::info('Searching for active Opencast recording events without active livestream room reservation');
        $this->info('Searching for active Opencast recording events');

        $recordingEvents = $opencastService->getEventsByStatus(OpencastWorkflowState::RECORDING);

        if ($recordingEvents->isEmpty()) {
            $this->info('No active recording events found');
        } else {
            $counter = $recordingEvents->count();
            $eventsName = $recordingEvents->pluck('title');
            $this->info("Find $counter recording events: $eventsName");
        }
        $recordingEvents->each(function ($event) use ($wowzaService) {
            $series = Series::where('opencast_series_id', $event['is_part_of'])->first();
            $seriesLivestreamClip = $series->fetchLivestreamClip();
            if (! is_null($seriesLivestreamClip)
                && ! is_null(Livestream::where('clip_id', $seriesLivestreamClip->id))) {
                $this->info(
                    "Series '{$series->title}' has a livestream clip now try to enable"
                    ." wowza app {$event['scheduling']['agent_id']} for this clip"
                );
                $wowzaService->reserveLivestreamRoom(
                    $event['scheduling']['agent_id'],
                    $seriesLivestreamClip,
                    $event['scheduling']['end']
                );
            }
        });
        Log::info('Check for Opencast scheduled events for the next 10 Minutes');
        $this->info("Searching for Opencast scheduled events between {$startDate->addMinutes(120)}
        and {$endDate->addMinutes(120)}");
        $events = $opencastService->getEventsByStatusAndByDate(
            OpencastWorkflowState::SCHEDULED,
            null,
            $startDate,
            $endDate
        );

        if ($events->isEmpty()) {
            $this->info('No Opencast scheduled events found for the next 10 minutes');

            return Command::SUCCESS;
        }

        $events->each(function ($event) use ($wowzaService) {
            $series = Series::where('opencast_series_id', $event['is_part_of'])->first();
            $seriesLivestreamClip = $series->fetchLivestreamClip();

            if ($seriesLivestreamClip) {
                $this->info(
                    "Series '{$series->title}' has a livestream clip now try to enable"
                    ." wowza app {$event['scheduling']['agent_id']} for this clip"
                );
                $wowzaService->reserveLivestreamRoom($event['scheduling']['agent_id'], $seriesLivestreamClip);
            }
        });
    }
}
