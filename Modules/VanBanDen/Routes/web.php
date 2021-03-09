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
Route::resource('in-so-van-ban-den', 'ThongkeVanBanDenController');
Route::resource('don-vi-nhan-van-ban-den', 'DonViNhanVanBanDenController');
Route::post('so-den', array('as' => 'soden', 'uses' => 'VanBanDenController@laysoden'));
Route::post('upload-multiple', array('as' => 'multiple_file', 'uses' => 'VanBanDenController@multiple_file'));
Route::post('luuGiayMoiMail', array('as' => 'luuGiayMoiMail', 'uses' => 'VanBanDenController@luuGiayMoiMail'));
Route::post('delete-van-ban-den', array('as' => 'delete_vb_den', 'uses' => 'VanBanDenController@delete_vb_den'));
Route::get('chi-tiet-van-ban-den/{id}', array('as' => 'chi_tiet_van_ban_den', 'uses' => 'VanBanDenController@chi_tiet_van_ban_den'));

Route::get('don-vi-nhan-van-ban-den/thong-tin-van-ban/{id}', array('as' => 'thongtinvb', 'uses' => 'DonViNhanVanBanDenController@thongtinvb'));
Route::get('don-vi-nhan-van-ban-den/thong-tin-van-ban-huyen/{id}', array('as' => 'thongtinvbhuyen', 'uses' => 'DonViNhanVanBanDenController@thongtinvbhuyen'));

Route::get('chi-tiet-van-ban-den-don-vi/{id}', array('as' => 'chi_tiet_van_ban_den_don_vi', 'uses' => 'DonViNhanVanBanDenController@chi_tiet_van_ban_den_don_vi'));
Route::match(['get', 'post','put'], 'ds-van-ban-den-tu-mail', ['as' =>'dsvanbandentumail','uses' => 'VanBanDenController@dsvanbandentumail']);
Route::match(['get', 'post','put'], 'van-ban-den-tu-mail', ['as' =>'vanbandentumail', 'uses' => 'VanBanDenController@taovbdentumail']);
Route::match(['get', 'post','put'], 'kiem_tra_trich_yeu', ['as' =>'ktratrichyeu', 'uses' => 'VanBanDenController@kiemTraTrichYeu']);
Route::post('ds-van-ban-den-tu-mail/delete-email/{id}','VanBanDenController@deleteEmail')->name('delete-email');
Route::post('ds-van-ban-den-tu-mail/luu-van-ban-tu-mail','VanBanDenController@luuvanbantumail')->name('luuvanbantumail');
Route::post('van-ban-den/vao-so-van-ban','DonViNhanVanBanDenController@vaosovanbandvnhan')->name('vaosovanbandvnhan');
Route::post('van-ban-den/vao-so-van-ban-huyen','DonViNhanVanBanDenController@vaosovanbanhuyen')->name('vaosovanbanhuyen');
Route::post('han-van-ban','VanBanDenController@layhantruyensangview')->name('layhantruyensangview');
