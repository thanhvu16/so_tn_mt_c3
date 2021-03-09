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
                    <form action="{{route('tieu-chuan.update',$tieuChuan->id)}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên tiêu chuẩn</label>
                                    <input type="text" class="form-control" value="{{$tieuChuan->ten_tieu_chuan}}" name="ten_tieu_chuan" id="exampleInputEmail1"
                                           placeholder="Tên loại văn bản" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Số ngày</label>
                                    <input type="text" class="form-control" value="{{$tieuChuan->so_ngay}}" name="so_ngay" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Mô tả</label>
                                    <input type="text" class="form-control" value="{{$tieuChuan->mo_ta}}" name="mo_ta" id="exampleInputEmail3"
                                           placeholder="Mô tả" required>
                                </div>
                            </div>

                            <div class="col-md-3 mt-4">
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
