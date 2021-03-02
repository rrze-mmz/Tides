<?php

use App\Http\Controllers\AssetsController;
use App\Http\Controllers\Backend\AdminDashboardController;
use App\Http\Controllers\ClipsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TweetsController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/clips',[ClipsController::class,'index']);
Route::get('/clips/{clip}',[ClipsController::class,'show']);

Route::post('/clips/{clip}/assets',[AssetsController::class,'store'])->middleware('auth');

Auth::routes();

Route::middleware('auth')->group(function(){
    Route::get('/admin/dashboard', [AdminDashboardController::class,'index'])->name('dashboard');
    Route::get('/admin/clips/create',[ClipsController::class,'create'])->name('clips.create');
    Route::post('/admin/clips',[ClipsController::class,'store'])->name('clips.store');
    Route::patch('/admin/clips/{clip}',[ClipsController::class,'update'])->middleware('auth');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

