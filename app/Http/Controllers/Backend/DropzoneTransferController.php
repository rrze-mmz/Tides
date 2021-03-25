<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
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

        return view('backend.clips.transfer');
    }
}
