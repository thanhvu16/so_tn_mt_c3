@extends('admin::layouts.master')
@section('page_title', 'Loại Văn Bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cập nhật đơn vị</h3>
                    </div>
                    @include('vanbandi::Du_thao_van_ban_di.error')
                    <form action="{{route('loai-van-ban.update',$loaivanban->id)}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên loai văn bản</label>
                                    <input type="text" class="form-control" value="{{$loaivanban->ten_loai_van_ban}}" name="ten_loai_van_ban" id="exampleInputEmail1"
                                           placeholder="Tên loại văn bản" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Tên viết tắt</label>
                                    <input type="text" class="form-control" value="{{$loaivanban->ten_viet_tat}}" name="ten_viet_tat" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Mô tả</label>
                                    <input type="text" class="form-control" value="{{$loaivanban->mo_ta}}" name="mo_ta" id="exampleInputEmail3"
                                           placeholder="Mô tả" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Loại văn bản</label>
                                    <select name="loai_van_ban" class="form-control lay-so">
                                        <option value="" >Chọn loại văn bản</option>
                                        <option value="1" {{ isset($loaivanban) && $loaivanban->loai_van_ban == 1 ? 'selected' : '' }}>Áp dụng cho vb đến</option>
                                        <option value="2" {{ isset($loaivanban) && $loaivanban->loai_van_ban == 2 ? 'selected' : '' }}>Áp dụng cho vb đi</option>
                                        <option value="3" {{ isset($loaivanban) && $loaivanban->loai_van_ban == 3 ? 'selected' : '' }}>Dùng chung</option>
                                        <option value="4" {{ isset($loaivanban) && $loaivanban->loai_van_ban == 4 ? 'selected' : '' }}>Dùng riêng</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group @if($loaivanban->loai_don_vi == null) hidden @endif don-vi">
                                    <label>Đơn vị riêng</label>
                                    <select name="don_vi" class="form-control ">
                                        @foreach($donvi as $ds_dv)
                                            <option value="{{$ds_dv->id}}" {{ $loaivanban->loai_don_vi == $ds_dv->id  ? 'selected' : '' }}>{{$ds_dv->ten_don_vi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row clearfix"></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Năm trước SKH</label>
                                    <select name="nam_truoc_skh" class="form-control" required>
                                        <option value="1" {{ isset($loaivanban) && $loaivanban->nam_truoc_skh == 1 ? 'selected' : '' }}>Có</option>
                                        <option value="2" {{ isset($loaivanban) && $loaivanban->nam_truoc_skh == 2 ? 'selected' : '' }}>Không</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mã VB trong SKH</label>
                                    <select name="ma_van_ban" class="form-control" required>
                                        <option value="1" {{ isset($loaivanban) && $loaivanban->ma_van_ban == 1 ? 'selected' : '' }}>Có</option>
                                        <option value="2" {{ isset($loaivanban) && $loaivanban->ma_van_ban == 2 ? 'selected' : '' }}>Không</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mã PB trong SKH</label>
                                    <select name="ma_phong_ban" class="form-control" required>
                                        <option value="1" {{ isset($loaivanban) && $loaivanban->ma_phong_ban == 1 ? 'selected' : '' }}>Có</option>
                                        <option value="2" {{ isset($loaivanban) && $loaivanban->ma_phong_ban == 2 ? 'selected' : '' }}>Không</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mã ĐV trong SKH</label>
                                    <select name="ma_don_vi" class="form-control" required>
                                        <option value="1" {{ isset($loaivanban) && $loaivanban->ma_don_vi == 1 ? 'selected' : '' }}>Có</option>
                                        <option value="2" {{ isset($loaivanban) && $loaivanban->ma_don_vi == 2 ? 'selected' : '' }}>Không</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
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
    <script type="text/javascript">
        $('.lay-so').on('change', function (e) {
            var loaiso = $('[name=loai_van_ban]').val();
            if (loaiso == 4) {
                $('.don-vi').removeClass('hidden');
            } else {
                $('.don-vi').addClass('hidden');
            }

        })
    </script>
@endsection
