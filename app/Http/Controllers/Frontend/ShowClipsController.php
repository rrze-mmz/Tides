<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Content;
use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Services\WowzaService;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @throws AuthorizationException
     */
    public function show(Clip $clip, WowzaService $wowzaService): View
    {
        $this->authorize('view-clips', $clip);

        $assetsResolutions = $clip->assets->map(function ($asset) {
            return match (true) {
                $asset->width >= 1920 => 'QHD',
                $asset->width >= 720 && $asset->width < 1920 => 'HD',
                $asset->width >= 10 && $asset->width < 720 => 'SD',
                $asset->type == Content::AUDIO() => 'Audio',
                default => 'PDF/CC'
            };
        })
            ->unique()
            ->filter(function ($value, $key) {
                return $value !== 'PDF/CC';
            });

        return view('frontend.clips.show', [
            'clip' => $clip,
            'wowzaStatus' => $wowzaService->getHealth(),
            'previousNextClipCollection' => $clip->previousNextClipCollection(),
            'assetsResolutions' => $assetsResolutions,
        ]);
    }
}
