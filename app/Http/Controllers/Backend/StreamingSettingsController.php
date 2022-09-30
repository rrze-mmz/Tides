<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStreamingSettings;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StreamingSettingsController extends Controller
{
    /**
     * Display streaming settings
     *
     * @return Application|Factory|View
     */
    public function show(): Application|Factory|View
    {
        $setting = Setting::streaming();

        $setting->data = [
            'engine_url' => $setting->data['engine_url'] ?? 'localhost:1935',
            'api_url' => $setting->data['api_url'] ?? 'localhost:8087',
            'username' => $setting->data['username'] ?? 'admin',
            'password' => $setting->data['password'] ?? 'username',
            'content_path' => $setting->data['content_path'] ?? '/content/videoportal',
            'secure_token' => $setting->data['secure_token'] ?? 'awsTides12tvv10',
            'token_prefix' => $setting->data['token_prefix'] ?? 'tides',
        ];

        return view('backend.settings.streaming', ['setting' => $setting->data]);
    }

    public function update(UpdateStreamingSettings $request)
    {
        $setting = Setting::streaming();

        $setting->data = $request->validated();

        $setting->save();

        return to_route('settings.streaming.show');
    }
}
