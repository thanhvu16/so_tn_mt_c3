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

Route::prefix('bao-cao-thong-ke')->group(function() {
    Route::get('/', 'BaoCaoThongKeController@index')->name('bao_cao_thong_ke.index');
    Route::get('detail', 'BaoCaoThongKeController@detail')->name('bao_cao_thong_ke.detail');
    //Route::get('van-ban-den-don-vi', 'BaoCaoThongKeController@vanBanDenDonVi')->name('bao_cao_thong_ke.van_ban_don_vi');
    //Route::get('van-ban-don-vi-hoan-thanh', 'VanBanDonViController@vanBanHoanThanh')->name('bao_cao_thong_ke.van_ban_hoan_thanh');
    //Route::get('van-ban-don-vi-dang-xu-ly', 'VanBanDonViController@vanBanDangXuLy')->name('bao_cao_thong_ke.van_ban_dang_xu_ly');
});
