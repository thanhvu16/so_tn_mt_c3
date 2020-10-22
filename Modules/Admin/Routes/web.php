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

Route::get('/', 'AdminController@index')->name('home');

Route::resource('nguoi-dung', 'NguoiDungController');
//đơn vị
Route::get('danh-sach-don-vi', 'DonViController@danhsach')->name('danhsachdonvi');
Route::resource('don-vi', 'DonViController')->except('show');
Route::post('don-vi/delete/{id}', array('as' => 'xoadonvi', 'uses' => 'DonViController@destroy'));
//chức vụ
Route::get('danh-sach-chuc-vu', 'ChucVuController@danhsach')->name('danhsachchucvu');
Route::resource('chuc-vu', 'ChucVuController')->except('show');
Route::post('chuc-vu/delete/{id}', array('as' => 'xoachucvu', 'uses' => 'ChucVuController@destroy'));

//Sổ văn bản
Route::get('danh-sach-so-van-ban', 'SoVanBanController@danhsach')->name('danhsachsovanban');
Route::resource('so-van-ban', 'SoVanBanController')->except('show');
Route::post('so-van-ban/delete/{id}', array('as' => 'xoasovanban', 'uses' => 'SoVanBanController@destroy'));
//Loại Văn bản
Route::get('danh-sach-loai-van-ban', 'LoaiVanBanController@danhsach')->name('danhsachloaivanban');
Route::resource('loai-van-ban', 'LoaiVanBanController')->except('show');
Route::post('loai-van-ban/delete/{id}', array('as' => 'xoaloaivanban', 'uses' => 'LoaiVanBanController@destroy'));
//Độ khẩn cấp
Route::get('danh-sach-do-khan-cap', 'DoKhanController@danhsach')->name('danhsachdokhancap');
Route::resource('do-khan-cap', 'DoKhanController')->except('show');
Route::post('do-khan-cap/delete/{id}', array('as' => 'xoadokhan', 'uses' => 'DoKhanController@destroy'));
//Độ bảo mật
Route::get('danh-sach-do-bao-mat', 'DoMatController@danhsach')->name('danhsachdobaomat');
Route::resource('do-bao-mat', 'DoMatController')->except('show');
Route::post('do-bao-mat/delete/{id}', array('as' => 'xoadobaomat', 'uses' => 'DoMatController@destroy'));

