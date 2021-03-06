@extends('admin::layouts.master')
@section('page_title', 'Thêm giấy mời đến')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Giấy mời đến</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-row"
                              action="{{route('giay-moi-den.store')}}"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-md-3">
                                <label for="vb_so_den" class="col-form-label">Số đến giấy mời</label>
                                <input type="text" name="vb_so_den" class="form-control soden" id="vb_so_den"
                                       value="{{ $sodengiaymoi}}"
                                       style="font-weight: 800;color: #F44336;"
                                       placeholder="Số đến văn bản">
                            </div>
                            <div class="form-group col-md-3" id="div_select_cqbh">
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Nơi gửi đến <span
                                        class="color-red">*</span></label>
                                <input type="text" value=""
                                       class="form-control"
                                       name="co_quan_ban_hanh_id" tabindex="1" autofocus required>

                            </div>
                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Số ký hiệu <span class="color-red">*</span></label>
                                <input type="text" name="so_ky_hieu"
                                       value="" style="text-transform: uppercase "
                                       required
                                       class="form-control file_insert tenfile" tabindex="2"
                                       id="sokyhieu"
                                       placeholder="Số ký hiệu">
                            </div>


{{--                            <div class="form-group col-md-3">--}}
{{--                                <label for="ngay_ban_hanh" class="col-form-label">Ngày ban hành <span--}}
{{--                                        class="color-red">*</span></label>--}}
{{--                                <input class="form-control vanbantrung" tabindex="3"--}}
{{--                                       id="vb_ngay_ban_hanh"--}}
{{--                                       value="" required type="date"--}}
{{--                                       name="ngay_ban_hanh">--}}
{{--                            </div>--}}
                            <div class="form-group col-md-3">
                                <div class="form-group">
                                    <label for="vb_ngay_ban_hanh">Ngày ban hành <span
                                            style="color: red">*</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control vanbantrung datepicker "
                                               value=""  tabindex="3"
                                               name="ngay_ban_hanh" id="vb_ngay_ban_hanh"
                                               placeholder="dd/mm/yyyy" required>
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" hidden style="margin-top: 10px">
                                <div class="form-group">
                                    <label for="">Người chủ trì <span class="color-red">*</span></label>
                                    <input type="text" class="form-control"
                                           value=""
                                           name="nguoi_chu_tri_hop" placeholder="người chủ trì">
                                </div>
                            </div>


                            <div class="form-group col-md-12">
                                <label for="sokyhieu" class="col-form-label ">Trích yếu <span class="color-red">*</span></label>
                                <textarea rows="3" class="form-control" tabindex="4" placeholder="nội dung" name="vb_trich_yeu"
                                          required
                                          type="text"></textarea>
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

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="">Ngày họp <span class="color-red">*</span></label>--}}
{{--                                    <input type="date" tabindex="6" required class="form-control ngaybanhanh2"--}}
{{--                                           value=""--}}
{{--                                           name="ngay_hop_chinh" placeholder="">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ngay_hop_chinh">Ngày họp <span
                                            style="color: red">*</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control ngaybanhanh2  datepicker" value=""
                                               name="ngay_hop_chinh" id="ngay_hop_chinh" tabindex="6"
                                               placeholder="dd/mm/yyyy" >
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Địa điểm <span class="color-red">*</span></label>
                                    <input type="text"  class="form-control"
                                           value=""
                                           name="dia_diem_chinh" tabindex="7" placeholder="Địa điểm">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày nhận </label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control han-xu-ly datepicker ngay-nhan" name="ngay_nhan"  value="{{$date}}" placeholder="Ngày nhận" >
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>


{{--                            <div class="col-md-3 text-right {{isset($vanban) ? 'hidden': ''}}" style="margin-top: 40px">--}}
{{--                                <a class="btn btn-primary btn-xs" role="button" data-toggle="collapse"--}}
{{--                                   href="#collapseExample"--}}
{{--                                   aria-expanded="false" aria-controls="collapseExample"><i--}}
{{--                                        class="fa fa-plus " ></i>--}}
{{--                                </a>--}}
{{--                                <b class="text-danger"> Hiển thị thêm nội dung</b>--}}
{{--                            </div>--}}

                            <div class="form-group col-md-3 hidden" id="loaivanban">
                                <label for="loai_van_ban_id" class="col-form-label">Loại văn bản <span
                                        class="color-red">*</span></label>
                                <select class="form-control " name="loai_van_ban_id" id="loai_van_ban_id" readonly
                                        required>
                                    <option value="100"
                                    >Giấy mời
                                    </option>
                                </select>
                            </div>

                            <div class="clearfix"></div>


                            <div class="col-md-12 collapse  "
                                 id="collapseExample">
                                <div class="col-md-12  gmoi layout3 ">
                                    <div class="row" style="margin-top:-15px;margin-left: 0px;">
                                        <hr style="border: 0.5px solid #3c8dbc">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="detail-job">Nội dung họp <span
                                                    class="color-red">*</span></label>
                                            <textarea name="noi_dung_hop_con[]" placeholder="nhập nội dung công việc"
                                                      rows="3"
                                                      class="form-control no-resize noi-dung-chi-dao"></textarea>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 10px">
                                            <div class="form-group">
                                                <label for="">Giờ họp</label>
                                                <input type="text" class="form-control timepicker"
                                                       value=""
                                                       name="gio_hop_con[]">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
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
                                                <input type="text"
                                                       value=""
                                                       placeholder="Nhập địa điểm" class="form-control"
                                                       name="dia_diem_con[]">
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="input-group-btn text-right {{ isset($vanban) ? 'hidden' : '' }}">
        <span class="btn btn-primary" onclick="themgiaymoi('noi_dung_hop_con[]')" type="button">
                        <i class="fa fa-plus"></i> thêm nội dung</span>
                                </div>
                            </div>
{{--                            <div class="col-sm-12" style="margin-top:-14px;margin-left: 0px;">--}}
{{--                                <hr style="border: 0.5px solid #3c8dbc">--}}
{{--                            </div>--}}
                            <div class="cotl-md-12">
                                <div class="row">
                                    <div class="form-group col-md-3 hidden">
                                        <label for="sokyhieu" class="col-form-label ">Số văn bản</label>
                                        <select class="form-control  select-so-van-ban check-so-den-vb"
                                                data-don-vi="{{auth::user()->don_vi_id }}" name="so_van_ban_id"
                                                id="so_van_ban_id">
                                            <option
                                                value="100">Giấy mời
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <label for="sokyhieu" class="col-form-label">Người ký <span
                                        class="color-red">*</span></label>
                                <input type="text" class="form-control " tabindex="8"
                                       value="" required
                                       name="nguoi_ky_id">
                            </div>
                            <div class="col-md-3" >
                                <label for="sokyhieu" class="col-form-label">Chức vụ </label>
                                <input type="text" class="form-control " placeholder="nhập chức vụ"
                                       value=""  name="chuc_vu">
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Độ Mật</label>
                                    <select class="form-control select2" name="do_mat">
                                        {{--                                        <option value="">-- Chọn độ mật--</option>--}}
                                        @foreach($ds_mucBaoMat as $domatds)
                                            <option value="{{ $domatds->id }}">{{ $domatds->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Độ khẩn</label>
                                    <select class="form-control select2" name="do_khan">
                                        {{--                                        <option value="">-- Chọn độ khẩn --</option>--}}
                                        @foreach($ds_doKhanCap as $dokhands)
                                            <option value="{{ $dokhands->id }}">{{ $dokhands->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

{{--                            <div class=" col-md-3 " >--}}
{{--                                <label for="vb_han_xu_ly" class="col-form-label">Hạn xử lý</label>--}}
{{--                                <input class="form-control" value=""--}}
{{--                                       name="vb_han_xu_ly" id="vb_han_xu_ly" type="date">--}}
{{--                                <input type="hidden" class="form-control" id="don_vi_id" name="don_vi_id"--}}
{{--                                       value="{{auth::user()->don_vi_id}}">--}}
{{--                            </div>--}}
                            @hasanyrole('văn thư sở')
                            @if (count($nguoi_dung) > 0)
                                <div class="col-md-3" >
                                    <label for="vb_ngay_ban_hanh" class="col-form-label">Lãnh đạo tham mưu</label>
                                    <select name="lanh_dao_tham_muu" class="form-control " id="">
                                        @foreach($nguoi_dung as $nguoidung)
                                            <option value="{{$nguoidung->id}}">{{$nguoidung->ho_ten}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            @endrole
                            @hasanyrole('văn thư đơn vị')
                            <div class="col-md-3 mt-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="1" name="don_vi_phoi_hop">
                                            Là văn bản phối hợp
                                        </label>
                                    </div>

                                </div>
                            </div>
                            @endrole

                            <div class="col-md-12 form-group" style="margin-top: 10px">
                                <div class="row increment ">
                                    <div class="col-md-3 ">
                                        <label for="sokyhieu" class="col-form-label">Tên tệp tin</label>
                                        <input class="form-control ten-tep-tin" value="" name="txt_file[]" type="text">
                                    </div>
                                    <div class="col-md-3 ">
                                        <label for="url-file" class="col-form-label">Chọn tệp</label>
                                        <div class="form-line input-group control-group">
                                            <input type="file" id="url-file" name="ten_file[]" class="form-control">
                                            <div class="input-group-btn">
                        <span class="btn btn-primary" onclick="multiUploadFilevanban('ten_file[]')" type="button">
                        <i class="fa fa-plus"></i> thêm file</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class=" col-md-3 mt-4" style="margin-top: 35px">
                                        <button
                                            class="btn btn-primary" tabindex="9" type="submit"><i class="fa fa-plus-square-o mr-1"></i>
                                            <span>Thêm mới</span></button>
                                    </div>

                                </div>
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
