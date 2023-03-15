<?php

namespace App\Http\Controllers\Backend;

use App\Jobs\CreateWowzaSmilFile;
use App\Jobs\TransferAssetsJob;
use App\Mail\AssetsTransferred;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait Transferable
{
    public function checkOpencastAssetsForClipUpload(Clip $clip, $eventID, OpencastService $opencastService)
    {
        $assets = $opencastService->getAssetsByEventID($eventID);
        $sourceDisk = 'opencast_archive';

        $deliveryAssets = $assets->filter(function ($value) {
            return Str::contains($value['tag'], 'final');
        });

        $this->uploadAssets($clip, $deliveryAssets, $eventID, $sourceDisk);
    }

    public function checkDropzoneFilesForClipUpload(Clip $clip, array $validatedFiles)
    {
        $sourceDisk = 'video_dropzone';
        $assets = fetchDropZoneFiles()->filter(function ($file, $key) use ($validatedFiles) {
            if (in_array($key, $validatedFiles['files'])) {
                return $file;
            }
        });

        $this->uploadAssets($clip, $assets, '', $sourceDisk);
    }

    private function uploadAssets(Clip $clip, Collection $assets, string $eventID = '', string $sourceDisk = '')
    {
        Bus::chain([
            new TransferAssetsJob($clip, $assets, $eventID, $sourceDisk),
            new CreateWowzaSmilFile($clip),
        ])->dispatch();

        //mail can be chained inside anonymous function bus parameter but then the test  fails
        Mail::to($clip->owner->email)->queue(new AssetsTransferred($clip));
    }
}
