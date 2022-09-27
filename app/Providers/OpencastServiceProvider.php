<?php

namespace App\Providers;

use App\Http\Clients\OpencastClient;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class OpencastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(OpencastClient::class, function () {
            $setting = Setting::firstOrCreate(
                ['name' => 'opencast'],
                [
                    'data' => [
                        'url' => 'localhost:8080',
                        'username' => 'admin',
                        'password' => 'opencast',
                        'archive_path' => '/archive/mh_default',
                        'default_workflow' => 'fast',
                    ],
                ]
            );
            $settingData = $setting->data;

            return new OpencastClient([
                'base_uri' => $settingData['url'],
                'verify' => config('app.env') === 'production',
                'auth' => [
                    $settingData['username'],
                    $settingData['password'],
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
