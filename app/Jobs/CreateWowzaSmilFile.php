<?php

namespace App\Jobs;

use App\Models\Clip;
use App\Services\WowzaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateWowzaSmilFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Clip $clip)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \DOMException
     */
    public function handle(WowzaService $wowzaService)
    {
        $wowzaService->createSmilFile($this->clip);
    }
}
