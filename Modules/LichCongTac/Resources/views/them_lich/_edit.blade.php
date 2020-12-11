<form action="{{ isset($lichCongTac) ? route('lich-cong-tac.update', $lichCongTac->id) : route('lich-cong-tac.store') }}" method="post" autocomplete="off" class="form-horizontal">
    @csrf

    <div class="modal-header" style="padding: 12px; background: #daf7f5;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold" id="exampleModalLabel">#{{ isset($lichCongTac) ? 'Cập nhật ': 'Thêm ' }} lịch họp tuần</h4>
    </div>
    <div class="modal-body">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label">Nội dung <label class="required">*</label></label>
                    @if (isset($lichCongTac))
                        @if ($lichCongTac->type == 1)
                            <textarea class="form-control" rows="3" required="" name="noi_dung" placeholder="Nội dung">{{ !empty($lichCongTac->vanBanDi->noi_dung_hop) ? $lichCongTac->vanBanDi->noi_dung_hop : !empty($lichCongTac->vanBanDi->vb_trichyeu) ? $lichCongTac->vanBanDi->vb_trichyeu : null  }}</textarea>
                        @elseif($lichCongTac->type == 2)
                            <textarea class="form-control" rows="3" required="" name="noi_dung" placeholder="Nội dung">{{  $lichCongTac->noi_dung }}</textarea>
                        @else
                            <textarea class="form-control" rows="3" required="" name="noi_dung" placeholder="Nội dung">{{ !empty($lichCongTac->vanBanDenDonVi->noi_dung_hop) ? $lichCongTac->vanBanDenDonVi->noi_dung_hop :  !empty($lichCongTac->vanBanDenDonVi->vb_trich_yeu) ? $lichCongTac->vanBanDenDonVi->vb_trich_yeu : null  }}</textarea>
                        @endif
                    @else
                        <textarea class="form-control" rows="3" required="" name="noi_dung" placeholder="Nội dung">{{ isset($lichCongTac) ? $lichCongTac->noi_dung : '' }}</textarea>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-6">
                    <label for="" class="control-label">Ngày họp<label class="required">*</label></label>
                    <input type="date" required="" value="{{ isset($lichCongTac) ? $lichCongTac->ngay : date('d/m/Y') }}" class="form-control"
                           name="ngay">
                </div>


                <div class="col-sm-6">
                    <label class="control-label mb-1">Giờ họp <span class="color-red">*</span></label>
                    <div class="input-group">
                        <input  type="text" required class="form-control time-picker-24h" value="{{ isset($lichCongTac) ? $lichCongTac->gio : '' }}" name="gio">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    <!-- /.input group -->
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-6">
                    <label class="control-label">Địa điểm <span class="color-red">*</span></label>
                    @if (isset($lichCongTac))
                        @if ($lichCongTac->type == 1)
                            <input type="text" name="dia_diem" placeholder="Nhập địa điểm" class="form-control" value="{{ $lichCongTac->vanBanDi->dia_diem_hop ?? null }}" required>
                        @elseif($lichCongTac->type == 2)
                            <input type="text" name="dia_diem" placeholder="Nhập địa điểm" class="form-control" value="{{ $lichCongTac->dia_diem ?? null }}" required>
                        @else
                            <input type="text" name="dia_diem" placeholder="Nhập địa điểm" class="form-control" value=" {{ $lichCongTac->vanBanDenDonVi->dia_diem_chinh ?? null }}" required>
                        @endif
                    @else
                        <input type="text" name="dia_diem" placeholder="Nhập địa điểm" class="form-control" value="{{ isset($lichCongTac) ? $lichCongTac->dia_diem : null }}" required>
                    @endif
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Lãnh đạo dự họp<span class="color-red">*</span></label>
                    <br>
                    <select name="lanh_dao_id" class="form-control select2">
                        <option value="">Chọn lãnh đạo</option>
                        @forelse($danhSachLanhDao as $lanhdao)
                            <option
                                value="{{ $lanhdao->id }}" {{ isset($lichCongTac) && $lichCongTac->lanh_dao_id == $lanhdao->id ? 'selected' : null }}>{{ $lanhdao->ho_ten }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="radio-info form-check-inline">
                        <input type="radio" name="trang_thai_lich" id="chinh-thuc{{ isset($lichCongTac) ? $lichCongTac->id : null }}"  value="1" checked {{ isset($lichCongTac) && $lichCongTac->trang_thai_lich == 1 ? 'checked' : '' }}>
                        <label for="chinh-thuc{{ isset($lichCongTac) ? $lichCongTac->id : null }}">Lịch chính thức</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="radio-info form-check-inline">
                        <input type="radio" name="trang_thai_lich" id="lich-hoan{{ isset($lichCongTac) ? $lichCongTac->id : null }}"  value="2" {{ isset($lichCongTac) && $lichCongTac->trang_thai_lich == 2 ? 'checked' : '' }}>
                        <label for="lich-hoan{{ isset($lichCongTac) ? $lichCongTac->id : null }}">Lịch hoãn</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="radio-info form-check-inline">
                        <input type="radio" name="trang_thai_lich" id="lich-dieu-chinh{{ isset($lichCongTac) ? $lichCongTac->id : null }}"  value="3" {{ isset($lichCongTac) && $lichCongTac->trang_thai_lich == 3 ? 'checked' : '' }}>
                        <label for="lich-dieu-chinh{{ isset($lichCongTac) ? $lichCongTac->id : null }}">Lịch điều chỉnh</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="radio-info form-check-inline">
                        <input type="radio" name="trang_thai_lich" id="lich-phat-sinh{{ isset($lichCongTac) ? $lichCongTac->id : null }}"  value="4" {{ isset($lichCongTac) && $lichCongTac->trang_thai_lich == 4 ? 'checked' : '' }}>
                        <label for="lich-phat-sinh{{ isset($lichCongTac) ? $lichCongTac->id : null }}">Lịch phát sinh</label>
                    </div>
                </div>
            </div>
            <div class="row trang-thai-lich {{ isset($lichCongTac) && ($lichCongTac->trang_thai_lich == 2 || $lichCongTac->trang_thai_lich == 3) ? 'show' : 'hide' }}">
                <div class="col-sm-12">
                    <label class="control-label">Ghi chú <span class="color-red">*</span></label>
                    <textarea name="ghi_chu" rows="4" class="form-control noi-dung-ghi-chu" placeholder="ghi chú">{{ isset($lichCongTac) ? $lichCongTac->ghi_chu : '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm" data-original-title="" title=""><i class="fa fa-save"></i> Lưu lại</button>
            <button type="button" class="btn btn-default btn-sm border" data-dismiss="modal" data-original-title="" title=""><i class="fa fa-close"></i> Đóng lại</button>
        </div>
    </div>
</form>
