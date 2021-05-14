

$('.nguoi-du-hop').on('click', function (e) {
    var id =$(this).data('id')
    var result = confirm("Bạn muốn xóa dữ liệu này?");
    if (result) {
        e.preventDefault();
        $.ajax({
            url: APP_URL + '/xoa-nguoi-tham-du/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function (res) {
            hideLoading();
            if (res.is_relate == true) {
                $('.remove-'+ id).remove();
                toastr['success'](res.message);
            }
        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    }

})
$('.luu_ykienchinhthuc').on('click', function (e) {

    var ykienchinhthuc = $('[name=ykienchinhthuc]').val();
    var id = $(this).data('id');

        e.preventDefault();
        $.ajax({
            url: APP_URL + '/them-du-lieu/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                ykienchinhthuc: ykienchinhthuc,
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function (res) {
            hideLoading();
            $('.luu_ykienchinhthuc').addClass('hidden');
            toastr['success'](res.message);

        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });


})
$('.luu_ghichepcuochop_qu').on('click', function (e) {

    var noidung_ghichepcuochop_qu = $('[name=noidung_ghichepcuochop_qu]').val();
    var id = $(this).data('id');

        e.preventDefault();
        $.ajax({
            url: APP_URL + '/luu_ghichepcuochop_qu/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                noidung_ghichepcuochop_qu: noidung_ghichepcuochop_qu,
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function (res) {
            hideLoading();
            $('.luu_ghichepcuochop_qu').addClass('hidden');
            toastr['success'](res.message);

        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });


})
$('.luu_ghichepcuochop').on('click', function (e) {

    var noidung_ghichepcuochop = $('[name=noidung_ghichepcuochop]').val();
    var id = $(this).data('id');

        e.preventDefault();
        $.ajax({
            url: APP_URL + '/luu_ghichepcuochop/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                noidung_ghichepcuochop: noidung_ghichepcuochop,
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function (res) {
            hideLoading();

            $('.luu_ghichepcuochop').addClass('hidden');
            toastr['success'](res.message);

        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });


})
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

$('.tim-kiem-cuoc-hop').on('click', function (e) {
    var ngay_bat_dau = $('[name=ngaybatdau]').val();
    var ngay_ket_thuc = $('[name=ngayketthuc]').val();
    var nguoi_chu_tri = $('[name=lanhdao_chutri]').val();
    var ten_lich_hop = $('[name=ten_cuochop]').val();
    var nam = $('[name=nam_chutri]').val();
    var id = $(this).data('id');


        e.preventDefault();
        $.ajax({
            url: APP_URL + '/cuoc-hop-lien-quan/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                ngay_bat_dau:ngay_bat_dau,
                ngay_ket_thuc:ngay_ket_thuc,
                nguoi_chu_tri:nguoi_chu_tri,
                ten_lich_hop:ten_lich_hop,
                nam:nam
            },
        }).done(function (res) {
            let data = res.lichCongTac;
            let dataAppend2 = '';
            hideLoading();
            dataAppend2 = data.map((function (item) {
                var ngaythang = new Date("2015-03-25");
                return  `<tr class="id-${item.id}">
                    <td class="text-center" style="vertical-align: middle;">
                           <input type="text" name="lich_hop_id" class="hidden " value="${res.id_lich_hop}">
                        <button type="button" name="chon" value="${item.id}"  data-chon="${item.id}" id="luachon${item.id}" class="btn btn-primary btn-sm chonLichHop">Chọn</button>
                    </td>
                    <td>${item.noi_dung}</td>

        </tr>`;
            }));
            $('.abcde').html(dataAppend2);

        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });


})

$('.XoaCuocHop').on('click', function (e) {
    var id = $(this).data('id');

        e.preventDefault();
    var result = confirm("Bạn muốn xóa cuộc họp này?");
    if(result)
    {
        $.ajax({
            url: APP_URL + '/XoaCuocHop/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                id:id,
            },
        }).done(function (res) {
            hideLoading();
            $('.lien-quan-' + id).remove();
            toastr['success'](res.message);

        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    }

})
$('.Xoatailieu').on('click', function (e) {
    var id = $(this).data('id');
        e.preventDefault();
    var result = confirm("Bạn muốn xóa tài liệu này?");
    if(result)
    {
        $.ajax({
            url: APP_URL + '/xoaTaiLieu/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                id:id,
            },
        }).done(function (res) {
            hideLoading();
            $('.tai-lieu-' + id).remove();
            toastr['success'](res.message);
        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    }

})
$('.luu_noidungchat').on('click', function (e) {
    var id = $(this).data('id');
    var noidungchat = $('[name=noidungchat]').val();
        e.preventDefault();
        $.ajax({
            url: APP_URL + '/luu_noidungchat/'+ id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                id:id,
                noidungchat:noidungchat,
            },
        }).done(function (res) {
            hideLoading();
            toastr['success'](res.message);
            location.reload();
            // $('.tai-lieu-' + id).remove();
        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });

})



$('.reset-cuoc-hop').on('click', function (e) {
    location.reload();
})

$('.reset-cuoc-hop').on('click', function (e) {
    location.reload();
})


$("body").on("click",'.chonLichHop', function () {
    var id = $(this).data('chon');
    var lich_hop_id = $('[name=lich_hop_id]').val();

    var result = confirm("Bạn muốn Thêm cuộc họp này?");
    if (result) {
        $.ajax({
            url: APP_URL + '/themCuocHop/' + id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                lich_hop_id: lich_hop_id,
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function (res) {
            hideLoading();
            if (res.is_relate == true) {
                $('.id-' + id).remove();
                toastr['success'](res.message);
            }else {
                toastr['error'](res.message);
            }


        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    }

});

$("body").on("click",'.luu_danhgiatonghop', function () {
    var id = $(this).data('lich');
    var danh_gia = $('[name=danhgiatonghop]').val();
    var result = confirm("Bạn muốn đánh giá cuộc họp này?");
    if (result) {
        $.ajax({
            url: APP_URL + '/luu_danhgiatonghop/' + id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                danh_gia: danh_gia,
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function (res) {
            hideLoading();
            if (res.is_relate == true) {
                $('.ket-luan3' ).remove();
                toastr['success'](res.message);
            }
        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    }

});

function danhGiaTaiLieu(e,p,don_vi,g) {
    var id_donVi = p;
    var danh_gia = $('[name='+e+']:checked').val();
    var nhan_xet = $('[name='+g+']').val();
    var lich = don_vi;
    $.ajax({
        url: APP_URL + '/nhanxetTaiLieu/' + lich,
        type: 'POST',
        beforeSend: showLoading(),
        dataType: 'json',
        data: {
            danh_gia: danh_gia,
            nhan_xet: nhan_xet,
            id_donVi: id_donVi,
            lich_cong_tac: lich,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
    }).done(function (res) {
        hideLoading();
        if (res.is_relate == true) {
            $('.button-danh-gia-'+ p ).remove();
            toastr['success'](res.message);

        }
    })
        .fail(function (error) {
            toastr['error'](error.message, 'Thông báo hệ thống');
        });


}
function danhGiaChatLuongGopY(e,p,g,n) {
    var ca_nhan = p;
    var danh_gia = $('[name='+e+']:checked').val();
    var nhan_xet = $('[name='+n+']').val();
    var lich_ct = g;
    $.ajax({
        url: APP_URL + '/danh-gia-y-kien/' + lich_ct,
        type: 'POST',
        beforeSend: showLoading(),
        dataType: 'json',
        data: {
            danh_gia: danh_gia,
            nhan_xet: nhan_xet,
            ca_nhan: ca_nhan,
            lich_ct: lich_ct,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
    }).done(function (res) {
        hideLoading();
        if (res.is_relate == true) {
            $('#goy-' + ca_nhan).remove();
            toastr['success'](res.message);
        }
    })
        .fail(function (error) {
            toastr['error'](error.message, 'Thông báo hệ thống');
        });


}
function thanhPhanThamDuNgoai(e,p) {
    var noidung = $('[name='+e+']').val();
    var lich_ct = p;
    $.ajax({
        url: APP_URL + '/tham-du-ngoai/' + lich_ct,
        type: 'POST',
        beforeSend: showLoading(),
        dataType: 'json',
        data: {
            noidung: noidung,
            lich_ct: lich_ct,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
    }).done(function (res) {
        hideLoading();
        if (res.is_relate == true) {
            $('.tham-du-ngoai').remove();
            toastr['success'](res.message);
        }
    })
        .fail(function (error) {
            toastr['error'](error.message, 'Thông báo hệ thống');
        });


}

function timKiemNguoiDung(id)
{
    var nhom_don_vi = $('[name=capnhatnhomcaobo]').val();
    var don_vi = $('[name=phongban_capnhatthamdu]').val();
    var chuc_vu = $('[name=chucvu_capnhatthamdu]').val();
    var ho_ten = $('[name=hoten_capnhatthamdu]').val();
    $.ajax({
        url: APP_URL + '/hoten_capnhatthamdu',
        type: 'POST',
        beforeSend: showLoading(),
        dataType: 'json',
        data: {
            nhom_don_vi: nhom_don_vi,
            don_vi: don_vi,
            chuc_vu: chuc_vu,
            ho_ten: ho_ten,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
    }).done(function (res) {
        hideLoading();
        if (res.is_relate == true) {
            let data = res.users;
            console.log(data);
            let dataAppend2 = '';
            hideLoading();
            dataAppend2 = data.map((function (item) {
                return  `<tr class="nguoi-${item.id}">
                    <td class="text-center" style="vertical-align: middle;">
                    <input type="text" name="lich_hop_id" class="hidden " value="${id}">
                        <button type="button" name="chon" value="${item.id}"  data-chon-nguoi="${item.id}" id="luachon${item.id}" class="btn btn-primary btn-sm nguoi-tham-du">Chọn</button>
                    </td>
                    <td>${item.ho_ten}</td>
        </tr>`;
            }));
            $('.dulieu_capnhatthamdu').html(dataAppend2);
        }
    })
        .fail(function (error) {
            toastr['error'](error.message, 'Thông báo hệ thống');
        });

}

$("body").on("click",'.nguoi-tham-du', function () {
    var id = $(this).data('chon-nguoi');
    var lich_hop_id = $('[name=lich_hop_id]').val();

    var result = confirm("Bạn muốn Thêm người tham dự này?");
    if (result) {
        $.ajax({
            url: APP_URL + '/LuuCanBoDuHop/' + lich_hop_id,
            type: 'POST',
            beforeSend: showLoading(),
            dataType: 'json',
            data: {
                lich_hop_id: lich_hop_id,
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function (res) {
            hideLoading();
            if (res.is_relate == true) {
                $('.nguoi-' + id).remove();
                toastr['success'](res.message);
            }
        })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    }

});
