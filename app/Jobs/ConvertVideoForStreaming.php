<?php

namespace App\Jobs;

use App\Models\Asset;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Asset $asset;

    /**
     * Create a new job instance.
     */
    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Transcode given video file to different resolutions
     */
    public function handle(): void
    {
        $lowBitrateFormat = (new X264('aac'))->setKiloBitrate(100);
        $midBitrateFormat = (new X264('aac'))->setKiloBitrate(200);
        $highBitrateFormat = (new X264('aac'))->setKiloBitrate(300);
        FFMpeg::fromDisk('videos')
            ->open($this->asset->path)
            ->exportForHLS()
            ->toDisk('streamable_videos')
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)
            ->save($this->asset->id.'.m3u8');

        // update the database so we know the convertion is done!
        $this->asset->update([
            'converted_for_streaming_at' => Carbon::now(),
        ]);
    }
}
