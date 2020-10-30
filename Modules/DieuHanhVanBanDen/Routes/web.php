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

Route::get('chi-tiet-van-ban/{id}', 'DieuHanhVanBanDenController@show')->name('van_ban_den_chi_tiet.show');

Route::get('van-ban-da-phan-loai', 'PhanLoaiVanBanController@daPhanLoai')->name('phan-loai-van-ban.da_phan_loai');

Route::resource('van-ban-lanh-dao-xu-ly', 'VanBanLanhDaoXuLyController');

Route::resource('tra-lai-van-van', 'TraLaiVanBanController');

Route::get('list-don-vi-phoi-hop/{id}', 'VanBanLanhDaoXuLyController@getListDonVi');

Route::post('save-don-vi-chu-tri', 'VanBanLanhDaoXuLyController@saveDonViChuTri')->name('van-ban-lanh-dao.save_don_vi_chu_tri');

Route::resource('van-ban-tra-lai', 'VanBanTraLaiController');
