<?php

use App\Http\Controllers\Backend\AdminPortalApplicationController;
use App\Http\Controllers\Backend\AssetDestroyController;
use App\Http\Controllers\Backend\AssetsTransferController;
use App\Http\Controllers\Backend\ChaptersController;
use App\Http\Controllers\Backend\ClipsCollectionsController;
use App\Http\Controllers\Backend\ClipsController;
use App\Http\Controllers\Backend\CollectionsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DevicesController;
use App\Http\Controllers\Backend\DocumentController;
use App\Http\Controllers\Backend\ElasticSearchSettingsController;
use App\Http\Controllers\Backend\FileUploadController;
use App\Http\Controllers\Backend\ImagesController;
use App\Http\Controllers\Backend\OpencastSettingsController;
use App\Http\Controllers\Backend\PortalSettingsController;
use App\Http\Controllers\Backend\PresentersController;
use App\Http\Controllers\Backend\SeriesClipsController;
use App\Http\Controllers\Backend\SeriesController;
use App\Http\Controllers\Backend\SeriesMembershipController;
use App\Http\Controllers\Backend\SeriesOpencastController;
use App\Http\Controllers\Backend\SeriesOwnership;
use App\Http\Controllers\Backend\StreamingSettingsController;
use App\Http\Controllers\Backend\SystemsCheckController;
use App\Http\Controllers\Backend\TriggerSmilFilesController;
use App\Http\Controllers\Backend\UpdateClipImage;
use App\Http\Controllers\Backend\UpdateSeriesImage;
use App\Http\Controllers\Backend\UploadImageController;
use App\Http\Controllers\Backend\UserNotificationsController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Frontend\AcceptUseTermsController;
use App\Http\Controllers\Frontend\AdminPortalUseTermsController;
use App\Http\Controllers\Frontend\ApiController;
use App\Http\Controllers\Frontend\AssetsDownloadController;
use App\Http\Controllers\Frontend\FeedsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\ShowClipsController;
use App\Http\Controllers\Frontend\ShowOrganizationsController;
use App\Http\Controllers\Frontend\ShowSeriesController;
use App\Http\Controllers\Frontend\UserApplicationsController;
use App\Http\Controllers\Frontend\UserCommentsController;
use App\Http\Controllers\Frontend\UserSettingsController;
use App\Http\Controllers\Frontend\UserSubscriptionsController;
use App\Models\Activity;
use App\Models\Clip;
use App\Models\Series;
use App\Services\ElasticsearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

Route::get('/', HomeController::class)->name('home');
Route::redirect('/home', '/');
Route::redirect('/admin', '/admin/dashboard');

//Quick search
Route::get('search', [SearchController::class, 'search'])->name('search');

//frontend series routes

Route::controller(ShowSeriesController::class)->prefix('/series')->group(function () {
    Route::get('/index', 'index')->name('frontend.series.index');
    Route::get('/{series}', 'show')->name('frontend.series.show');
});

Route::get('/series/{series}/feed/{assetsResolution}', [FeedsController::class, 'series'])
    ->name('frontend.series.feed');

//keep backwards compatibility
Route::get('/course/id/{series}', function (Series $series) {
    return to_route('frontend.series.show', $series);
});
//Frontend clip routes·
Route::controller(ShowClipsController::class)->prefix('/clips')->group(function () {
    Route::get('/', 'index')->name('frontend.clips.index');
    Route::get('/{clip}', 'show')->name('frontend.clips.show');
});

Route::get('/clips/{clip}/feed/{assetsResolution}', [FeedsController::class, 'clips'])
    ->name('frontend.clips.feed');

Route::get('/organizations/', [ShowOrganizationsController::class, 'index'])
    ->name('frontend.organizations.index');
Route::get('/organizations/{organization:slug}', [ShowOrganizationsController::class, 'show'])
    ->name('frontend.organizations.show');

//Frontend myPortal links
Route::prefix('/my'.str(config('app.name')))->middleware(['auth'])->group(function () {
    Route::put('/', AcceptUseTermsController::class)->name('frontend.acceptUseTerms');
    Route::middleware(['use.terms'])->group(function () {
        Route::get('/settings', [UserSettingsController::class, 'edit'])
            ->name('frontend.userSettings.edit');
        Route::put('/settings', [UserSettingsController::class, 'update'])
            ->name('frontend.userSettings.update');
        Route::get('/subscriptions', UserSubscriptionsController::class)
            ->name('frontend.user.subscriptions');
        Route::get('/comments', UserCommentsController::class)
            ->name('frontend.user.comments');
        Route::get('/adminPortal/useTerms', [AdminPortalUseTermsController::class, 'terms'])
            ->name('frontend.admin.portal.use.terms');
        Route::put('/adminPortal/useTerms', [AdminPortalUseTermsController::class, 'accept'])
            ->name('frontend.admin.portal.accept.use.terms');
        Route::get('/applications', UserApplicationsController::class)
            ->name('frontend.user.applications');
    });
});

Route::get(
    '/protector/link/{objType}/{objID}/{token}/{time}/{client}',
    function (string $objType, int $objID, $token, $time, $client) {
        $objType = getUrlTokenType($objType);
        $client = getUrlClientType($client);

        $model = "App\Models\\".ucfirst($objType);

        $obj = $model::findorFail($objID);

        setSessionAccessToken($obj, $token, $time, $client);

        return to_route('frontend.'.str($objType)->plural().'.show', $obj);
    }
)
    ->middleware(['access.token'])
    ->name('lms.link');

//Routes used for select2 js component
Route::controller(ApiController::class)->prefix('/api')->group(function () {
    Route::get('/clips', 'clips')->name('api.clips');
    Route::get('/tags', 'tags')->name('api.tags');
    Route::get('/presenters', 'presenters')->name('api.presenters');
    Route::get('/users', 'users')->name('api.users');
    Route::get('/organizations', 'organizations')->name('api.organizations');
    Route::get('/images', 'images')->name('api.images');
});

//change portal language
Route::get('/set_lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'de'])) {
        abort(400);
    }
    session()->put('locale', $locale);

    return back();
});

Route::get('/assetDownload/{asset}', AssetsDownloadController::class)->name('assets.download');

//Backend routes
Route::prefix('admin')->middleware(['auth', 'saml', 'can:access-dashboard'])->group(function () {
    //Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/goto/series', function (Request $request) {
        $validated = $request->validate([
            'seriesID' => ['required', 'integer'],
        ]);
        $series = Series::find($validated['seriesID']);

        return (is_null($series)) ? to_route('dashboard')->with('flashMessage', 'Series not found')
            : to_route('series.edit', $series);
    })->name('goto.series');

    Route::post('/goto/clip', function (Request $request) {
        $validated = $request->validate([
            'clipID' => ['required', 'integer'],
        ]);
        $clip = Clip::find($validated['clipID']);

        return (is_null($clip)) ? to_route('dashboard')->with('flashMessage', 'Clip not found')
            : to_route('clips.edit', $clip);
    })->name('goto.clip');

    Route::get('/notifications', UserNotificationsController::class)->name('user.notifications');

    //Series routes
    Route::resource('series', SeriesController::class)->except(['show', 'edit']);
    Route::get('/series/{series}', [SeriesController::class, 'edit'])->name('series.edit');

    //Series Opencast routes
    Route::post('/series/{series}/createOpencastSeries/', [SeriesOpencastController::class, 'createSeries'])
        ->name('series.opencast.createSeries');
    Route::post('/series/{series}/updateOpencastSeriesAcl}', [SeriesOpencastController::class, 'updateAcl'])
        ->name('series.opencast.updateSeriesAcl');

    Route::controller(SeriesClipsController::class)->prefix('/series')->group(function () {
        // Create a clip for a certain series.
        Route::get('/{series}/addClip', 'create')->name('series.clips.create');
        Route::post('/{series}/addClip', 'store')->name('series.clips.store');

        Route::get('/{series}/reorder', 'listClips')->name('series.clips.changeEpisode');
        Route::post('/{series}/reorder', 'reorder')->name('series.clips.reorder');

        //add/remove an existing clip to selected series
        Route::get('/listSeries/{clip}', 'listSeries')->name('series.clips.listSeries');
        Route::post('/{series}/assignSeries/{clip}', 'assign')->name('series.clips.assign');
        Route::delete('/clip/removeSeries/{clip}', 'remove')->name('series.clips.remove');
    });

    //Series chapters
    Route::controller(ChaptersController::class)->prefix('/series')
        ->middleware('can:edit,series')
        ->group(function () {
            Route::get('/{series}/chapters', 'index')->name('series.chapters.index');
            Route::post('/{series}/chapters', 'store')->name('series.chapters.create');
            Route::put('/{series}/chapters/', 'update')->name('series.chapters.update');
            Route::get('/{series}/chapters/{chapter}', 'edit')->name('series.chapters.edit');
            Route::patch('/{series}/chapters/{chapter}/addClips', 'addClips')->name('series.chapters.addClips');
            Route::patch('/{series}/chapters/{chapter}/removeClips', 'removeClips')
                ->name('series.chapters.removeClips');
            Route::delete('/{series}/chapters/{chapter}', 'destroy')->name('series.chapters.delete');
        });

    //Series invitations - Invite a user to be a member of a Series
    Route::post('/series/{series}/ownership', SeriesOwnership::class)->name('series.ownership.change');
    Route::post('/series/{series}/membership/addUser', [SeriesMembershipController::class, 'add'])
        ->name('series.membership.addUser');
    Route::post('/series/{series}/membership/removeUser', [SeriesMembershipController::class, 'remove'])
        ->name('series.membership.removeUser');

    //Series Images
    Route::put('/series/{series}/updateImage/', UpdateSeriesImage::class)->name('update.series.image');

    //Clip routes
    Route::resource('clips', ClipsController::class)->except(['show', 'edit']);
    Route::get('/clips/{clip}/', [ClipsController::class, 'edit'])->name('clips.edit');
    /*
     * A group of clip assets functions
     */
    Route::middleware('can:edit,clip')->group(function () {
        /*
         * Transfer assets from a certain path in the server
         * called "dropzone". This path is usually the export path
         * for an extern service ffmpeg video transcoding. In FAU case
         * the encoding is performed by the HPC cluster
         */
        Route::controller(AssetsTransferController::class)->prefix('/clips')->group(function () {
            Route::post('/{clip}/transferSingle', 'transferSingleAsset')->name('admin.clips.asset.transferSingle');

            Route::get('/{clip}/dropzone/list', 'listDropzoneFiles')->name('admin.clips.dropzone.listFiles');
            Route::post('/{clip}/dropzone/transfer', 'transferDropzoneFiles')->name('admin.clips.dropzone.transfer');
            /*
             * Transfer assets from Opencast Server
             */
            Route::get('/{clip}/opencast/list', 'listOpencastEvents')->name('admin.clips.opencast.listEvents');
            Route::post('/{clip}/opencast/transfer', 'transferOpencastFiles')->name('admin.clips.opencast.transfer');
        });

        // Create SMIL files for WOWZA hls streaming
        Route::get('/clips/{clip}/triggerSmilFiles', TriggerSmilFilesController::class)
            ->name('admin.clips.triggerSmilFiles');
    });

    //Clips Images
    Route::put('/clips/{clip}/updateImage/', UpdateClipImage::class)->name('update.clip.image');

    //Assets routes
    Route::delete('assets/{asset}', AssetDestroyController::class)->name('assets.destroy');

    //Documents routes
    Route::post('/document/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/series/{series}/document/{document}', [DocumentController::class, 'viewSeriesDocument'])
        ->name('document.series.view');
    Route::get('/clip/{clip}/document/{document}', [DocumentController::class, 'viewClipDocument'])
        ->name('document.clip.view');
    Route::delete('/document/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    //Presenter routes
    Route::resource('presenters', PresentersController::class)->except(['show']);

    Route::get('/activities', function () {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        return view('backend.activities.index', [
            'activities' => Activity::paginate(20),
        ]);
    })->name('activities.index');

    Route::resource('images', ImagesController::class);
    Route::resource('devices', DevicesController::class)->except(['show']);
    Route::post('images/import/', UploadImageController::class)->name('images.import');
    Route::post('/uploads/process', [FileUploadController::class, 'process'])->name('uploads.process');

    // Portal admin resources
    Route::middleware(['user.admin'])->group(function () {
        Route::resource('users', UsersController::class)->except(['show']);

        //Collections administration
        Route::resource('collections', CollectionsController::class)->except(['show']);
        Route::post('collections/{collection}/toggleClips', ClipsCollectionsController::class)
            ->name('collections.toggleClips');
    });

    //Superadmin routes
    Route::middleware('can:administrate-superadmin-portal-pages')->group(function () {
        Route::get('/systems', SystemsCheckController::class)->name('systems.status');
        Route::get('/settings/index', function () {
            return view('backend.settings.index');
        })->name('settings.portal.index');
        Route::get('/settings/portal', [PortalSettingsController::class, 'show'])->name('settings.portal.show');
        Route::put('/settings/portal', [PortalSettingsController::class, 'update'])->name('settings.portal.update');
        Route::get('/settings/opencast', [OpencastSettingsController::class, 'show'])->name('settings.opencast.show');
        Route::put('/settings/opencast', [OpencastSettingsController::class, 'update'])
            ->name('settings.opencast.update');
        Route::get('/settings/streaming', [StreamingSettingsController::class, 'show'])
            ->name('settings.streaming.show');
        Route::put('/settings/streaming', [StreamingSettingsController::class, 'update'])
            ->name('settings.streaming.update');
        Route::get('/settings/elasticSearch', [ElasticSearchSettingsController::class, 'show'])
            ->name('settings.elasticSearch.show');
        Route::put('/settings/elasticSearch', [ElasticSearchSettingsController::class, 'update'])
            ->name('settings.elasticSearch.update');
        Route::post('/adminPortal/application', AdminPortalApplicationController::class)
            ->name('admin.portal.application.grant');
    });
});

Route::get('/test/{series}/elk', function (Series $series, ElasticsearchService $elkService) {
    $elkService->createIndex($series);
})->name('elasticsearch.test');

require __DIR__.'/auth.php';
