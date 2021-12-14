<?php

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

Route::prefix('timkiemvanban')->group(function() {
    Route::get('/', 'TimKiemVanBanController@index');
});
Route::resource('tim-kiem-van-ban-den-full', 'TimKiemVanBanDenController');
//Route::get('tim-kiem-van-ban-den', array('as' => 'TimKiemVanBanDen', 'uses' => 'TimKiemVanBanDenController@index'));
Route::get('tim-kiem-van-ban-di', array('as' => 'TimKiemVanBanDi', 'uses' => 'TimKiemVanBanDiController@index'));
