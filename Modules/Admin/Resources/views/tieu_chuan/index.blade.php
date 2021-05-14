<div class="col-md-12">
    <form role="form" action="{{route('tieu-chuan.store')}}" method="post" enctype="multipart/form-data"
          id="myform">
        @csrf
        <div class="box-body">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Tên tiêu chuẩn</label>
                    <input type="text" class="form-control" name="ten_tieu_chuan" id="exampleInputEmail1"
                           placeholder="Tên loại văn bản" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="exampleInputEmail2">số ngày</label>
                    <input type="text" class="form-control" name="so_ngay" id="exampleInputEmail2"
                           placeholder="Số ngày.." required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="exampleInputEmail3">Mô tả</label>
                    <input type="text" class="form-control" name="mo_ta" id="exampleInputEmail3"
                           placeholder="Mô tả" >
                </div>
            </div>

            <div class="col-md-3 mt-4">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </div>
        </div>
    </form>
</div>
@section('script')
    <script type="text/javascript">

    </script>
@endsection
