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

//Route::prefix('congviecdonvi')->group(function() {
//    Route::get('/', 'CongViecDonViController@index');
//});
Route::resource('cong-viec-don-vi', 'CongViecDonViController');
Route::get('data-don-vi-chu-tri', 'CongViecDonViController@getDataDonVi');
Route::get('get-don-vi-phoi-hop/{id}', 'CongViecDonViController@getDonViPhoiHop');
