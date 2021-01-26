@extends('admin::layouts.master')
@section('page_title', 'Chi tiết cuộc họp')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chi tiết cuộc họp</h3>
                    </div>
                    <div class="box-body">

                            <!-- Nav tabs -->

                            <!-- Tab panes -->
                            <div class="">
                                <div role="tabpanel" class="tab-pane hide" id="home"><br>
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="modal fade" id="themthanhphanthamdu" role="dialog" aria-labelledby="exampleModalLabel">
                                                <div class="modal-dialog" role="document" style="width: 92%;">
                                                    <div class="modal-content">
                                                        <form action="" method="post" autocomplete="off" class="form-horizontal">
                                                            <div class="modal-header" style="padding: 12px; background: #daf7f5;">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="exampleModalLabel">Cập nhật thành phần tham dự</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="box-body">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                <select name="nhomcaobo" id="" class="form-control select2">
                                                                                    <option value="0">-- Chọn nhóm cán bộ --</option>
                                                                                    <option value="1">Thường trực Quận ủy</option>
                                                                                    <option value="2">Ban chấp hành Đảng bộ</option>

                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                <select name="phongban_themthamdu" id="" class="form-control select2">
                                                                                    <option value="0">-- Chọn phòng ban --</option>
                                                                                    <option value="1">VP HĐND&UBND</option>
                                                                                    <option value="2">Phòng TC-KH</option>
                                                                                    <option value="3">Phòng TNMT</option>
                                                                                    <option value="4">Phòng QLĐT</option>

                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                <select name="chucvu_themthamdu" id="" class="form-control select2">
                                                                                    <option value="0">-- Chọn chức vụ --</option>
                                                                                    <option value="1">Trưởng Phòng</option>
                                                                                    <option value="2">Phó Trưởng phòng</option>
                                                                                    <option value="3">Chuyên viên</option>

                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                <input type="text" class="form-control" name="hoten_themthamdu" placeholder="Nhập họ tên tìm kiếm">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <button type="button" name="timkiem_themthamdu" value="timkiem_themthamdu" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Tìm kiếm</button>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="dulieu_themthamdu" class="table table-bordered table-hover">

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="col-md-12">
                                                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Đóng lại</button>
                                                                    <button type="submit" name="luu_thanhphanduhop" value="luu_thanhphanduhop" class="btn btn-primary btn-sm"><i class="fa fa-close"></i> Lưu lại</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end danh sách đảng viên-->

                                <div role="tabpanel" class="tab-pane active" id="profile">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <a href="" style="margin-right: 10px;" class="tin"> {{$lich_cong_tac->noi_dung ?? ''}}</a>
                                                <p style="color:red;"></p>
                                                <button type="button" name="capnhatthongtin" value="capnhatthongtin" class="btn btn-info" data-toggle="modal" data-target="#capnhatthongtinlich" data-original-title="" title=""><i class="fa fa-calendar-plus-o"></i> CẬP NHẬT LỊCH HỌP</button>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <div class="modal fade" id="capnhatthongtinlich" role="dialog" aria-labelledby="exampleModalLabel">
                                                        <div class="modal-dialog" role="document" style="width: 76%;">
                                                            <div class="modal-content">

                                                                <form
                                                                    action="{{ isset($lich_cong_tac) ? route('lich-cong-tac.update', $lich_cong_tac->id) : route('lich-cong-tac.store') }}"
                                                                    method="post" autocomplete="off" class="form-horizontal">
                                                                    @csrf

                                                                    <div class="modal-header" style="padding: 12px; background: #daf7f5;">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-original-title="" title="">
                                                                            <span aria-hidden="true">×</span></button>
                                                                        <h4 class="modal-title text-bold" id="exampleModalLabel">#{{ isset($lich_cong_tac) ? 'Cập nhật ': 'Thêm ' }} lịch
                                                                            họp </h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="box-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <label class="control-label">Nội dung <label class="required">*</label></label>
                                                                                    <textarea class="form-control" rows="3" required="" name="noi_dung"
                                                                                              placeholder="Nội dung">{{ isset($lich_cong_tac) ? $lich_cong_tac->noi_dung : '' }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-3">
                                                                                <div class="col-sm-6">
                                                                                    <label for="" class="control-label">Ngày họp<label class="required">*</label></label>
                                                                                    <input type="date" required=""
                                                                                           value="{{ isset($lich_cong_tac) ? $lich_cong_tac->ngay : date('d/m/Y') }}" class="form-control"
                                                                                           name="ngay">
                                                                                </div>


                                                                                <div class="col-sm-6">
                                                                                    <label class="control-label mb-1">Giờ họp <span class="color-red">*</span></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" required class="form-control time-picker-24h"
                                                                                               value="{{ isset($lich_cong_tac) ? $lich_cong_tac->gio : '' }}" name="gio">
                                                                                        <div class="input-group-addon">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-3">
                                                                                <div class="col-sm-6">
                                                                                    <label class="control-label">Địa điểm <span class="color-red">*</span></label>
                                                                                    <input type="text" name="dia_diem" placeholder="Nhập địa điểm" class="form-control"
                                                                                           value="{{ isset($lich_cong_tac) ? $lich_cong_tac->dia_diem : null }}" required>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label class="control-label">Lãnh đạo dự họp<span class="color-red">*</span></label>
                                                                                    <br>
                                                                                    <select name="lanh_dao_id" class="form-control select2">
                                                                                        <option value="">Chọn lãnh đạo</option>
                                                                                        @forelse($danhSachLanhDao as $lanhdao)
                                                                                            <option
                                                                                                value="{{ $lanhdao->id }}" {{ isset($lich_cong_tac) && $lich_cong_tac->lanh_dao_id == $lanhdao->id ? 'selected' : null }}>{{ $lanhdao->ho_ten }}</option>
                                                                                        @empty
                                                                                        @endforelse
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-3">
                                                                                <div class="col-sm-3">
                                                                                    <div class="radio-info form-check-inline">
                                                                                        <input type="radio" name="trang_thai_lich"
                                                                                               id="chinh-thuc{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}" value="1"
                                                                                               checked {{ isset($lich_cong_tac) && $lich_cong_tac->trang_thai_lich == 1 ? 'checked' : '' }}>
                                                                                        <label for="chinh-thuc{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}">Lịch chính
                                                                                            thức</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <div class="radio-info form-check-inline">
                                                                                        <input type="radio" name="trang_thai_lich"
                                                                                               id="lich-hoan{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}"
                                                                                               value="2" {{ isset($lich_cong_tac) && $lich_cong_tac->trang_thai_lich == 2 ? 'checked' : '' }}>
                                                                                        <label for="lich-hoan{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}">Lịch hoãn</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <div class="radio-info form-check-inline">
                                                                                        <input type="radio" name="trang_thai_lich"
                                                                                               id="lich-dieu-chinh{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}"
                                                                                               value="3" {{ isset($lich_cong_tac) && $lich_cong_tac->trang_thai_lich == 3 ? 'checked' : '' }}>
                                                                                        <label for="lich-dieu-chinh{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}">Lịch điều
                                                                                            chỉnh</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <div class="radio-info form-check-inline">
                                                                                        <input type="radio" name="trang_thai_lich"
                                                                                               id="lich-phat-sinh{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}"
                                                                                               value="4" {{ isset($lich_cong_tac) && $lich_cong_tac->trang_thai_lich == 4 ? 'checked' : '' }}>
                                                                                        <label for="lich-phat-sinh{{ isset($lich_cong_tac) ? $lich_cong_tac->id : null }}">Lịch phát
                                                                                            sinh</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="row trang-thai-lich">
                                                                                <div class="col-sm-12">
                                                                                    <label class="control-label">Ghi chú <span class="color-red">*</span></label>
                                                                                    <textarea name="ghi_chu" rows="4" class="form-control noi-dung-ghi-chu"
                                                                                              placeholder="ghi chú">{{ isset($lich_cong_tac) ? $lich_cong_tac->ghi_chu : '' }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <div class="col-md-12 text-center">
                                                                            <button type="submit" class="btn btn-primary btn-sm" data-original-title="" title=""><i
                                                                                    class="fa fa-save"></i> Lưu lại
                                                                            </button>
                                                                            <button type="button" class="btn btn-default btn-sm border" data-dismiss="modal" data-original-title=""
                                                                                    title=""><i class="fa fa-close"></i> Đóng lại
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12"><br>
                                            <div class="col-md-8">
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#xu-ly-van-ban-den" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Tài liệu cuộc họp:
                                                                <i class="fa fa-plus pull-right"></i>
                                                            </a>
                                                        </h3>
                                                        <div id="xu-ly-van-ban-den" class="panel-collapse collapse mt-2">


                                                                <table class="table table-bordered table-hover mt-2">
                                                                    <thead>
                                                                    <tr>
                                                                        <th width="5%" class="text-center">STT</th>
                                                                        <th width="" class="text-center">File tài liệu</th>
                                                                        <th width="20%" class="text-center">Người tạo</th>
                                                                        <th width="10%" class="text-center">Tác vụ</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @if($lich_cong_tac->fileCuocHop != null)
                                                                        @forelse($lich_cong_tac->fileCuocHop as $key=>$data)
                                                                            <tr class="tai-lieu-{{ $data->id }}">
                                                                                <td>{{$key+1}}</td>
                                                                                <td class="text-center vertical">
                                                                                            <a href="{{$data->getUrlFile()}}">[file_tài liệu {{$key+1}}]</a>
                                                                                </td>
                                                                                <td>{{$data->nguoiDung->ho_ten ?? ''}}</td>
                                                                                <td class="text-center">
                                                                                    @if(auth::user()->id == $data->nguoi_tao)
                                                                                        <a class="Xoatailieu " data-id="{{$data->id}}" ><i class="fa fa-trash" aria-hidden="true" style="color: red"></i></a>
                                                                                    @else
                                                                                        -
                                                                                    @endif

                                                                                </td>
                                                                            </tr>
                                                                            @empty
                                                                                <td colspan="4" class="text-center">Không có file tài liệu cuộc họp !</td>
                                                                            @endforelse
                                                                    @endif

                                                                    </tbody>
                                                                </table>
                                                            @if(in_array(auth::user()->id, $nguoi_upTaiLieu->pluck('user_id')->toArray()))
                                                            <form enctype="multipart/form-data" action="{{route('upload_tai_lieu',$id)}}" class="uploader"  method="post">
                                                                @csrf
                                                                <input type="file" name="tailieucuochop[]" multiple readonly="" class="form-control">
                                                                <br>
                                                                <button type="submit" name="luu_tailieucuochop" value="3312" class="btn btn-primary btn-sm pull-right" style="margin-bottom: 10px;">Lưu lại</button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#xu-ly-van-ban-den7" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Tài liệu tham khảo:
                                                                <i class="fa fa-plus pull-right"></i>
                                                            </a>
                                                        </h3>

                                                        <div id="xu-ly-van-ban-den7" class="panel-collapse collapse mt-2">
                                                            <table class="table table-bordered table-hover mt-2">
                                                                <thead>
                                                                <tr>
                                                                    <th width="5%" class="text-center">STT</th>
                                                                    <th width="" class="text-center">File tài liệu tham khảo</th>
                                                                    <th width="20%" class="text-center">Người tạo</th>
                                                                    <th width="10%" class="text-center">Tác vụ</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if($lich_cong_tac->fileThamKhao != null)
                                                                        @forelse($lich_cong_tac->fileThamKhao as $key=>$data)
                                                                        <tr class="tai-lieu-{{ $data->id }}">
                                                                            <td>{{$key+1}}</td>
                                                                            <td class="text-center vertical">
                                                                                        <a href="{{$data->getUrlFile()}}">[file_tài liệu_tham_khảo {{$key+1}}]</a>
                                                                            </td>
                                                                            <td>{{$data->nguoiDung->ho_ten ?? ''}}</td>
                                                                            <td>
                                                                                @if(auth::user()->id == $data->nguoi_tao)
                                                                                    <a class="Xoatailieu " data-id="{{$data->id}}" ><i class="fa fa-trash" aria-hidden="true" style="color: red"></i></a>
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        @empty
                                                                            <td colspan="4" class="text-center">Không có file tài liệu tham khảo !</td>
                                                                        @endforelse
                                                                    @endif
                                                                </tbody>
                                                            </table>


                                                            @if(in_array(auth::user()->id, $nguoi_upTaiLieu->pluck('user_id')->toArray()))
                                                            <form enctype="multipart/form-data" action="{{route('upload_tai_lieu',$id)}}" class="uploader"  method="post">
                                                                @csrf
                                                                <input type="file" name="tailieuthamkhao[]" multiple readonly="" class="form-control">
                                                                <br>
                                                                <button type="submit" name="luu_tailieuthamkhao"  value="3312" class="btn btn-primary btn-sm pull-right" style="margin-bottom: 10px;">Lưu lại</button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#dulieu_lienquan" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Cuộc họp liên quan
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                            <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#cuochoplienquan"><i class="fa fa-plus-square"></i>
                                                            </button>

                                                        </h3>
                                                        <div id="dulieu_lienquan" class="panel-collapse collapse mt-2">
                                                            <table class="table table-bordered table-hover mt-2">
                                                                <thead>
                                                                <tr>
                                                                    <th width="8%" class="text-center">STT</th>
                                                                    <th width="" class="text-center">Nội dung họp</th>
                                                                    <th width="10%" class="text-center">Tác vụ</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="cuoc-hop-lien-quan">
                                                                @if($cuocHopLienQuan != null)
                                                                    @forelse($cuocHopLienQuan as $key=>$item)
                                                                        <tr class="lien-quan-{{$item->id}}">
                                                                            <td class="text-center">{{$key+1}}</td>
                                                                            <td><p>{{$item->CuocHopLienQuan->noi_dung ?? ''}}</p></td>
                                                                            <td class="text-center vertical">
                                                                                <a class="XoaCuocHop " data-id="{{$item->id}}" ><i class="fa fa-trash" aria-hidden="true" style="color: red"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <td class="text-center" colspan="3">Không có cuộc họp nào !</td>
                                                                    @endforelse
                                                                @endif

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#trao-doi" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Trao đổi thảo luận
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>
                                                        <div id="trao-doi" class="panel-collapse collapse mt-2">
                                                            <div class="noidungchat" style="overflow: auto; max-height: 250px;">
                                                            </div>
                                                            <input type="text" class="hide" name="malich" value="3312">
                                                            <input type="text" class="hide" name="kiemtrahanlich" value="2">
                                                            <input type="text" class="hide" name="kiemtra_lanhdao" value="1">
                                                            <input type="text" class="hide" name="kiemtra_tonghop" value="2">
                                                            <textarea name="noidungchat" id="" class="form-control" maxlength="500" rows="3" placeholder="Nhập ý kiến tại đây">{{isset($GopY) ? $GopY->trao_doi_thao_luan : ''}}</textarea><br>
                                                            <button name="luu_noidungchat" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_noidungchat" style="margin-bottom: 10px;">@if($GopY && $GopY != null) Cập nhật @else Gửi  @endif</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#y-kien" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Ý kiến chính thức
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>
                                                        <div id="y-kien" class="panel-collapse collapse mt-2">
                                                            <div class="noidungketluan" style="overflow: auto; max-height: 250px;">

                                                            </div>
                                                            <textarea name="ykienchinhthuc" id=""  rows="4" class="form-control" placeholder="Nhập ý kiến chính thức">{{isset($cuochop) ? $cuochop->y_kien_chinh_thuc : ''}}</textarea>
                                                            <br>
                                                            @if(auth::user()->id == $lich_cong_tac->lanh_dao_id)
                                                                @if($cuochop)
                                                                    <button name="luu_ykienchinhthuc" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_ykienchinhthuc " style="margin-bottom: 10px;">@if($cuochop && $cuochop->y_kien_chinh_thuc == null) Lưu lại @else Cập nhật @endif</button>
                                                                @else
                                                                    <button name="luu_ykienchinhthuc" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_ykienchinhthuc " style="margin-bottom: 10px;">Lưu lại</button>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#y-kien3" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Ghi chép cuộc họp của VP Quận Ủy
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>
                                                        <div id="y-kien3" class="panel-collapse collapse mt-2">
                                                                <textarea name="noidung_ghichepcuochop_qu" id=""  rows="20" class="form-control" placeholder="Nhập ghi chép cuộc họp">{{isset($cuochop) ? $cuochop->ghi_chep_quan_uy : ''}}</textarea>
                                                                <br>
                                                            @if(auth::user()->id == $lich_cong_tac->lanh_dao_id)
                                                                @if($cuochop)
                                                                    <button type="submit" name="luu_ghichepcuochop_qu" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_ghichepcuochop_qu " style="margin-bottom: 10px;">@if($cuochop && $cuochop->ghi_chep_quan_uy == null) Lưu lại @else Cập nhật @endif</button>
                                                                @else
                                                                    <button type="submit" name="luu_ghichepcuochop_qu" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_ghichepcuochop_qu " style="margin-bottom: 10px;">Lưu lại</button>
                                                                @endif

                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#y-kien2" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Ghi chép cuộc họp của VP HĐND UBND Quận
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>
                                                        <div id="y-kien2" class="panel-collapse collapse mt-2">
                                                                <textarea name="noidung_ghichepcuochop" id=""  rows="20" class="form-control" placeholder="Nhập ghi chép cuộc họp">{{isset($cuochop) ? $cuochop->ghi_chep_HDND : ''}}</textarea>
                                                                <br>
                                                            @if(auth::user()->id == $lich_cong_tac->lanh_dao_id)
                                                                @if($cuochop)
                                                                    <button type="submit" name="luu_ghichepcuochop" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_ghichepcuochop " style="margin-bottom: 10px;">@if($cuochop  && $cuochop->ghi_chep_HDND == null)  Lưu lại @else  Cập nhật @endif</button>
                                                                    @else
                                                                    <button type="submit" name="luu_ghichepcuochop" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_ghichepcuochop " style="margin-bottom: 10px;"> Lưu lại</button>
                                                                @endif
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#ket-luan" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Kết luận cuộc họp
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>
                                                        <form enctype="multipart/form-data" action="{{route('luu_ketluan',$id)}}" class="uploader"  method="post">
                                                            @csrf
                                                        <div id="ket-luan" class="panel-collapse collapse mt-2">
                                                                <textarea name="noidung_ketluan" id=""  style="height: 345px!important;" class="form-control textarea noidung_ketluan" placeholder="Nhập kết luận cuộc họp">{{isset($cuochop) ? $cuochop->ket_luan_cuoc_hop : ''}}</textarea>
                                                                <br>
                                                            @if($cuochop && $cuochop->ket_luan_cuoc_hop  != null)
                                                                @if($lich_cong_tac != null && $lich_cong_tac->fileKetLuan != null)
                                                                @foreach($lich_cong_tac->fileKetLuan as $data)
                                                                    <a href="{{$data->getUrlFile()}}">[file kết luận cuộc họp]</a>
                                                                @endforeach
                                                                @endif

                                                            @else
                                                                <input type="file" id="file_ket_luan" name="file_ketluan[]" multiple readonly="" class="form-control">
                                                            @endif
                                                                <br>
                                                                <!-- them moi dau viec -->

                                                                <!-- ket thuc dau viec -->
                                                                <br>
                                                            @if(auth::user()->id == $lich_cong_tac->lanh_dao_id)
                                                                <button type="submit"  name="luu_ketluan" data-id="{{$id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_ketluan @if($cuochop && $cuochop->ket_luan_cuoc_hop != null) hidden @endif" style="margin-bottom: 10px;">Lưu lại</button>
                                                            @endif
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                @if(auth::user()->id == $lich_cong_tac->lanh_dao_id)
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#ket-luan3" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Đánh giá cuộc họp
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>

                                                        <div id="ket-luan3" class="panel-collapse collapse mt-2">
                                                            <input type="radio" name="danhgiatonghop" value="1" class="flat-red" @if($lich_cong_tac->danh_gia == 1 || $lich_cong_tac->danh_gia == null)checked @endif checked> Đạt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="danhgiatonghop" value="2" class="flat-red" @if($lich_cong_tac->danh_gia == 0) @endif > Không đạt
                                                            <button type="button" name="luu_danhgiatonghop" data-lich="{{$lich_cong_tac->id}}" value="3312" class="btn btn-primary btn-sm pull-right luu_danhgiatonghop @if($lich_cong_tac->danh_gia != null)hidden @endif" style="margin-bottom: 10px;">Lưu lại</button>

                                                        </div>

                                                    </div>
                                                </div>
                                                @endif




                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel" >
                                                        <h3>
                                                            <a data-toggle="collapse" href="#chat-luong5" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Đánh giá chất lượng chuẩn bị tài liệu họp
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>
                                                        <div id="chat-luong5" class="panel-collapse collapse mt-2">
                                                            <table class="table table-bordered table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th width="5%" class="text-center">STT</th>
                                                                    <th width="25%">Phòng ban</th>
                                                                    <th width="20%">Chất lượng</th>
                                                                    <th>Nhận xét</th>
                                                                    <th width="12%" class="text-center">Đánh giá</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @forelse($phong_up_tai_lieu as $key=>$data)
                                                                <tr>
                                                                    <td class="text-center">{{$key+1}}</td>
                                                                    <td>{{DonViUpTaiLieu($data)->ten_don_vi ?? ''}}</td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" name="dat_{{$data}}" @if(layDanhGia($data,$id) != null && layDanhGia($data,$id)->danh_gia_chat_luong_chuan_bi_tai_lieu == 1)checked @else @endif checked  value="1" >
                                                                                    Đạt
                                                                                </label> &emsp;
                                                                                <label>
                                                                                    <input type="radio" name="dat_{{$data}}" @if(layDanhGia($data,$id) != null && layDanhGia($data,$id)->danh_gia_chat_luong_chuan_bi_tai_lieu == 2)checked @else @endif  value="2">
                                                                                    Không đạt
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio">

                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><textarea name="nhanxet_{{$data}}" class="form-control nhanxet_3586" rows="2">@if(layDanhGia($data,$id) != null && layDanhGia($data,$id)->nhan_xet){{layDanhGia($data,$id)->nhan_xet}} @else @endif</textarea></td>
                                                                    <td class="text-center vertical ">
                                                                        <div class="button-danh-gia-{{$data}}">
                                                                            @if(auth::user()->id == $lich_cong_tac->lanh_dao_id)
                                                                                <button name="nhanxetTaiLieu" onclick="danhGiaTaiLieu('dat_{{$data}}',{{$data}},{{$id}},'nhanxet_{{$data}}')"  value="3586" data-don-vi="{{$data}}" class="btn btn-primary btn-sm nhan-xet-danh-gia @if(layDanhGia($data,$id) != null && layDanhGia($data,$id)->danh_gia_chat_luong_chuan_bi_tai_lieu != null) hidden @else @endif" data-original-title="" title="">Đánh giá</button>

                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </div>


                                                                    </td>
                                                                </tr>
                                                                @empty
                                                                    <td colspan="5" class="text-center">Không có phòng chuẩn bị nào !</td>
                                                                @endforelse
                                                                </tbody>
                                                            </table> </div>
                                                    </div>
                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel" >
                                                        <h3>
                                                            <a data-toggle="collapse" href="#chat-luong6" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Đánh giá chất lượng góp ý - ý kiến
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                        </h3>
                                                        <div id="chat-luong6" class="panel-collapse collapse mt-2">
                                                            <table class="table table-bordered table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th width="5%" class="text-center">STT</th>
                                                                    <th width="25%">Họ tên - Phòng ban</th>
                                                                    <th width="15%">Chất lượng</th>
                                                                    <th>Nhận xét</th>
                                                                    <th width="12%" class="text-center">Đánh giá</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @forelse($canBoGopY as $key=>$data)
                                                                <tr id="goy-{{$data->user_id}}">
                                                                    <td class="text-center">{{$key+1}}</td>
                                                                    <td>{{$data->user->ho_ten ?? ''}}</td>
                                                                    <td>
                                                                        <label>
                                                                            <input type="radio" name="chatluong_38613_{{$data->id}}"  value="1" checked="">
                                                                            Đạt
                                                                        </label> &emsp;
                                                                        <label>
                                                                            <input type="radio" name="chatluong_38613_{{$data->id}}" @if($data->chat_luong == 2) checked @endif  value="2">
                                                                            Không đạt
                                                                        </label>
                                                                    </td>
                                                                    <td><textarea name="nhanxetchatluong_{{$data->user_id}}" class="form-control nhanxetchatluong_38613" rows="2">{{$data->nhan_xet}}</textarea></td>
                                                                    <td class="text-center vertical">
                                                                        @if(auth::user()->id == $lich_cong_tac->lanh_dao_id)
                                                                        <button name="nhanxet_chatluong" value="38613" id="1145" onclick="danhGiaChatLuongGopY('chatluong_38613_{{$data->id}}',{{$data->user_id}},{{$id}},'nhanxetchatluong_{{$data->user_id}}')" class="btn btn-primary btn-sm @if($data->chat_luong != null)  hidden @else @endif" data-original-title="" title="">Đánh giá</button>
                                                                        @else
                                                                            -
                                                                        @endif

                                                                    </td>
                                                                </tr>

                                                                @empty
                                                                    <td colspan="5" class="text-center">Không có phòng chuẩn bị nào !</td>
                                                                @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>













                                            </div>
                                            <div class="col-md-4">
                                                <div class="box box-solid table table-bordered table-hover">
                                                    <div class="box-header with-border" style="background: #b8e2f5">
                                                        <h3 class="box-title">Thông tin</h3>

                                                        <div class="box-tools">
                                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="box-body no-padding mt-2">
                                                        <ul class="nav nav-pills nav-stacked">
                                                            <li class=""><a href="javascript:void(0);">Bắt đầu: {{ date('d/m/Y', strtotime($lich_cong_tac->ngay)) }} - {{ date('H:i', strtotime($lich_cong_tac->gio)) }}</a></li>
                                                            <li class=""><a href="javascript:void(0);">Kết thúc: </a></li>
                                                            <li class=""><a href="javascript:void(0);">Người chủ trì: Đ/c {{$lich_cong_tac->lanhDao->ho_ten ?? ''}}</a></li>
                                                            <li class=""><a href="javascript:void(0);">Đơn vị chuẩn bị:
                                                                {{$lich_cong_tac->lanhDao->donVi->ten_don_vi ?? ''}}
                                                                    <!-- 1 -->
                                                                </a></li>
                                                            <li class=""><a href="javascript:void(0);">Địa điểm: {{$lich_cong_tac->dia_diem ?? ''}}</a></li>

                                                        </ul>
                                                    </div>
                                                    <!-- /.box-body -->
                                                </div>

                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#y-kien8" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Thành phần tham dự
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>
                                                            @if(auth::user()->id ==  $lich_cong_tac->lanh_dao_id)
                                                            <button type="button" name="capnhatthanhphancuochop" value="3312" class="btn btn-danger pull-right" data-toggle="modal" data-target="#thanhphanthamdu"><i class="fa fa-plus-square"></i>
                                                            </button>
                                                                @endif
                                                        </h3>
                                                        <div id="y-kien8" class="panel-collapse collapse mt-2">


                                                                <table class="table table-bordered table-hover mt-2">
                                                                    <thead>
                                                                    <tr>
                                                                        <th width="5%" class="text-center">STT</th>
                                                                        <th width="">Cán bộ</th>
                                                                        <th width="20%">Tác vụ</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @if($nguoi_tham_du != null)
                                                                    @foreach($nguoi_tham_du as $key=>$item)
                                                                        <tr class="remove-{{ $item->id }}">
                                                                            <td>{{$key+1}}</td>
                                                                            <td><p>{{$item->nguoiDung->ho_ten}}</p></td>
                                                                            <td class="text-center vertical">
                                                                                @if(auth::user()->id ==  $lich_cong_tac->lanh_dao_id && $item->thanh_phan_moi == 2)
                                                                                <a class="nguoi-du-hop xoa-du-hop-{{$item->id}} " data-id="{{$item->id}}" ><i class="fa fa-trash" aria-hidden="true" style="color: red"></i></a>
                                                                                @else
                                                                                -
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    @endif

                                                                    </tbody>
                                                                </table>

                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="box box-solid collapsed-box">
                                                    <div class="table-responsive box-panel">
                                                        <h3>
                                                            <a data-toggle="collapse" href="#y-kien9" class="color-black font-weight-bold">
                                                                <i class="fa fa-link"></i> Thành phần tham dự nhập ngoài
                                                                <i class="fa fa-plus pull-right"></i>

                                                            </a>

                                                        </h3>
                                                        <div id="y-kien9" class="panel-collapse collapse mt-2">
                                                            <textarea name="thanhphanthamdu" id=""  rows="4" class="form-control" placeholder="Nhập thành phần tham dự">{{isset($cuochop) ? $cuochop->thanh_phan_ben_ngoai : ''}}</textarea>
                                                            <br>
                                                            <div class="tham-du-ngoai">
                                                                <button name="luu_thanhphanthamdu" onclick="thanhPhanThamDuNgoai('thanhphanthamdu',{{$id}})" value="3312" class="btn btn-primary btn-sm pull-right" style="margin-bottom: 10px;">@if($cuochop && $cuochop->thanh_phan_ben_ngoai == null) Lưu lại @else Cập nhật @endif</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="col-md-12">
                                                <div class="modal fade" id="cuochoplienquan" role="dialog" aria-labelledby="exampleModalLabel">
                                                    <div class="modal-dialog" role="document" style="width: 86%;">
                                                        <div class="modal-content">
                                                            <form action="" method="post" autocomplete="off" class="form-horizontal">
                                                                <div class="modal-header" style="padding: 12px; background: #daf7f5;">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="exampleModalLabel">Cập nhật cuộc họp liên quan </h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="box-body">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-4 control-label">Bắt đầu</label>
                                                                                <div class="col-sm-8">
                                                                                    <input type="date" class="form-control datepic datemask" value="01/01/2021" name="ngaybatdau" placeholder="dd/mm/yyyy">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-4 control-label">Kết thúc</label>
                                                                                <div class="col-sm-8">
                                                                                    <input type="date" class="form-control datepic datemask" value="15/01/2021" name="ngayketthuc" placeholder="dd/mm/yyyy">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-4 control-label">Chủ trì</label>
                                                                                <div class="col-sm-8">
                                                                                    <select name="lanhdao_chutri" id="" class="form-control select2">
                                                                                        <option value="">-- Chọn lãnh đạo chủ trì --</option>
                                                                                        @foreach($nguoi_chu_tri as $data)
                                                                                            <option value="{{$data->id}}">{{$data->ho_ten}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-2 control-label"></label>
                                                                                <div class="col-sm-10">
                                                                                    <input type="text" class="form-control" name="ten_cuochop" placeholder="Tên lịch hợp">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <select name="nam_chutri" id="" class="form-control select2">
                                                                                <option value="2018" >Năm 2018</option>
                                                                                <option value="2019" >Năm 2019</option>
                                                                                <option value="2020" >Năm 2020</option>
                                                                                <option value="2021" selected>Năm 2021</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button type="button" name="timkiem" value="timkiem" data-id="{{$id}}" class="btn btn-primary btn-sm tim-kiem-cuoc-hop"><i class="fa fa-search"></i> Tìm kiếm</button>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <table id="dulieu_timkiem" class="table table-bordered table-hover ">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th class="text-center" width="10%">Chọn</th>
                                                                                        <th class="text-center" width="">Tên cuốc họp</th>
{{--                                                                                        <th class="text-center" width="15%">Chủ trì</th>--}}
{{--                                                                                        <th class="text-center" width="10%">Ngày họp</th>--}}
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody class="abcde">

                                                                                    </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-default btn-sm reset-cuoc-hop" data-dismiss="modal"><i class="fa fa-close"></i> Đóng lại</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="modal fade" id="thanhphanthamdu" role="dialog" aria-labelledby="exampleModalLabel">
                                                    <div class="modal-dialog" role="document" style="width: 92%;">
                                                        <div class="modal-content">
                                                            <form action="" method="post" autocomplete="off" class="form-horizontal">
                                                                <div class="modal-header" style="padding: 12px; background: #daf7f5;">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="exampleModalLabel">Cập nhật thành phần tham dự</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="box-body">
{{--                                                                        <div class="col-md-3">--}}
{{--                                                                            <div class="form-group">--}}
{{--                                                                                <div class="col-sm-12">--}}
{{--                                                                                    <select name="capnhatnhomcaobo" id="nhom-don-vi"  onchange="selectNhomDonViAppend()" class="form-control select2">--}}
{{--                                                                                        <option value="">-- Chọn nhóm phòng ban--</option>--}}
{{--                                                                                        @foreach($nhom_don_vi as $data)--}}
{{--                                                                                        <option value="{{$data->id}}">{{$data->ten_nhom_don_vi}}</option>--}}
{{--                                                                                        @endforeach--}}
{{--                                                                                    </select>--}}
{{--                                                                                </div>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <div class="col-sm-12">
                                                                                    <select name="phongban_capnhatthamdu" id="don-vi" onchange="selectDonViAppend()" class="form-control select2 nhom-don-vi">
                                                                                        <option value="">-- Chọn phòng ban --</option>
                                                                                        @foreach($donvi as $data)
                                                                                            <option value="{{$data->id}}">{{$data->ten_don_vi}}</option>
                                                                                        @endforeach

                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <div class="col-sm-12">
                                                                                    <select name="chucvu_capnhatthamdu" id="" class="form-control select2 chuc-vu">
                                                                                        <option value="">-- Chọn chức vụ --</option>
                                                                                        @foreach($chucVu as $data)
                                                                                            <option value="{{$data->id}}">{{$data->ten_chuc_vu}}</option>
                                                                                        @endforeach

                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <div class="col-sm-12">
                                                                                    <input type="text" class="form-control" name="hoten_capnhatthamdu" placeholder="Nhập họ tên tìm kiếm">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button type="button" name="timkiem_capnhatthamdu" value="timkiem_capnhatthamdu" onclick="timKiemNguoiDung({{$id}})" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Tìm kiếm</button>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <table id="dulieu_capnhatthamdu" class="table table-bordered table-hover">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th class="text-center" width="10%">Chọn</th>
                                                                                    <th class="text-center" width="">Tên cán bộ</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody class="dulieu_capnhatthamdu">

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-default btn-sm reset-cuoc-hop" data-dismiss="modal"><i class="fa fa-close"></i> Đóng lại</button>
{{--                                                                        <button type="submit" name="luu_thanhphanduhop" value="luu_thanhphanduhop" class="btn btn-primary btn-sm luu_capnhatthanhphanduhop"> Lưu lại</button>--}}
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="modal fade" id="xemlike" role="dialog" aria-labelledby="exampleModalLabel">
                                                    <div class="modal-dialog" role="document" style="width: 50%;">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="padding: 12px; background: #daf7f5;">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="exampleModalLabel">Cán bộ đã thích bình luận này</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="box-body danhsachlike">

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="col-md-12">
                                                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Đóng lại</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end thông tin lý lịch đảng viên-->
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/LichCongTac/app.js') }}"></script>
    <script>
        donVi='#don-vi';
        nhomdonvi='#nhom-don-vi';
        function selectDonViAppend() {
            let $this = $(donVi);
            var don_vi = $('[name=phongban_capnhatthamdu]').val();
            let arrId = $this.val;
            if (arrId) {
                //lấy danh sach cán bộ phối hơp
                $.ajax({
                    url: APP_URL + '/get-chuc-vu/' + don_vi,
                    type: 'GET',
                })
                    .done(function (response) {

                        var html = '<option value="">--Chọn chức vụ--</option>';
                        if (response.success) {
                            let selectAttributes = response.data.map((function (attribute) {
                                return `<option value="${attribute.id}" >${attribute.ten_chuc_vu}</option>`;
                            }));
                            $('.chuc-vu').html(html+ selectAttributes);
                        }
                    })
                    .fail(function (error) {
                        toastr['error'](error.message, 'Thông báo hệ thống');
                    });
            }

        }
        function selectNhomDonViAppend() {
            let $this = $(nhomdonvi);
            var don_vi = $('[name=capnhatnhomcaobo]').val();
            let arrId = $this.val;
            if (arrId) {
                //lấy danh sach cán bộ phối hơp
                $.ajax({
                    url: APP_URL + '/get-don-vi/' + don_vi,
                    type: 'GET',
                })
                    .done(function (response) {
                        console.log(response);
                        var html = '<option value="">--Chọn phòng ban--</option>';
                        if (response.success) {
                            let selectAttributes = response.data.map((function (attribute) {
                                return `<option value="${attribute.id}" >${attribute.ten_don_vi}</option>`;
                            }));
                            $('.nhom-don-vi').html(html+ selectAttributes);
                        }
                    })
                    .fail(function (error) {
                        toastr['error'](error.message, 'Thông báo hệ thống');
                    });
            }

        }
    </script>
@endsection
