<div class="modal fade" id="modal-de_xuat_gia_han" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">#Gia hạn thêm thời gian giải quyết</h4>
            </div>
            <form action="{{ route('gia-han-cong-viec.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="cong_viec_don_vi_id" value="">
                    <div class="col-12 form-group">
                        <label for="han-xu-ly-cu" class="col-form-label">Hạn công việc</label>
                        <div>
                            <input type="date" name="han_cu" id="han-xu-ly-cu"
                                   value=""
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-12 form-group">
                        <label for="thoiHan" class="col-form-label">Thời hạn đề xuất <label
                                class="required">*</label></label>
                        <input type="date" name="thoi_han_de_xuat" id="thoiHan"
                               placeholder="Chọn thời hạn công việc.."
                               value="" class="form-control" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="noi-dung" class="control-label">Nội dung giải trình: <label
                                class="required">*</label></label>
                        <textarea class="form-control" id="noi-dung" name="noi_dung" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gửi</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>
