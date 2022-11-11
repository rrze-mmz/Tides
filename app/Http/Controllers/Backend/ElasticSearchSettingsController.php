<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateElasticSearchSettings;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;

class ElasticSearchSettingsController extends Controller
{
    public function show()
    {
        $setting = Setting::elasticSearch();

        $setting->data = [
            'url' => $setting->data['url'] ?? 'localhost',
            'port' => $setting->data['port'] ?? 9200,
            'username' => $setting->data['username'] ?? 'elastic',
            'password' => $setting->data['password'] ?? 'changeme',
            'prefix' => $setting->data['prefix'] ?? 'tides_',

        ];

        return view('backend.settings.elasticSearch', ['setting' => $setting->data]);
    }

    public function update(UpdateElasticSearchSettings $request): RedirectResponse
    {
        $setting = Setting::elasticSearch();

        $setting->data = $request->validated();

        $setting->save();

        return to_route('settings.elasticSearch.show');
    }
}
