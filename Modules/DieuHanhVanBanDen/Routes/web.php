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

//Route::prefix('dieuhanhvanbanden')->group(function() {
//    Route::get('/', 'DieuHanhVanBanDenController@index');
//});

Route::resource('phan-loai-van-ban', 'PhanLoaiVanBanController');

Route::get('van-ban-den-chi-tiet/{id}', 'DieuHanhVanBanDenController@show')->name('van_ban_den_chi_tiet.show');

Route::get('van-ban-da-phan-loai', 'PhanLoaiVanBanController@daPhanLoai')->name('phan-loai-van-ban.da_phan_loai');

Route::resource('van-ban-lanh-dao-xu-ly', 'VanBanLanhDaoXuLyController');

Route::resource('tra-lai-van-van', 'TraLaiVanBanController');

Route::get('list-don-vi-phoi-hop/{id}', 'VanBanLanhDaoXuLyController@getListDonVi');

Route::post('save-don-vi-chu-tri', 'VanBanLanhDaoXuLyController@saveDonViChuTri')->name('van-ban-lanh-dao.save_don_vi_chu_tri');

Route::resource('van-ban-tra-lai', 'VanBanTraLaiController');

Route::resource('van-ban-den-don-vi', 'VanBanDenDonViController');

Route::get('list-can-bo-phoi-hop/{id}', 'VanBanDenDonViController@getCanBoPhoiHop');

Route::get('van-ban-da-chi-dao', 'VanBanDenDonViController@vanBanDaChiDao')->name('van_ban_don_vi.da_chi_dao');

Route::resource('gia-han-van-ban', 'GiaHanVanBanController');

Route::post('duyet-gia-han-van-ban', 'GiaHanVanBanController@duyetGiaHan');

Route::resource('giai-quyet-van-ban', 'GiaiQuyetVanBanController');

Route::get('van-ban-den-hoan-thanh-cho-duyet', 'VanBanDenHoanThanhController@choDuyet')->name('van-ban-den-hoan-thanh.cho-duyet');

Route::post('duyet-van-ban', 'VanBanDenHoanThanhController@duyetVanBan');
