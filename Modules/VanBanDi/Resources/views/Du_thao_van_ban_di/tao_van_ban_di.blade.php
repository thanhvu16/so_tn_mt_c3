@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-3">Tạo văn bản đi</h4>
                <div class="card-box pd-0" >
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">
                                <form class="form-row"
                                      action="{{route('tao_van_ban_di')}}"
                                      method="post" enctype="multipart/form-data" id="formCreateDoc">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_don_vi_id" value="{{ isset($vanbanduthao) ? $vanbanduthao->van_ban_den_don_vi_id : null }}" >
                                    <div class="form-group col-md-3" id="loaivanban">
                                        <label for="linhvuc_id" class="col-form-label">Loại văn bản <span class="color-red">*</span></label>
                                        <select class="form-control show-tick dropdown-search" name="loaivanban_id"
                                                id="loaivanban_id" required>
                                            <option value="">-- Chọn Loại Văn Bản --</option>
                                            @foreach ($ds_loaiVanBan as $loaiVanBan)
                                                <option value="{{$loaiVanBan->id}}"
                                                    {{ isset($vanbanduthao) && $vanbanduthao->loai_van_ban_id == $loaiVanBan->id ? 'selected' : '' }}>{{$loaiVanBan->ten_loai_van_ban}}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="hidden" name="id_duthao" value="{{$id_duthao}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi <span style="color: red">*</span></label>
                                        <select class="form-control show-tick dropdown-search" name="sovanban_id"
                                                required>
                                            <option value="">-- Chọn Sổ Văn Bản Đi --</option>
                                            <option value="1">Sổ Ủy ban</option>
                                            <option value="2">Sổ văn phòng</option>
                                            <option value="3">Sổ khác</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Người nhận <span style="color: red">*</span></label>
                                        <select name="nguoi_nhan" id="" class="form-control dropdown-search">
                                            @foreach ($nguoinhan as $data)
                                                <option value="{{ $data->id }}" {{ isset($vanbanduthao) && $vanbanduthao->nguoi_ky == $data->id ? 'selected' : '' }}
                                                >{{ $data->ho_ten}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Số ký hiệu <span class="color-red">*</span></label>
                                        <input type="text" style="font-size:16px"
                                               value="{{ old('vb_sokyhieu', isset($vanbanduthao) ? $vanbanduthao->so_ky_hieu : '') }}"
                                               id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                               placeholder="Nhập số ký hiệu văn bản đi..." required>
                                    </div>



                                    <div class="form-group col-md-3" style="margin-top: -10px">
                                        <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span class="color-red">*</span></label>
                                        <select class="form-control show-tick dropdown-search" name="donvisoanthao_id"
                                                required>
                                            <option value="">-- Chọn Đơn Vị Soạn Thảo --</option>
                                            @foreach ($ds_DonVi as $donVi)
                                                <option value="{{ $donVi->ma_id }}"
                                                    {{ isset($userAuth) && $userAuth->donVi->ma_id == $donVi->ma_id ? 'selected' : '' }}>{{ $donVi->ten_don_vi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -10px">
                                        <label for="linhvuc_id" class="col-form-label">Lĩnh vực <span style="color: red">*</span></label>
                                        <select class="form-control show-tick dropdown-search" name="linhvuc_id"
                                                required>
                                            <option value="">-- Chọn Lĩnh Vực --</option>
                                            @foreach ($ds_linhVuc as $linhVuc)
                                                <option value="{{ $linhVuc->id }}"
                                                    {{ isset($vanbanduthao) && $vanbanduthao->linhvuc_id == $linhVuc->id ? 'selected' : '' }}>{{ $linhVuc->ten_linh_vuc_van_ban}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -10px">
                                        <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span class="color-red">*</span></label>
                                        <select class="form-control show-tick dropdown-search" name="nguoiky_id"
                                                required>
                                            <option value="">-- Chọn Người Ký --</option>
                                            @foreach ($ds_nguoiKy as $nguoiKy)
                                                <option value="{{ $nguoiKy->id }}"
                                                    {{ isset($vanbanduthao) && $vanbanduthao->nguoi_ky == $nguoiKy->id ? 'selected' : '' }}>{{$nguoiKy->ho_ten}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -10px">
                                        <label for="sokyhieu" class="col-form-label">Chức vụ <span class="color-red">*</span></label>
                                        <input type="text" style="font-size:16px"
                                               value="{{isset($vanbanduthao) ? $vanbanduthao->chuc_vu : ''}}"
                                               id="chuc_vu" name="chuc_vu"  class="form-control"
                                               placeholder="chức vụ..." required>
                                    </div>
                                    <div class="form-group col-md-12" style="margin-top: -10px; ">
                                        <label for="sokyhieu" class="col-form-label ">Trích yếu <span class="color-red">*</span></label>
                                        <textarea rows="3" style="font-size:16px" name="vb_trichyeu" class="form-control no-resize"
                                                  placeholder="Nhập nội dung trích yếu ..."
                                                  required>{{ old('vb_trichyeu', isset($vanbanduthao) ? $vanbanduthao->vb_trich_yeu : '') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="sokyhieu" class="col-form-label">Đơn vị nhận trong thành phố</label>
                                        <select name="don_vi_nhan_trong_thanh_php[]" id="don_vi_nhan"
                                                class="form-controlmultiple-select select2-search"
                                                multiple
                                                data-placeholder=" Chọn đơn vị nhận ...">
                                            @foreach ($emailtrongthanhpho as $email)
                                                <option value="{{ $email->id }}"
                                                >{{ $email->ten_don_vi}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="" class="col-form-label">Đơn vị nhận ngoài thành phố</label>
                                        <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                                class="form-controlmultiple-select select2-search"
                                                multiple
                                                data-placeholder=" Chọn đơn vị nhận ...">
                                            @foreach ($emailngoaithanhpho as $emailngoai)
                                                <option value="{{ $emailngoai->id }}"
                                                >{{ $emailngoai->ten_don_vi}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="row clearfix"></div>

                                    <div class=" col-md-3" style="margin-top: -10px">
                                        <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                        <select class="form-control show-tick dropdown-search" name="dokhan_id"
                                                required>--}}
                                            @foreach ($ds_doKhanCap as $doKhanCap)
                                                <option value="{{ $doKhanCap->id }}"
                                                    {{ isset($vanbanduthao) && $vanbanduthao->dokhan_id == $doKhanCap->id ? 'selected' : '' }}>{{ $doKhanCap->ten_muc_khan_cap}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -10px">
                                        <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                        <select class="form-control show-tick dropdown-search" name="dobaomat_id"
                                                required>--}}
                                            @foreach ($ds_mucBaoMat as $doBaoMat)
                                                <option
                                                    value="{{ $doBaoMat->id }}" {{ $doBaoMat->macDinh ? 'selected' : ''  }}
                                                    {{ isset($vanbanduthao) && $vanbanduthao->dobaomat_id == $doBaoMat->id ? 'selected' : '' }}>{{ $doBaoMat->tenmucdobaomat}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class=" col-md-3" style="margin-top: -10px">
                                        <label for="sokyhieu" class="col-form-label">Số bản</label>
                                        <input type="text" id="vb_soBan"
                                               value="1"
                                               name="vb_soBan" class="form-control"  required>
                                    </div>
                                    <div class=" col-md-3" style="margin-top: -10px;padding-left: 20px;">
                                        <label for="sokyhieu" class="col-form-label">Số trang</label>
                                        <input type="text" id="vb_soTrang" name="vb_soTrang"
                                               value="1"
                                               class="form-control"
                                               autocomplete="off" required>
                                    </div>
                                    <div class="col-md-12">
                                        <span style="color: red">*</span> <span style="color: black;font-style: italic;">Danh sách file đã upload:&ensp; </span>
                                        @foreach($file as $key=>$data)
                                            <a href="{{$data->getUrlFile()}}"
                                               target="_blank">{{$data -> ten_file}} &ensp;</a>
                                            <a href="{{route('delete_duthao',$data->id)}}" class="btn-remove-item" style="color: red"><i class="far fa-trash-alt"></i></a> &ensp; &ensp;@if(count($file) == $key+1) @else &nbsp;|&nbsp; @endif&ensp; &ensp;

                                            @endforeach<span style="font-style: italic">(Chú ý: Nếu không chọn file thì file cuối cùng sẽ là file văn bản đi)</span>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <div class="row duthaovb">
                                            <div class="col-md-3 ">
                                                <label for="sokyhieu" class="col-form-label">File trình ký</label>
                                                <div class="form-line input-group control-group">
                                                    <input type="file" id="url-file" name="file_trinh_ky[]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3 ">
                                                <label for="url-file" class="col-form-label">File phiếu trình</label>
                                                <div class="form-line input-group control-group">
                                                    <input type="file" id="url-file" name="file_phieu_trinh[]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Ngày ban hành</label>
                                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh"
                                                       class="form-control" style="font-size:16px;width: 100%"
                                                       value="{{$date}}"
                                                       autocomplete="off" required>
                                            </div>

                                            <div class=" col-md-3" style="margin-top: 35px">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a class="btn btn-success btn-xs" style="color: white"
                                                           onclick="duthaovanban()"
                                                           role="button"
                                                        ><i class="fa fa-plus"></i>
                                                        </a>
                                                        <b class="text-danger"> Thêm file hồ sơ</b> &emsp;

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12 text-center mt-4">
                                            <button type="submit"  class="btn btn-primary"><i
                                                    class="far fa-plus-square mr-1"></i>
                                                <span>{{ isset($vanbandi) ? 'Cập nhật' : 'Thêm mới' }}</span>
                                            </button>
                                        </div>

                                    </div>

                                    <div
                                        class="col-md-12  gmoi {{isset($vanbandi) && $vanbandi->loai_van_ban_id == 4 ? '' : 'hidden'}}">
                                        <div class="row">
                                            <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px">
                                                <label for="detail-job">Nội Dung Họp <span
                                                        style="color: red">*</span></label>
                                                <textarea name="noi_dung_hop" placeholder="nhập nội dung công việc"
                                                          rows="3"
                                                          class="form-control no-resize noi-dung-chi-dao"
                                                          aria-required="true">{{ old('noi_dung_hop', isset($vanbandi) ? $vanbandi->noi_dung_hop : '') }}</textarea>
                                            </div>
                                            <div class="col-md-4" style="margin-top: 10px">
                                                <div class="form-group">
                                                    <label for="">Giờ Họp<span style="color: red">*</span></label>
                                                    <input type="time" class="form-control"
                                                           value="{{ isset($vanbandi) ? $vanbandi->gio_hop : '' }}"
                                                           name="gio_hop" placeholder="ví dụ: 5:57">
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="margin-top: 10px">
                                                <div class="form-group">
                                                    <label for="">Ngày Họp<span style="color: red">*</span></label>
                                                    <input type="text" class="form-control"
                                                           value="{{ isset($vanbandi) ? date('d/m/Y', strtotime($vanbandi->ngay_hop)) : '' }}"
                                                           name="ngay_hop" placeholder="Nhập ngày họp">
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="margin-top: 10px">
                                                <div class="form-group">
                                                    <label for="">Địa Điểm<span style="color: red">*</span></label>
                                                    <input type="text"
                                                           value="{{ old('dia_diem_hop', isset($vanbandi) ? $vanbandi->dia_diem_hop : '') }}"
                                                           placeholder="Nhập địa điểm" class="form-control"
                                                           name="dia_diem_hop">
                                                </div>
                                            </div>
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
