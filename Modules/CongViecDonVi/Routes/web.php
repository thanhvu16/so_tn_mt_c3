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

//Route::prefix('congviecdonvi')->group(function() {
//    Route::get('/', 'CongViecDonViController@index');
//});
Route::resource('cong-viec-don-vi', 'CongViecDonViController');
Route::resource('gia-han-cong-viec', 'GiaHanCongViecDonViController');
Route::resource('tao-cong-viec-don-vi', 'TaoCongViecDonViController');
Route::resource('giai-quyet-cong-viec', 'GiaiQuyetCongViecController');
Route::resource('cong-viec-don-vi-phoi-hop', 'CongViecPhoiHopController');
Route::resource('cong-viec-don-vi-phoi-hop', 'CongViecDonViPhoiHopController');
Route::get('data-don-vi-chu-tri', 'CongViecDonViController@getDataDonVi');
Route::get('get-don-vi-phoi-hop/{id}', 'CongViecDonViController@getDonViPhoiHop');
Route::get('cong-viec-dang-xu-ly', 'CongViecDonViController@dangXuLy')->name('cong-viec-don-vi.dang-xu-ly');
Route::get('cong-viec-da-xu-ly', 'CongViecDonViController@congViecDaXuLy')->name('cong-viec-don-vi.da-xu-ly');
Route::get('cong-viec-hoan-thanh-cho-duyet', 'CongViecHoanThanhController@hoanThanhChoDuyet')->name('cong-viec-hoan-thanh.cho-duyet');
Route::get('cong-viec-hoan-thanh', 'CongViecHoanThanhController@index')->name('cong-viec-don-vi.hoan-thanh');
Route::post('duyet-cong-viec', 'CongViecHoanThanhController@duyetCongViec');
Route::post('duyet-gia-han-cong-viec', 'GiaHanCongViecDonViController@duyetGiaHan');
Route::get('cong-viec-phoi-hop', 'CongViecDonViController@chuyenVienPhoiHop')->name('cong-viec-don-vi.chuyen-vien-phoi-hop');
Route::get('cong-viec-da-phoi-hop', 'CongViecDonViController@chuyenVienDaPhoiHop')->name('cong-viec-don-vi.chuyen-vien-da-phoi-hop');
Route::get('cong-viec-don-vi-phoi-hop-da-xu-ly', 'CongViecDonViPhoiHopController@daXuLy')->name('cong-viec-don-vi-phoi-hop.da-xu-ly');
Route::get('cong-viec-don-vi-phoi-hop-dang-xu-ly', 'CongViecDonViPhoiHopController@dangXuLy')->name('cong-viec-don-vi-phoi-hop.dang-xu-ly');
Route::get('list-can-bo-phoi-hop/{id}', 'CongViecDonViController@getCanBoPhoiHop');
Route::get('cong-viec-xem-de-biet', 'CongViecDonViController@CongViecXemDeBiet')->name('cong-viec-don-vi.can-bo-xem-de-biet');
