<?php

namespace App\Providers;

use App\Http\Clients\WowzaClient;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class WowzaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WowzaClient::class, function () {
            $setting = Setting::firstOrCreate(
                ['name' => 'streaming'],
                [
                    'data' => [
                        'engine_url' => 'localhost:1935',
                        'api_url' => 'localhost:8087',
                        'username' => 'admin',
                        'password' => 'opencast',
                    ],
                ]
            );

            $settingsData = $setting->data;

            $authType = config('app.env') === 'local' ? 'basic' : 'digest';

            return new WowzaClient([
                'base_uri' => $settingsData['wowza_api_url'],
                'verify' => config('app.env') === 'production',
                'auth' => [
                    $settingsData['wowza_username'],
                    $settingsData['wowza_password'],
                    $authType,
                ],
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
