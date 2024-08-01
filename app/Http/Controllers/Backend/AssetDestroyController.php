<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class AssetDestroyController extends Controller
{
    /**
     * Delete the given asset
     *
     *
     * @throws Exception
     * @throws AuthorizationException
     */
    public function __invoke(Asset $asset): RedirectResponse
    {
        $this->authorize('edit', $asset);
        if ($asset->clips()->exists()) {
            $clip = $asset->clips->first();
            $asset->delete();
            $clip->has_video_assets = $clip->refresh()->hasVideoAsset();
            $clip->save();
            $clip->updatePosterImage();

            session()->flash('flashMessage', "{$asset->original_file_name} deleted successfully");

            return to_route('clips.edit', $clip);
        } elseif ($asset->podcastEpisodes()->exists()) {
            $episode = $asset->podcastEpisodes()->first();
            $podcast = $episode->podcast;

            $asset->delete();

            session()->flash('flashMessage', "{$asset->original_file_name} deleted successfully");

            return to_route('podcasts.episodes.edit', compact('podcast', 'episode'));
        } else {
            $asset->delete();

            session()->flash('flashMessage', "{$asset->original_file_name} deleted successfully");

            return to_route('dashboard');
        }
    }
}
