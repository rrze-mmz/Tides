<?php

use App\Http\Controllers\Backend\AssetsController;
use App\Http\Controllers\Backend\AdminDashboardController;
use App\Http\Controllers\Backend\ClipsController;
use App\Http\Controllers\Frontend\ApiTagsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\ShowClipsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', HomeController::class)->name('home');
Route::redirect('/home', '/');
Route::redirect('/admin','/admin/dashboard');

//Quick search
Route::get('search', [SearchController::class, 'search']);

//Frontend clip route
Route::get('/clips',[ShowClipsController::class,'index']);
Route::get('/clips/{clip}',[ShowClipsController::class,'show']);

Route::get('/api/tags', ApiTagsController::class)->name('api.tags');

//Backend routes
Route::prefix('admin')->middleware('auth')->group(function(){
   //Dashboard
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

    //Clip
    Route::get('/clips',[ClipsController::class,'index'])->name('clips.index');
    Route::get('/clips/create',[ClipsController::class,'create'])->name('clips.create');
    Route::post('/clips',[ClipsController::class,'store'])->name('clips.store');
    Route::get('/clips/{clip}/',[ClipsController::class,'edit'])->name('clips.edit');
    Route::patch('/clips/{clip}/',[ClipsController::class,'update'])->name('clips.update');
    Route::delete('/clips/{clip}/',[ClipsController::class,'destroy'])->name('clips.destroy');

    //Assets
    Route::post('/clips/{clip}/assets',[AssetsController::class,'store'])->name('admin.assets.store');
    Route::delete('assets/{asset}',[AssetsController::class, 'destroy'])->name('assets.destroy');
});

Auth::routes();

