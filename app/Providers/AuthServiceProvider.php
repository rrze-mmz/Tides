<?php

namespace App\Providers;

use App\Models\Clip;
use App\Policies\ClipPolicy;
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
        Clip::class => ClipPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('edit-clips', [ClipPolicy::class, 'edit']);
        Gate::define('create-clips', [ClipPolicy::class, 'create']);
    }
}
