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
     * Create a wowza smil file based on clip assets
     *
     * @return void
     * @throws \DOMException
     */
    public function handle(WowzaService $wowzaService): void
    {
        $wowzaService->createSmilFile($this->clip);
    }
}
