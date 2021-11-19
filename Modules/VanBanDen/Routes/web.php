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


Route::resource('van-ban-den', 'VanBanDenController');
Route::resource('don-thu-khieu-lai', 'DonThuKhieuLaiController');
Route::resource('in-so-van-ban-den', 'ThongkeVanBanDenController');
Route::resource('don-vi-nhan-van-ban-den', 'DonViNhanVanBanDenController');
Route::resource('thong-ke-lanh-dao', 'ThongKeCapDuoiController');
Route::resource('thong-ke-cap-duoi-lanh-dao', 'ThongkeCapDuoiPhoChuTichController');
Route::post('so-den', array('as' => 'soden', 'uses' => 'VanBanDenController@laysoden'));
Route::post('upload-multiple', array('as' => 'multiple_file', 'uses' => 'VanBanDenController@multiple_file'));
Route::post('luuGiayMoiMail', array('as' => 'luuGiayMoiMail', 'uses' => 'VanBanDenController@luuGiayMoiMail'));
Route::post('delete-van-ban-den', array('as' => 'delete_vb_den', 'uses' => 'VanBanDenController@delete_vb_den'));
Route::get('chi-tiet-van-ban-den/{id}', array('as' => 'chi_tiet_van_ban_den', 'uses' => 'VanBanDenController@chi_tiet_van_ban_den'));

Route::get('don-vi-nhan-van-ban-den/thong-tin-van-ban/{id}', array('as' => 'thongtinvb', 'uses' => 'DonViNhanVanBanDenController@thongtinvb'));
Route::get('so-nhan-van-ban-den/thong-tin-van-ban/{id}', array('as' => 'thongtinvbsonhan', 'uses' => 'DonViNhanVanBanDenController@thongtinvbsonhan'));
Route::get('don-vi-nhan-van-ban-den/thong-tin-van-ban-huyen/{id}', array('as' => 'thongtinvbhuyen', 'uses' => 'DonViNhanVanBanDenController@thongtinvbhuyen'));
Route::get('van-ban-den-so', array('as' => 'vanBanDonViGuiSo', 'uses' => 'DonViNhanVanBanDenController@vanBanDonViGuiSo'));
Route::get('van-ban-den-so-vao-so/{id}', array('as' => 'vaoSoVanBanDonViGuiSo', 'uses' => 'DonViNhanVanBanDenController@vaoSoVanBanDonViGuiSo'));

//
Route::post('check-giay-moi', 'VanBanDenController@checkGiayMoi')->name('checkGiayMoi');


Route::get('chi-tiet-van-ban-den-don-vi/{id}', array('as' => 'chi_tiet_van_ban_den_don_vi', 'uses' => 'DonViNhanVanBanDenController@chi_tiet_van_ban_den_don_vi'));
Route::match(['get', 'post','put'], 'ds-van-ban-den-tu-mail', ['as' =>'dsvanbandentumail','uses' => 'VanBanDenController@dsvanbandentumail']);
Route::match(['get', 'post','put'], 'van-ban-den-tu-mail', ['as' =>'vanbandentumail', 'uses' => 'VanBanDenController@taovbdentumail']);
Route::match(['get', 'post','put'], 'kiem_tra_trich_yeu', ['as' =>'ktratrichyeu', 'uses' => 'VanBanDenController@kiemTraTrichYeu']);
Route::post('ds-van-ban-den-tu-mail/delete-email/{id}','VanBanDenController@deleteEmail')->name('delete-email');
Route::post('ds-van-ban-den-tu-mail/luu-van-ban-tu-mail','VanBanDenController@luuvanbantumail')->name('luuvanbantumail');
Route::post('van-ban-den/vao-so-van-ban','DonViNhanVanBanDenController@vaosovanbandvnhan')->name('vaosovanbandvnhan');
Route::post('van-ban-den/vao-so-van-ban-huyen','DonViNhanVanBanDenController@vaosovanbanhuyen')->name('vaosovanbanhuyen');
Route::post('han-van-ban','VanBanDenController@layhantruyensangview')->name('layhantruyensangview');
//Route::get('xoa-van-ban-den/{id}','VanBanDenController@xoaFileDen')->name('xoaFileDen');
Route::match(['get', 'post','put'], 'xoa-file-van-ban/{id}', ['as' =>'xoaFileDen', 'uses' => 'VanBanDenController@xoaFileDen']);

Route::get('hanXuLYC', array('as' => 'hanXuLYC', 'uses' => 'VanBanDenController@hanXuLYC'));
Route::get('thong-ke-van-ban-so', array('as' => 'thongkevbso', 'uses' => 'ThongkeVanBanDenController@thongkevbso'));
Route::get('thong-ke-van-ban-chi-cuc', array('as' => 'thongkevbchicuc', 'uses' => 'ThongKeVanBanChiCucController@index'));
Route::get('thong-ke-van-ban-phong', array('as' => 'thongkevbphong', 'uses' => 'ThongKeVanBanPhongController@index'));

//chi tiết thông kê
Route::get('chi-tiet-tong-van-ban-so/{id}', array('as' => 'chiTietTongVanBanSo', 'uses' => 'ThongkeVanBanDenController@chiTietTongVanBanSo'));
Route::get('chi-tiet-da-giai-quyet-trong-han-so/{id}', array('as' => 'chiTietDaGiaiQuyetTrongHanVanBanSo', 'uses' => 'ThongkeVanBanDenController@chiTietDaGiaiQuyetTrongHanVanBanSo'));
Route::get('chi-tiet-da-giai-quyet-qua-han-so/{id}', array('as' => 'chiTietDaGiaiQuyetQuaHanVanBanSo', 'uses' => 'ThongkeVanBanDenController@chiTietDaGiaiQuyetQuaHanVanBanSo'));

Route::get('chi-tiet-giay-moi/{id}', array('as' => 'chiTietgiayMoi', 'uses' => 'ThongkeVanBanDenController@chiTietgiayMoi'));
Route::get('chi-tiet-chua-giai-quyet-trong-han-so/{id}', array('as' => 'chiTietChuaGiaiQuyetTrongHanVanBanSo', 'uses' => 'ThongkeVanBanDenController@chiTietChuaGiaiQuyetTrongHanVanBanSo'));
Route::get('chi-tiet-chua-giai-quyet-qua-han-so/{id}', array('as' => 'chiTietChuaGiaiQuyetQuaHanVanBanSo', 'uses' => 'ThongkeVanBanDenController@chiTietChuaGiaiQuyetQuaHanVanBanSo'));
Route::get('tong-so-van-ban', array('as' => 'tongSoVanBanDen', 'uses' => 'ThongkeVanBanDenController@tongSoVanBanDen'));

//thống kê chi tiết chi cuch
Route::get('chi-tiet-da-giai-quyet-trong-han-chi-cuc/{id}', array('as' => 'chiTietDaGiaiQuyetTrongHanVanBanChiCuc', 'uses' => 'ThongKeVanBanChiCucController@chiTietDaGiaiQuyetTrongHanVanBanChiCuc'));
Route::get('chi-tiet-da-giai-quyet-qua-han-chi-cuc/{id}', array('as' => 'chiTietDaGiaiQuyetQuaHanVanBanChiCuc', 'uses' => 'ThongKeVanBanChiCucController@chiTietDaGiaiQuyetQuaHanVanBanChiCuc'));

Route::get('chi-tiet-chua-giai-quyet-trong-han-chi-cuc/{id}', array('as' => 'chiTietChuaGiaiQuyetTrongHanVanBanChiCuc', 'uses' => 'ThongKeVanBanChiCucController@chiTietChuaGiaiQuyetTrongHanVanBanChiCuc'));
Route::get('chi-tiet-chua-giai-quyet-qua-han-chi-cuc/{id}', array('as' => 'chiTietChuaGiaiQuyetQuaHanVanBanChiCuc', 'uses' => 'ThongKeVanBanChiCucController@chiTietChuaGiaiQuyetQuaHanVanBanChiCuc'));


//thống kê chi tiết phòng
Route::get('chi-tiet-da-giai-quyet-trong-han-phong/{id}', array('as' => 'chiTietDaGiaiQuyetTrongHanVanBanphong', 'uses' => 'ThongKeVanBanPhongController@chiTietDaGiaiQuyetTrongHanVanBanphong'));
Route::get('chi-tiet-da-giai-quyet-qua-han-phong/{id}', array('as' => 'chiTietDaGiaiQuyetQuaHanVanBanphong', 'uses' => 'ThongKeVanBanPhongController@chiTietDaGiaiQuyetQuaHanVanBanphong'));

Route::get('chi-tiet-chua-giai-quyet-trong-han-phong/{id}', array('as' => 'chiTietChuaGiaiQuyetTrongHanVanBanphong', 'uses' => 'ThongKeVanBanPhongController@chiTietChuaGiaiQuyetTrongHanVanBanphong'));
Route::get('chi-tiet-chua-giai-quyet-qua-han-phong/{id}', array('as' => 'chiTietChuaGiaiQuyetQuaHanVanBanphong', 'uses' => 'ThongKeVanBanPhongController@chiTietChuaGiaiQuyetQuaHanVanBanphong'));

//chi tiet van ban
Route::get('chi-tiet-thong-ke-van-ban-trong-phong/{id}', 'ThongKeVanBanPhongController@show')->name('thong_ke_chi_tiet_van_ban');



Route::get('upload-tai-lieu-tham-khao', array('as' => 'taiLieuThamKhao', 'uses' => 'VanBanDenController@taiLieuThamKhao'));
Route::get('van-ban-don-vi', array('as' => 'vanBanDonVi', 'uses' => 'VanBanDenController@vanBanDonVi'));
Route::post('post-upload-tai-lieu-tham-khao', array('as' => 'postTaiLieuThamKhao', 'uses' => 'VanBanDenController@postTaiLieuThamKhao'));



Route::post('han-xu-ly-van-ban', array('as' => 'hanXuLyvb', 'uses' => 'VanBanDenController@hanXuLyvb'));

Route::post('han-xu-ly-giay-moi', array('as' => 'hanXuLygb', 'uses' => 'VanBanDenController@hanXuLygb'));

Route::post('thong-tin-dang-nhap', array('as' => 'thongTindn', 'uses' => 'VanBanDenController@thongTindn'));






