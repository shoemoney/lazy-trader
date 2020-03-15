<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout')->middleware('auth:api');
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::middleware('auth:api')->namespace('Api')->group(function() {
//    Route::get('/user', function (Request $request) {
//        return $request->user();
//    });

    Route::get('/coins/top', 'CoinsController@top');
    Route::resource('/coins', 'CoinsController');
});

//Route::any('test', function(){
//    $user =  \App\Models\User::findOrFail(1);
//    $user->notify(new \App\Notifications\TestBroadcastNotification());
//
//    return response()->json(['success' => true]);
//});

