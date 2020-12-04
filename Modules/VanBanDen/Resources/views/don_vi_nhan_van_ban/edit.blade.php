@extends('admin::layouts.master')
@section('page_title', 'Thêm văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vào sổ văn Bản Đến</h3>
                    </div>
                    <form role="form" action="{{route('van-ban-den.store')}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Loại văn bản <span style="color: red">(*)</span></label>
                                    <select class="form-control select2" autofocus name="loai_van_ban" required>
                                        <option value="">-- Chọn loại văn bản --</option>
                                        @foreach($loaivanban as $loaivanbands)
                                            <option value="{{ $loaivanbands->id }}"{{ $van_ban_den->vanbandi->loai_van_ban_id == $loaivanbands->id ? 'selected' : '' }}
                                            >{{ $loaivanbands->ten_loai_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Sổ văn bản <span style="color: red">(*)</span></label>
                                    <select class="form-control select2 check-so-den-vb" data-don-vi="{{auth::user()->id}}" name="so_van_ban" required>
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
                                    <input type="text" class="form-control " value="{{$van_ban_den->vanbandi->so_den}}" readonly name="so_den" id="exampleInputEmail3"
                                           placeholder="Số đến" style="font-weight: 800;color: #F44336;cursor: not-allowed;" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Số ký hiệu <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->vanbandi->so_ky_hieu}}" name="so_ky_hieu" id="exampleInputEmail4"
                                           placeholder="Số ký hiệu" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày ban hành <span style="color: red">(*)</span></label>
                                    <input type="date" class="form-control" value="{{$van_ban_den->vanbandi->ngay_ban_hanh}}" name="ngay_ban_hanh" id="exampleInputEmail5"
                                           placeholder="" required >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Cơ quan ban hành <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->vanbandi->dvSoanThao->ten_don_vi}}" name="co_quan_ban_hanh" id="exampleInputEmail6"
                                           placeholder="Cơ quan ban hành" required >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Người ký <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->vanbandi->nguoidung2->ho_ten}}" name="nguoi_ky" id="exampleInputEmail7"
                                           placeholder="Người ký" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Lãnh đạo tham mưu <span style="color: red">(*)</span></label>
                                    <select class="form-control select2"  name="lanh_dao_tham_muu" required>
                                        <option value="">-- Chọn lãnh đạo tham mưu --</option>
                                        @foreach($users as $nguoidung)
                                            <option value="{{ $nguoidung->id }}" >{{ $nguoidung->ho_ten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Trích yếu <span style="color: red">(*)</span></label>
                                    <textarea class="form-control" name="trich_yeu" rows="3" required>{{$van_ban_den->vanbandi->trich_yeu}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 text-right {{isset($van_ban_den->vanbandi) ? 'hidden': ''}}">
                                <a class="btn btn-primary " role="button" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"><i
                                        class="fa fa-plus"></i>
                                </a>
                                <b class="text-danger"> Hiển thị thêm nội dung</b>
                            </div>

                            <div class="col-md-12 collapse @if($van_ban_den->vanbandi) @if($van_ban_den->vanbandi->noi_dung != null)show @endif @else in @endif "
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
                                                      name="noi_dung[]">  </textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="han_giai_quyet" class="col-form-label">Hạn giải quyết</label>
                                            <div id="">
                                                <input type="date" class="form-control"
                                                       name="han_giai_quyet[]" value="">
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
                                            <option value="{{ $dokhands->id }}" {{ $nguoidung->id }}"  >{{ $dokhands->ten_muc_do }}</option>
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
                                            <option value="{{ $domatds->id }}" {{ $nguoidung->id }}" >{{ $domatds->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Hạn xử lý </label>
                                    <input type="date" class="form-control" value="{{$hangiaiquyet}}" name="han_xu_ly" placeholder="Hạn xử lý" required>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button
                                        class="btn btn-primary" type="submit"><i class="fa fa-plus-square-o mr-1"></i>
                                        <span>Thêm mới</span></button>
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
