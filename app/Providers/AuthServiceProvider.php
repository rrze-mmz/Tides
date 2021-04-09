<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Clip;
use App\Policies\AssetPolicy;
use App\Policies\ClipPolicy;
use App\Policies\SeriesPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Clip::class => ClipPolicy::class,
        Asset::class => AssetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('create-series',[SeriesPolicy::class,'create']);
        Gate::define('edit-clips', [ClipPolicy::class, 'edit']);
        Gate::define('create-clips', [ClipPolicy::class, 'create']);
        Gate::define('edit-assets', [AssetPolicy::class, 'edit']);
    }
}
