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

Route::post('/threads/{channel}/{thread}/replies', [App\Http\Controllers\ReplyController::class, 'store']);

Route::patch('/replies/{reply}', [App\Http\Controllers\ReplyController::class, 'update']);
Route::delete('/replies/{reply}', [App\Http\Controllers\ReplyController::class, 'destroy']);
Route::post('/replies/{reply}/favorites', [App\Http\Controllers\FavoritesController::class, 'store']);

Route::get('/profiles/{user}', [App\Http\Controllers\ProfilesController::class,'show'])->name('profile');

Route::post('/threads/{channel}/{thread}/subscriptions', [App\Http\Controllers\ThreadSubscriptionsController::class,'store'])->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', [App\Http\Controllers\ThreadSubscriptionsController::class,'destroy'])->middleware('auth');

Route::get('/profiles/{user}/notifications', [App\Http\Controllers\UserNotificationsController::class,'index']);
Route::delete('/profiles/{user}/notifications/{notification}', [App\Http\Controllers\UserNotificationsController::class,'destroy']);

Route::get('/api/users', [App\Http\Controllers\Api\UsersController::class,'index']);

Route::post('/api/users/{user}/avatar', [App\Http\Controllers\Api\UserAvatarController::class,'store'])->middleware('auth')->name('avatar');