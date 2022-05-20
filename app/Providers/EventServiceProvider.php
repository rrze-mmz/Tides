<?php

namespace App\Providers;

use App\Events\AssetDeleted;
use App\Events\ChapterDeleted;
use App\Events\ClipDeleted;
use App\Events\DocumentDeleted;
use App\Events\SeriesDeleted;
use App\Listeners\DeleteAssetFile;
use App\Listeners\DeleteClipResources;
use App\Listeners\DeleteDocumentFile;
use App\Listeners\DeleteSeriesResources;
use App\Listeners\UpdateClipChapter;
use App\Models\Clip;
use App\Models\Collection;
use App\Models\Document;
use App\Models\Presenter;
use App\Models\Series;
use App\Models\User;
use App\Observers\ClipObserver;
use App\Observers\CollectionObserver;
use App\Observers\PresenterObserver;
use App\Observers\SeriesObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AssetDeleted::class    => [
            DeleteAssetFile::class
        ],
        DocumentDeleted::class => [
            DeleteDocumentFile::class
        ],
        ChapterDeleted::class  => [
            UpdateClipChapter::class
        ],
        ClipDeleted::class     => [
            DeleteClipResources::class
        ],
        Registered::class      => [
            SendEmailVerificationNotification::class,
        ],
        SeriesDeleted::class   => [
            DeleteSeriesResources::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Series::observe(SeriesObserver::class);
        Clip::observe(ClipObserver::class);
        User::observe(UserObserver::class);
        Presenter::observe(PresenterObserver::class);
        Collection::observe(CollectionObserver::class);
    }
}
