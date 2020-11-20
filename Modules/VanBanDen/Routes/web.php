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
Route::post('so-den', array('as' => 'soden', 'uses' => 'VanBanDenController@laysoden'));
Route::post('upload-multiple', array('as' => 'multiple_file', 'uses' => 'VanBanDenController@multiple_file'));
Route::post('delete-van-ban-den', array('as' => 'delete_vb_den', 'uses' => 'VanBanDenController@delete_vb_den'));
Route::get('chi-tiet-van-ban-den/{id}', array('as' => 'chi_tiet_van_ban_den', 'uses' => 'VanBanDenController@chi_tiet_van_ban_den'));
