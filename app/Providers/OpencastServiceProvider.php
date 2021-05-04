<?php

namespace App\Providers;

use App\Http\Clients\OpencastClient;
use Illuminate\Support\ServiceProvider;

class OpencastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OpencastClient::class, function(){
            $config = $this->app->get('config')['opencast'];
            return new OpencastClient([
                'base_uri'  => $config['base_uri'],
                'verify'    => config('app.env')==='production',
                'auth'     => [
                    $config['digest_user'],
                    $config['digest_pass']
                ]
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
