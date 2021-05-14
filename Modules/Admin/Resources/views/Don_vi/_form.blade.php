<form role="form" action="{{route('don-vi.store')}}" method="post" enctype="multipart/form-data"
      id="myform">
    @csrf
    <div class="box-body">
        <div class="col-md-3 parent-id">
            <div class="form-group">
                <label for="exampleInputEmail1">Chọn đơn vị chủ quản</label>
                <select class="form-control select2" name="parent_id">
                    <option value="">Chọn đơn vị</option>
                    @foreach($donViCapXa as $donVi)
                        <option value="{{ $donVi->id }}">{{ $donVi->ten_don_vi }}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Đơn vị trực thuộc</label>
                <input type="text" class="form-control" name="ten_don_vi" id="exampleInputEmail1"
                       placeholder="Nhập tên đơn vị" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Nhóm đơn vị</label>
                <select class="form-control select2" name="nhom_don_vi">
                    @foreach($nhom_don_vi as $data)
                        <option value="{{$data->id}}">{{$data->ten_nhom_don_vi}}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail2">Tên viết tắt</label>
                <input type="text" class="form-control" name="ten_viet_tat" id="exampleInputEmail2"
                       placeholder="Tên viết tắt" >
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail3">Mã hành chính</label>
                <input type="text" class="form-control" name="ma_hanh_chinh" id="exampleInputEmail3"
                       placeholder="Mã hành chính" >
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" class="form-control" name="dia_chi"
                       placeholder="Địa chỉ" >
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label >Điện thoại</label>
                <input type="text" class="form-control" name="dien_thoai"
                       placeholder="Điện thoại" >
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label >Email</label>
                <input type="text" class="form-control" name="email"
                       placeholder="Email" >
            </div>
        </div>
        <input type="hidden" name="dieu_hanh" value="0" checked>
        <input type="hidden" name="check_parent" class="check_parent" value="1" checked>
        <div class="col-md-12 mt-2">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </div>
        </div>
    </div>
</form>
