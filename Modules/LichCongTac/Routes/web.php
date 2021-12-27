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

Route::prefix('lich-cong-tac')->group(function() {
    Route::get('/', 'LichCongTacController@index')->name('lich-cong-tac.index');
    Route::post('store', 'LichCongTacController@store')->name('lich-cong-tac.store');
    Route::get('edit/{id}', 'LichCongTacController@edit');
    Route::post('update/{id}', 'LichCongTacController@update')->name('lich-cong-tac.update');
});

Route::get('soan-bao-cao', 'SoanBaoCaoController@create')->name('soan_bao_cao.create');
Route::post('soan-bao-cao/store', 'SoanBaoCaoController@store')->name('soan-bao-cao.store');



Route::resource('quan-ly-cuoc-hop', 'QuanLyCuocHopController');
Route::get('chi-tiet-cuoc-hop/{id}', 'QuanLyCuocHopController@chiTietCuocHop')->name('chitiethop');
Route::get('lich-hop-phong', 'LichCongTacController@caPhong')->name('caPhong');
Route::post('xoa-nguoi-tham-du/{id}', 'QuanLyCuocHopController@deleteNguoiDuHop')->name('xoanguoithamdu');
Route::post('them-du-lieu/{id}', 'QuanLyCuocHopController@themDuLieuCuocHop')->name('themDuLieuCuocHop');
Route::post('luu_ghichepcuochop_qu/{id}', 'QuanLyCuocHopController@luu_ghichepcuochop_qu')->name('luu_ghichepcuochop_qu');
Route::post('luu_ghichepcuochop/{id}', 'QuanLyCuocHopController@luu_ghichepcuochop')->name('luu_ghichepcuochop');
Route::post('luu_ketluan/{id}', 'QuanLyCuocHopController@luu_ketluan')->name('luu_ketluan');
Route::post('tai-lieu-cuoc-hop/{id}', 'QuanLyCuocHopController@upload_tai_lieu')->name('upload_tai_lieu');
Route::post('tai-lieu-tham-khao/{id}', 'QuanLyCuocHopController@luu_ketluan')->name('luu_ketluan');
Route::post('cuoc-hop-lien-quan/{id}', 'QuanLyCuocHopController@cuocHopLienQuan')->name('cuocHopLienQuan');
Route::post('themCuocHop/{id}', 'QuanLyCuocHopController@themCuocHop')->name('themCuocHop');
Route::post('XoaCuocHop/{id}', 'QuanLyCuocHopController@XoaCuocHop')->name('XoaCuocHop');
Route::post('xoaTaiLieu/{id}', 'QuanLyCuocHopController@xoaTaiLieu')->name('xoaTaiLieu');
Route::post('LuuCanBoDuHop/{id}', 'QuanLyCuocHopController@LuuCanBoDuHop')->name('LuuCanBoDuHop');
Route::post('hoten_capnhatthamdu', 'QuanLyCuocHopController@hoten_capnhatthamdu')->name('hoten_capnhatthamdu');
Route::post('luu_danhgiatonghop/{id}', 'QuanLyCuocHopController@luu_danhgiatonghop')->name('luu_danhgiatonghop');
Route::post('luu_noidungchat/{id}', 'QuanLyCuocHopController@luu_noidungchat')->name('luu_noidungchat');
Route::post('nhanxetTaiLieu/{id}', 'QuanLyCuocHopController@nhanxetTaiLieu')->name('nhanxetTaiLieu');
Route::post('danh-gia-y-kien/{id}', 'QuanLyCuocHopController@danhgiaykien')->name('danhgiaykien');
Route::post('tham-du-ngoai/{id}', 'QuanLyCuocHopController@thamDuNgoai')->name('thamDuNgoai');



Route::resource('tham-du-cuoc-hop', 'ThamDuCuocHopController');
Route::resource('thong-ke-tieu-chi-cuoc-hop', 'ThongKeCuocHopController');
