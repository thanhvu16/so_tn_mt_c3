@extends('admin::layouts.master')
@section('page_title', 'Thêm văn bản đến')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title " id="tenLoaiVanBan"></h3>
                    </div>
                    <div class="row" @if($data_trung) style="padding-top: 5px;padding-bottom: 5px;" @endif>


                        @if($data_trung)
                            <div class="col-md-4 blink_me">
                                <h4 class="header-title mb-3 color-red ">Văn bản đã tồn tại trên hệ thống!!!
                                </h4>
                            </div>
                            <div class="col-md-3" style="margin-top: -8px;">
                                <a href="{{route('dsvanbandentumail')}}" class="btn btn-danger">Quay lại hòm thư
                                    công</a>
                            </div>
                        @endif
                    </div>
                    <form role="form"
                          action="{{ route('luuvanbantumail') }}"
                          method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Loại văn bản <span
                                            style="color: red">*</span></label>
                                    <select class="form-control select2 loaiVanBan" autofocus name="loai_van_ban" required>
                                        <option value="">-- Chọn loại văn bản --</option>
                                        @foreach($ds_loaiVanBan as $loaivanbands)
                                            <option value="{{ $loaivanbands->id }}"
                                                {{ isset($loaivb_email) && $loaivb_email->id == $loaivanbands->id ? 'selected="selected"' : '' }}>{{ $loaivanbands->ten_loai_van_ban }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="giay_moi" value="{{isset($loaivb_email) ? $loaivb_email->ten_loai_van_ban : ''}}" class="hidden">
                                </div>
                            </div>
                            <div class="col-md-3 hidden">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Sổ văn bản <span style="color: red">*</span></label>
                                    <select class="form-control select2 check-so-den-vb"
                                            data-don-vi="{{auth::user()->don_vi_id}}" name="so_van_ban" required>
                                        @foreach($ds_soVanBan as $data)
                                            <option
                                                value="{{ $data->id }}"{{ $data->ten_so_van_ban == 'Công văn' ? 'selected' : '' }} >{{ $data->ten_so_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Số đến văn bản</label>
                                    <input type="number" class="form-control " value="{{$soDen}}" name="so_den"
                                           id="exampleInputEmail3"
                                           placeholder="Số đến"
                                           style="font-weight: 800;color: #F44336;cursor: not-allowed;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Cơ quan ban hành <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control"
                                           value="{{empty($data_xml) ? '': $data_xml->STRNOIGUI}}"
                                           name="co_quan_ban_hanh" id="exampleInputEmail6"
                                           placeholder="Cơ quan ban hành" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Số ký hiệu <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="so_ky_hieu" id="exampleInputEmail4"
                                           placeholder="Số ký hiệu"
                                           value="{{ empty($data_xml) ? '' : strtoupper($data_xml->STRKYHIEU) }}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày ban hành <span
                                            style="color: red">*</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control vanbantrung ngay-ban-hanh datepicker"
                                               value=" @if($data_xml){{!empty($data_xml->STRNGAYKY) && ($data_xml->STRNGAYKY > '1970/01/01') ? date('d/m/Y', strtotime($data_xml->STRNGAYKY)) : ''}}  @endif"
                                               name="ngay_ban_hanh" id="exampleInputEmail5"
                                               placeholder="dd/mm/yyyy" required>
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Trích yếu <span style="color: red">*</span></label>
                                    <textarea class="form-control" name="trich_yeu" rows="3"
                                              required>{{ empty($data_xml) ? $email->mail_subject : $data_xml->STRTRICHYEU }}</textarea>
                                </div>
                            </div>
                            <div class="row clearfix"></div>



                            <div class="giay-moi col-md-12 hidden">
                                <div class="row">
                                    <div class="col-md-3" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>Giờ họp <span class="color-red">*</span></label>

                                            <div class="input-group">
                                                <input type="text" name="gio_hop_chinh" required
                                                       value="{{isset($data_xml->STRTHOIGIANHOP)? $data_xml->STRTHOIGIANHOP : ''}}"
                                                       class="form-control timepicker">

                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label for="">Ngày họp <span style="color: red">*</span></label>
                                            <input type="date"  class="form-control "
                                                   value="{{isset($data_xml) ? $data_xml->STRNGAYHOP : $ngayHopNull}}"
                                                   name="ngay_hop_chinh" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label for="">Địa điểm <span style="color: red">*</span></label>
                                            <input type="text"  class="form-control"
                                                   value="{{isset($data_xml->STRDIADIEM)? $data_xml->STRDIADIEM : ''}}"
                                                   name="dia_diem_chinh" placeholder="Địa điểm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    <div class="row clearfix"></div>
                            <div class="col-md-12 collapse "
                                 id="collapseExample">
                                <div class="col-md-12 layout2 ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr style="border: 0.5px solid #3c8dbc">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="noi_dung" class="col-form-label">Nội dung</label>
                                            <textarea rows="3" class="form-control"
                                                      name="noi_dung[]"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="han_giai_quyet" class="col-form-label">Hạn giải quyết</label>
                                            <div id="">
                                                <input type="date" class="form-control"
                                                       value="" name="han_giai_quyet[]">
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="input-group-btn text-right " style="margin-top: 10px">
                                    <span class="btn btn-primary" onclick="noidungvanban('noi_dung[]')" type="button">
                                    <i class="fa fa-plus"></i> thêm nội dung</span>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Người ký <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="nguoi_ky" id="exampleInputEmail7"
                                           placeholder="Người ký"
                                           value="{{ isset($data_xml) ? $data_xml->STRNGUOIKY : null  }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Người nhập </label>
                                    <input type="text" class="form-control" value="{{auth::user()->ho_ten}}"
                                           placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày nhận </label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control han-xu-ly datepicker ngay-nhan"
                                               name="ngay_nhan" value="{{$date}}" placeholder="Ngày nhận">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Hạn xử lý </label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control han-xu-ly datepicker" name="han_xu_ly"
                                               value="" placeholder="Hạn xử lý">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix"></div>
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
                            <div class="col-md-3  van-ban">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Thời hạn theo quy chế </label>
                                    <select class="form-control select2 lay_van_ban tieu-chuan" name="tieu_chuan">
                                        @foreach($tieuChuan as $tieuChuandata)
                                            <option
                                                value="{{ $tieuChuandata->id }}">{{ $tieuChuandata->ten_tieu_chuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(auth::user()->role_id == QUYEN_VAN_THU_HUYEN && count($users) > 0)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail4">Lãnh đạo tham mưu <span
                                                style="color: red">*</span></label>
                                        <select class="form-control select2" name="lanh_dao_tham_muu" required>
                                            @foreach($users as $nguoidung)
                                                <option value="{{ $nguoidung->id }}">{{ $nguoidung->ho_ten }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="row clearfix"></div>

                            <div class="row clearfix"></div>

                            <div class="col-md-3">
                                <label for="exampleInputEmail4">File</label>
                                <input type="file" class="form-control han-xu-ly" name="File" value="">
                            </div>

                            <div class="col-md-3 mt-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="giao-so-chu-tri"  value="1" name="chu_tri_phoi_hop"> Giao sở chủ trì
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3 mt-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-plus-square-o mr-1"></i> Thêm mới
                                    </button>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="sokyhieu" class="col-form-label">File văn bản:</label>
                                        &nbsp;&nbsp;
                                        <a href="{{$url_pdf}}" target="popup" class="seen-new-window">[File_pdf]</a>
                                        <input type="hidden" name="file_pdf[]" value="{{$url_pdf}}">

                                        @if($url_doc)
                                            &nbsp;&nbsp;|&nbsp;&nbsp;
                                            <a href="{{$url_doc}}" target="popup"
                                               class="seen-new-window">[File_docx]</a>
                                            <input type="hidden" name="file_pdf[]" value="{{$url_doc}}">
                                        @endif
                                        @if($url_xls)
                                            &nbsp;&nbsp;|&nbsp;&nbsp;
                                            <a href="{{$url_xls}}" target="popup" class="seen-new-window">[File_xls]</a>
                                            <input type="hidden" name="file_pdf[]" value="{{$url_xls}}">
                                        @endif
                                        <input type="hidden" name="id_vanban_tumail" value="{{$id}}">
                                    </div>

                                </div>
                            </div>
                            <div id="moda-search" class="modal fade" role="dialog">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script type="text/javascript">
        $('.ngay-ban-hanh').on('change', function () {
            // console.log($('[name=ngay_ban_hanh]').val());
            // $('.van-ban').removeClass('hidden');
        });

        $(document).ready(function () {
            var ngay_nhan = $('input[name="ngay_nhan"]').val();
            var loai_van_ban = $('input[name="giay_moi"]').val();
            var tieu_chuan = $('.tieu-chuan').val();




            if(loai_van_ban == 'Giấy mời')
            {
                $('.giay-moi').removeClass('hidden');
                document.getElementById("tenLoaiVanBan").innerHTML = "Tạo giấy mời đến";

            }else{
                document.getElementById("tenLoaiVanBan").innerHTML = "Tạo văn bản đến";


            }
            $.ajax({
                // beforeSend: showLoading(),
                url: APP_URL + '/han-xu-ly-van-ban',
                type: 'POST',
                dataType: 'json',

                data: {
                    tieu_chuan: tieu_chuan,
                    ngay_nhan: ngay_nhan,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },

            }).done(function (res) {
                // hideLoading();
                $("input[name='han_xu_ly']").val(res.html);


            });
        });

        $('.loaiVanBan').on('change', function (e) {
            var loai_van_ban = $('.loaiVanBan').val();
            e.preventDefault();
            $.ajax({
                // beforeSend: showLoading(),
                url: APP_URL + '/check-giay-moi',
                type: 'POST',
                dataType: 'json',

                data: {
                    loai_van_ban: loai_van_ban,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },

            }).done(function (res) {
                // hideLoading();
                $("input[name='so_den']").val(res.soDen);
                if(res.giayMoi == "Giấy mời")
                {
                    $('.giay-moi').removeClass('hidden');
                    document.getElementById("tenLoaiVanBan").innerHTML = "Tạo giấy mời đến";
                }else {
                    $('.giay-moi').addClass('hidden');
                    document.getElementById("tenLoaiVanBan").innerHTML = "Tạo văn bản đến";
                }



            });
        });



        $('.lay_van_ban').on('change', function (e) {
            var tieu_chuan = $('[name=tieu_chuan]').val();
            var ngay_ban_hanh = $('input[name="ngay_nhan"]').val();
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
                $("input[name='han_xu_ly']").val(res.html);


            });
        });
        $('.ngay-nhan').on('change', function (e) {
            var tieu_chuan = $('[name=tieu_chuan]').val();
            var ngay_ban_hanh = $('input[name="ngay_nhan"]').val();
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
                $("input[name='han_xu_ly']").val(res.html);


            });
        });
    </script>
@endsection

