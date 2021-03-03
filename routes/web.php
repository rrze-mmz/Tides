<?php

use App\Http\Controllers\Backend\AssetsController;
use App\Http\Controllers\Backend\AdminDashboardController;
use App\Http\Controllers\Backend\ClipsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShowClipsController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::redirect('/admin','/admin/dashboard');

//Frontend clip routes
Route::get('/clips',[ShowClipsController::class,'index']);
Route::get('/clips/{clip}',[ShowClipsController::class,'show']);

//Backend routes
Route::prefix('admin')->middleware('auth')->group(function(){
   //Dashboard
    Route::get('/dashboard', [AdminDashboardController::class,'index'])->name('dashboard');

    //Clip
    Route::get('/clips/create',[ClipsController::class,'create'])->name('clips.create');
    Route::post('/clips',[ClipsController::class,'store'])->name('clips.store');
    Route::get('/clips/{clip}/edit',[ClipsController::class,'edit'])->name('clips.edit');
    Route::patch('/clips/{clip}',[ClipsController::class,'update'])->name('clips.update');

    //Assets
    Route::post('/clips/{clip}/assets',[AssetsController::class,'store']);
});

Auth::routes();

