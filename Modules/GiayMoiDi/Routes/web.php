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

//Route::prefix('giaymoidi')->group(function() {
//    Route::get('/', 'GiayMoiDiController@index');
//});
Route::resource('giay-moi-di', 'GiayMoiDiController');
Route::post('giay-moi-di/delete/{id}', ['as' =>'giaymoididelete' , 'uses' => 'GiayMoiDiController@destroy']);
Route::get('giay-moi-di-co-so', ['as' =>'dacoso' , 'uses' => 'GiayMoiDiController@giay_moi_di_co_so']);
