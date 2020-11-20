@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-3">Văn bản đi {{$userAuth->donVi->getDonViQuanLy() }}</h4>
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="far fa-plus-square"></i> Thêm văn bản
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">
                                <form class="form-row"
                                      action="{{ route('van_ban_di.update',$vanbandi->id)}}"
                                      method="post" enctype="multipart/form-data" id="formCreateDoc">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group col-md-3">
                                        <label for="linhvuc_id" class="col-form-label">Loại văn bản</label>
                                        <select class="form-control show-tick dropdown-search" autofocus name="loaivanban_id" id="loaivanban_id" required>
                                            <option value="">-- Chọn Loại Văn Bản --</option>
                                            @foreach ($ds_loaiVanBan as $loaiVanBan)
                                                <option value="{{$loaiVanBan->id}}"
                                                    {{ isset($vanbandi) && $vanbandi->loaivanban_id == $loaiVanBan->id ? 'selected' : '' }}>{{$loaiVanBan->ten_loai_van_ban}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi</label>
                                        <select class="form-control show-tick dropdown-search" name="sovanban_id" required>
                                            <option value="">-- Chọn Sổ Văn Bản Đi --</option>
                                            @foreach ($ds_soVanBan as $soVB)
                                                <option value="{{$soVB->ma_id}}"
                                                    {{ isset($vanbandi) && $vanbandi->sovanban_id == $soVB->ma_id ? 'selected' : '' }}>{{$soVB->ten_so_van_ban}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                        <input type="text" value="{{ old('vb_sokyhieu', isset($vanbandi) ? $vanbandi->vb_sokyhieu : '') }}"
                                               id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                               placeholder="Nhập số ký hiệu văn bản đi..." required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div id="bs_datepicker_container">
                                            <label for="sokyhieu" class="col-form-label">Ngày ban hành</label>
                                            <input type="text" name="vb_ngaybanhanh" id="vb_ngaybanhanh" class="form-control"
                                                   value="{{isset($vanbandi) ? !empty($vanbandi->vb_ngaybanhanh) ? dateFormat('d/m/Y',$vanbandi->vb_ngaybanhanh)  : null : ''}}"
                                                   autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo</label>
                                        <select class="form-control show-tick dropdown-search" name="donvisoanthao_id" required>
                                            <option value="">-- Chọn Đơn Vị Soạn Thảo --</option>
                                            @foreach ($ds_DonVi as $donVi)
                                                <option value="{{ $donVi->ma_id }}"
                                                    {{ isset($vanbandi) && $vanbandi->donvisoanthao_id == $donVi->ma_id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group col-md-3" >
                                        <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký</label>
                                        <select class="form-control show-tick dropdown-search" name="nguoiky_id" required>
                                            <option value="">-- Chọn Người Ký --</option>
                                            @foreach ($ds_nguoiKy as $nguoiKy)
                                                <option value="{{ $nguoiKy->id }}"
                                                    {{ isset($vanbandi) && $vanbandi->nguoiky_id == $nguoiKy->id ? 'selected' : '' }}>{{$nguoiKy->ho_ten}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" >
                                        <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ</label>
                                        <input type="text" class="form-control" name="chuc_vu" value="{{$vanbandi->chuc_vu}}">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                        <textarea rows="3" name="vb_trichyeu" class="form-control no-resize" placeholder="Nhập nội dung trích yếu ..."
                                                  required>{{ old('vb_trichyeu', isset($vanbandi) ? $vanbandi->vb_trichyeu : '') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="sokyhieu" class="col-form-label">Đơn vị nhận trong thành phố</label>
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
                                        <label for="" class="col-form-label">Đơn vị nhận ngoài thành phố</label>
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

                                    <div class="form-group col-md-3" >
                                        <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                        <select class="form-control show-tick dropdown-search" name="dokhan_id" required>--}}
                                            @foreach ($ds_doKhanCap as $doKhanCap)
                                                <option value="{{ $doKhanCap->id }}"
                                                    {{ isset($vanbandi) && $vanbandi->dokhan_id == $doKhanCap->id ? 'selected' : '' }}>{{ $doKhanCap->ten_muc_khan_cap}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                        <select class="form-control show-tick dropdown-search" name="dobaomat_id" required>--}}
                                            @foreach ($ds_mucBaoMat as $doBaoMat)
                                                <option value="{{ $doBaoMat->id }}" {{ $doBaoMat->macDinh ? 'selected' : ''  }}
                                                    {{ isset($vanbandi) && $vanbandi->dobaomat_id == $doBaoMat->id ? 'selected' : '' }}>{{ $doBaoMat->tenmucdobaomat}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Số bản</label>
                                        <input type="text" id="vb_soBan" value="{{ old('vb_soBan', isset($vanbandi) ? $vanbandi->vb_soBan : '') }}"
                                               name="vb_soBan" class="form-control" autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Số trang</label>
                                        <input type="text" id="vb_soTrang" name="vb_soTrang"
                                               value="{{ old('vb_soTrang', isset($vanbandi) ? $vanbandi->vb_soTrang : '') }}" class="form-control"
                                               autocomplete="off" required>
                                    </div>


                                    <div class="form-group col-md-3 mt-4">
                                        <button  type="submit" class="btn btn-info waves-effect waves-light"><i class="far fa-plus-square mr-1"></i>
                                            <span>Cập nhật</span></button>
                                    </div>
                                </form>

                                @section('script')
                                    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
                                    <script src="{{ asset('modules/quanlyvanban/js/giaymoi.js')}}"></script>
                                    <script type="text/javascript">

                                    </script>

                                @endsection

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
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
