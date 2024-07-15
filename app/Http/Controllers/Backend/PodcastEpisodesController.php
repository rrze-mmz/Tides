<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\PodcastEpisode;

class PodcastEpisodesController extends Controller
{
    public function edit(Podcast $podcast, PodcastEpisode $episode)
    {
        return view('backend.podcastEpisodes.edit', compact('podcast', 'episode'));
    }
}
