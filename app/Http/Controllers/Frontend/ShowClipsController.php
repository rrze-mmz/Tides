<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Services\WowzaService;
use Illuminate\View\View;

class ShowClipsController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $clips = Clip::all();

        return view('frontend.clips.index', compact('clips'));
    }

    /**
     * @param Clip $clip
     * @return View
     */
    public function show(Clip $clip, WowzaService $wowzaServiceProvider): View
    {
        return view('frontend.clips.show', [
            'clip'                       => $clip,
            'wowzaStatus'                => $wowzaServiceProvider->checkApiConnection(),
            'previousNextClipCollection' => $clip->previousNextClipCollection(),
        ]);
    }
}
