<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
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

    public function transfer(Clip $clip, Request $request)
    {
        $files = $request->validate([
            'files[]' => 'required|array'
        ]);

        return 'transfer';
    }
}
