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
                    <form role="form" action="{{route('van-ban-den.store')}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Loại văn bản <span style="color: red">*</span></label>
                                    <select class="form-control select2" autofocus name="loai_van_ban" required>
                                        <option value="">-- Chọn loại văn bản --</option>
                                        @foreach($loaivanban as $loaivanbands)
                                            <option value="{{ $loaivanbands->id }}" >{{ $loaivanbands->ten_loai_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Sổ văn bản <span style="color: red">*</span></label>
                                    <select class="form-control select2 check-so-den-vb" data-don-vi="{{auth::user()->don_vi_id}}" name="so_van_ban" required>
                                        <option value="">-- Chọn sổ văn bản --</option>
                                            @foreach($sovanban as $data)
                                                <option value="{{ $data->id }}" >{{ $data->ten_so_van_ban }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Số đến văn bản</label>
                                    <input type="text" class="form-control " readonly name="so_den" id="exampleInputEmail3"
                                           placeholder="Số đến" style="font-weight: 800;color: #F44336;cursor: not-allowed;" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Số ký hiệu <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="so_ky_hieu" id="exampleInputEmail4"
                                           placeholder="Số ký hiệu" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày ban hành <span style="color: red">*</span></label>
                                    <input type="date" class="form-control vanbantrung" name="ngay_ban_hanh" id="exampleInputEmail5"
                                           placeholder="" required >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Cơ quan ban hành <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="co_quan_ban_hanh" id="exampleInputEmail6"
                                           placeholder="Cơ quan ban hành" required >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Người ký <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="nguoi_ky" id="exampleInputEmail7"
                                           placeholder="Người ký" required>
                                </div>
                            </div>
                            @if(auth::user()->role_id == QUYEN_VAN_THU_HUYEN)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Lãnh đạo tham mưu <span style="color: red">*</span></label>
                                    <select class="form-control select2"  name="lanh_dao_tham_muu" required>
                                        @foreach($users as $nguoidung)
                                            <option value="{{ $nguoidung->id }}">{{ $nguoidung->ho_ten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Trích yếu <span style="color: red">*</span></label>
                                    <textarea class="form-control" name="trich_yeu" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <a class="btn btn-primary " role="button" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"><i
                                        class="fa fa-plus"></i>
                                </a>
                                <b class="text-danger"> Hiển thị thêm nội dung</b>
                            </div>

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
                                    <label for="exampleInputEmail4">Độ khẩn</label>
                                    <select class="form-control select2"  name="do_khan">
{{--                                        <option value="">-- Chọn độ khẩn --</option>--}}
                                        @foreach($dokhan as $dokhands)
                                            <option value="{{ $dokhands->id }}" >{{ $dokhands->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Độ Mật</label>
                                    <select class="form-control select2"  name="do_mat">
{{--                                        <option value="">-- Chọn độ mật--</option>--}}
                                        @foreach($domat as $domatds)
                                            <option value="{{ $domatds->id }}" >{{ $domatds->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Hạn xử lý </label>
                                    <input type="date" class="form-control" name="han_xu_ly" value="{{$hangiaiquyet}}" placeholder="Hạn xử lý" required>
                                </div>
                            </div>
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
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square-o mr-1"></i> Thêm mới</button>
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
@endsection
