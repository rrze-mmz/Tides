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
     */
    public function show(): Application|Factory|View
    {
        $setting = Setting::streaming();

        return view('backend.settings.streaming', ['setting' => $setting->data]);
    }

    public function update(UpdateStreamingSettings $request)
    {
        $setting = Setting::streaming();

        $setting->data = $this->transformStreamingSettingsData($request->validated());
        $setting->save();

        return to_route('settings.streaming.show');
    }

    private function transformStreamingSettingsData(array $settings)
    {
        return [
            'wowza' => [
                'server1' => [
                    'engine_url' => $settings['wowza_server1_engine_url'],
                    'api_url' => $settings['wowza_server1_api_url'],
                    'api_username' => $settings['wowza_server1_api_username'],
                    'api_password' => $settings['wowza_server1_api_password'],
                    'content_path' => $settings['wowza_server1_content_path'],
                    'secure_token' => $settings['wowza_server1_secure_token'],
                    'token_prefix' => $settings['wowza_server1_token_prefix'],
                ],
                'server2' => [
                    'engine_url' => $settings['wowza_server2_engine_url'],
                    'api_url' => $settings['wowza_server2_api_url'],
                    'api_username' => $settings['wowza_server2_api_username'],
                    'api_password' => $settings['wowza_server2_api_password'],
                    'content_path' => $settings['wowza_server2_content_path'],
                    'secure_token' => $settings['wowza_server2_secure_token'],
                    'token_prefix' => $settings['wowza_server2_token_prefix'],
                ],
            ],
            'nginx' => [],
            'cdn' => [
                'server1' => [
                    'url' => $settings['cdn_server1_url'],
                    'secret' => $settings['cdn_server1_secret'],
                ],

            ],
        ];
    }
}
