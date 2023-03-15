<?php

namespace App\Providers;

use App\Http\Clients\ElasticsearchClient;
use App\Models\Setting;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    private array $settingsData;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ClientBuilder::class, function () {
            $this->settingsData = $this->getConfig();

            $builder = new ClientBuilder();

            $connectionString =
                "{$this->settingsData['username']}:{$this->settingsData['password']}
                @{$this->settingsData['url']}:{$this->settingsData['port']}";

            $builder->setHosts([$connectionString]);

            return $builder;
        });

        $this->app->singleton(ElasticsearchClient::class, function () {
            $this->settingsData = $this->getConfig();

            return new ElasticsearchClient([
                'base_uri' => "{$this->settingsData['url']}:{$this->settingsData['port']}",
                'verify' => config('app.env') === 'production',
                'auth' => [
                    $this->settingsData['username'],
                    $this->settingsData['password'],
                ],
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    private function getConfig()
    {
        $setting = Setting::firstOrCreate(
            ['name' => 'elasticSearch'],
            [
                'data' => config('settings.elasticSearch'),
            ]
        );

        return $setting->data;
    }
}
