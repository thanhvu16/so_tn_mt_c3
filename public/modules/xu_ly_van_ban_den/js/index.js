let vanBanDenDonViId = null;
let ArrVanBanDenDonViId = [];
let txtChuTich = null;

$('.chu-tich').on('change', function () {
    let $this = $(this);
    let id = $this.val();
    vanBanDenDonViId = $this.data('id');
    let statusTraLai = $this.data('tra-lai');

    let textChuTich = $this.find("option:selected").text() + ' xem xét';

    let checkPhoChuTich = $this.parents('.tr-tham-muu').find('.pho-chu-tich option:selected').val();

    if (id) {
        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính báo cáo giám đốc ' + textChuTich);
        checkVanBanDenId(vanBanDenDonViId);
        txtChuTich = 'Kính báo cáo giám đốc ' + textChuTich;
        $this.parents('.tr-tham-muu').find('.chu-tich-du-hop').val(id);
        checkedDuHop($this, '.chu-tich-du-hop');
    } else {
        removeVanBanDenDonViId(vanBanDenDonViId);
        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chu_tich[${vanBanDenDonViId}]"]`).addClass('hide');
        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chu_tich[${vanBanDenDonViId}]"]`).text('');
        $this.parents('.tr-tham-muu').find('.chu-tich-du-hop').val();
        removeDuHop($this, '.chu-tich-du-hop');
    }

    if (statusTraLai) {
        $('#form-tham-muu').find('input[name="van_ban_tra_lai"]').val(statusTraLai);
    }

    lanhDaoXemDeBiet($this, 'CT');
});

$('.pho-chu-tich').on('change', function () {
    let $this = $(this);
    let id = $this.val();
    let textPhoChuTich = $this.find("option:selected").text() + ' chỉ đạo';
    vanBanDenDonViId = $this.data('id');
    let checkChuTich = $this.parents('.tr-tham-muu').find('.chu-tich option:selected').val();
    let textChuTich = $this.parents('.tr-tham-muu').find('.chu-tich option:selected').text() + ' xem xét';

    if (id) {
        let txtChiDao = 'Kính báo cáo giám đốc ' + textChuTich + ', giao PGD ' + textPhoChuTich;

        // check empty chu tich
        if (checkChuTich && checkChuTich.length > 0) {
            $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính chuyển phó giám đốc ' + textPhoChuTich);
        } else {
            $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính báo cáo phó giám đốc ' + textPhoChuTich);
        }

        checkVanBanDenId(vanBanDenDonViId);
        $this.parents('.tr-tham-muu').find('.noi-dung-chu-tich').text(txtChiDao);
        $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val(id);
        checkedDuHop($this, '.pho-ct-du-hop');
    } else {
        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).text('');
        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).addClass('hide');
        removeVanBanDenDonViId(vanBanDenDonViId);
        $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val();
        $this.parents('.tr-tham-muu').find('.noi-dung-chu-tich').text('Kính báo cáo giám đốc ' + textChuTich);
        removeDuHop($this, '.pho-ct-du-hop');
    }
    lanhDaoXemDeBiet($this, 'PCT');
});

$('body').on('change', '.don-vi-chu-tri', function () {
    let $this = $(this);
    let arrId = $this.find("option:selected").map(function () {
        return parseInt(this.value);
    }).get();

    let id = $(this).val();
    let statusTraLai = $this.data('tra-lai');

    vanBanDenDonViId = $this.data('id');

    let donViChuTri = $(this).find("option:selected").map(function () {
        return this.text;
    }).get();

    if (donViChuTri.length > 0 && id.length > 0) {
        checkVanBanDenId(vanBanDenDonViId);
        $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị chủ trì: ' + donViChuTri.toString());
        $this.parents('.tr-tham-muu').find('.don-vi-du-hop').val(id);
    } else {
        removeVanBanDenDonViId(vanBanDenDonViId);
        $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).addClass('hide');
        $this.parents('.tr-tham-muu').find('.don-vi-du-hop').val(id);
    }
    checkedDuHop($this, '.don-vi-du-hop');

    if (statusTraLai) {
        $('#form-tham-muu').find('input[name="van_ban_tra_lai"]').val(statusTraLai);
    }

    if (arrId) {
        //lấy danh sach cán bộ phối hơp
        $.ajax({
            url: APP_URL + '/list-don-vi-phoi-hop/' + JSON.stringify(arrId),
            type: 'GET',
            beforeSend: function () {
                showLoading();
            }
        })
            .done(function (response) {
                hideLoading();
                var html = '<option value="">chọn đơn vị phối hợp</option>';
                if (response.success) {

                    let selectAttributes = response.data.map((function (attribute) {
                        return `<option value="${attribute.id}" >${attribute.ten_don_vi}</option>`;
                    }));

                    $this.parents('.dau-viec-chi-tiet').find('.don-vi-phoi-hop').html(selectAttributes);
                    $this.parents('.tr-tham-muu').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).text(' ').addClass('hide');
                } else {
                    $this.parents('.dau-viec-chi-tiet').find('.don-vi-phoi-hop').html(html);
                }
            })
            .fail(function (error) {
                hideLoading();
                toastr['error'](error.message, 'Thông báo hệ thống');
            });
    }

});

$('body').on('change', '.don-vi-phoi-hop', function () {

    let donViPhoiHop = $(this).find("option:selected").map(function () {
        return this.text;
    }).get();

    let statusTraLai = $(this).data('tra-lai');

    vanBanDenDonViId = $(this).data('id');

    if (donViPhoiHop.length > 0) {

        checkVanBanDenId(vanBanDenDonViId);

        $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị phối hợp: ' + donViPhoiHop.join(', '));
    } else {
        removeVanBanDenDonViId(vanBanDenDonViId);
        $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).addClass('hide');
    }

    if (statusTraLai) {
        $('#form-tham-muu').find('input[name="van_ban_tra_lai"]').val(statusTraLai);
    }
});

$('.btn-update').on('click', function () {
    let vanBanDenDonViId = $(this).data('id');
    checkVanBanDenId(vanBanDenDonViId);
    if (confirm('Xác nhận gửi?')) {
        $('#form-tham-muu').submit();
    }
});

function checkVanBanDenId(vanBanDenDonViId) {

    if (ArrVanBanDenDonViId.indexOf(vanBanDenDonViId) === -1) {
        ArrVanBanDenDonViId.push(vanBanDenDonViId);
    }

    $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(ArrVanBanDenDonViId));

    $('.btn-duyet-all').removeClass('disabled');
}

function removeVanBanDenDonViId(vanBanDenDonViId) {
    let index = ArrVanBanDenDonViId.indexOf(vanBanDenDonViId);

    if (index > -1) {
        ArrVanBanDenDonViId.splice(index, 1);
    }
    $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(ArrVanBanDenDonViId));
}

$('.btn-submit').on('click', function () {
    let id = $('#form-tham-muu').find('input[name="van_ban_den_id"]').val();
    if (id.length == 0) {
        toastr['warning']('Vui lòng chọn trước khi duyệt', 'Thông báo hệ thống');
    } else {
        $('#form-tham-muu').submit();
    }
});

$('.don-vi-du-hop').on('click', function () {
    $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val(1);
});

$('.pho-ct-du-hop').on('click', function () {
    $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
});

$('.chu-tich-du-hop').on('click', function () {
    $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
});

// check du hop
function checkedDuHop($this, $className) {
    $this.parents('.tr-tham-muu').find($className).prop('checked', true);
    if ($className === '.don-vi-du-hop') {
        $this.parents('.tr-tham-muu').find('.check-don-vi-du-hop').val(1);
    } else {
        $this.parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
    }
}

function removeDuHop($this, $className) {
    $this.parents('.tr-tham-muu').find($className).prop('checked', false);
}
