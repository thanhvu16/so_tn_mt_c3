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
    radioClass   : 'iradio_minimal-blue'
});
//Red color scheme for iCheck
$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
    radioClass   : 'iradio_minimal-red'
});
//Flat red color scheme for iCheck
$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
});


$('.go-back').on('click', function () {
    window.history.back();
});

$('.seen-new-window').on('click', function () {
    let url = $(this).attr('href');

    return window.open(url,'popup','width=600,height=600, margin:0 auto');
});

$('.btn-remove-item').on('click', function () {

    return confirm('Bạn muốn xóa dữ liệu này?');
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
    showMeridian:!1,
    showInputs: false
});


$("input[type=date]").on("change", function() {
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
