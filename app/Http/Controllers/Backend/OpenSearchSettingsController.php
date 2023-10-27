<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOpenSearchSettings;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;

class OpenSearchSettingsController extends Controller
{
    public function show()
    {
        $setting = Setting::openSearch();

        $setting->data = [
            'url' => $setting->data['url'] ?? 'localhost',
            'port' => $setting->data['port'] ?? 9200,
            'username' => $setting->data['username'] ?? 'admin',
            'password' => $setting->data['password'] ?? 'admin',
            'prefix' => $setting->data['prefix'] ?? 'tides_',
        ];

        return view('backend.settings.openSearch', ['setting' => $setting->data]);
    }

    public function update(UpdateOpenSearchSettings $request): RedirectResponse
    {
        $setting = Setting::openSearch();

        $setting->data = $request->validated();

        $setting->save();

        return to_route('settings.openSearch.show');
    }
}
