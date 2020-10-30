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

$('.select2').select2()


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
