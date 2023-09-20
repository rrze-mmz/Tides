<?php

namespace App\Console\Commands;

use App\Models\Clip;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckTimeAvailabilityClips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-time-availability-clips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and toggles time availability for clips';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $clips = Clip::whereHasTimeAvailability(true)
            ->get();

        Log::info('Starting artisan app:check-time-availability-clips command');

        $clipsCount = $clips->count();

        if ($clipsCount == 0) {
            $this->info('No time availability Clips found for '.$now);

            return Command::SUCCESS;
        }
        $this->info("Found {$clipsCount} clips with time availability");
        Log::info("app:check-time-availability-clips -> Found {$clipsCount} clips with time availability");
        $bar = $this->output->createProgressBar($clipsCount);
        $bar->start();

        $clips->each(function ($clip) use ($bar, $now) {
            //check whether they are any clips in the past escaping the check and still online
            if ($now->greaterThanOrEqualTo($clip->time_availability_end) && $clip->is_public) {
                //if clip found disable them
                $clip->is_public = false;
                $clip->has_time_availability = false;
                $this->info(
                    "ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} will be withdrawn for users"
                );
            } elseif ($now->greaterThanOrEqualTo($clip->time_availability_start)) {
                if (is_null($clip->time_availability_end)) {
                    //the clip has no end time meaning it will be published and stay online
                    $clip->is_public = true;
                    $clip->has_time_availability = false;
                    $this->info(
                        "ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} will be available for users
                        and time availability will be turned off"
                    );
                } elseif (! $clip->is_public) {
                    $clip->is_public = true;
                    $this->info(
                        "ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} will be  now available for users"
                    );
                } else {
                    $this->info(
                        "ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} is still available for users"
                    );
                }
            } elseif ($now->lessThan($clip->time_availability_start)) {
                if ($clip->is_public) {
                    $clip->is_public = false;
                    $this->info(
                        "ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} will be withdrawn for users"
                    );
                } else {
                    $this->info("ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} should remain offline");
                }
            } else {
                $this
                    ->info("ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} does not
                        met the criteria for checks");
            }

            $clip->save();
            $bar->advance();
            $this->newLine(2);
        });
        $bar->finish();
        $this->info('Check for time available clips finished!');
        Log::info('app:check-time-availability-clips -> Check for time available clips finished!');

        return Command::SUCCESS;
    }
}
