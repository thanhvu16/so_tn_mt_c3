<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::get('don-vi', 'DonViController@index');
    Route::get('chuc-vu', 'ChucVuController@index');
    Route::get('quyen-han', 'ChucVuController@getRole');
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {

        Route::post('user/update', 'UserController@update');
        Route::post('user/update-avatar', 'UserController@updateAvatar');
        Route::post('user/change-password', 'UserController@changePassword');
        Route::post('user/logout', 'UserController@logout');

        Route::get('home', 'HomeController@index');
    });
});
