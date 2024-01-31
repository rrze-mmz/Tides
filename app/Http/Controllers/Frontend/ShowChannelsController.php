<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\View\View;

class ShowChannelsController extends Controller
{
    public function index(): View
    {
        $channels = Channel::all();

        return view('frontend.channels.index', ['channels' => $channels]);
    }

    public function show(Channel $channel)
    {
        return view('frontend.channels.show', compact('channel'));
    }
}
