<div class="modal fade" id="modal-de_xuat_gia_han" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog    " role="document">
        <div class="modal-content">
            <form action="{{ route('gia-han-van-ban.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-bold" id="exampleModalLabel">#Gia hạn thêm thời gian giải quyết</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="van_ban_den_id" value="">
                        <div class="col-md-12 form-group">
                            <label for="han-xu-ly-cu" class="col-form-label">Hạn văn bản</label>
                            <div>
                                <input type="text" name="thoi_han_cu" id="han-xu-ly-cu"
                                       value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="thoiHan" class="col-form-label">Thời hạn đề xuất <label
                                    class="required">*</label></label>
                            <input type="date" name="thoi_han_de_xuat" id="thoiHan"
                                   placeholder="Chọn thời hạn công việc.."
                                   value="" class="form-control"   required>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="noi-dung" class="control-label">Nội dung giải trình: <label
                                    class="required">*</label></label>
                            <textarea class="form-control" id="noi-dung" name="noi_dung" rows="5" required></textarea>
                        </div>
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

<div class="modal fade" id="modal-tra-lai">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('van-ban-tra-lai.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-bold" id="exampleModalLabel">#Trả lại văn bản</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="van_ban_den_id" value="">
                        <input type="hidden" name="active" value="{{ isset($active) ? $active : null }}">
                        <input type="hidden" name="type" value="">
                        <div class="col-md-12 form-group">
                            <label for="noi-dung" class="control-label">Nội dung trả lại: <label
                                    class="color-red">*</label></label>
                            <textarea class="form-control" id="noi-dung" name="noi_dung" rows="5" required></textarea>
                        </div>
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


