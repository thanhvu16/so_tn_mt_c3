<form
    action="{{ isset($email) ? route('email-don-vi-ngoai-he-thong.update', $email->id) : route('email-don-vi-ngoai-he-thong.store') }}"
    method="post" enctype="multipart/form-data" id="formCreateDoc">
    @csrf
    @if (isset($email))
        @method('PUT')
    @endif


    <div class="col-md-12">
        <div class="form-group col-md-3">
            <label for="vb_so_den" class="col-form-label">Tên đơn vị <span class="color-red">*</span></label>
            <input type="text" name="ten_Dv" autofocus class="form-control"
                   value="{{ isset($email) ? $email->ten_don_vi  : '' }}"
                   placeholder="Tên đơn vị" required>
        </div>
        <div class="form-group col-md-3">
            <label for="ngay_nhap" class="col-form-label">Mã định danh</label>
            <input type="text" name="ma_dinh_danh" value="{{isset($email) ? $email->ma_dinh_danh  : ''}}"
                   class="form-control" placeholder="Mã định danh">
        </div>
        <div class="form-group col-md-3">
            <label for="email" class="col-form-label">Email <span class="color-red">*</span></label>
            <input type="text" name="email" class="form-control" value="{{isset($email) ? $email->email  : ''}}"
                   placeholder="Email" required>
        </div>
        <div class="form-group col-md-3">
            <label for="dia_chi" class="col-form-label">Địa chỉ </label>
            <input type="text" name="dia_chi" id="dia_chi" class="form-control"
                   value="{{isset($email) ? $email->dia_chi  : ''}}"
                   placeholder="Địa chỉ">
        </div>
        <div class="form-group col-md-3">
            <label for="dien-thoai" class="col-form-label">Số điện thoại </label>
            <input type="text" name="sdt" class="form-control" value="{{isset($email) ? $email->sdt  : ''}}"
                   placeholder="Nhập sdt">
        </div>

        <div class="form-group col-md-3">
            <label for="web" class="col-form-label">Website </label>
            <input type="text" name="web" class="form-control" value="{{isset($email) ? $email->web  : ''}}"
                   placeholder="Email">
        </div>
        <div class="col-md-3">
            <div class="custom-control custom-checkbox">
                <label for=""></label><br> <br>
                <input type="checkbox" id="md_checkbox_27" name="accepted" value="1"
                       class="custom-control-input" {{ isset($email) && $email->accepted == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="md_checkbox_27">Cho phép gửi email</label>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group col-md-3">
            <button class="btn btn-primary" type="submit">{{ isset($email) ? 'Cập nhật':'Lưu' }}</button>
        </div>
    </div>

</form>
