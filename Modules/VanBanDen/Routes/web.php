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
Route::resource('don-vi-nhan-van-ban-den', 'DonViNhanVanBanDenController');
Route::post('so-den', array('as' => 'soden', 'uses' => 'VanBanDenController@laysoden'));
Route::post('upload-multiple', array('as' => 'multiple_file', 'uses' => 'VanBanDenController@multiple_file'));
Route::post('delete-van-ban-den', array('as' => 'delete_vb_den', 'uses' => 'VanBanDenController@delete_vb_den'));
Route::get('chi-tiet-van-ban-den/{id}', array('as' => 'chi_tiet_van_ban_den', 'uses' => 'VanBanDenController@chi_tiet_van_ban_den'));
Route::match(['get', 'post','put'], 'ds-van-ban-den-tu-mail', ['as' =>'dsvanbandentumail','uses' => 'VanBanDenController@dsvanbandentumail']);
Route::match(['get', 'post','put'], 'van-ban-den-tu-mail', ['as' =>'vanbandentumail', 'uses' => 'VanBanDenController@taovbdentumail']);
Route::match(['get', 'post','put'], 'kiem_tra_trich_yeu', ['as' =>'ktratrichyeu', 'uses' => 'VanBanDenController@kiemTraTrichYeu']);
Route::post('ds-van-ban-den-tu-mail/delete-email/{id}','VanBanDenController@deleteEmail')->name('delete-email');
Route::post('ds-van-ban-den-tu-mail/luu-van-ban-tu-mail','VanBanDenController@luuvanbantumail')->name('luuvanbantumail');
