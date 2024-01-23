<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class ChannelsController extends Controller
{
    public function index()
    {
        return view('backend.channels.index')->withChannels(auth()->user()->channels);
    }
}
