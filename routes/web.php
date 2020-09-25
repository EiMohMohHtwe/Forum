<?php

use App\Http\Controllers\ThreadsController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/threads', [ThreadsController::class,'index'])->name('threads.index');
Route::get('/threads/{channel}', [ThreadsController::class,'index']);
Route::get('/create', [ThreadsController::class,'create']);
Route::get('threads/{channel}/{thread}', [ThreadsController::class,'show']);
Route::delete('threads/{channel}/{thread}', [ThreadsController::class,'destroy']);
Route::post('/threads', [ThreadsController::class,'store']);

Route::get('/threads/{channel}/{thread}/replies', [App\Http\Controllers\ReplyController::class, 'store']);

Route::post('/replies/{reply}/favorites', [App\Http\Controllers\FavoritesController::class, 'store']);

Route::get('/profiles/{user}', [App\Http\Controllers\ProfilesController::class,'show'])->name('profile');