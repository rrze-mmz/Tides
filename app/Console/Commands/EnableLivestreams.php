<?php

namespace App\Console\Commands;

use App\Enums\OpencastWorkflowState;
use App\Models\Livestream;
use App\Models\Series;
use App\Services\OpencastService;
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
    public function handle(OpencastService $opencastService)
    {
        if ($opencastService->getHealth()->get('status') === 'failed') {
            $this->info('No Opencast server found or server is offline!');

            return Command::SUCCESS;
        }

        $startDate = (Carbon::now()->isDST()) ? Carbon::now()->subMinutes(120) : Carbon::now()->subMinutes(60);
        $endDate = (Carbon::now()->isDST()) ? Carbon::now()->subMinutes(110) : Carbon::now()->subMinutes(50);
        //        $endDate = (Carbon::now()->isDST()) ? Carbon::now()->addMinutes(110) : Carbon::now()->subMinutes(50);
        Log::info('Check for Opencast scheduled events for the next 10 Minutes');
        $this->info("Finding opencast scheduled events between {$startDate->addMinutes(120)}
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

        $events->each(function ($event) {
            $series = Series::where('opencast_series_id', $event['is_part_of'])->first();
            $seriesLivestreamClip = $series->fetchLivestreamClip();

            if ($seriesLivestreamClip) {
                $this->info(
                    "Series '{$series->title}' has a livestream clip now try to enable"
                    ." wowza app {$event['scheduling']['agent_id']} for this clip"
                );
                $livestream = Livestream::search($event['scheduling']['agent_id'])->first();
                //livestream is matching with opencast capture agent
                if ($livestream) {
                    //reserve the livestream for this clipID
                    $livestream->clip_id = $seriesLivestreamClip->id;
                    $livestream->time_availability_start = Carbon::now();
                    $livestream->time_availability_end = Carbon::now()->addHours(2); // needs to be calculated properly
                    $livestream->save();
                }
            }
        });
    }
}
