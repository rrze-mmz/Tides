<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Content;
use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class ShowSeriesController extends Controller
{
    public function index(): View
    {
        return view('frontend.series.index', [
            'series' => Series::with('clips')->isPublic()->orderByDesc('updated_at')->paginate(12),
        ]);
    }

    /**
     * Series main page
     *
     *
     * @throws AuthorizationException
     */
    public function show(Series $series): View
    {
        $this->authorize('view-series', $series);

        /*
         * for visitors fetch only clips that containing a video asset
         */
        $series->clips = (auth()->user()?->id === $series->owner_id || auth()->user()?->isAdmin())
            ? $series->clips
            : $series->clips->filter(fn ($clip) => $clip->assets()->count() && $clip->is_public);
//
        $assetsResolutions = $series->clips
                        ->map(function ($clip) {
                            return $clip->assets->map(function ($asset) {
                                return match (true) {
                                    $asset->width >= 1920 => 'QHD',
                                    $asset->width >= 720 && $asset->width < 1920 => 'HD',
                                    $asset->width >= 10 && $asset->width < 720 => 'SD',
                                    $asset->type == Content::AUDIO() => 'Audio',
                                    default => 'PDF/CC'
                                };
                            })->unique();
                        })
                        ->flatten()
                        ->unique()
                        ->filter(function ($value, $key) {
                            return $value !== 'PDF/CC';
                        });

        return view('frontend.series.show', compact(['series', 'assetsResolutions']));
    }
}
