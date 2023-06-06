<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Content;
use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ShowSeriesController extends Controller
{
    public function index(): View
    {
        $series = Series::whereHas('clips', function (Builder $query) {
            $query->has('assets');
        })->isPublic()
            ->with('presenters')
            ->withLastPublicClip()
            ->orderByDesc('updated_at')
            ->paginate(12);

        return view('frontend.series.index', compact('series'));
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
        //
        $clips = (auth()->user()?->id === $series->owner_id || auth()->user()?->isAdmin())
                ?
            Clip::select(['id', 'title', 'slug', 'episode', 'is_public'])
                ->where('series_id', $series->id)
                ->WithSemester()
                ->with('acls')
                ->orderBy('episode')->get()
                :
                Clip::has('assets')
                    ->select(['id', 'title', 'slug', 'episode', 'is_public'])
                    ->where('series_id', $series->id)
                    ->where('is_public', true)
                    ->WithSemester()
                    ->with('acls')
                    ->orderBy('episode')->get();

        $assetsResolutions = $clips
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

        return view('frontend.series.show', compact(['series', 'clips', 'assetsResolutions']));
    }
}
