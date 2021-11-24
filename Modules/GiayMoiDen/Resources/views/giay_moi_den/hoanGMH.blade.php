@extends('admin::layouts.master')
@section('page_title', 'Thêm giấy mời đến')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Hoãn giấy mời đến</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-row"
                              action="{{route('hoanHOP')}}"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-md-3">
                                <label for="vb_so_den" class="col-form-label">Số đến giấy mời <span class="color-red">*</span></label></label>
                                <input type="number" name="vb_so_den" class="form-control soden" id="vb_so_den"
                                       value=""
                                       style="font-weight: 800;color: #F44336;"
                                       placeholder="Số đến văn bản">
                            </div>



                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Giờ họp <span class="color-red">*</span></label>

                                    <div class="input-group">
                                        <input type="text" name="gio_hop_chinh" tabindex="5" class="form-control timepicker">

                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ngay_hop_chinh">Ngày họp <span
                                            style="color: red">*</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control ngaybanhanh2  datepicker" value=""
                                               name="ngay_hop_chinh" id="ngay_hop_chinh"
                                               placeholder="dd/mm/yyyy" required>
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Địa điểm <span class="color-red">*</span></label>
                                    <input type="text" required class="form-control"
                                           value=""
                                           name="dia_diem_chinh" tabindex="7" placeholder="Địa điểm">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="sokyhieu" class="col-form-label ">Nội dung hoãn họp <span class="color-red">*</span></label>
                                <textarea rows="3" class="form-control" tabindex="4" placeholder="nội dung" name="vb_trich_yeu"
                                          required
                                          type="text"></textarea>
                            </div>

                            <div class=" col-md-3 mt-4" style="margin-top: 35px">
                                <button
                                    class="btn btn-primary" tabindex="9" type="submit"><i class="fa fa-plus-square-o mr-1"></i>
                                    <span>Gửi đi</span></button>
                            </div>
                        </form>
                        <div id="moda-search" class="modal fade" role="dialog">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script>
        $('.ngaybanhanh2').on('blur', function (e) {
            var ngay_ban_hanh = $('[name=ngay_hop_chinh]').val();
            console.log(ngay_ban_hanh);
            e.preventDefault();
            $.ajax({
                url: APP_URL + '/layhantruyensangview',
                type: 'POST',
                dataType: 'json',
                data: {
                    ngay_ban_hanh: ngay_ban_hanh,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
            }).done(function (res) {
                console.log(res);
                if (res.html) {
                    $('input[name="vb_han_xu_ly"]').val(res.html);
                } else {
                }

            });
        })
    </script>
    <script type="text/javascript">
        $('.ngay-ban-hanh').on('change', function () {
            console.log($('[name=ngay_ban_hanh]').val());
            $('.van-ban').removeClass('hidden');
        });
        //
        // $(document).ready(function () {
            //     var ngay_nhan = $('input[name="ngay_nhan"]').val();
        //     // var tieu_chuan = $('.tieu-chuan').val();
        //
        //     console.log(ngay_nhan, tieu_chuan );
        //     $.ajax({
        //         // beforeSend: showLoading(),
        //         url: APP_URL + '/han-xu-ly-van-ban',
        //         type: 'POST',
        //         dataType: 'json',
        //
        //         data: {
        //             tieu_chuan: tieu_chuan,
        //             ngay_nhan: ngay_nhan,
        //             _token: $('meta[name="csrf-token"]').attr('content'),
        //         },
        //
        //     }).done(function (res) {
        //         // hideLoading();
        //         $("input[name='han_xu_ly']").val(res.html);
        //
        //
        //     });
        // });
        $('.lay_van_ban').on('change', function (e) {
            var tieu_chuan = $('[name=tieu_chuan]').val();
            var ngay_ban_hanh = $('[name=ngay_ban_hanh]').val();

            e.preventDefault();
            $.ajax({
                beforeSend: showLoading(),
                url: APP_URL + '/han-van-ban',
                type: 'POST',
                dataType: 'json',

                data: {
                    tieu_chuan: tieu_chuan,
                    ngay_ban_hanh: ngay_ban_hanh,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },

            }).done(function (res) {
                hideLoading();
                $('.han-xu-ly').val(res.html);


            });
        });
    </script>
@endsection
