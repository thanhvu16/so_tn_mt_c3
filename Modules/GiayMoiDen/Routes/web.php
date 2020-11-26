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
//Route::get('so-den', array('as' => 'soden', 'uses' => 'VanBanDenController@laysoden'));
