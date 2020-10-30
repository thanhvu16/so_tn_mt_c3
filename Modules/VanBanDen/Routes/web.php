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

//Route::prefix('vanbanden')->group(function() {
//    Route::get('/', 'VanBanDenController@index');
//
//
//    Route::get('danh-sach-do-bao-mat', 'DoMatController@danhsach')->name('danhsachdobaomat');
//    Route::resource('do-bao-mat', 'DoMatController')->except('show');
//    Route::post('do-bao-mat/delete/{id}', array('as' => 'xoadobaomat', 'uses' => 'DoMatController@destroy'));
//});
Route::resource('van-ban-den', 'VanBanDenController');
