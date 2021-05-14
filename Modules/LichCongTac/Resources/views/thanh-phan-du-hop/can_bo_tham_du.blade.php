<form action="{{ route('tham-du-cuoc-hop.store') }}" method="post" class="form-can-bo-tham-du">
    @csrf
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Cập nhật cán bộ tham dự cuộc họp</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                @if (!empty($canbophong))
                    @foreach ($canbophong as $cb)
                        <div class="col-sm-6">
                            <div class="form-group customize-checkbox">
                                <input type="checkbox" id="md_checkbox_{{ $cb->id }}" name="user_id[]"
                                       value="{{ $cb->id }}"
                                       class="filled-in chk-col-light-blue" {{ in_array($cb->id, $thanhPhanDuHop->pluck('user_id')->toArray()) ? 'checked' : '' }}>
                                <label for="md_checkbox_{{ $cb->id }}">{{ $cb->ho_ten }}</label>
                            </div>
                        </div>
                    @endforeach
                @endif
                <input type="hidden" name="lich_cong_tac_id" value="{{ $id }}">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu lại
            </button>
        </div>
    </div>
</form>
