<?php


namespace App\Jobs;

use App\Enums\Content;
use App\Models\Clip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileExistsException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class TransferAssetsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param Clip $clip
     * @param Collection $files
     * @param string $eventID
     */
    public function __construct(protected Clip $clip, protected Collection $files, protected string $eventID = '')
    {
    }

    /**
     * Copy a collection of video files to clip path
     *
     * @return void
     * @throws FileExistsException
     */
    public function handle(): void
    {
        $clipStoragePath = getClipStoragePath($this->clip);
        $this->files->each(function ($file, $key) use ($clipStoragePath) {
            $isVideo = (bool)$file['video'];
            $storageDisk = ($this->eventID !== '')
                ? Storage::disk('opencast_archive')->readStream('/' . config('opencast.archive_path') .
                    '/' . $this->eventID .
                    '/' . $file['version'] .
                    '/' . $file['name'])
                : Storage::disk('video_dropzone')->readStream($file['name']);

            try {
                Storage::disk('videos')->makeDirectory($clipStoragePath);
                Storage::disk('videos')->writeStream($clipStoragePath . '/' . $file['name'], $storageDisk);
            } catch (FileNotFoundException $e) {
                Log::error($e);
            }

            $storedFile = $clipStoragePath . '/' . $file['name'];
            $ffmpeg = FFMpeg::fromDisk('videos')->open($storedFile);

            $attributes = [
                'disk'               => 'videos',
                'original_file_name' => $file['name'],
                'path'               => $clipStoragePath,
                'duration'           => $ffmpeg->getDurationInSeconds(),
                'width'              => ($isVideo)
                    ? $ffmpeg->getVideoStream()->getDimensions()->getWidth()
                    : 0,
                'height'             => ($isVideo)
                    ? $ffmpeg->getVideoStream()->getDimensions()->getHeight()
                    : 0,
                'type'               => ($isVideo) ? Content::Presenter->lower() : Content::Audio->lower(),
            ];

            $this->clip->addAsset($attributes);

            if ($isVideo) {
                //generate a poster image for the clip
                $ffmpeg->getFrameFromSeconds(5)
                    ->export()
                    ->toDisk('thumbnails')
                    ->save($this->clip->id . '_poster.png');

                $this->clip->updatePosterImage();
            }
        });
    }
}
