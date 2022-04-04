<?php

use App\Http\Controllers\Backend\ActivitiesController;
use App\Http\Controllers\Backend\AssetsController;
use App\Http\Controllers\Backend\AssetsTransferController;
use App\Http\Controllers\Backend\ClipsCollectionsController;
use App\Http\Controllers\Backend\ClipsController;
use App\Http\Controllers\Backend\CollectionsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\OpencastController;
use App\Http\Controllers\Backend\PresentersController;
use App\Http\Controllers\Backend\ChaptersController;
use App\Http\Controllers\Backend\SeriesClipsController;
use App\Http\Controllers\Backend\SeriesController;
use App\Http\Controllers\Backend\TriggerSmilFilesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Frontend\ApiController;
use App\Http\Controllers\Frontend\AssetsDownloadController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\ShowClipsController;
use App\Http\Controllers\Frontend\ShowSeriesController;
use App\Http\Middleware\CheckLMSToken;
use App\Models\Activity;
use App\Models\Clip;
use App\Models\Series;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\Route;

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


//Frontend clip routes
Route::controller(ShowClipsController::class)->prefix('/clips')->group(function () {
    Route::get('/', 'index')->name('frontend.clips.index');
    Route::get('/{clip}', 'show')->name('frontend.clips.show');
});


Route::get('/protector/link/clip/{clip:id}/{token}/{time}/{client}', function (Clip $clip, $token, $time, $client) {
    session()->put([
        'clip_' . $clip->id . '_token'  => $token,
        'clip_' . $clip->id . '_time'   => $time,
        'clip_' . $clip->id . '_client' => $client,
    ]);

    return to_route('frontend.clips.show', $clip);
})
    ->middleware(['lms.token'])
    ->name('clip.lms.link');

Route::controller(ApiController::class)->prefix('/api')->group(function () {
    Route::get('/clips', 'clips')->name('api.clips');
    Route::get('/tags', 'tags')->name('api.tags');
    Route::get('/presenters', 'presenters')->name('api.presenters');
    Route::get('/organizations', 'organizations')->name('api.organizations');
});


//change portal language
Route::get('/set_lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'de'])) {
        abort(400);
    }
    session()->put('locale', $locale);

    return back();
});

Route::get('/assetDownload/{asset}', AssetsDownloadController::class)->name('assets.download');

//Backend routes
Route::prefix('admin')->middleware(['auth', 'can:access-dashboard'])->group(function () {
    //Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    //Series routes
    Route::resource('series', SeriesController::class)->except(['show', 'edit']);
    Route::get('/series/{series}', [SeriesController::class, 'edit'])->name('series.edit');

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

    //Chapter routes for a certain series
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


    //Assets routes
    Route::post('/clips/{clip}/assets', [AssetsController::class, 'store'])->name('admin.assets.store');
    Route::delete('assets/{asset}', [AssetsController::class, 'destroy'])->name('assets.destroy');

    //Opencast routes
    Route::get('/opencast', OpencastController::class)->name('opencast.status');

    Route::get('/activities', function () {
        return view('backend.activities.index', [
            'activities' => Activity::paginate(20),
        ]);
    })->name('activities.index');

    // Portal admin resources
    Route::middleware(['user.admin'])->group(function () {
        Route::resource('users', UsersController::class)->except(['show']);
        Route::resource('presenters', PresentersController::class)->except(['show']);

        //Collections administration
        Route::resource('collections', CollectionsController::class)->except(['show']);
        Route::post('collections/{collection}/toggleClips', ClipsCollectionsController::class)
            ->name('collections.toggleClips');
    });
});

Route::get('/test/{series}/elk', function (Series $series, ElasticsearchService $elkService) {
    $elkService->createIndex($series);
})->name('elasticsearch.test');

require __DIR__ . '/auth.php';
