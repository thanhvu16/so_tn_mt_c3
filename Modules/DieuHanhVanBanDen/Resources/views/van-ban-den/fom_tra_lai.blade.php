<div class="modal fade" id="modal-tra-lai">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('van-ban-tra-lai.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
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
                        <div class="col-md-12">
                            <div class="increment">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="ten_file">Tên tệp</label>
                                        <input type="text" class="form-control pho-phong-file"
                                               name="txt_file[]" value=""
                                               placeholder="Nhập tên file...">
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label>Chọn tệp tin</label>
                                        <div class="form-line input-group control-group">
                                            <input type="file" name="ten_file[]"
                                                   class="form-control">
                                            <div class="input-group-btn">
                                            <span class="btn btn-info"
                                                  onclick="multiUploadFile('ten_file[]')"
                                                  type="button">
                                                <i class="fa fa-plus"></i> thêm</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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


