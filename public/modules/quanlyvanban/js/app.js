$("body").on("click", ".btn-remove-file", function () {

    $(this).parents(".remove-multi-file").remove();
});



function noidungvanban(fileName) {
    let htmlForm = `<div class="remove-multi-file">
                     <div class="row">
                       <div class="col-md-8">
                            <label for="vb_ngay_ban_hanh" class="col-form-label">Nội dung</label>
                            <textarea rows="3" class="form-control" name="${fileName}"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="vb_ngay_ban_hanh" class="col-form-label">Hạn giải quyết</label>
                            <div>
                                <input type="date" class="form-control" name="han_giai_quyet[]">
                                        <div class="input-group-btn customize-group-btn">
                                        <span class="btn btn-danger btn-remove-file" type="button">
                                        <i class="fa fa-remove"></i></span>
                                    </div>

                            </div>
                        </div>
                     </div>
                    </div>`;

    $('.layout2').append(htmlForm);
}

$('.vanbantrung').on('blur', function (e) {
    var so_ky_hieu = $('[name=so_ky_hieu]').val();
    var ngay_ban_hanh = $('[name=ngay_ban_hanh]').val();
    e.preventDefault();
    $.ajax({
        url: APP_URL + '/kiem_tra_trich_yeu',
        type: 'POST',
        dataType: 'json',
        data: {
            so_ky_hieu: so_ky_hieu,
            ngay_ban_hanh: ngay_ban_hanh,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
    }).done(function (res) {
        if (res.is_relate) {
            $('#moda-search').html(res.html);
            $('#moda-search').modal('show');
        } else {
            // $('#formCreateDoc').submit();
            // $('#formCreateDoc').submit();
        }

    });
})
function multiUploadFilevanban(fileName) {
    let htmlForm = `<div class="remove-multi-file col-md-12 ">
                         <div class="row">
                            <div class="col-md-3">
                                <label for="sokyhieu" class="col-form-label">Tên tệp tin</label>
                                <input class="form-control" name="txt_file[]" type="text">
                            </div>
                            <div class="col-md-3">
                                <label for="url-file" class="col-form-label">Chọn tệp</label>
                                <div class="form-line input-group control-group">
                                    <input type="file" id="url-file" name="${fileName}" class="form-control">
                                    <div class="input-group-btn customize-group-btn">
                                        <span class="btn btn-danger btn-remove-file" type="button">
                                        <i class="fa fa-remove"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

    $('.increment').append(htmlForm);
}

function themgiaymoi(fileName) {
    let htmlForm = `<div class="remove-multi-file">
                     <div class="row">
                       <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px">
                            <label for="detail-job">Nội dung họp</label>
                            <textarea name="${fileName}" placeholder="nhập nội dung " rows="3"
                                      class="form-control no-resize noi-dung-chi-dao"
                                      aria-required="true"></textarea>
                        </div>
                        <div class="col-md-4" style="margin-top: 10px">
                            <div class="form-group">
                                <label for="">Giờ họp</label>
                                 <div class="input-group">
                                                <input type="time" name="gio_hop_con[]" value="" class="form-control timepicker">

                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top: 10px">
                            <div class="form-group">
                                <label for="">Ngày họp</label>
                                <input type="date" class="form-control"
                                       value=""
                                       name="ngay_hop_con[]" placeholder="Nhập ngày họp">
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top: 10px">
                            <div class="form-group">
                                <label for="">Địa điểm</label>
                                <input type="text" value=""
                                       placeholder="Nhập địa điểm" class="form-control" name="dia_diem_con[]">
                            </div>
                        </div>
                     </div>
                    </div>`;

    $('.layout3').append(htmlForm);
}

$('.check-so-den-vb').on('change', function () {
    let soVanBanId = $(this).val();
    let donViId = $(this).data('don-vi');

    $.ajax({
        url: APP_URL + '/so-den',
        type: 'POST',
        beforeSend: showLoading(),
        data: {
            donViId: donViId,
            soVanBanId: soVanBanId
        },


    })
        .done(function (res) {
            hideLoading();
            if (res.html) {
                var soDen = res.html;
                $('[name=so_den]').val(soDen);
            }
        });

});

$('.tenfile').on('change', function () {
   let sokyhieu = $('[name=so_ky_hieu]').val()
    console.log(sokyhieu);
    $('.ten-tep-tin').val(sokyhieu);

});
function duthaovanban() {
    let htmlForm = `<div class="remove-multi-file col-md-12">
                         <div class="row">
                           <div class="col-md-3">
                                <label for="sokyhieu" class="col-form-label">Tên tệp tin</label>
                                <input class="form-control" name="txt_file[]" type="text">
                            </div>
                            <div class="col-md-3">
                                <label for="url-file" class="col-form-label">File hồ sơ</label>
                                <div class="form-line input-group control-group">
                                    <input type="file" id="url-file" name="file_name[]" class="form-control">
                                    <div class="input-group-btn customize-group-btn">
                                        <span class="btn btn-danger btn-remove-file" type="button">
                                        <i class="fa fa-remove"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

    $('.duthaovb').append(htmlForm);
}function themnoinhan() {
    let htmlForm = `<div class="remove-multi-file col-md-12 mt-2">
                         <div class="row">
                             <div class="col-md-4">
                                        <label for="" class="col-form-label">Tên Đơn vị nhận ngoài</label>
                                        <input type="text"
                                               value=""
                                                name="ten_don_vi_them[]"  class="form-control"
                                               placeholder="Tên đơn vị..." >
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email_them" class="col-form-label">Email</label>
                                        <div class="form-line input-group control-group">
                                        <input type="text"
                                               value=""
                                                name="email_them[]"  class="form-control"
                                               placeholder="Email..." >
                                               <div class="input-group-btn customize-group-btn">
                                        <span class="btn btn-danger btn-remove-file" type="button">
                                        <i class="fa fa-remove"></i></span>
                                    </div>
                                    </div>
                                    </div>
                        </div>
                    </div>`;

    $('.themnoinhan').append(htmlForm);
}





$('.layidnguoiky').on('change', function () {
    let chucVu = $(this).find('option:selected').data('chuc-vu');
    $('[name=chuc_vu]').val(chucVu);
})
$('.lay_van_ban').on('click', function (e) {
    var noi_gui_den = $('[name=noi_gui_den]').val();
    var vb_trich_yeu = $('[name=vb_trich_yeu]').val();
    var vb_so_ky_hieu = $('[name=vb_so_ky_hieu]').val();
    var loai_van_ban = $('[name=loai_van_ban]:checked').val();
    console.log(loai_van_ban);
    e.preventDefault();
    $.ajax({
        beforeSend: showLoading(),
        url: APP_URL + '/lay-danh-sach-tim-kiem',
        type: 'POST',
        dataType: 'json',

        data: {
            vb_trich_yeu: vb_trich_yeu,
            noi_gui_den: noi_gui_den,
            vb_so_ky_hieu: vb_so_ky_hieu,
            loai_van_ban: loai_van_ban,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },

    }).done(function (res) {
        hideLoading();
        let data = res.data.data;
        let type = res.loaiVanBan;
        let dataAppend = '';
        let dataAppend2 = '';

        if (type == 2) {

            dataAppend = data.map((function (item) {
                return `<tr>
                    <td class="text-center">
                        <div class="custom-control custom-radio ">
                            <input type="radio" id="luachon${item.id}" value="${item.id}"
                                   name="id_van_ban"
                                   class="custom-control-input">
                            <label class="custom-control-label" for="luachon${item.id}"></label>
                            <input name="loai_van_ban" value="${type}" class="hidden">
                        </div>
                    </td>
                    <td>${item.trich_yeu}</td>
                </tr>`;
            }));
            $('.data-append').html(dataAppend);
        } else {
            dataAppend2 = data.map((function (item) {
                return  `<tr>
                    <td class="text-center">
                        <div class="custom-control custom-radio ">
                            <input type="radio" id="luachon${item.id}" value="${item.id}"
                                   name="id_van_ban"
                                   class="custom-control-input">
                            <label class="custom-control-label" for="luachon${item.id}"></label>
                        </div>
                        <input name="loai_van_ban" value="${type}" class="hidden">
                    </td>
                    <td>${item.trich_yeu}</td>
        </tr>`;
            }));
            $('.data-append').html(dataAppend2);
        }



    });
})
