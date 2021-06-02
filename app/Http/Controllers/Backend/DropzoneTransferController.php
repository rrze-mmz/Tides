<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Jobs\TransferDropzoneFiles;
use App\Mail\VideoUploaded;
use App\Models\Clip;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DropzoneTransferController extends Controller
{

    /**
     * List all available files inside the dropzone folder
     *
     * @param  Clip  $clip
     * @return View
     * @throws AuthorizationException
     */
    public function listFiles(Clip $clip): View
    {
        $this->authorize('edit', $clip);

        return view('backend.clips.dropzone.listFiles', [
            'clip'  => $clip,
            'files' => fetchDropZoneFiles()
        ]);
    }

    /**
     * Transfer files from dropzone to file path

     * @param Clip $clip
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function transfer(Clip $clip, Request $request): RedirectResponse
    {
        $this->authorize('edit', $clip);

        $validated = $request->validate([
            'files' => 'required|array'
        ]);

        TransferDropzoneFiles::dispatch($clip, fetchDropZoneFiles()->whereIn('hash', $validated['files']));

        Mail::to($clip->owner->email)->queue(new VideoUploaded($clip));

        return redirect($clip->adminPath());
    }
}
