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

