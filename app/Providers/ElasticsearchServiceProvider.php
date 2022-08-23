<?php

namespace App\Providers;

use App\Http\Clients\ElasticsearchClient;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ClientBuilder::class, function () {
            $config = $this->app->get('config')['elasticsearch'];
            $builder = new ClientBuilder();

            $connectionString =
                $config['username'].':'.$config['password'].'@'.$config['url'].':'.$config['port'];

            $builder->setHosts([$connectionString]);

            return $builder;
        });

        $this->app->singleton(ElasticsearchClient::class, function () {
            $config = $this->app->get('config')['elasticsearch'];

            return new ElasticsearchClient([
                'base_uri' => $config['url'].':'.$config['port'],
                'verify' => config('app.env') === 'production',
                'auth' => [
                    $config['username'],
                    $config['password'],
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
