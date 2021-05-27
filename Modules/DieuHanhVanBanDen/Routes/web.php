<?php

Route::resource('phan-loai-van-ban', 'PhanLoaiVanBanController');

Route::get('van-ban-den-chi-tiet/{id}', 'DieuHanhVanBanDenController@show')->name('van_ban_den_chi_tiet.show');

Route::get('van-ban-da-phan-loai', 'PhanLoaiVanBanController@daPhanLoai')->name('phan-loai-van-ban.da_phan_loai');

Route::get('giay-moi-da-phan-loai', 'PhanLoaiVanBanController@daPhanLoai')->name('phan-loai-giay-moi.da_phan_loai');

Route::get('giay-moi-lanh-dao-xu-ly', 'VanBanLanhDaoXuLyController@index')->name('giayMoiLanhDaoXuLy');

Route::resource('van-ban-lanh-dao-xu-ly', 'VanBanLanhDaoXuLyController');


Route::get('list-don-vi-phoi-hop/{id}', 'VanBanLanhDaoXuLyController@getListDonVi');
Route::get('get-list-lanh-dao-xem-de-biet/{id}', 'DieuHanhVanBanDenController@getListLanhDao');

Route::post('save-don-vi-chu-tri', 'VanBanLanhDaoXuLyController@saveDonViChuTri')->name('van-ban-lanh-dao.save_don_vi_chu_tri');

Route::resource('van-ban-tra-lai', 'VanBanTraLaiController');
Route::get('van-ban-tra-lai-cho-duyet', 'VanBanTraLaiController@choDuyet')->name('van_ban_tra_lai.cho_duyet');

Route::get('giay-moi-tra-lai-cho-duyet', 'VanBanTraLaiController@choDuyet')->name('giay_moi_tra_lai.cho_duyet');

Route::resource('van-ban-den-don-vi', 'VanBanDenDonViController');

Route::get('giay-moi-den-don-vi-vb', 'VanBanDenDonViController@index')->name('giay_moi_den_don_vi_index');


Route::get('list-can-bo-phoi-hop/{id}', 'VanBanDenDonViController@getCanBoPhoiHop');

Route::get('van-ban-da-chi-dao', 'VanBanDenDonViController@vanBanDaChiDao')->name('van_ban_don_vi.da_chi_dao');

Route::get('giay-moi-da-chi-dao', 'VanBanDenDonViController@vanBanDaChiDao')->name('giay_moi_don_vi.da_chi_dao');

Route::get('gia-han-giay-moi', 'GiaHanVanBanController@index')->name('giaHanGiayMoi');

Route::resource('gia-han-van-ban', 'GiaHanVanBanController');

Route::post('duyet-gia-han-van-ban', 'GiaHanVanBanController@duyetGiaHan');

Route::resource('giai-quyet-van-ban', 'GiaiQuyetVanBanController');

Route::get('van-ban-den-hoan-thanh-cho-duyet', 'VanBanDenHoanThanhController@choDuyet')->name('van-ban-den-hoan-thanh.cho-duyet');

Route::get('giay-moi-den-hoan-thanh-cho-duyet', 'VanBanDenHoanThanhController@choDuyet')->name('giay-moi-den-hoan-thanh.cho-duyet');

Route::get('duyet-van-ban-cap-duoi-trinh', 'VanBanDenHoanThanhController@duyetVanBanCapDuoiTrinh')->name('duyet-van-ban-cap-duoi-trinh');

Route::get('duyet-giay-moi-cap-duoi-trinh', 'VanBanDenHoanThanhController@duyetVanBanCapDuoiTrinh')->name('duyet-giay-moi-cap-duoi-trinh');

Route::post('duyet-van-ban', 'VanBanDenHoanThanhController@duyetVanBan');

Route::get('van-ban-hoan-thanh', 'VanBanDenHoanThanhController@index')->name('van-ban-den-hoan-thanh.index');

Route::get('giay-moi-hoan-thanh', 'VanBanDenHoanThanhController@index')->name('giay-moi-den-hoan-thanh.index');

Route::get('van-ban-den-chuyen-vien-phoi-hop', 'VanBanDenPhoiHopController@chuyenVienPhoiHop')->name('van_ban_den_chuyen_vien.index');

Route::get('giay-moi-den-chuyen-vien-phoi-hop', 'VanBanDenPhoiHopController@chuyenVienPhoiHop')->name('giay_moi_den_chuyen_vien.index');

Route::post('phoi-hop-giai-quyet', 'VanBanDenPhoiHopController@phoiHopGiaiQuyet')->name('phoi_hop_giai_quyet.store');
Route::post('phoi-hop-giai-quyet/update/{id}', 'VanBanDenPhoiHopController@update')->name('phoi_hop_giai_quyet.update');

Route::get('van-ban-den-chuyen-vien-phoi-hop-da-xu-ly', 'VanBanDenPhoiHopController@chuyenVienPhoiHop')->name('van_ban_den_chuyen_vien.da_xu_ly');

Route::get('giay-moi-den-chuyen-vien-phoi-hop-da-xu-ly', 'VanBanDenPhoiHopController@chuyenVienPhoiHop')->name('giay_moi_den_chuyen_vien.da_xu_ly');

Route::get('van-ban-den-phoi-hop', 'VanBanDenPhoiHopController@index')->name('van-ban-den-phoi-hop.index');
Route::get('giay-moi-den-phoi-hop', 'VanBanDenPhoiHopController@index')->name('giay-moi-den-phoi-hop.index');
Route::post('van-ban-den-phoi-hop/store', 'VanBanDenPhoiHopController@store')->name('van-ban-den-phoi-hop.store');

Route::get('van-ban-den-phoi-hop-dang-xu-ly', 'VanBanDenPhoiHopController@index')->name('van-ban-den-phoi-hop.dang-xu-ly');

Route::get('giay-moi-den-phoi-hop-dang-xu-ly', 'VanBanDenPhoiHopController@index')->name('giay-moi-den-phoi-hop.dang-xu-ly');

Route::get('van-ban-den-phoi-hop-da-xu-ly', 'VanBanDenPhoiHopController@donViPhoiHopDaXuLy')->name('van-ban-den-phoi-hop.da-xu-ly');

Route::get('giay-moi-den-phoi-hop-da-xu-ly', 'VanBanDenPhoiHopController@donViPhoiHopDaXuLy')->name('giay-moi-den-phoi-hop.da-xu-ly');

Route::get('van-ban-den-dang-xu-ly', 'VanBanDenDonViController@dangXuLy')->name('van-ban-den-don-vi.dang_xu_ly');

Route::get('giay-moi-den-dang-xu-ly', 'VanBanDenDonViController@dangXuLy')->name('giay-moi-den-don-vi.dang_xu_ly');

Route::get('giay-moi-xem-de-biet', 'DieuHanhVanBanDenController@vanBanXemDeBiet')->name('giay-moi-den-don-vi.xem_de_biet');

Route::get('van-ban-xem-de-biet', 'DieuHanhVanBanDenController@vanBanXemDeBiet')->name('van-ban-den-don-vi.xem_de_biet');
Route::get('van-ban-trong', 'DieuHanhVanBanDenController@vanBanQuanTrong')->name('van-ban-den-don-vi.quan_trong');

Route::get('giay-moi-trong', 'DieuHanhVanBanDenController@vanBanQuanTrong')->name('giay-moi-den-don-vi.quan_trong');

Route::post('remove-file/{id}', 'DieuHanhVanBanDenController@removeFile');

Route::resource('phan-loai-van-ban-phoi-hop', 'PhanLoaiVanBanPhoiHopController');
Route::get('van-ban-phoi-hop-da-phan-loai', 'PhanLoaiVanBanPhoiHopController@index')->name('van-ban-phoi-hop.da_phan_loai');
