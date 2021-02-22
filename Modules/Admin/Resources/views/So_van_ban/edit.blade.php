@extends('admin::layouts.master')
@section('page_title', 'Sổ Văn Bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cập nhật sổ văn bản</h3>
                    </div>
                    <form action="{{route('so-van-ban.update',$sovanban->id)}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên sổ văn bản</label>
                                    <input type="text" class="form-control" value="{{$sovanban->ten_so_van_ban}}" name="ten_so_van_ban" id="exampleInputEmail1"
                                           placeholder="Tên sổ văn bản" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Tên viết tắt</label>
                                    <input type="text" class="form-control" value="{{$sovanban->ten_viet_tat}}" name="ten_viet_tat" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Mô tả</label>
                                    <input type="text" class="form-control" value="{{$sovanban->mo_ta}}" name="mo_ta" id="exampleInputEmail3"
                                           placeholder="Mô tả" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Loại Sổ</label>
                                    <select name="loai_so" class="form-control lay-so">
                                        <option value="">Chọn loại sổ</option>
                                        <option value="1" {{ isset($sovanban) && $sovanban->loai_so == 1 ? 'selected' : '' }}>Sổ đến</option>
                                        <option value="2" {{ isset($sovanban) && $sovanban->loai_so == 2 ? 'selected' : '' }}>Sổ đi</option>
                                        <option value="3" {{ isset($sovanban) && $sovanban->loai_so == 3 ? 'selected' : '' }}>Sổ dùng chung</option>
                                        <option value="4" {{ isset($sovanban) && $sovanban->loai_so == 4 ? 'selected' : '' }}>Sổ riêng</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group @if($sovanban->so_don_vi == null) hidden @endif don-vi">
                                    <label>Loại Sổ</label>
                                    <select name="don_vi" class="form-control ">
                                        <option value="">Chọn đơn vị</option>
                                        @foreach($donvi as $ds_dv)
                                            <option value="{{$ds_dv->id}}"  {{ $sovanban->so_don_vi == $ds_dv->id && $sovanban->so_don_vi != null ? 'selected' : '' }}>{{$ds_dv->ten_don_vi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group @if($sovanban->so_don_vi == null) hidden @endif don-vi">
                                    <label>Sổ áp dụng</label>
                                    <select name="ap_dung" class="form-control ">
                                        <option value="2" {{ $sovanban->type == 2 && $sovanban->type != null ? 'selected' : '' }}>Áp dụng cho sổ đi</option>
                                        <option value="1" {{ $sovanban->type == 1 && $sovanban->type != null ? 'selected' : '' }}>Áp dụng cho sổ đến</option>
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
            var loaiso = $('[name=loai_so]').val();
            if (loaiso == 4) {
                $('.don-vi').removeClass('hidden');
            } else {
                $('.don-vi').addClass('hidden');
            }

        })
    </script>
@endsection
