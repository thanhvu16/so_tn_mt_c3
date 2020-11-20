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
Route::match(['get', 'post'],
    'thong-tin-du-thao-chot/{id}',
    [
        'as' =>'thongtinvanban' ,
        'uses' => 'DuThaoVanBanController@thongtinvanban'
    ]
);
