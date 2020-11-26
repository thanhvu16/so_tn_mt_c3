<form class="form-row"
      action="{{  route('du-thao-van-ban.store')}}"
      method="post" enctype="multipart/form-data" id="formCreateDoc">
    @csrf

    <div class=" col-md-3">
        <label for="exampleInputEmail2">Loại văn bản<span class="color-red">*</span></label>
        <select class="form-control" name="loai_van_ban_id" id="loai_van_ban_id" autofocus required>
            <option value="">Chọn loại văn bản</option>
            @foreach ($ds_loaiVanBan as $loaiVanBan)
                <option value="{{ $loaiVanBan->id }}" >{{ $loaiVanBan->ten_loai_van_ban }}</option>
            @endforeach
        </select>
    </div>
    <div class=" col-md-3">
        <label for="exampleInputEmail2">Cán bộ trong phòng góp ý<span class="color-red">*</span></label>
        <select name="lanh_dao_phong_phoi_hop[]" id="lanh_dao_phong_phoi_hop"
                class="form-control select2"
                multiple="multiple"
                required data-placeholder="Lãnh đạo phối hợp ...">
            @foreach ($lanhdaotrongphong as $trongphong)
                <option value="{{ $trongphong->id }}"
                    {{ isset($vanbandi) && $vanbandi->donvisoanthao_id == $donVi->ma_id ? 'selected' : '' }}>{{ $trongphong->ho_ten }}</option>
            @endforeach
        </select>
    </div>

    <div class=" col-md-3">
        <label for="exampleInputEmail2">Cán bộ Ủy ban góp ý <span class="color-red">*</span></label>
        <select name="lanh_dao_phong_khac[]" id="lanh_dao_phong_khac"
                class="form-control select2"
                multiple="multiple"
                required data-placeholder="Lãnh đạo phòng khác  ...">
            @foreach ($lanhdaokhac as $trongphong)
                <option value="{{ $trongphong->id }}"
                    {{ isset($vanbandi) && $vanbandi->donvisoanthao_id == $donVi->ma_id ? 'selected' : '' }}>{{ $trongphong->ho_ten }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="exampleInputEmail2">Ký hiệu <span class="color-red">*</span></label>
        <input type="text" class="form-control sokyhieu" placeholder="số kí hiệu..." name="so_ky_hieu">
    </div>
    <div class=" col-md-12  mt-1">
        <label for="exampleInputEmail2">Ý kiến <span class="color-red">*</span></label>
        <textarea rows="3" class="form-control" required placeholder="nội dung" name="y_kien"
                  type="text">{{ old('y_kien') }}</textarea>
    </div>
    <div class=" col-md-12  mt-1">
        <label for="exampleInputEmail2">Trích yếu <span class="color-red">*</span></label>
        <textarea rows="3" class="form-control" required placeholder="nội dung" name="vb_trich_yeu"
                  type="text">{{ old('vb_trich_yeu') }}</textarea>
    </div>
    <div class="col-md-3  mt-1">
        <label for="exampleInputEmail2">Người ký <span class="color-red">*</span></label>
        <select class="form-control dropdown-search layidnguoiky"  name="nguoi_ky" id="nguoi_ky" autofocus required>
            <option>--Chọn người ký--</option>
            @foreach ($ds_nguoiKy as $nguoiky)
                <option value="{{ $nguoiky->id }}"  data-chuc-vu ="{{ $nguoiky->chucvu->ten_chuc_vu ?? ''}}">{{ $nguoiky->ho_ten }}</option>
            @endforeach
        </select>
    </div>

    <div class=" col-md-3  mt-1">
        <label for="loai_van_ban_id" class="col-form-label">Chức vụ</label>
        <input type="text" class="form-control" name="chuc_vu" placeholder="Chức vụ......">
    </div>

    <div class=" col-md-3  mt-1">
        <label for="loai_van_ban_id" class="col-form-label">Ngày tháng</label>
        <input type="date" class="form-control" name="ngay_thang" placeholder="......">
    </div>
    <div class=" col-md-3  mt-1">
        <label for="vb_ngay_ban_hanh" class="col-form-label">Hạn xử lý</label>
        <input class="form-control" id="so_trang"
               value="" type="date"
               name="han_xu_ly">
    </div>
    <div class=" col-md-3 hidden">
        <label for="vb_ngay_ban_hanh" class="col-form-label">Số trang</label>
        <input class="form-control" id="so_trang"
               value="1" type="number"
               name="so_trang">
    </div>
    <div class="col-md-12">
        <div class=" row duthaovb">


            <div class="col-md-3  mt-1 ">
                <label for="sokyhieu" class="col-form-label">File trình ký</label>
                <div class="form-line input-group control-group">
                <input type="file" id="url-file" name="file_trinh_ky[]" class="form-control">
                </div>
            </div>
            <div class="col-md-3   mt-1">
                <label for="url-file" class="col-form-label">File phiếu trình</label>
                <div class="form-line input-group control-group">
                    <input type="file" id="url-file" name="file_phieu_trinh[]" class="form-control">
                </div>
            </div>




            <div class="col-md-3" style="margin-top: 37px;color: white">
                <a class="btn btn-primary btn-xs" onclick="duthaovanban()" role="button"
                   ><i class="fa fa-plus"></i>
                </a>
                <b class="text-danger"> Thêm file hồ sơ</b>
            </div>
            <div class=" col-md-3"  style="margin-top: 35px">
                <button type="submit"
                        class="btn btn-primary"><i class="fa fa-plus-square-o"></i>
                    <span>Tạo dự thảo</span></button>
            </div>
        </div>
    </div>
    <div class="col-md-12"><br></div>
    <div class="col-md-12"><br></div>

</form>

@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
