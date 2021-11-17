<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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

// ログイン状態
Route::group(['middleware' => 'auth'], function() {

    // ユーザ関連
    Route::resource('users', UsersController::class, ['only' => ['index', 'show', 'edit', 'update']]);
    // フォロー
    Route::post('users/{user}/follow', 'UsersController@follow')->name('follow');
    // フォロー解除
    Route::delete('users/{user}/unfollow', 'UsersController@unfollow')->name('unfollow');

});
