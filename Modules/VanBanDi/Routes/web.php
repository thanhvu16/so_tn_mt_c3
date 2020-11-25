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

//Route::prefix('vanbandi')->group(function() {
//    Route::get('/', 'VanBanDiController@index');
//});
Route::resource('du-thao-van-ban', 'DuThaoVanBanController');
Route::get('Danh-sach-du-thao', array('as' => 'Danhsachduthao', 'uses' => 'DuThaoVanBanController@Danhsachduthao'));
Route::get('thong-tin-du-thao-cu/{id}', array('as' => 'laythongtinduthaocu', 'uses' => 'DuThaoVanBanController@laythongtinduthaocu'));
Route::get('thong-tin-du-thao-chot/{id}', array('as' => 'thongtinvanban', 'uses' => 'DuThaoVanBanController@thongtinvanban'));
Route::match(['get', 'post'], 'thong-tin-du-thao-chot/{id}', ['as' =>'thongtinvanban' , 'uses' => 'DuThaoVanBanController@thongtinvanban']);
Route::get('du-thao-van-ban/delete/{id}', ['as' =>'duthaodelete' , 'uses' => 'DuThaoVanBanController@destroy']);
Route::match(['get', 'post'], 'delete-file-du-thao/{id}', ['as' =>'delete_file_duthao' , 'uses' => 'DuThaoVanBanController@delete_duthao']);
Route::match(['get', 'post'], 'tao_du_thao_lan_tiep/{id}', ['as' =>'tao_du_thao_lan_tiep' , 'uses' => 'DuThaoVanBanController@tao_du_thao_lan_tiep']);
Route::match(['get', 'post'], 'Van-ban-di', ['as' =>'tao_van_ban_di' , 'uses' => 'DuThaoVanBanController@tao_van_ban_di']);
