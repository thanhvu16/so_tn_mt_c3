<div class="modal fade" id="modal-them-noi-nhan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">#Thêm đơn vị </h4>
            </div>
            <form action="{{ route('van_ban_di.them_don_vi_nhan') }}" method="POST">
                @csrf
                <input type="hidden" name="van_ban_di_id" value="">
                <div class="modal-body">
                    <div class="col-md-12 form-group">
                        <div class="form-group col-md-12">
                            <label for="don-vi-nhan" class="col-form-label">Đơn vị nhận trong thành phố</label>
                            <select name="don_vi_nhan_trong_thanh_pho[]" id="don-vi-nhan"
                                    class="form-controlmultiple-select select2-search"
                                    multiple
                                    data-placeholder=" Chọn đơn vị nhận ...">
                                @foreach ($emailTrongThanhPho as $email)
                                    <option value="{{ $email->id }}"
                                    >{{ $email->ten_don_vi}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="don_vi_nhan_ngoai" class="col-form-label">Đơn vị nhận ngoài thành phố</label>
                            <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                    class="form-controlmultiple-select select2-search"
                                    multiple
                                    data-placeholder=" Chọn đơn vị nhận ...">
                                @foreach ($emailNgoaiThanhPho as $emailngoai)
                                    <option value="{{ $emailngoai->id }}"
                                    >{{ $emailngoai->ten_don_vi}}</option>
                                @endforeach

                            </select>
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
