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

Route::get('/', 'AdminController@index')->name('home');

Route::resource('nguoi-dung', 'NguoiDungController');
Route::resource('nhat-ky-truy-cap', 'UserLogsController');
Route::resource('Nhom-don-vi', 'NhomDonViController');
Route::post('Nhom-don-vi/delete/{id}', array('as' => 'xoanhomdonvi', 'uses' => 'NhomDonViController@destroy'));
//đơn vị
Route::get('danh-sach-don-vi', 'DonViController@danhsach')->name('danhsachdonvi');
Route::get('cau-hinh-email-don-vi', 'NguoiDungController@cauHinhEmailDonVi')->name('cau_hinh_emai_don_vi');
Route::post('cau-hinh-email-don-vi', 'NguoiDungController@luuCauHinhEmailDonVi')->name('luu_cau_hinh_email_don_vi');
Route::resource('don-vi', 'DonViController')->except('show');
Route::post('don-vi/delete/{id}', array('as' => 'xoadonvi', 'uses' => 'DonViController@destroy'));
Route::post('cap-nhat-password-email', array('as' => 'guiXuLy', 'uses' => 'NguoiDungController@guiXuLy'));
//chức vụ
Route::get('danh-sach-chuc-vu', 'ChucVuController@danhsach')->name('danhsachchucvu');
Route::resource('chuc-vu', 'ChucVuController')->except('show');
Route::post('chuc-vu/delete/{id}', array('as' => 'xoachucvu', 'uses' => 'ChucVuController@destroy'));

//Sổ văn bản
Route::get('danh-sach-so-van-ban', 'SoVanBanController@danhsach')->name('danhsachsovanban');
Route::resource('so-van-ban', 'SoVanBanController')->except('show');
Route::post('so-van-ban/delete/{id}', array('as' => 'xoasovanban', 'uses' => 'SoVanBanController@destroy'));
//Loại Văn bản
Route::get('danh-sach-loai-van-ban', 'LoaiVanBanController@danhsach')->name('danhsachloaivanban');
Route::resource('loai-van-ban', 'LoaiVanBanController')->except('show');
Route::post('loai-van-ban/delete/{id}', array('as' => 'xoaloaivanban', 'uses' => 'LoaiVanBanController@destroy'));
//Độ khẩn cấp
Route::get('danh-sach-do-khan-cap', 'DoKhanController@danhsach')->name('danhsachdokhancap');
Route::resource('do-khan-cap', 'DoKhanController')->except('show');
Route::post('do-khan-cap/delete/{id}', array('as' => 'xoadokhan', 'uses' => 'DoKhanController@destroy'));
//Độ bảo mật
Route::get('danh-sach-do-bao-mat', 'DoMatController@danhsach')->name('danhsachdobaomat');
Route::resource('do-bao-mat', 'DoMatController')->except('show');
Route::post('do-bao-mat/delete/{id}', array('as' => 'xoadobaomat', 'uses' => 'DoMatController@destroy'));


//
Route::get('get-chuc-vu/{id}', 'NguoiDungController@getChucVu');
Route::get('get-don-vi/{id}', 'NguoiDungController@getDonVi');
Route::resource('vai-tro', 'VaiTroController');
Route::resource('chuc-nang', 'ChucNangController');
Route::resource('tieu-chuan', 'TieuChuanController');
Route::post('tieu-chuan/delete/{id}', array('as' => 'xoaTieuChuan', 'uses' => 'TieuChuanController@destroy'));

Route::resource('ngay-nghi', 'NgayNghiController');

Route::get('sao-luu-du-lieu', 'AdminController@exportDatabase')->name('sao-luu-du-lieu.index');
Route::post('create-backup', 'AdminController@createBackup')->name('backup.create');
Route::get('download-backup/{fileName}', 'AdminController@downloadBackup')->name('backup.download');
Route::post('delete-backup/{fileName}', 'AdminController@deleteBackup')->name('backup.destroy');

Route::get('get-list-phong-ban/{id}', 'DonViController@getListPhongBan');

// switch user
Route::post('switch-user', 'NguoiDungController@switchOtherUser')->name('user.switch_user');
Route::get('stop-switch-user', 'NguoiDungController@stopSwitchUser')->name('user.stop_switch_user');
