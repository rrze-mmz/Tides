<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Services\WowzaService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ShowClipsController extends Controller
{
    /**
     * Indexes all portal clips
     *
     * @return View
     */
    public function index(): View
    {
        $clips = Clip::all();

        return view('frontend.clips.index', compact('clips'));
    }

    /**
     * Clip main page
     *
     * @param  Clip  $clip
     * @param  WowzaService  $wowzaService
     * @return View
     *
     * @throws AuthorizationException|GuzzleException
     */
    public function show(Clip $clip, WowzaService $wowzaService): View
    {
        $this->authorize('view-clips', $clip);

        Log::info('clip ID'.$clip->title);

        return view('frontend.clips.show', [
            'clip' => $clip,
            'wowzaStatus' => $wowzaService->getHealth(),
            'previousNextClipCollection' => $clip->previousNextClipCollection(),
        ]);
    }
}
