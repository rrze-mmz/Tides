<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSearchSettings;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;

class SearchSettingsController extends Controller
{
    public function show()
    {
        $setting = Setting::openSearch();

        return view('backend.settings.search', ['setting' => $setting->data]);
    }

    public function update(UpdateSearchSettings $request): RedirectResponse
    {
        $setting = Setting::openSearch();

        $setting->data = $request->validated();

        $setting->save();

        return to_route('settings.openSearch.show');
    }
}
