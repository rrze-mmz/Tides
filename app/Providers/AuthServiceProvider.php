<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use App\Policies\AssetPolicy;
use App\Policies\ClipPolicy;
use App\Policies\CommentPolicy;
use App\Policies\SeriesPolicy;
use App\Policies\UserPolicy;
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
        Clip::class    => ClipPolicy::class,
        Asset::class   => AssetPolicy::class,
        Series::class  => SeriesPolicy::class,
        Comment::class => CommentPolicy::class,
        User::class    => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //series
        Gate::define('index-all-series', [SeriesPolicy::class, 'index']);
        Gate::define('create-series', [SeriesPolicy::class, 'create']);
        Gate::define('edit-series', [SeriesPolicy::class, 'edit']);
        Gate::define('update-series', [SeriesPolicy::class, 'update']);
        Gate::define('view-series', [SeriesPolicy::class, 'view']);
        Gate::define('delete-series', [SeriesPolicy::class, 'delete']);
        Gate::define('change-series-owner', [SeriesPolicy::class, 'changeOwner']);

        //clips
        Gate::define('index-all-clips', [ClipPolicy::class, 'index']);
        Gate::define('create-clips', [ClipPolicy::class, 'create']);
        Gate::define('edit-clips', [ClipPolicy::class, 'edit']);
        Gate::define('view-clips', [ClipPolicy::class, 'view']);
        Gate::define('view-comments', [ClipPolicy::class, 'viewComments']);
        Gate::define('view-video', [ClipPolicy::class, 'viewVideo']);
        Gate::define('edit-assets', [AssetPolicy::class, 'edit']);
        //user
        Gate::define('show-users', [UserPolicy::class, 'show']);
        Gate::define('access-dashboard', [UserPolicy::class, 'dashboard']);
        Gate::define('view-opencast-workflows', [UserPolicy::class, 'opencastWorkflows']);
        Gate::define('view-superadmin-pages', [UserPolicy::class, 'viewSuperadminPages']);
        Gate::define('view-superadmin-menu-items', [UserPolicy::class, 'viewSuperadminPages']);
        Gate::define('view-admin-menu-items', fn(User $user) => $user->isAdmin());
        Gate::define('view-assistant-menu-items', fn(User $user) => $user->isAssistant() || $user->isAdmin());
        Gate::define('view-moderator-menu-items', fn(User $user) => $user->isModerator());
        //comments
        Gate::define('create-comment', [CommentPolicy::class, 'create']);
        Gate::define('delete-comment', [CommentPolicy::class, 'delete']);
    }
}
