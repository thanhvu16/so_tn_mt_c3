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

//Route::prefix('giaymoiden')->group(function() {
//    Route::get('/', 'GiayMoiDenController@index');
//});
//Route::match(['get', 'post'],
//    'giay-moi-den/create',
//    [
//        'as' =>'nhapmoigiaymoi',
//        'uses' => 'GiayMoiDenController@create'
//    ]
//);
Route::resource('giay-moi-den', 'GiayMoiDenController');
Route::post('giay-moi-den/delete/{id}', ['as' =>'giaymoidelete' , 'uses' => 'GiayMoiDenController@destroy']);
Route::match(['get', 'post'], 'layhantruyensangview', ['as' =>'hanview' , 'uses' => 'GiayMoiDenController@layhantruyensangview']);
//Route::get('so-den', array('as' => 'soden', 'uses' => 'VanBanDenController@laysoden'));
Route::get('nhap-giay-moi-di', array('as' => 'nhapGiayMoiDi', 'uses' => 'GiayMoiDenController@nhapGiayMoiDiVanThuSo'));
Route::get('giay-moi-don-vi', array('as' => 'giayMoiDonVi', 'uses' => 'GiayMoiDenController@giayMoiDonVi'));
Route::get('gui-tin-nhan-hoa-GM', 'GiayMoiDenController@guiTinHoanGM')->name('guiTinHoanGM');
Route::post('hoan-hop', ['as' =>'hoanHOP' , 'uses' => 'GiayMoiDenController@hoanHOP']);
