@extends('admin::layouts.master')
@section('page_title', 'Thêm giấy mời đến')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Giấy mời đi</h3>
                    </div>
                    @include('vanbandi::van_ban_di.form_them_van_ban_den')
                    <div class="box-body">
                        <form class="form-row"
                              action="{{ route('giay-moi-di.store')}}"
                              method="post" enctype="multipart/form-data" id="formCreateDoc">
                            @csrf
                            <input type="hidden" name="van_ban_den_id">
                            <div class="form-group col-md-3 hidden">
                                <label for="linhvuc_id" class="col-form-label">Loại văn bản </label>
                                <select class="form-control show-tick loai-van-ban-chanh-vp"  name="loaivanban_id" id="loaivanban_id" required>
                                    <option value="{{ $giayMoi->id ?? null }}">{{ $giayMoi->ten_loai_van_ban ?? null }}</option>
                                </select>
                            </div>
{{--                            <div class="form-group col-md-3">--}}
{{--                                <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi <span style="color: red">*</span></label>--}}
{{--                                <select class="form-control show-tick" autofocus name="sovanban_id" required>--}}
{{--                                    @foreach ($ds_soVanBan as $data)--}}
{{--                                        <option value="{{ $data->id }}"--}}
{{--                                        >{{ $data->ten_so_van_ban}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Ký hiệu <span style="color: red">*</span></label>
                                <input type="text" value=""
                                       id="vb_sokyhieu" name="vb_sokyhieu"  class="form-control"
                                       placeholder="Nhập ký hiệu văn bản đi..." required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Ngày ban hành <span style="color: red">*</span></label>
                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh" class="form-control"
                                       value=""
                                       autocomplete="off" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span style="color: red">*</span></label>
                                <select class="form-control show-tick select2-search" name="donvisoanthao_id" required>
                                    @foreach ($ds_DonVi as $donVi)
                                        <option value="{{ $donVi->id }}" class="select2" {{ auth::user()->don_vi_id == $donVi->id ? 'selected' : null     }}
                                        >{{ $donVi->ten_don_vi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="sokyhieu" class="col-form-label">Người duyệt <span style="color: red">*</span></label>
                                <select name="nguoi_nhan" id="" class="form-control ">
                                    @foreach ($nguoinhan as $data)
                                        <option value="{{ $data->id }}"
                                        >{{ $data->ho_ten}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row clearfix"></div>
                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label>Giờ họp <span style="color: red">*</span></label>

                                    <div class="input-group">
                                        <input type="text" name="gio_hop" class="form-control timepicker">

                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label for="" class="col-form-label">Ngày họp <span style="color: red">*</span></label>
                                    <input type="date"  required class="form-control ngaybanhanh2" value=""
                                           name="ngay_hop" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label for="" class="col-form-label">Địa điểm <span style="color: red">*</span></label>
                                    <input type="text" required class="form-control" value=""
                                           name="dia_diem" placeholder="Địa điểm">
                                </div>
                            </div>
                            <div class="row clearfix"></div>

                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span style="color: red">*</span></label>
                                <select class="form-control show-tick  layidnguoiky" name="nguoiky_id" required>
                                    <option value="">-- Chọn Người Ký --</option>
                                    @foreach ($ds_nguoiKy as $nguoiKy)
                                        <option data-chuc-vu ="{{ $nguoiKy->chucvu->ten_chuc_vu ?? null }}" value="{{ $nguoiKy->id }}"
                                        >{{$nguoiKy->ho_ten}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ <span style="color: red">*</span></label>
                                <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu" value="">
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                <select class="form-control show-tick" name="dokhan_id" required>
                                    @foreach ($ds_doKhanCap as $doKhanCap)
                                        <option value="{{ $doKhanCap->id }}"
                                        >{{ $doKhanCap->ten_muc_do}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                <select class="form-control show-tick " name="dobaomat_id" required>--}}
                                    @foreach ($ds_mucBaoMat as $doBaoMat)
                                        <option value="{{ $doBaoMat->id }}" {{ $doBaoMat->macDinh ? 'selected' : ''  }}
                                        >{{ $doBaoMat->ten_muc_do}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12" >
                                <label for="sokyhieu" class="col-form-label ">Trích yếu <span style="color: red">*</span></label>
                                <textarea rows="3" name="vb_trichyeu" class="form-control no-resize" placeholder="Nhập nội dung trích yếu ..."
                                          required></textarea>
                            </div>
                            <div class=" col-md-6 form-group">
                                <label for="exampleInputEmail2">Đơn vị phát hành  <span class="color-red">*</span></label>
                                <select class="form-control select2" name="phong_phat_hanh" id=""  required>
                                    <option value="">Chọn phòng phát hành</option>
                                    @foreach ($ds_DonVi_phatHanh as $DonVi_phatHanh)
                                        <option value="{{ $DonVi_phatHanh->id }}" {{ isset($vanbanduthao) && $vanbanduthao->phong_phat_hanh == $DonVi_phatHanh->id ? 'selected' : '' }} >{{ $DonVi_phatHanh->ten_don_vi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label">Trả lời cho văn bản đến:</label><br>
                                <a class="them-van-ban-den" style="cursor: pointer" data-toggle="modal" data-target="#modal-them-van-ban-den">
                                    <span><i class="fa fa-plus-square-o"></i> Thêm văn bản đến</span>
                                </a>
                                <div class="row main-so-ky-hieu-van-ban-den">

                                </div>
                            </div>

                            <div class="form-group col-md-12 ">
                                <label for="sokyhieu" class="col-form-label">Đơn vị nội bộ </label>
                                <select name="don_vi_nhan_trong_thanh_php[]" id="don_vi_nhan"
                                        class="form-control select2 select2-hidden-accessible"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($ds_DonVi_nhan as $email)
                                        <option value="{{ $email->id }}"
                                        >{{ $email->ten_don_vi}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-12 text-right">
                                <a role="button" onclick="showModal()" class="btn btn-primary ">
                                    <span style="color: white;font-size: 14px"><i class="fa fa-check-square-o"></i> Chọn đơn vị</span></a>

                                <a role="button" onclick="xoatatca()" class="btn btn-danger ">
                                    <span style="color: white;font-size: 14px"><i class="far fa-trash-alt"></i> Xóa tất cả</span></a>
                            </div>
                            <div class="form-group col-md-12 " >
                                <label for="" class="col-form-label">Đơn vị nhận ngoài hệ thống</label>
                                <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                        class="form-control select2 select2-hidden-accessible"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($emailngoaithanhpho as $emailngoai)
                                        <option value="{{ $emailngoai->id }}"
                                        >{{ $emailngoai->ten_don_vi}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-12 ">
                                <span style="font-style: italic">( <span style="color: red">Có thể thêm mới nơi nhận mail bên ngoài phía dưới</span> )</span><br>

                                <div class="row themnoinhan">

                                    <div class="col-md-4">
                                        <label for="" class="col-form-label">Tên Đơn vị nhận ngoài</label>
                                        <input type="text"
                                               value=""
                                               name="ten_don_vi_them[]"  class="form-control"
                                               placeholder="Tên đơn vị..." >
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email_them" class="col-form-label">Email</label>
                                        <input type="text"
                                               value=""
                                               name="email_them[]"  class="form-control"
                                               placeholder="Email..." >
                                    </div>
                                    <div class="col-md-5 mt-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a class="btn btn-success btn-xs" style="color: white"
                                                   onclick="themnoinhan()"
                                                   role="button"
                                                ><i class="fa fa-plus"></i>
                                                </a>
                                                <b class="text-danger"> Thêm nơi nhận</b> &emsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3" style="margin-top: 25px">
                                <button  type="submit" class="btn btn-info waves-effect waves-light"><i class="fa fa-plus-square-o mr-1"></i>
                                    <span>Tạo văn bản</span></button>
                            </div>
                        </form>
                    </div>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog modal-lg" style="max-width: 1800px">
                            <div class="modal-content">
                                <form action="{{ route('multiple_file_di') }}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title"><i class="fa fa-home"></i> Nơi nhận mail Sở ban ngành</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">




                                            <div class="col-md-12">
                                                <div class="col-md-12 text-left">
                                                    <a class=" " data-toggle="collapse"
                                                       href="#collapseExample" style="color: black"
                                                       aria-expanded="false" aria-controls="collapseExample"> <i class="fa fa-home"></i>
                                                        <span
                                                            style="font-size: 14px">Nơi nhận mail Sở ban ngành</span>
                                                    </a>
                                                </div>
                                                <div class="col-md-12 collapse "
                                                     id="collapseExample">
                                                    <table id="dtVerticalScrollExample" class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center " width="10%"><input type="checkbox" name="checkall1" class="checkboxall1" onclick="docheckall1();"></th>
                                                            <th class="text-center" width="">Sở ban ngành</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dulieu_phoihop">
                                                        @foreach($emailSoBanNganh as $data1)
                                                            <tr id="chon_phoihop_1">
                                                                <td class="text-center">
                                                                    <input type="checkbox" name="CBphongban1[]" value="{{$data1->id}}" class="CBphongban1 loaiPB1">
                                                                </td>
                                                                <td class="text-left">{{$data1->ten_don_vi}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-12 text-left">
                                                    <a class=" " data-toggle="collapse"
                                                       href="#collapseExample1"
                                                       aria-expanded="false" style="color: black" aria-controls="collapseExample"> <i class="fa fa-home"></i>
                                                        <span
                                                            style="font-size: 14px">Nơi nhận Quận huyện</span>
                                                    </a>
                                                </div>
                                                <div class="col-md-12 collapse "
                                                     id="collapseExample1">
                                                    <table id="dtVerticalScrollExample" class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" width="10%"><input type="checkbox" name="checkall2" class="checkboxall1" onchange="docheckall2();"></th>
                                                            <th class="text-center" width="">Nơi nhận Quận huyện</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dulieu_phoihop1">
                                                        @foreach($emailQuanHuyen as $data2)
                                                            <tr id="chon_phoihop_16">
                                                                <td class="text-center">
                                                                    <input type="checkbox" name="CBphongban1[]" value="{{$data2->id}}" class="CBphongban1 loaiPB2">
                                                                </td>
                                                                <td class="text-left">{{$data2->ten_don_vi}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-12 text-left">
                                                    <a class="  " data-toggle="collapse"
                                                       href="#collapseExample2"
                                                       aria-expanded="false" style="color: black" aria-controls="collapseExample"> <i class="fa fa-home"></i>
                                                        <span
                                                            style="font-size: 14px">Nơi nhận mail đơn vị trực thuộc</span>
                                                    </a>
                                                </div>
                                                <div class="col-md-12 collapse "
                                                     id="collapseExample2">
                                                    <table id="dtVerticalScrollExample" class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" width="10%"><input type="checkbox" name="checkall3" class="checkboxall1" onchange="docheckall3();"></th>
                                                            <th class="text-center" width="">Nơi nhận mail đơn vị trực thuộc</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dulieu_phoihop1">
                                                        @foreach($emailTrucThuoc as $data3)
                                                            <tr id="chon_phoihop_16">
                                                                <td class="text-center">
                                                                    <input type="checkbox" name="CBphongban1[]" value="{{$data3->id}}"  class="CBphongban1 loaiPB3">
                                                                </td>
                                                                <td class="text-left">{{$data3->ten_don_vi}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-right">
                                                <button type="button" name="luu_phoihop" value="luu_phoihop"  onclick="themphoihop()" class="btn btn-primary btn-sm"  data-dismiss="modal"><i class="fa fa-close"></i> Ghi lại</button>
                                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Đóng lại</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script src="{{ asset('modules/quanlyvanban/js/noigui.js') }}"></script>
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
