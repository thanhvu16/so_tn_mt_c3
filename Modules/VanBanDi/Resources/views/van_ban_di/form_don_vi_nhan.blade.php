<div class="modal fade" id="modal-chuyen-van-ban" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">#Chuyển văn bản</h4>
            </div>
            <form action="{{ route('van_ban_di.gui_mail_di_don_vi') }}" method="POST" id="form-tao-phieu-chuyen-van-ban">
                @csrf
                <input type="hidden" name="van_ban_den_don_vi_id" value="">
                <div class="modal-body">
                    <div class="col-md-12 form-group">
                        <label for="don-vi-nhan">Chọn đơn vị nhận trong TP <label
                                class="required">*</label></label>
                        <select name="don_vi_id[]"
                                class="form-control select2-search"
                                data-placeholder="Chọn đơn vị nhận" multiple>
                            <option value="">Chọn đơn vị nhận</option>
                            @forelse($danhSachDonVi as $donVi)
                                <option
                                    value="{{ $donVi->id }}">{{ $donVi->ten_don_vi }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
{{--                    <div class="col-md-12 form-group">--}}
{{--                        <label for="noi-dung" class="control-label">Nơi nhận<label--}}
{{--                                class="required">*</label> <br>--}}
{{--                            <small><i>(Nội dung cách nhau bởi dấu ";")</i></small>--}}
{{--                        </label>--}}
{{--                        <textarea class="form-control" name="noi_dung" rows="5"--}}
{{--                                  placeholder="Ví dụ: Như trên; Đ/c PCT TT Nguyễn Văn Sửu (để báo cáo); Đ/c PCT Nguyễn Thế Hùng (để báo cáo); ...vv" required></textarea>--}}
{{--                    </div>--}}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gửi</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>
