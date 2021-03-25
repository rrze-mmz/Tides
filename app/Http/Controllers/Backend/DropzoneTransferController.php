<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DropzoneTransferController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  Clip  $clip
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function listFiles(Clip $clip): View
    {
        $this->authorize('edit', $clip);

        //fetch drop zone files
        $files = collect(Storage::disk('video_dropzone')->files())->map(function ($file) {
            return [
                'date_modified' => Carbon::createFromTimestamp(Storage::disk('video_dropzone')->lastModified($file))->format('Y-m-d H:i:s'),
                'name'          => $file
            ];
        });


        return view('backend.clips.transfer',compact('files'));
    }
}
