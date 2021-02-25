@extends('admin::layouts.master')
@section('page_title', 'Sửa giấy mời đến')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sửa giấy mời đến</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-row"
                              action="{{route('giay-moi-den.update',$vanban->id)}}"
                              method="post" enctype="multipart/form-data" id="formCreateDoc">
                            @csrf
                            @if (isset($vanban))
                                @method('PUT')
                            @endif
                            <div class="form-group col-md-3">
                                <label for="vb_so_den" class="col-form-label">Số đến giấy mời</label>
                                <input type="text" name="vb_so_den" class="form-control soden" id="vb_so_den"
                                       value="{{isset($vanban)? $vanban->so_den : $sodengiaymoi}}"
                                       style="font-weight: 800;color: #F44336;"
                                       placeholder="Số đến văn bản">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Số ký hiệu <span class="color-red">*</span></label>
                                <input type="text" name="vb_so_ky_hieu"
                                       value="{{ old('vb_so_ky_hieu', isset($vanban) ? $vanban->so_ky_hieu : '') }}" required autofocus
                                       class="form-control file_insert"
                                       id="sokyhieu"
                                       placeholder="Số ký hiệu">
                            </div>
                            <div class="form-group col-md-3" id="div_select_cqbh">
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Nơi gửi đến <span class="color-red">*</span></label>
                                <input type="text" value="{{isset($vanban)? $vanban->co_quan_ban_hanh : ''}}" class="form-control"
                                       name="co_quan_ban_hanh_id" required>

                            </div>

                            <div class="form-group col-md-3">
                                <label for="vb_ngay_ban_hanh" class="col-form-label">Ngày ban hành <span class="color-red">*</span></label>
                                <input class="form-control {{isset($vanban) ? '': 'giaymoitrung'}}" id="vb_ngay_ban_hanh"
                                       value="{{isset($vanban)? $vanban->ngay_ban_hanh : ''}}" required type="date"
                                       name="vb_ngay_ban_hanh">
                            </div>



                            <div class="form-group col-md-12" >
                                <label for="sokyhieu" class="col-form-label ">Trích yếu <span class="color-red">*</span></label>
                                <textarea rows="3" class="form-control" placeholder="nội dung" name="trich_yeu" required
                                          type="text">{{ old('trich_yeu', isset($vanban) ? $vanban->trich_yeu : '') }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Giờ họp <span class="color-red">*</span></label>

                                    <div class="input-group">
                                        <input type="text" name="gio_hop_chinh" value="{{$vanban->gio_hop}}" class="form-control timepicker">

                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label for="">Ngày họp <span class="color-red">*</span></label>
                                    <input type="date" required class="form-control ngaybanhanh2"
                                           value="{{isset($vanban)? $vanban->ngay_hop : ''}}"
                                           name="ngay_hop_chinh" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label for="">Địa điểm <span class="color-red">*</span></label>
                                    <input type="text" required class="form-control" value="{{isset($vanban)? $vanban->dia_diem : ''}}"
                                           name="dia_diem_chinh" placeholder="Địa điểm">
                                </div>
                            </div>


                            <div class="col-md-3 text-right {{isset($vanban) ? 'hidden': ''}}" style="margin-top: 40px">
                                <a class="btn btn-primary btn-xs" role="button" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"><i
                                        class="fa fa-plus"></i>
                                </a>
                                <b class="text-danger"> Hiển thị thêm nội dung</b>
                            </div>

                            <div class="form-group col-md-3 hidden" id="loaivanban">
                                <label for="loai_van_ban_id" class="col-form-label">Loại văn bản <span class="color-red">*</span></label>
                                <select class="form-control " name="loai_van_ban_id" id="loai_van_ban_id" readonly required>
                                    <option value="100"
                                    >Giấy mời
                                    </option>
                                </select>
                            </div>

                            <div class="clearfix"></div>


                            <div class="col-md-12 collapse @if($vanban) @if($vanban->noi_dung != null)show @endif @else in @endif "
                                 id="collapseExample">
                                <div class="col-md-12  gmoi layout3 ">
                                    <div class="row" style="margin-top:-15px;">
                                        <hr style="border: 0.5px solid #3c8dbc">
                                    </div>
                                    <div class="row" style="margin-top: -30px">
                                        <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px">
                                            <label for="detail-job">Nội dung họp <span class="color-red">*</span></label>
                                            <textarea name="noi_dung_hop_con[]" placeholder="nhập nội dung công việc" rows="3"
                                                      class="form-control no-resize noi-dung-chi-dao">{{ old('noi_dung_hop', isset($vanban) ? $vanban->noi_dung : '') }}</textarea>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 10px">
                                            <label>Giờ họp <span class="color-red">*</span></label>

                                            <div class="input-group">
                                                <input type="text" name="gio_hop_con[]" value="{{$vanban->gio_hop_phu}}" class="form-control timepicker">

                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4" style="margin-top: 10px">
                                            <div class="form-group">
                                                <label for="">Ngày họp</label>
                                                <input type="date" class="form-control"
                                                       value="{{ isset($vanban) ? $vanban->ngay_hop_phu : '' }}"
                                                       name="ngay_hop_con[]" placeholder="Nhập ngày họp">
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 10px">
                                            <div class="form-group">
                                                <label for="">Địa điểm</label>
                                                <input type="text" value="{{ isset($vanban) ? $vanban->dia_diem_phu : '' }}"
                                                       placeholder="Nhập địa điểm" class="form-control" name="dia_diem_con[]">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="input-group-btn text-right {{ isset($vanban) ? 'hidden' : '' }}">
        <span class="btn btn-primary" onclick="themgiaymoi('noi_dung_hop_con[]')" type="button">
                        <i class="fa fa-plus"></i> thêm nội dung</span>
                                </div>
                            </div>
                            <div class="col-sm-12" style="margin-top:-14px;margin-left: 0px;">
                                <hr style="border: 0.5px solid #3c8dbc">
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-3 hidden">
                                        <label for="sokyhieu" class="col-form-label ">Số văn bản</label>
                                        <select class="form-control  select-so-van-ban check-so-den-vb"
                                                data-don-vi="{{auth::user()->don_vi_id }}" name="so_van_ban_id" id="so_van_ban_id">
                                            <option
                                                value="100">Giấy mời
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <label for="sokyhieu" class="col-form-label">Người ký <span class="color-red">*</span></label>
                                <input type="text" class="form-control " value="{{isset($vanban) ? $vanban->nguoi_ky : ''}}" required
                                       name="nguoi_ky_id">
                            </div>
                            <div class="col-md-3" >
                                <label for="sokyhieu" class="col-form-label">Chức vụ <span class="color-red">*</span></label>
                                <input type="text" class="form-control " placeholder="nhập chức vụ"
                                       value="{{isset($vanban) ? $vanban->chuc_vu : ''}}" required name="chuc_vu">
                            </div>

                            <div class=" col-md-3 " >
                                <label for="vb_han_xu_ly" class="col-form-label">Hạn xử lý</label>
                                <input class="form-control" value="{{isset($vanban) ? $vanban->han_xu_ly : ''}}"
                                       name="vb_han_xu_ly" id="vb_han_xu_ly" type="date">
                                <input type="hidden" class="form-control" id="don_vi_id" name="don_vi_id" value="{{auth::user()->don_vi_id}}">
                            </div>
                            @hasanyrole('văn thư huyện')
                            <div class="col-md-3" >
                                <label for="vb_ngay_ban_hanh" class="col-form-label">Lãnh đạo tham mưu</label>
                                <select name="lanh_dao_tham_muu" class="form-control " id="">
                                    @foreach($nguoi_dung as $nguoidung)
                                        <option value="{{$nguoidung->id}}" {{$vanban->lanh_dao_tham_muu ? 'select' : ''}}>{{$nguoidung->ho_ten}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endrole

                            <div class="col-md-12" style="margin-top: 10px">
                                <div class="row increment ">
                                    <div class="col-md-3 ">
                                        <label for="sokyhieu" class="col-form-label">Tên tệp tin</label>
                                        <input class="form-control " value="{{ old('vb_so_ky_hieu', isset($vanban) ? $vanban->so_ky_hieu : '') }}" name="txt_file[]" type="text">
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
                                        <div class=" col-md-3 mt-4" >
                                            <button
                                                class="btn btn-danger" type="submit"><i class="fa fa-check mr-1"></i>
                                                <span>Cập nhật</span></button>
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-12"><br></div>
                            <div class="col-md-12"><br></div>
                            <div class="col-md-12"><br></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
