@extends('admin::layouts.master')
@section('page_title', 'Loại Văn Bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thêm đơn vị</h3>
                    </div>
                    <form role="form" action="{{route('loai-van-ban.store')}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên loai văn bản</label>
                                    <input type="text" class="form-control" name="ten_loai_van_ban" id="exampleInputEmail1"
                                           placeholder="Tên loại văn bản" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Tên viết tắt</label>
                                    <input type="text" class="form-control" name="ten_viet_tat" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Mô tả</label>
                                    <input type="text" class="form-control" name="mo_ta" id="exampleInputEmail3"
                                           placeholder="Mô tả" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Loại văn bản</label>
                                    <select name="loai_so" class="form-control lay-so">
                                        <option value="">Chọn loại văn bản</option>
                                        <option value="1">Áp dụng cho vb đến</option>
                                        <option value="2">Áp dụng cho vb đi</option>
                                        <option value="3">Dùng chung</option>
                                        <option value="4">Dùng riêng</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group hidden don-vi">
                                    <label>Đơn vị riêng</label>
                                    <select name="don_vi" class="form-control ">
                                        @foreach($donvi as $ds_dv)
                                            <option value="{{$ds_dv->id}}">{{$ds_dv->ten_don_vi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
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
