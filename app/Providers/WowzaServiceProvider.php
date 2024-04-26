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
            $setting = Setting::streaming();

            $settingsData = $setting->data;

            $authType = config('app.env') === 'local' ? 'basic' : 'digest';

            return new WowzaClient([
                'base_uri' => $settingsData['wowza_vod_api_url'],
                'verify' => config('app.env') === 'production',
                'auth' => [
                    $settingsData['wowza_vod_username'],
                    $settingsData['wowza_vod_password'],
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
