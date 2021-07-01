<?php

use App\Http\Controllers\Backend\AssetsController;
use App\Http\Controllers\Backend\ClipsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DropzoneTransferController;
use App\Http\Controllers\Backend\OpencastController;
use App\Http\Controllers\Backend\SeriesClipsController;
use App\Http\Controllers\Backend\SeriesController;
use App\Http\Controllers\Backend\TriggerSmilFilesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Frontend\ApiTagsController;
use App\Http\Controllers\Frontend\AssetsDownloadController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\ShowClipsController;
use App\Http\Controllers\Frontend\ShowSeriesController;
use App\Http\Middleware\CheckLMSToken;
use App\Http\Middleware\EnsureLMSTokenIsValid;
use App\Models\Clip;
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
        'clip_' . $clip->id . '_token'=> $token,
        'clip_' . $clip->id . '_time'=> $time,
        'clip_' . $clip->id . '_client'=> $client,
    ]);

    return redirect()->route('frontend.clips.show', $clip);
})
    ->middleware(EnsureLMSTokenIsValid::class)
    ->name('clip.lms.link');


Route::get('/api/tags', ApiTagsController::class)->name('api.tags');

//change portal language
Route::get('/set_lang/{locale}', function ($locale) {

    if (! in_array($locale, ['en','de'])) {
        abort(400);
    }

    session()->put('locale', $locale);

    return back();
});

Route::get('/assetDownload/{asset}', AssetsDownloadController::class)->name('assets.download');

//Backend routes
Route::prefix('admin')->middleware('auth')->group(function () {
    //Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    //Series routes
    Route::resource('series', SeriesController::class)->except(['show','edit']);
    Route::get('/series/{series}', [SeriesController::class, 'edit'])->name('series.edit');

    //Clip routes
    Route::resource('clips', ClipsController::class)->except(['show','edit']);
    Route::get('/clips/{clip}/', [ClipsController::class, 'edit'])->name('clips.edit');

    Route::get('/clips/{clip}/transfer', [DropzoneTransferController::class, 'listFiles'])
        ->name('admin.clips.dropzone.listFiles');
    Route::post('/clips/{clip}/transfer', [DropzoneTransferController::class, 'transfer'])
        ->name('admin.clips.dropzone.transfer');

    Route::get('/clips/{clip}/triggerSmilFiles', TriggerSmilFilesController::class)
        ->name('admin.clips.triggerSmilFiles');

    Route::get('/series/{series}/addClip', [SeriesClipsController::class, 'create'])->name('series.clip.create');
    Route::post('series/{series}/addClip', [SeriesClipsController::class, 'store'])->name('series.clip.store');

    //Assets routes
    Route::post('/clips/{clip}/assets', [AssetsController::class, 'store'])->name('admin.assets.store');
    Route::delete('assets/{asset}', [AssetsController::class, 'destroy'])->name('assets.destroy');

    //Opencast routes
    Route::get('/opencast', OpencastController::class)->name('opencast.status');

    Route::get('/users', [UsersController::class,'index'])->name('users.index');
});

Auth::routes();
