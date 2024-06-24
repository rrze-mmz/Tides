<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Podcast;

class ShowPodcastsController extends Controller
{
    /**
     * Display a listing of all podcasts.
     */
    public function index()
    {
        $podcasts = Podcast::all();

        return view('frontend.podcasts.index', compact('podcasts'));
    }
}
