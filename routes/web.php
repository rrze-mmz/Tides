<?php

use App\Http\Controllers\AssetsController;
use App\Http\Controllers\ClipsController;
use App\Http\Controllers\HomeController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/clips',[ClipsController::class,'index']);
Route::get('/clips/{clip}',[ClipsController::class,'show']);
Route::post('/clips',[ClipsController::class,'store'])->middleware('auth');
Route::patch('/clips/{clip}',[ClipsController::class,'update'])->middleware('auth');

Route::post('/clips/{clip}/assets',[AssetsController::class,'store'])->middleware('auth');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

