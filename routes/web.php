<?php

use App\Http\Controllers\Backend\AssetsController;
use App\Http\Controllers\Backend\AssetsTransferController;
use App\Http\Controllers\Backend\ClipsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\OpencastController;
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
use App\Models\Clip;
use App\Models\Series;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::redirect('/home', '/');
Route::redirect('/admin', '/admin/dashboard');

//Quick search
Route::get('search', [SearchController::class, 'search'])->name('search');

Route::get('/series/{series}', [ShowSeriesController::class, 'show'])->name('frontend.series.show');

//Frontend clip route
Route::get('/clips', [ShowClipsController::class, 'index'])->name('frontend.clip.index');

Route::get('/clips/{clip}', [ShowClipsController::class, 'show'])
    ->name('frontend.clips.show');

Route::get('/protector/link/clip/{clip:id}/{token}/{time}/{client}', function (Clip $clip, $token, $time, $client) {
    session()->put([
        'clip_' . $clip->id . '_token'  => $token,
        'clip_' . $clip->id . '_time'   => $time,
        'clip_' . $clip->id . '_client' => $client,
    ]);

    return redirect()->route('frontend.clips.show', $clip);
})
    ->middleware(['lms.token'])
    ->name('clip.lms.link');


Route::get('/api/tags', [ApiController::class, 'tags'])->name('api.tags');
Route::get('/api/organizations', [ApiController::class, 'organizations'])->name('api.organizations');

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
        Route::get('/clips/{clip}/dropzone/list', [AssetsTransferController::class, 'listDropzoneFiles'])
            ->name('admin.clips.dropzone.listFiles');
        Route::post('/clips/{clip}/dropzone/transfer', [AssetsTransferController::class, 'transferDropzoneFiles'])
            ->name('admin.clips.dropzone.transfer');

        /*
         * Transfer assets from Opencast Server
         */
        Route::get('/clips/{clip}/opencast/list', [AssetsTransferController::class, 'listOpencastEvents'])
            ->name('admin.clips.opencast.listEvents');
        Route::post('/clips/{clip}/opencast/transfer', [AssetsTransferController::class, 'transferOpencastFiles'])
            ->name('admin.clips.opencast.transfer');

        // Create SMIL files for WOWZA hls streaming
        Route::get('/clips/{clip}/triggerSmilFiles', TriggerSmilFilesController::class)
            ->name('admin.clips.triggerSmilFiles');
    });


    // Create a clip for a certain series.
    Route::get('/series/{series}/addClip', [SeriesClipsController::class, 'create'])->name('series.clip.create');
    Route::post('series/{series}/addClip', [SeriesClipsController::class, 'store'])->name('series.clip.store');

    //Assets routes
    Route::post('/clips/{clip}/assets', [AssetsController::class, 'store'])->name('admin.assets.store');
    Route::delete('assets/{asset}', [AssetsController::class, 'destroy'])->name('assets.destroy');

    //Opencast routes
    Route::get('/opencast', OpencastController::class)->name('opencast.status');

    // Basic portal user administration
    Route::middleware(['user.admin'])->group(function () {
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/users/create', [UsersController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
//        TODO
//        Route::get('/users/{user}', [UsersController::class,'show'])->name('users.show')
        Route::patch('/users/{user}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    });
});

Route::get('/test/{series}/elk', function (Series $series, ElasticsearchService $elkService) {
    $elkService->createIndex($series);
})->name('elasticsearch.test');

Auth::routes(['register' => config('tides.allow_user_registration')]);
