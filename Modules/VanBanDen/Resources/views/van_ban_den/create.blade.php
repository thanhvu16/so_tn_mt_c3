@extends('admin::layouts.master')
@section('page_title', 'Thêm văn bản đến')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Văn Bản Đến</h3>
                    </div>
                    <form role="form" action="{{route('van-ban-den.store')}}" method="post"
                          enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Loại văn bản <span
                                            style="color: red">*</span></label>
                                    <select class="form-control select2" autofocus name="loai_van_ban" id="loai-van-ban" required>
                                        <option value="">-- Chọn loại văn bản --</option>
                                        @foreach($loaivanban as $loaivanbands)
                                            <option
                                                value="{{ $loaivanbands->id }}">{{ $loaivanbands->ten_loai_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 hidden">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Sổ văn bản <span style="color: red">*</span></label>
                                    <select class="form-control select2 check-so-den-vb"
                                            data-don-vi="{{auth::user()->don_vi_id}}" name="so_van_ban">
                                        {{--                                        <option value="">-- Chọn sổ văn bản --</option>--}}
                                        @foreach($sovanban as $data)
                                            <option
                                                value="{{ $data->id }}" {{ $data->ten_so_van_ban == 'Công văn' ? 'selected' : '' }} >{{ $data->ten_so_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Số đến văn bản</label>
                                    <input type="number" class="form-control " value="{{$soDen}}" name="so_den"
                                           id="so-den-vb"
                                           placeholder="Số đến" readonly required
                                           style="font-weight: 800;color: #F44336;cursor: not-allowed;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Cơ quan ban hành <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="co_quan_ban_hanh"
                                           id="co-quan-ban-hanh"
                                           placeholder="Cơ quan ban hành" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Số ký hiệu <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="so_ky_hieu" id="so-ky-hieu-vb" style="text-transform: uppercase "
                                           placeholder="Số ký hiệu" required>
                                </div>
                            </div>
                            <div class="row clearfix"></div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày ban hành <span
                                            style="color: red">*</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control vanbantrung ngay-ban-hanh datepicker"
                                               name="ngay_ban_hanh" id="ngay-ban-hanh-vb"
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
                                    <textarea class="form-control" name="trich_yeu" id="trich-yeu" rows="3" required></textarea>
                                </div>
                            </div>






                            <div class="row clearfix"></div>




                            {{--                            <div class="col-md-12 text-right">--}}
                            {{--                                <a class="btn btn-primary " role="button" data-toggle="collapse"--}}
                            {{--                                   href="#collapseExample"--}}
                            {{--                                   aria-expanded="false" aria-controls="collapseExample"><i--}}
                            {{--                                        class="fa fa-plus"></i>--}}
                            {{--                                </a>--}}
                            {{--                                <b class="text-danger"> Hiển thị thêm nội dung</b>--}}
                            {{--                            </div>--}}

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
                                    <input type="text" class="form-control" name="nguoi_ky" id="nguoi-ky"
                                           placeholder="Người ký" required>
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
                                        @foreach($domat as $domatds)
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
                                        @foreach($dokhan as $dokhands)
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
                            @if(auth::user()->hasrole(QUYEN_VAN_THU_HUYEN) && count($users) > 0)
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label for="exampleInputEmail4">Lãnh đạo tham mưu <span
                                                style="color: red">*</span></label>
                                        <select class="form-control select2" name="lanh_dao_tham_muu" disabled required>
                                            @foreach($users as $nguoidung)
                                                <option value="{{ $nguoidung->id }}">{{ $nguoidung->ho_ten }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 hidden">
                                    <div class="form-group">
                                        <label for="exampleInputEmail4">Lãnh đạo tham mưu <span
                                                style="color: red">*</span></label>
                                        <select class="form-control select2" name="lanh_dao_tham_muu"  required>
                                            @foreach($users as $nguoidung)
                                                <option value="{{ $nguoidung->id }}">{{ $nguoidung->ho_ten }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif


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
                                    <button type="button" onclick="checktrung1()" class="btn btn-primary"><i
                                            class="fa fa-plus-square-o mr-1"></i> Thêm mới
                                    </button>
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

        function checktrung1()
        {
            var so_ky_hieu = $('[name=so_ky_hieu]').val();
            var ngay_ban_hanh = $('[name=ngay_ban_hanh]').val();
            var loai_van_ban = $('#loai-van-ban').val();
            // console.log(loai_van_ban);
            // console.log(them_tiep);
            // e.preventDefault();
            $.ajax({
                url: APP_URL + '/kiem_tra_trich_yeu',
                type: 'POST',
                beforeSend: showLoading(),
                dataType: 'json',
                data: {
                    so_ky_hieu: so_ky_hieu,
                    ngay_ban_hanh: ngay_ban_hanh,
                    loai_van_ban: loai_van_ban,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
            }).done(function (res) {
                if (res.is_relate) {
                    hideLoading();
                    // document.getElementById("them-moi").submit();
                    // document.getElementById("them-moi").submit();
                    $('#moda-search').html(res.html);
                    $('#moda-search').modal('show');
                } else {
                    var co_quan_ban_hanh = document.getElementById("co-quan-ban-hanh");
                    var so_ky_hieu = document.getElementById("so-ky-hieu-vb");
                    var vb_so_den = document.getElementById("so-den-vb");
                    var vb_ngay_ban_hanh = $('#ngay-ban-hanh-vb').val();
                    var trichYeu = document.getElementById("trich-yeu");
                    var nguoiKy = document.getElementById("nguoi-ky");
                    var loai_van_ban = document.getElementById("loai-van-ban");

                    console.log(vb_ngay_ban_hanh);
                    var value1 = co_quan_ban_hanh.value;
                    var value2 = so_ky_hieu.value;
                    var value3 = vb_so_den.value;
                    // var value4 = vb_ngay_ban_hanh.value;
                    var value5 = trichYeu.value;
                    var value6 = nguoiKy.value;
                    var value7 = loai_van_ban.value;

                    if( value1 == "" ||  value2 == ""||  value3 == ""||  vb_ngay_ban_hanh == ""||  value5 == ""||  value6 == ""||  value7 == "")  {
                        hideLoading();
                        alert("Bạn cần nhập đủ thông tin");
                        // so_van_ban_id.focus();
                        return false;
                    }else{
                        document.getElementById("myform").submit();

                    }
                }

            });
        }

        $(document).ready(function () {
            var ngay_nhan = $('input[name="ngay_nhan"]').val();
            var tieu_chuan = $('.tieu-chuan').val();

            console.log(ngay_nhan, tieu_chuan);
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
