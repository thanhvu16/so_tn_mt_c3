<div class="form-group col-md-12">
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label for="so-den" class="col-form-label">Số đến</label>
                    </div>
                    <div class="col-md-8">
                        <input type="number"
                               class="form-control so-den"
                               value="{{Request:: get('so_den_start')}}"
                               name="so_den_start">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">&emsp;======></label>
                    </div>
                    <div class="col-md-8">
                        <input type="number"
                               class="form-control"
                               value="{{Request:: get('so_den_end')}}"
                               name="so_den_end">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">Ngày nhập</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group date">
                            <input type="text"
                                   name="ngay_den_start"
                                   value="{{ Request::get('ngay_den_start') }}"
                                   class="form-control datepicker ngay-den"
                                   placeholder="dd/mm/yyyy">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">&emsp;======></label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group date">
                            <input type="text"
                                   name="ngay_den_end"
                                   value="{{ Request::get('ngay_den_end') }}"
                                   class="form-control datepicker"
                                   placeholder="dd/mm/yyyy">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">Ngày ký</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group date">
                            <input type="text"
                                   name="ngay_ban_hanh_start"
                                   value="{{ Request::get('ngay_ban_hanh_start') }}"
                                   class="form-control datepicker"
                                   placeholder="dd/mm/yyyy">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">
                            &emsp;======></label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group date">
                            <input type="text"
                                   name="ngay_ban_hanh_end"
                                   value="{{ Request::get('ngay_ban_hanh_end') }}"
                                   class="form-control datepicker"
                                   placeholder="dd/mm/yyyy">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">Số Ký hiệu</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control"
                               value="{{ Request::get('so_ky_hieu') }}" name="so_ky_hieu">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">
                            &emsp;Người ký</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control"
                               value="{{ Request::get('nguoi_ky') }}" name="nguoi_ky">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">Loại văn bản</label>
                    </div>
                    <div class="col-md-8">
                        <select name="loai_van_ban_id" class="form-control select2-search select2">
                            <option value="">Chọn loại văn bản</option>
                                @foreach ($danhSachLoaiVanBan as $loaiVanBan)
                                <option
                                    value="{{ $loaiVanBan->id }}" {{ Request::get('loai_van_ban_id') == $loaiVanBan->id ? 'selected' : null }}>{{ $loaiVanBan->ten_loai_van_ban }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">
                            &emsp;Sổ văn bản</label>
                    </div>
                    <div class="col-md-8">
                        <select name="so_van_ban_id" class="form-control select2-search select2">
                            <option value="">Chọn sổ văn bản</option>
                                @foreach ($danhSachSoVanBan as $soVanBan)
                                    <option
                                        value="{{ $soVanBan->id }}" {{ Request::get('so_van_ban_id') == $soVanBan->id ? 'selected' : null }}>{{ $soVanBan->ten_so_van_ban }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">Đơn vị xử lý chính</label>
                    </div>
                    <div class="col-md-8">
                        <select name="don_vi_id" class="form-control select2-search select2">
                            <option value="">Chọn đơn vị</option>
                            @foreach ($danhSachDonViXuLy as $donVi)
                                <option
                                    value="{{ $donVi->id }}" {{ Request::get('don_vi_id') == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label">
                            &emsp;Đơn vị phối hợp</label>
                    </div>
                    <div class="col-md-8">
                        <select name="don_vi_phoi_hop_id" class="form-control select2-search select2">
                            <option value="">Chọn đơn vị</option>
                            @foreach ($danhSachDonViXuLy as $donVi)
                                <option
                                    value="{{ $donVi->id }}" {{ Request::get('don_vi_phoi_hop_id') == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <label class="col-form-label">Trích yếu</label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="trich_yeu" value="{{ Request::get('trich_yeu') }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <label class="col-form-label">Tóm tắt nội dung</label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="tom_tat" value="{{ Request::get('tom_tat') }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="margin-top: 5px">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <label class="col-form-label">Cơ quan ban hành</label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" value="{{ Request::get('co_quan_ban_hanh') }}" name="co_quan_ban_hanh" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2 text-right">
        <button type="submit" class="btn btn-primary" name="search" value="1"><i
                class="fa fa-search"></i>Tìm
            kiếm
        </button>

    </div>
    <div class="col-md-12">
        <br>
    </div>
</div>
