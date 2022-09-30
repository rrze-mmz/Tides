<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserSettingsController extends Controller
{
    /**
     * Show user settings
     *
     * @return Application|Factory|View
     */
    public function edit(): Application|Factory|View
    {
        return view('frontend.myPortal.userSettings.edit', ['settings' => auth()->user()->settings()->data]);
    }

    /**
     * Update user settings
     *
     * @param  UpdateUserSettings  $request
     * @return RedirectResponse
     */
    public function update(UpdateUserSettings $request): RedirectResponse
    {
        $settings = auth()->user()->settings();

        $validated = $request->validated();

        $merged = array_merge($settings->data, $validated);
        session()->put('locale', $validated['language']);
        $settings->data = $merged;
        $settings->save();

        return to_route('frontend.userSettings.edit');
    }
}
