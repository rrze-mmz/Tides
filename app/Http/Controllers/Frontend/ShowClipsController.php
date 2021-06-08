<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Services\WowzaService;
use Illuminate\View\View;

class ShowClipsController extends Controller
{
    /**
     * Indexes all portal clips
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
     * @param Clip $clip
     * @return View
     */
    public function show(Clip $clip, WowzaService $wowzaService): View
    {
        return view('frontend.clips.show', [
            'clip'                       => $clip,
            'wowzaStatus'                => $wowzaService->checkApiConnection(),
            'previousNextClipCollection' => $clip->previousNextClipCollection(),
        ]);
    }
}
