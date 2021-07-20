<?php


namespace App\Jobs;

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

class TransferDropzoneFiles implements ShouldQueue
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
     */
    public function __construct(protected Clip $clip, protected Collection $files)
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
            try {
                Storage::disk('videos')->writeStream(
                    $clipStoragePath . '/' . $file['name'],
                    Storage::disk('video_dropzone')->readStream($file['name'])
                );
            } catch (FileNotFoundException $e) {
                Log::error($e);
            }

            $storedFile = $clipStoragePath . '/' . $file['name'];
            $ffmpeg = FFMpeg::fromDisk('videos')->open($storedFile);

            $attributes = [
                'disk'               => 'videos',
                'original_file_name' => $file['name'],
                'path'               => $storedFile,
                'duration'           => $ffmpeg->getDurationInSeconds(),
                'width'              => $ffmpeg->getVideoStream()->getDimensions()->getWidth(),
                'height'             => $ffmpeg->getVideoStream()->getDimensions()->getHeight(),
                'type'               => 'video',
            ];

            $this->clip->addAsset($attributes);

            //generate a poster image for the clip
            $ffmpeg->getFrameFromSeconds(5)
                ->export()
                ->toDisk('thumbnails')
                ->save($this->clip->id . '_poster.png');

            $this->clip->updatePosterImage();
        });
    }
}
