<form role="form" action="{{ isset($ngayNghi) ? route('ngay-nghi.update', $ngayNghi->id) : route('ngay-nghi.store') }}" method="post" enctype="multipart/form-data"
      id="myform">
    @csrf
    @if(isset($ngayNghi))
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="col-md-3">
                <label>Tên ngày nghỉ @include('admin::required')</label>
                <input type="text" class="form-control" name="ten_ngay_nghi" value="{{ isset($ngayNghi) ? $ngayNghi->ten_ngay_nghi : null }}"
                       placeholder="Nhập tên ngày nghỉ ..." required>
        </div>
        <div class="col-md-3">
                <label>Mô tả</label>
                <input type="text" class="form-control" name="mo_ta"
                       value="{{ isset($ngayNghi) ? $ngayNghi->mo_ta : null }}"
                       placeholder="Mô tả...">
        </div>
        <div class="col-md-3">
                <label>Ngày nghỉ @include('admin::required')</label>
                <input type="date" class="form-control" name="ngay_nghi"
                       value="{{ isset($ngayNghi) ? $ngayNghi->ngay_nghi : null }}"
                       required>
        </div>
        <div class="col-md-3">
            <label class="col-form-label" for="trang_thai">Trạng thái</label>
            <br>
            <label>
                <input type="radio" name="trang_thai" class="flat-red" value="1"
                    {{ isset($ngayNghi) && $ngayNghi->trang_thai == 1 ? 'checked' : 'checked' }}> Hoạt động
            </label>
            &nbsp;
            <label>
                <input type="radio" name="trang_thai" class="flat-red" value="2"
                    {{ isset($ngayNghi) && $ngayNghi->trang_thai == 2 ? 'checked' : '' }}
                > Tạm khóa
            </label>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3 text-left" style="margin-top: 20px">
            <div class="form-group">
                <button type="submit" class="btn btn-primary"> {{ isset($ngayNghi) ? 'Cập nhật' : 'Thêm mới' }} </button>
            </div>
        </div>
    </div>
</form>
