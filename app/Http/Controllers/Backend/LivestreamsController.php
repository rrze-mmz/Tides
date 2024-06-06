<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LivestreamRequest;
use App\Models\Livestream;
use App\Services\WowzaService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class LivestreamsController extends Controller
{
    public function index()
    {
        return view('backend.livestreams.index')->with('livestreams', Livestream::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(LivestreamRequest $request): RedirectResponse
    {
        $livestream = Livestream::create($request->validated());

        return to_route('livestreams.edit', $livestream);
    }

    public function create(): Application|Factory|View
    {
        Gate::allowIf(fn ($user) => $user->isAdmin());

        return view('backend.livestreams.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livestream $livestream, WowzaService $wowzaService): Application|Factory|View
    {
        return view('backend.livestreams.edit')->with([
            'livestream' => $livestream,
            'livestreamURL' => $wowzaService->livestreamSecureUrls($livestream)->first(),
        ]);
    }

    public function update(LivestreamRequest $request, Livestream $livestream): RedirectResponse
    {
        $livestream->update($request->validated());

        return to_route('livestreams.edit', $livestream);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livestream $livestream): RedirectResponse
    {
        Gate::allowIf(fn ($user) => $user->isAdmin());

        $livestream->delete();

        return to_route('livestreams.index');
        //
    }
}
