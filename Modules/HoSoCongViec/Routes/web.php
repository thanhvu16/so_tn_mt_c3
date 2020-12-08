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

//Route::prefix('hosocongviec')->group(function() {
//    Route::get('/', 'HoSoCongViecController@index');
//});
Route::resource('ho-so-cong-viec', 'HoSoCongViecController');
Route::match(['get', 'post','put'], 'danh-sach-van-ban/{id}', ['as' =>'ds_van_ban_hs' ,'uses' => 'HoSoCongViecController@ds_van_ban_hs']);
Route::match(['get', 'post','put'], 'danh-sach-tim-kiem-van-ban/{id}', ['as' =>'ds_tim_kiem_van_ban_hs' , 'uses' => 'HoSoCongViecController@ds_tim_kiem_van_ban_hs']);
Route::match(['get', 'post','put'], 'luu-vao-detail', ['as' =>'luu_vao_detail' ,'uses' => 'HoSoCongViecController@luu_vao_detail']);
Route::match(['get', 'post','put'], 'lay-danh-sach-tim-kiem', ['as' =>'lay_danh_sach_tim_kiem' , 'uses' => 'HoSoCongViecController@lay_danh_sach_tim_kiem']);
Route::match(['get', 'post','put'], 'delete/{id}', ['as' =>'delete_tailieuhs' ,'uses' => 'HoSoCongViecController@delete_tai_lieu']);
