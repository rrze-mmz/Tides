<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    public function index()
    {
        $channels = (auth()->user()->can('administrate-admin-portal-pages')) ? Channel::all() : auth()->user()->channels;

        return view('backend.channels.index')->withChannels($channels);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url_handle' => ['required', 'string', 'unique:channels', function ($attribute, $value, $fail) {
                if (! str_starts_with($value, '@')) {
                    return $fail('The '.$attribute.' must start with @.');
                }
                if (str_contains($value, ' ')) {
                    return $fail('The '.$attribute.' cannot contain spaces.');
                }
            }, ],
            'name' => ['required', 'string',  'max:255'],
            'description' => ['max:1000'],
        ]);
        $this->authorize('activate-channel', $validated['url_handle']);
        auth()->user()->channels()->create($validated);

        return to_route('channels.index');
    }
}
