<?php

namespace App\Providers;

use App\Http\Clients\WowzaClient;
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
            $config = $this->app->get('config')['wowza'];

            return new WowzaClient([
                'base_uri' => $config['base_uri'],
                'verify'   => config('app.env') === 'production',
                'auth'     => [
                    $config['digest_user'],
                    $config['digest_pass'],
                    'digest',
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
