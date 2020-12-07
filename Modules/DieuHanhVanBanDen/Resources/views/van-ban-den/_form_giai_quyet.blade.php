@if (Request::get('type') && Request::get('type') == 'cv_phoi_hop' && empty($vanBanDen->chuyenVienphoiHopGiaiQuyet))
    <div class="col-md-12 mt-4">
        <div class="row mt-2">
            <form action="{{ route('phoi_hop_giai_quyet.store') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="van_ban_den_id" value="{{ $vanBanDen->id }}">
                <input type="hidden" name="type" value="2">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-noi-dung">Nội dung <span class="color-red">*</span></label>
                        <textarea name="noi_dung"
                                  placeholder="nhập nội dung kết quả"
                                  cols="5" rows="3"
                                  class="form-control noi-dung-cong-viec"
                                  aria-required="true" required=""></textarea>
                    </div>
                </div>
                <div class="col-md-6">
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
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-sm btn-primary waves-effect text-uppercase">
                        <i class="fa fa-save"></i> Lưu
                    </button>
                    <a title="hủy" class="btn btn-default go-back btn-sm"><i class="fa fa-arrow-left"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>

@elseif(Request::get('type') && Request::get('type') == 'phoi_hop' && empty($vanBanDen->giaiQuyetPhoiHopHoanThanh()))
    <div class="col-md-12 mt-4">
        <div class="row mt-2">
            <form action="{{ route('phoi_hop_giai_quyet.store') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="van_ban_den_id" value="{{ $vanBanDen->id }}">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-noi-dung">Nội dung <span class="color-red">*</span></label>
                        <textarea name="noi_dung"
                                  placeholder="nhập nội dung kết quả"
                                  cols="5" rows="3"
                                  class="form-control noi-dung-cong-viec"
                                  aria-required="true" required=""></textarea>
                    </div>
                </div>
                <div class="col-md-6">
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
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-sm btn-primary waves-effect text-uppercase">
                        <i class="fa fa-save"></i> Lưu
                    </button>
                    <a title="hủy" class="btn btn-default go-back btn-sm"><i class="fa fa-arrow-left"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
@else
    @if (!empty(Request::get('xuly')) && Request::get('xuly') == true)
        @if (count($vanBanDen->giaiQuyetVanBan) > 0 || (empty($vanBanDen->giaiQuyetVanBanTraLai()) || !empty($vanBanDen->giaiQuyetVanBanTraLai())))
            <div class="col-md-12 mt-4">
                <label>
                    <input type="radio" name="status_action" id="van-ban-tra-loi" value="1">
                    <b>Văn bản cần trả lời</b>
                </label>
                &nbsp;
                &nbsp;
                <label>
                    <input type="radio" name="status_action" id="van-ban-luu" value="2">
                    <b>Văn bản lưu</b>
                </label>

                <div class="row mt-2 truc-tiep-giai-quyet hide">
                    <form action="{{ route('giai-quyet-van-ban.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="van_ban_den_id" value="{{ $vanBanDen->id }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-noi-dung">Nhập kết quả <span class="color-red">*</span></label>
                                <textarea name="noi_dung"
                                          placeholder="nhập kết quả.."
                                          cols="5" rows="3"
                                          class="form-control noi-dung-cong-viec"
                                          aria-required="true" required="">Lưu văn bản</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-sm btn-primary waves-effect text-uppercase">
                                <i class="fa fa-save"></i> Lưu
                            </button>
                            <a title="hủy" class="btn btn-default go-back btn-sm"><i class="fa fa-arrow-left"></i>
                                Hủy</a>
                        </div>
                    </form>
                </div>
                <div class="row form-du-thao hide">
                    <div class="col-md-12">
                        <h5 class="text-bold">Tạo văn bản dự thảo</h5>
                        @include('vanbandi::Du_thao_van_ban_di.form_du_thao')
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif
