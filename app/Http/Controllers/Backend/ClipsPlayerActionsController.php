<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Content;
use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Rules\ValidImageFile;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ClipsPlayerActionsController extends Controller
{
    public function generatePreviewImageFromFrame(Clip $clip, Request $request)
    {
        $validated = $request->validate([
            'recentFrame' => 'required|integer',
        ]);
        $frame = $validated['recentFrame'];
        $this->updateAssetPreview(clip: $clip, framePosition: $frame);

        //create the clip log
        //set a flash message
        //local saved video files -> ClipID:43746
        //update the search index?
        $clip->recordActivity(description: 'Generate Player preview from frame at second: '.$frame);

        return back();
    }

    private function updateAssetPreview(Clip $clip, $framePosition = null, $image = null): void
    {
        $clip->assets->filter(function ($asset) {
            return $asset->type == Content::PRESENTER()
                || $asset->type == Content::COMPOSITE()
                || $asset->type == Content::PRESENTATION();
        })->each(function ($asset) use ($framePosition, $image) {
            $ulid = Str::ulid();
            $posterName = "resID_{$asset->id}_{$ulid}.png";
            $oldPreview = $asset->player_preview;
            if ($framePosition) {
                $ffmpeg = FFMpeg::fromDisk('videos')->open($asset->path);
                $ffmpeg->getFrameFromSeconds($framePosition)
                    ->export()
                    ->toDisk('thumbnails')
                    ->save("previews-ng/{$posterName}");
                $asset->player_preview = $posterName;
                $asset->save();
            } elseif ($image) {
                Storage::putFileAs(
                    path: 'thumbnails/previews-ng/',
                    file: $image,
                    name: $posterName
                );
                $asset->player_preview = $posterName;
                $asset->save();
            } else {
                echo 'nothing to do';
            }
            Storage::disk('thumbnails')->delete('previews-ng/'.$oldPreview);
        });
    }

    public function generatePreviewImageFromUser(Clip $clip, Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'string', new ValidImageFile(['image/png', 'image/jpeg'])],
        ]);
        $uploadedImage = new File(Storage::path($validated['image']));
        $this->updateAssetPreview($clip, image: $uploadedImage);
        //update the search index?
        $clip->recordActivity(description: 'Create player preview from user uploaded file');

        return back();
    }
}
