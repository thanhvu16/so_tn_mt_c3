@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-3">Tạo văn bản đi1</h4>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">
                                <form class="form-row"
                                      action="{{route('suavanban')}}"
                                      method="post" enctype="multipart/form-data" id="formCreateDoc">
                                    @csrf

                                    <div class="form-group col-md-3" id="loaivanban">
                                        <label for="linhvuc_id" class="col-form-label">Loại văn bản</label>
                                        <select class="form-control show-tick dropdown-search" name="loaivanban_id"
                                                id="loaivanban_id" required>
                                            <option value="">-- Chọn Loại Văn Bản --</option>
                                            @foreach ($ds_loaiVanBan as $loaiVanBan)
                                                <option value="{{$loaiVanBan->id}}"
                                                    {{ isset($vanbandi) && $vanbandi->loaivanban_id == $loaiVanBan->id ? 'selected' : '' }}>{{$loaiVanBan->ten_loai_van_ban}}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="hidden" name="id_van_ban" value="{{$id_van_ban}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi</label>
                                        <select class="form-control show-tick dropdown-search" name="sovanban_id"
                                                required>
                                            <option value="">-- Chọn Sổ Văn Bản Đi --</option>
                                            @foreach ($ds_soVanBan as $soVB)
                                                <option value="{{$soVB->ma_id}}"
                                                    {{ isset($vanbandi) && $vanbandi->sovanban_id == $soVB->ma_id ? 'selected' : '' }}>{{$soVB->ten_so_van_ban}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                        <input type="text"
                                               value="{{ old('vb_sokyhieu', isset($vanbandi) ? $vanbandi->vb_sokyhieu : '') }}"
                                               id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                               placeholder="Nhập số ký hiệu văn bản đi..." required>
                                    </div>


                                    @if(auth::user()->hasRole(VAN_THU_HUYEN))

                                    <div class="form-group col-md-3">
                                        <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo</label>
                                        <select class="form-control show-tick dropdown-search" name="donvisoanthao_id"
                                                required>
                                            <option value="">-- Chọn Đơn Vị Soạn Thảo --</option>
                                            @if($vanbandi->donvisoanthao_id == null && $vanbandi->van_ban_huyen_ky != null)

                                            @foreach ($ds_DonVi as $donVi)
                                                <option value="{{ $donVi->id }}"
                                                    {{ isset($vanbandi) && $vanbandi->donvisoanthao_id == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi}}</option>
                                            @endforeach
                                                @elseif()
                                        </select>
                                    </div>
                                    @else
                                        <div class="form-group col-md-3">
                                            <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo</label>
                                            <select class="form-control show-tick dropdown-search" name="donvisoanthao_id"
                                                    required>
                                                <option value="">-- Chọn Đơn Vị Soạn Thảo --</option>
                                                @foreach ($ds_DonVi as $donVi)
                                                    <option value="{{ $donVi->id }}"
                                                        {{ isset($vanbandi) && $vanbandi->van_ban_huyen_ky == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif


                                    <div class="form-group col-md-3">
                                        <label for="linhvuc_id" class="col-form-label">Lĩnh vực</label>
                                        <select class="form-control show-tick dropdown-search" name="linhvuc_id"
                                                required>
                                            <option value="">-- Chọn Lĩnh Vực --</option>
                                            @foreach ($ds_linhVuc as $linhVuc)
                                                <option value="{{ $linhVuc->id }}"
                                                    {{ isset($vanbandi) && $vanbandi->linhvuc_id == $linhVuc->id ? 'selected' : '' }}>{{ $linhVuc->ten_linh_vuc_van_ban}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" id="div_select_cqbh">
                                        <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký</label>
                                        <select class="form-control show-tick dropdown-search" name="nguoiky_id"
                                                required>
                                            <option value="">-- Chọn Người Ký --</option>
                                            @foreach ($ds_nguoiKy as $nguoiKy)
                                                <option value="{{ $nguoiKy->id }}"
                                                    {{ isset($vanbandi) && $vanbandi->nguoiky_id == $nguoiKy->id ? 'selected' : '' }}>{{$nguoiKy->ho_ten}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Chức vụ</label>
                                        <input type="text"
                                               value="{{isset($vanbandi) ? $vanbandi->chuc_vu : ''}}"
                                               id="chuc_vu" name="chuc_vu"  class="form-control"
                                               placeholder="chức vụ..." required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                        <textarea rows="3" name="vb_trichyeu" class="form-control no-resize"
                                                  placeholder="Nhập nội dung trích yếu ..."
                                                  required>{{ old('vb_trichyeu', isset($vanbandi) ? $vanbandi->vb_trichyeu : '') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="sokyhieu" class="col-form-label">Đơn vị nội bộ</label>
                                        <select name="don_vi_nhan_trong_thanh_php[]" id="don_vi_nhan"
                                                class="form-controlmultiple-select select2-search"
                                                multiple
                                                data-placeholder=" Chọn đơn vị nhận ...">
                                            @foreach ($emailtrongthanhpho as $emailtrong)
                                                <option {{ isset($vanbandi) && in_array($emailtrong->id, $lay_email_trong_tp->pluck('email')->toArray()) ? 'selected' : '' }} value="{{ $emailtrong->id }}"
                                                >{{ $emailtrong->ten_don_vi}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="" class="col-form-label">Đơn vị nhận ngoài hệ thống</label>
                                        <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                                class="form-controlmultiple-select select2-search"
                                                multiple
                                                data-placeholder=" Chọn đơn vị nhận ...">
                                            @foreach ($emailngoaithanhpho as $emailngoai)
                                                <option {{ isset($vanbandi) && in_array($emailngoai->id, $lay_email_ngoai_tp->pluck('email')->toArray()) ? 'selected' : '' }} value="{{ $emailngoai->id }}"
                                                >{{ $emailngoai->ten_don_vi}}</option>
                                            @endforeach

                                        </select>
                                    </div>
{{--                                    <div class="form-group col-md-12">--}}
{{--                                        <label for="sokyhieu" class="col-form-label">Đơn vị nhận văn bản</label>--}}
{{--                                        <select name="don_vi_nhan[]" id="don_vi_nhan"--}}
{{--                                                class="form-control don_vi_nhan multiple-select select2-search"--}}
{{--                                                multiple--}}
{{--                                                data-placeholder="Chọn đơn vị nhận ...">--}}
{{--                                            --}}
{{--                                        </select>--}}
{{--                                    </div>--}}

                                    <div class="form-group col-md-3" id="loaivanban">
                                        <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                        <select class="form-control show-tick dropdown-search" name="dokhan_id"
                                                required>--}}
                                            @foreach ($ds_doKhanCap as $doKhanCap)
                                                <option value="{{ $doKhanCap->id }}"
                                                    {{ isset($vanbandi) && $vanbandi->dokhan_id == $doKhanCap->id ? 'selected' : '' }}>{{ $doKhanCap->ten_muc_khan_cap}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                        <select class="form-control show-tick dropdown-search" name="dobaomat_id"
                                                required>--}}
                                            @foreach ($ds_mucBaoMat as $doBaoMat)
                                                <option
                                                    value="{{ $doBaoMat->id }}" {{ $doBaoMat->macDinh ? 'selected' : ''  }}
                                                    {{ isset($vanbandi) && $vanbandi->dobaomat_id == $doBaoMat->id ? 'selected' : '' }}>{{ $doBaoMat->tenmucdobaomat}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Số bản</label>
                                        <input type="text" id="vb_soBan"
                                               value="{{isset($vanbandi) ? $vanbandi->vb_soBan : 1}}"
                                               name="vb_soBan" class="form-control" autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Số trang</label>
                                        <input type="text" id="vb_soTrang" name="vb_soTrang"
                                               value="{{isset($vanbandi) ? $vanbandi->vb_soTrang : 1}}"
                                               class="form-control"
                                               autocomplete="off" required>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <div class="row increment">
                                            <div class="col-md-3 ">
                                                <label for="sokyhieu" class="col-form-label">Tên file</label>
                                                <input class="form-control" name="txt_file[]" type="text">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="url-file" class="col-form-label">Chọn tệp</label>
                                                <div class="form-line input-group control-group">
                                                    <input type="file" id="url-file" name="files_attached[]"
                                                           class="form-control">
                                                    <div class="input-group-btn">
                                            <span class="btn btn-primary"
                                                  onclick="multiUploadFilevanbandi('files_attached[]')"
                                                  type="button">
                                            <i class="fa fa-plus"></i> thêm</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Ngày ban hành</label>
                                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh"
                                                       class="form-control"
                                                       value="{{$date}}"
                                                       autocomplete="off" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Số đi</label>
                                                <input type="text" id="vb_sothutu" readonly name="vb_sothutu" autofocus
                                                       value=""
                                                       class="form-control"
                                                       autocomplete="off" placeholder="Nhập số thứ tự ..." required>
                                            </div>

                                        </div>
                                        <div class="col-md-12 text-center" style="margin-top: 38px">
                                            <button type="button" onclick="submit();" class="btn btn-primary"><i
                                                    class="far fa-plus-square mr-1"></i>
                                                <span>{{ isset($vanbandi) ? 'Cập nhật' : 'Thêm mới' }}</span>
                                            </button>
                                        </div>
                                    </div>


                                </form>
                                <div class="col-md-12"><br></div>
                                <div class="col-md-12"><br></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        function submit() {
            $('#formCreateDoc').submit();
        }
    </script>
@endsection
