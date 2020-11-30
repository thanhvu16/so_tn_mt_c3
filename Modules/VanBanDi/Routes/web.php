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
Route::resource('van-ban-di', 'VanBanDiController');
Route::get('Danh-sach-du-thao', array('as' => 'Danhsachduthao', 'uses' => 'DuThaoVanBanController@Danhsachduthao'));
Route::get('thong-tin-du-thao-cu/{id}', array('as' => 'laythongtinduthaocu', 'uses' => 'DuThaoVanBanController@laythongtinduthaocu'));
Route::get('thong-tin-du-thao-chot/{id}', array('as' => 'thongtinvanban', 'uses' => 'DuThaoVanBanController@thongtinvanban'));
Route::match(['get', 'post'], 'thong-tin-du-thao-chot/{id}', ['as' =>'thongtinvanban' , 'uses' => 'DuThaoVanBanController@thongtinvanban']);
Route::get('du-thao-van-ban/delete/{id}', ['as' =>'duthaodelete' , 'uses' => 'DuThaoVanBanController@destroy']);
Route::get('van-ban-di/delete/{id}', ['as' =>'vanbandidelete' , 'uses' => 'VanBanDiController@destroy']);
Route::match(['get', 'post'], 'delete-file-du-thao/{id}', ['as' =>'delete_file_duthao' , 'uses' => 'DuThaoVanBanController@delete_duthao']);
Route::match(['get', 'post'], 'tao_du_thao_lan_tiep/{id}', ['as' =>'tao_du_thao_lan_tiep' , 'uses' => 'DuThaoVanBanController@tao_du_thao_lan_tiep']);
Route::match(['get', 'post'], 'Van-ban-di', ['as' =>'tao_van_ban_di' , 'uses' => 'DuThaoVanBanController@tao_van_ban_di']);
Route::get('danh-sach-gop-y', array('as' => 'danhsachgopy', 'uses' => 'GopYVanbanDiController@danhsachgopy'));
Route::get('danh-sach-da-gop-y', array('as' => 'danhsachgopyxong', 'uses' => 'GopYVanbanDiController@danhsachgopyxong'));
Route::post('gop-y/{id}', array('as' => 'gopy', 'uses' => 'GopYVanbanDiController@gopy'));
Route::match(['get', 'post'], 'Sua_gop_y', ['as' =>'sugopy' , 'uses' => 'GopYVanbanDiController@sugopy']);
Route::match(['get', 'post','put'], 'them-gop-y-vb-ngoai/{id}', ['as' =>'themgopyvbngoai' , 'uses' => 'GopYVanbanDiController@themgopyvbngoai']);
Route::match(['get', 'post','put'], 'multiple-file-di', ['as' =>'multiple_file_di' , 'uses' => 'VanBanDiController@multiple_file_di']);
