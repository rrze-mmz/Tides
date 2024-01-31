<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    public function index()
    {
        $channels = (auth()->user()->can('administrate-admin-portal-pages'))
            ? Channel::all()
            : auth()->user()->channels;

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

    public function edit(Channel $channel): Application|Factory|View
    {
        $this->authorize('edit-channel', $channel);

        return view('backend.channels.edit', compact('channel'));
    }

    public function update(Channel $channel, Request $request): RedirectResponse
    {
        $this->authorize('edit-channel', $channel);
        $validated = $request->validate([
            'name' => ['required', 'string',  'max:255'],
            'description' => ['max:1000'],
        ]);

        $channel->update($validated);

        return to_route('channels.edit', $channel);
    }
}
