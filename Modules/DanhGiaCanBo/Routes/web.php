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

//Route::prefix('danhgiacanbo')->group(function() {
//    Route::get('/', 'DanhGiaCanBoController@index');
//});

Route::resource('danh-gia-can-bo', 'DanhGiaCanBoController');
Route::resource('danh-gia-can-bo-c2', 'LanhDaoController');
Route::resource('danh-gia-can-bo-chi_cuc', 'ChiCucDanhGiaController');
Route::get('cap-tren-danh-gia-can-bo-c2','LanhDaoController@captrendanhgia')->name('captrendanhgiac2');
Route::match(['post','put'],'danh-gia-cap-tren-c2', ['as' =>'danhgiacaptrenc2' , 'uses' => 'LanhDaoController@danhgiacaptren']);
Route::match(['post','put'],'chuyen-noi-vu', ['as' =>'chuyennoivu' , 'uses' => 'DanhGiaCanBoController@chuyennoivu']);
Route::get('chi-tiet-ca-nhan/{id}','LanhDaoController@chitietcanhan')->name('chitietcanhan');
Route::get('thong-ke-thang-phong','DanhGiaCanBoController@thongkephongthang')->name('thongkephongthang');
Route::get('cap-tren-danh-gia','DanhGiaCanBoController@captrendanhgia')->name('captrendanhgia');
Route::match(['post','put'],'danh-gia-cap-tren', ['as' =>'danhgiacaptren' , 'uses' => 'DanhGiaCanBoController@danhgiacaptren']);
