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

Route::post('/register', 'API\AuthController@register')->name('API.register');
Route::post('/login', 'API\AuthController@login')->name('API.login');

Route::group(['middleware' => 'ApiMiddleware'], function () {
    Route::get('/profile', 'API\UserController@fetch')->name('API.profile');
});
