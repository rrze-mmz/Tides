<?php

namespace App\Jobs;

use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IngestVideoFileToOpencast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Clip $clip, private string $videoFile)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OpencastService $opencastService)
    {
        $opencastService->ingestMediaPackage($this->clip, $this->videoFile);
    }
}
