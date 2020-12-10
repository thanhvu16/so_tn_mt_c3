
<div class="modal fade" id="modal-tra-lai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">#Trả lại công việc</h4>
            </div>
            <form action="{{ route('tra-lai-van-van.store') }}" method="POST">
                @csrf
                <input type="hidden" name="van_ban_den_don_vi_id" value="">
                <input type="hidden" name="type" value="">
                <div class="modal-body">
                    <div class="col-md-12 form-group">
                        <label for="noi-dung" class="control-label">Nội dung trả lại: <label
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
