@extends('admin::layouts.master')
@section('page_title', 'Sửa văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Văn Bản Đến</h3>
                    </div>
                    <form role="form" action="{{route('van-ban-den.update',$van_ban_den->id)}}" method="post"
                          enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Loại văn bản <span
                                            style="color: red">(*)</span></label>
                                    <select class="form-control select2" autofocus name="loai_van_ban" required>
                                        <option value="">-- Chọn loại văn bản --</option>
                                        @foreach($loaivanban as $loaivanbands)
                                            <option
                                                value="{{ $loaivanbands->id }}"{{ $van_ban_den->loai_van_ban_id == $loaivanbands->id ? 'selected' : '' }}
                                            >{{ $loaivanbands->ten_loai_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 hidden">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Sổ văn bản <span
                                            style="color: red">(*)</span></label>
                                    <select class="form-control select2 check-so-den-vb"
                                            data-don-vi="{{auth::user()->don_vi_id}}" name="so_van_ban" required>
                                        <option value="">-- Chọn sổ văn bản --</option>
                                        @foreach($sovanban as $data)
                                            <option
                                                value="{{ $data->id }}" {{ $van_ban_den->so_van_ban_id == $data->id ? 'selected' : '' }}>{{ $data->ten_so_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Số đến văn bản</label>
                                    <input type="text" class="form-control " value="{{$van_ban_den->so_den}}"
                                           name="so_den" id="exampleInputEmail3"
                                           placeholder="Số đến" style="font-weight: 800;color: #F44336">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Cơ quan ban hành <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->co_quan_ban_hanh}}"
                                           name="co_quan_ban_hanh" id="exampleInputEmail6"
                                           placeholder="Cơ quan ban hành" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Số ký hiệu <span
                                            style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->so_ky_hieu}}"
                                           name="so_ky_hieu" id="exampleInputEmail4"
                                           placeholder="Số ký hiệu" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày ban hành <span
                                            style="color: red">(*)</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control datepicker"
                                               value="{{ !empty($van_ban_den->ngay_ban_hanh) ? formatDMY($van_ban_den->ngay_ban_hanh) : null }}"
                                               name="ngay_ban_hanh" id="exampleInputEmail5"
                                               placeholder="dd/mm/yyyy" required>
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if(auth::user()->role_id == QUYEN_VAN_THU_HUYEN && count($users) > 0)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail4">Lãnh đạo tham mưu <span
                                                style="color: red">(*)</span></label>
                                        <select class="form-control select2" name="lanh_dao_tham_muu" required>
                                            <option value="">-- Chọn lãnh đạo tham mưu --</option>
                                            @foreach($users as $nguoidung)
                                                <option
                                                    value="{{ $nguoidung->id }}" {{ $van_ban_den->lanh_dao_tham_muu == $nguoidung->id ? 'selected' : '' }}>{{ $nguoidung->ho_ten }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Trích yếu <span
                                            style="color: red">(*)</span></label>
                                    <textarea class="form-control" name="trich_yeu" rows="3"
                                              required>{{$van_ban_den->trich_yeu}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Người ký <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->nguoi_ky}}"
                                           name="nguoi_ky" id="exampleInputEmail7"
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
                                        <input type="text" class="form-control han-xu-ly datepicker ngay-nhan" name="ngay_nhan"  value="{{ date('d/m/Y', strtotime($van_ban_den->ngay_nhan)) }}" placeholder="Ngày nhận" >
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
                                        <input type="text" class="form-control datepicker"
                                               value="{{ !empty($van_ban_den->han_xu_ly) ? formatDMY($van_ban_den->han_xu_ly) : null }}"
                                               name="han_xu_ly" placeholder="Hạn xử lý" required>
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix"></div>
                            <div class="col-md-12 text-right {{isset($van_ban_den) ? 'hidden': ''}}">
                                <a class="btn btn-primary " role="button" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"><i
                                        class="fa fa-plus"></i>
                                </a>
                                <b class="text-danger"> Hiển thị thêm nội dung</b>
                            </div>

                            <div
                                class="col-md-12 collapse @if($van_ban_den) @if($van_ban_den->noi_dung != null)show @endif @else in @endif "
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
                                                      name="noi_dung[]">  {{$van_ban_den->noi_dung}}</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="han_giai_quyet" class="col-form-label">Hạn giải quyết</label>
                                            <div id="">
                                                <input type="date" class="form-control"
                                                       name="han_giai_quyet[]" value="{{$van_ban_den->han_giai_quyet}}">
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
                                    <label for="exampleInputEmail4">Độ khẩn</label>
                                    <select class="form-control select2" name="do_khan">
                                        {{--                                        <option value="">-- Chọn độ khẩn --</option>--}}
                                        @foreach($dokhan as $dokhands)
                                            <option
                                                value="{{ $dokhands->id }}" {{ $van_ban_den->do_khan == $dokhands->id ? 'selected' : '' }} >{{ $dokhands->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Độ Mật</label>
                                    <select class="form-control select2" name="do_mat">
                                        {{--                                        <option value="">-- Chọn độ mật--</option>--}}
                                        @foreach($domat as $domatds)
                                            <option
                                                value="{{ $domatds->id }}" {{ $van_ban_den->do_mat == $domatds->id ? 'selected' : '' }} >{{ $domatds->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="exampleInputEmail4">File</label>
                                <input type="file" class="form-control han-xu-ly" name="File" value="">
                            </div>
                            <div class="row clearfix"></div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="">File văn bản:
                                        @forelse($van_ban_den->vanBanDenFile as $key=>$filedata)
                                            <a class="seen-new-window" target="popup"
                                               href="{{$filedata->getUrlFile()}}">[File văn bản] &emsp; </a> <a
                                                class="btn-remove-item" href="{{route('xoaFileDen',$filedata->id)}}"><i
                                                    class="fa fa-trash" aria-hidden="true" style="color: red"></i></a> |
                                        @empty
                                        @endforelse</label>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-group">
                                    <button
                                        class="btn btn-danger" type="submit"><i class="fa fa-check mr-1"></i>
                                        <span>Cập nhật</span></button>
                                </div>
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
@endsection
