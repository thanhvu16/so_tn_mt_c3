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
                            </div>
                        </div>
                     </div>
                    </div>`;

    $('.layout2').append(htmlForm);
}
$("body").on("click", ".btn-remove-file", function () {

    $(this).parents(".remove-multi-file").remove();
});
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
}
