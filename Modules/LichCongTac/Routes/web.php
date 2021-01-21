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

Route::prefix('lich-cong-tac')->group(function() {
    Route::get('/', 'LichCongTacController@index')->name('lich-cong-tac.index');
    Route::post('store', 'LichCongTacController@store')->name('lich-cong-tac.store');
    Route::get('edit/{id}', 'LichCongTacController@edit');
    Route::post('update/{id}', 'LichCongTacController@update')->name('lich-cong-tac.update');
});

Route::get('soan-bao-cao', 'SoanBaoCaoController@create')->name('soan_bao_cao.create');
Route::post('soan-bao-cao/store', 'SoanBaoCaoController@store')->name('soan-bao-cao.store');

Route::resource('tham-du-cuoc-hop', 'ThamDuCuocHopController');
