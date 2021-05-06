<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\IngestVideoFileToOpencast;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OpencastController extends Controller
{

    /**
     * @param OpencastService $opencastService
     * @return View
     */
    public function status(OpencastService $opencastService ): View
    {
        $status = $opencastService->getHealth();
        return view('backend.opencast.status', compact('status'));
    }

    /**
     * Indest a video file to Opencast
     * @param Clip $clip
     * @param Request $request
     * @return RedirectResponse
     */
    public function ingestMediaPackage(Clip $clip, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'videoFile'  => 'required|file|mimetypes:video/mp4,video/mpeg,video/x-matroska'
        ]);

        $videoFile = $validated['videoFile'];

        $storedFile = $videoFile->storeAs(getClipStoragePath($clip), $videoFile->getClientOriginalName(), 'videos');

        $this->dispatch(new IngestVideoFileToOpencast($clip, $storedFile));

        return redirect(route('clips.edit', $clip));
    }
}