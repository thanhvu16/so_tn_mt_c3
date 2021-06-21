function showModalpass() {
    $("#myModal_pass").modal('show');
}
function readURL(input,name) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(name)
                .attr('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0]);
    }
}

//iCheck for checkbox and radio inputs
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
});
//Red color scheme for iCheck
$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
    radioClass: 'iradio_minimal-red'
});
//Flat red color scheme for iCheck
$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
});


$('.go-back').on('click', function () {
    window.history.back();
});

$('body').on('click', '.seen-new-window', function () {
    let url = $(this).attr('href');

    return window.open(url, 'popup', 'width=683,height=672, margin:0 auto');
});

$('.btn-remove-item').on('click', function () {

    return confirm('Bạn muốn xóa dữ liệu này?');
});
$('.btn-remove-item-duyet').on('click', function () {

    return confirm('Bạn có chắc chắc muốn duyệt?');
});

$('.select2').select2({
    width: '100%'
})


function showLoading() {
    $('body').loadingModal({
        position: 'auto',
        text: '',
        color: '#fff',
        opacity: '0.7',
        backgroundColor: 'rgb(0,0,0)',
        // animation: 'fadingCircle'
        animation: 'wave'
    });
}

function hideLoading() {
    $('body').loadingModal('destroy');

}

$(".timepicker").timepicker({
    showInputs: false
});

$('.time-picker-24h').timepicker({
    showMeridian: !1,
    showInputs: false
});


$("input[type=date]").on("change", function () {
    if (this.value) {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
                // .format( this.getAttribute("data-date-format") )
                .format("DD/MM/YYYY")
        )
    } else {

        $(this).attr('data-date', 'dd/mm/yyyy');
    }

}).trigger("change");

// upload file giai quyet van ban
function multiUploadFile(fileName) {
    let htmlForm = `<div class="remove-multi-file">
                     <div class="row">
                        <div class="form-group col-md-4">
                            <label for="ten_file" class="col-form-label">Tên tệp tin</label>
                            <input type="text" class="form-control" name="txt_file[]" value=""
                             placeholder="Nhập tên file..." required>
                        </div>
                        <div class="form-group col-md-8">
                            <div class="">
                                <label for="url-file" class="col-form-label">Chọn tệp</label>
                                <div class="form-line input-group control-group">
                                    <input type="file" name="${fileName}" class="form-control">
                                    <div class="input-group-btn customize-group-btn">
                                        <span class="btn btn-danger btn-remove-file" type="button">
                                        <i class="fa fa-remove"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                    </div>`;

    $('.increment').append(htmlForm);
}

$("body").on("click", ".btn-remove-file", function () {

    $(this).parents(".remove-multi-file").remove();
});

$('.datepicker').datepicker({
    todayHighlight: true,
    autoclose: true,
    format: "d/m/yyyy",
    language: "vi"
});

$('.date-ranger-picker').daterangepicker({
    "autoApply": true,
    locale: {
        format: "DD/MM/YYYY",
        cancelLabel: 'Clear'
    }
});

$("form").on('submit', function () {
    showLoading();
    // $('.loading-modal-before-submit').removeClass('hide');
});

// get danh sach lanh dao xem de biet
function lanhDaoXemDeBiet($this, type) {
    let arrLanhDaoId = [];
    let id = $this.val();
    if (id) {
        arrLanhDaoId.push(parseInt(id));
    }

    if (type == 'PCT') {
        let idCT = $this.closest('.dau-viec-chi-tiet').find('.chu-tich').val();
        if (idCT) {
            arrLanhDaoId.push(parseInt(idCT));
        }
    } else {
        let idPCT = $this.closest('.dau-viec-chi-tiet').find('.pho-chu-tich').val();
        if (idPCT) {
            arrLanhDaoId.push(parseInt(idPCT));
        }
    }

    // if (arrLanhDaoId.length > 0) {
    //lấy danh sach lanh dao xem de biet
    $.ajax({
        url: APP_URL + '/get-list-lanh-dao-xem-de-biet/' + JSON.stringify(arrLanhDaoId),
        type: 'GET',
    })
        .done(function (response) {
            var html = '<option value="">chọn lãnh đạo xem để biết</option>';
            if (response.success) {

                let selectAttributes = response.data.map((function (attribute) {
                    return `<option value="${attribute.id}" >${attribute.ho_ten}</option>`;
                }));

                $this.parents('.dau-viec-chi-tiet').find('.lanh-dao-xem-de-biet').html(selectAttributes);
            } else {
                $this.parents('.dau-viec-chi-tiet').find('.lanh-dao-xem-de-biet').html(html);
            }
        })
        .fail(function (error) {
            toastr['error'](error.message, 'Thông báo hệ thống');
        });
    // }
}

// them nhieu van ban den
$('body').on('submit', '#form-search-van-ban-den', function (e) {
    e.preventDefault();
    let link = $(this).attr('action');

    $.ajax({
        url: link,
        type: 'GET',
        data: $('#form-search-van-ban-den').serialize(),
    })
        .done(function (response) {
            $('#modal-them-van-ban-den').find('.show-row-van-ban-den').html(response.data);
            hideLoading();
        })
        .fail(function (error) {
            toastr['error'](error.message, 'Thông báo hệ thống');
        });
});

// check all
let allVanBanDenId = [];
let allSoKyHieu = [];

$('body').on('change', 'input[name=check_all_van_ban_den]', function () {

    if ($(this).is(':checked', true)) {
        $(this).closest('.main-data').find(".sub-check-ban-ban-den:not(:checked)").prop(    'checked', true);

        $(this).closest('.main-data').find('.sub-check-ban-ban-den:checked').each(function () {
            if (allVanBanDenId.indexOf($(this).val()) === -1) {
                allVanBanDenId.push($(this).val());
                allSoKyHieu.push({
                    'id': $(this).val(),
                    'so_ky_hieu': $(this).closest('tr').find('.so-ky-hieu').text().trim()
                });
            }

        });

        if (allVanBanDenId.length != 0) {
            $('.btn-duyet-all').removeClass('disabled');
        }
    } else {
        $(this).closest('.main-data').find(".sub-check-ban-ban-den").prop('checked', false);
        allVanBanDenId = [];
        allSoKyHieu = [];
    }

});

$('body').on('click', '.sub-check-ban-ban-den', function () {

    let id = $(this).val();
    let soKyHieu = $(this).closest('tr').find('.so-ky-hieu').text().trim();
    let data = {
        'id': id,
        'so_ky_hieu': soKyHieu
    };

    if ($(this).is(':checked')) {
        if (allVanBanDenId.indexOf(id) === -1) {
            allVanBanDenId.push(id);
            allSoKyHieu.push(data);
        }

    } else {
        let index = allVanBanDenId.indexOf(id);

        if (index > -1) {
            allVanBanDenId.splice(index, 1);
        }

        allSoKyHieu = allSoKyHieu.filter(function (item) {
            return item.id != id;
        });
    }

});

$('body').on('click', '.btn-add-van-ban-den', function () {

    console.log(allSoKyHieu);
    if (allSoKyHieu.length > 0) {
        let link = APP_URL+'/van-ban-den-chi-tiet/';
        let dataHtml = allSoKyHieu.map(function (item) {
            return `<div class="col-md-3 chi-tiet-vb-den">
            <p>
               số ký hiệu: <a href="${link+item.id}" target="_blank">${item.so_ky_hieu}</a>
                <i class="fa fa-trash rm-van-ban-den" data-id="${item.id}" data-van-ban-di=""></i>
            </p>
        </div>`;
        });

        $('.main-so-ky-hieu-van-ban-den').html(dataHtml);
        $('#modal-them-van-ban-den').modal('hide');
        $('#formCreateDoc').find('input[name="van_ban_den_id"]').val(allVanBanDenId);
        // $('#formCreateDoc').find('input[name="van_ban_den_id"]').val(JSON.stringify(allVanBanDenId));

    } else {
        return  toastr['warning']('Vui lòng chọn văn bản trước khi thêm.', 'Thông báo hệ thống');
    }
});

// remove van ban den
$('body').on('click', '.rm-van-ban-den', function () {
    if (confirm('Bạn có chắc muốn xoá văn bản này?')) {
        let $this = $(this);
        let id = $this.data('id');
        let vanBanDiId = $this.data('van-ban-di');
        if (vanBanDiId) {
            $.ajax({
                url: APP_URL + '/remove-van-ban-den',
                type: 'POST',
                data: {
                    van_ban_di_id: vanBanDiId,
                    van_ban_den_id: id
                }
            }).done(function (res) {
                toastr['success'](res.message, 'Thông báo hệ thống');
                $this.closest('.chi-tiet-vb-den').remove();
            }).fail(function (err) {
                toastr['error'](err.message, 'Thông báo hệ thống');
            });

        } else {
            let index = allVanBanDenId.indexOf(id.toString());
            if (index > -1) {
                allVanBanDenId.splice(index, 1);
            }

            $('#formCreateDoc').find('input[name="van_ban_den_id"]').val(allVanBanDenId);
            $this.closest('.chi-tiet-vb-den').remove();
            toastr['success']('Đã xoá thành công', 'Thông báo hệ thống');
        }

    }
});

$('.select-option-don-vi').on('change', function () {
    let donViId = $(this).val();

    if (donViId) {
        $.ajax({
            url: APP_URL + '/get-list-phong-ban/' + donViId,
            type: 'GET',
        })
            .done(function (response) {
                let html = '<option value="">Chọn phòng ban</option>';
                if (response.success && response.data.length > 0) {
                    let selectAttributes = response.data.map((function (attribute) {
                        return `<option value="${attribute.id}" >${attribute.ten_don_vi}</option>`;
                    }));
                    $('.show-phong-ban').removeClass('hide');

                    $('.select-phong-ban').html(html + selectAttributes);
                } else {
                    $('.show-phong-ban').addClass('hide');
                    $('.select-phong-ban').html(' ');
                }
            })
            .fail(function (error) {
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    } else {
        $('.show-phong-ban').addClass('hide');
        $('.select-phong-ban').html(' ');
    }
});


// dieu han van ban den
let vanBanDenId = null;
let arrVanBanDenId = [];

$('.check-van-ban-can-tra-loi').on('click', function () {
    let checkTraLai = $(this).data('tra-lai');
    let vanBanDenId = $(this).data('id');
    let phoPhong = $(this).closest('.dau-viec-chi-tiet').find('.pho-phong').val();
    if (checkTraLai == 1) {
        checkHasVanBanDenId(vanBanDenId);
    }
});

function checkHasVanBanDenId(id) {
    if (arrVanBanDenId.indexOf(id) === -1) {
        arrVanBanDenId.push(id);
    }

    $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(arrVanBanDenId));

    $('.btn-duyet-all').removeClass('disabled');
}

function removeVanBanDenId(id) {
    let index = arrVanBanDenId.indexOf(id);

    if (index > -1) {
        arrVanBanDenId.splice(index, 1);
    }
    $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(arrVanBanDenId));
}
// chon all chuyen vien phoi hop
$('.check-all-cv').on('click', function () {
    let id = $(this).data('id');
    let chuyenVienPhoiHopId = `#chuyen-vien-phoi-hop${id}`;
    if($(this).is(':checked') ){
        $(this).parent('p').find(chuyenVienPhoiHopId + "> option").prop("selected","selected");
        $(this).parent('p').find(chuyenVienPhoiHopId).trigger("change");

    }else{
        $(this).parent('p').find(chuyenVienPhoiHopId + "> option").prop('selected', '');
        $(this).parent('p').find(chuyenVienPhoiHopId).trigger("change");
    }
});


