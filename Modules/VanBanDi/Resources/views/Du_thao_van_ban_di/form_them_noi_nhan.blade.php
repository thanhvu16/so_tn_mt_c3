
<div class="modal fade" id="modal-them-noi-nhan">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('van_ban_di.them_don_vi_nhan') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i
                            class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</h4>
                </div>
                <div class="modal-body">
                        <div class="form-group col-md-12">
                            <label for="don_vi_nhan_ngoai" class="col-form-label">Đơn vị nhận ngoài thành phố</label><br>
                            <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                    class="form-control select2"
                                    multiple="multiple"
                                    data-placeholder=" Chọn đơn vị nhận ...">
                                @foreach ($emailNgoaiThanhPho as $emailngoai)
                                    <option value="{{ $emailngoai->id }}"
                                    >{{ $emailngoai->ten_don_vi}}</option>
                                @endforeach

                            </select>
                        </div>

                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>
