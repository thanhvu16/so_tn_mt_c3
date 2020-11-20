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
                            <i class="far fa-plus-square"></i> Thêm giấy mời
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">
                                <form class="form-row"
                                      action="{{ route('updategiaymoi',$vanbandi->id)}}"
                                      method="post" enctype="multipart/form-data" id="formCreateDoc">
                                    @csrf

                                    <div class="form-group col-md-3 hidden">
                                        <label for="linhvuc_id" class="col-form-label">Loại văn bản </label>
                                        <select class="form-control show-tick " name="loaivanban_id" id="loaivanban_id"
                                                required>
                                            <option value="1000">-- Chọn Loại Văn Bản --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="cap_ban_hanh_id" autofocus class="col-form-label">Sổ văn bản đi
                                            <span style="color: red">(*)</span></label>
                                        <select class="form-control show-tick" name="sovanban_id" required>
                                            <option value="">-- Chọn Sổ Văn Bản Đi --</option>
                                            <option {{ isset($vanbandi) && $vanbandi->sovanban_id == 1 ? 'selected' : '' }} value="1">Sổ Ủy ban</option>
                                            <option {{ isset($vanbandi) && $vanbandi->sovanban_id == 2 ? 'selected' : '' }} value="2">Sổ văn phòng</option>
                                            <option {{ isset($vanbandi) && $vanbandi->sovanban_id == 3 ? 'selected' : '' }} value="3">Sổ khác</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Ký hiệu <span style="color: red">(*)</span></label>
                                        <input type="text" value="{{ old('vb_sokyhieu', isset($vanbandi) ? $vanbandi->vb_sokyhieu : '') }}"
                                               id="vb_sokyhieu" name="vb_sokyhieu" class="form-control"
                                               placeholder="Nhập số ký hiệu văn bản đi..." required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="sokyhieu" class="col-form-label">Ngày ban hành <span
                                                style="color: red">(*)</span></label>
                                        <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh"
                                               class="form-control"
                                               value="{{$vanbandi->vb_ngaybanhanh}}"
                                               autocomplete="off" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span
                                                style="color: red">(*)</span></label>
                                        <select class="form-control show-tick select2-search" name="donvisoanthao_id"
                                                required>
                                            @foreach ($ds_DonVi as $donVi)
                                                <option {{ isset($vanbandi) && $vanbandi->donvisoanthao_id == $donVi->id ? 'selected' : '' }} value="{{ $donVi->ma_id }}"
                                                >{{ $donVi->ten_don_vi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3" style="margin-top: -5px">
                                        <div class="form-group">
                                            <label for="">Giờ họp <span style="color: red">(*)</span></label>
                                            <input type="time" required class="form-control" value="{{ old('gio_hop', isset($vanbandi) ? $vanbandi->gio_hop : '') }}" name="gio_hop">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: -5px">
                                        <div class="form-group">
                                            <label for="">Ngày họp <span style="color: red">(*)</span></label>
                                            <input type="date" required class="form-control ngaybanhanh2" value="{{ old('gio_hop', isset($vanbandi) ? $vanbandi->ngay_hop : '') }}"
                                                   name="ngay_hop" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: -5px">
                                        <div class="form-group">
                                            <label for="">Địa điểm <span style="color: red">(*)</span></label>
                                            <input type="text" required class="form-control" value="{{ old('gio_hop', isset($vanbandi) ? $vanbandi->dia_diem_hop : '') }}"
                                                   name="dia_diem" placeholder="Địa điểm">
                                        </div>
                                    </div>
{{--                                    <div class="form-group col-md-3" style="margin-top: -15px">--}}
{{--                                        <label for="sokyhieu" class="col-form-label">Người duyệt <span--}}
{{--                                                style="color: red">(*)</span></label>--}}
{{--                                        <select name="nguoi_nhan" id="" class="form-control ">--}}
{{--                                            @foreach ($nguoinhan as $data)--}}
{{--                                                <option value="{{ $data->id }}"--}}
{{--                                                >{{ $data->ho_ten}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                    <div class="form-group col-md-3" style="margin-top: -15px">
                                        <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span
                                                style="color: red">(*)</span></label>
                                        <select class="form-control show-tick  layidnguoiky" name="nguoiky_id" required>
                                            <option value="">-- Chọn Người Ký --</option>
                                            @foreach ($ds_nguoiKy as $nguoiKy)
                                                <option {{ isset($vanbandi) && $vanbandi->nguoiky_id == $nguoiKy->id ? 'selected' : '' }} data-chuc-vu="{{ $nguoiKy->chucvu->ten_chuc_vu ?? null }}"
                                                        value="{{ $nguoiKy->id }}"
                                                >{{$nguoiKy->ho_ten}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -15px">
                                        <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ <span
                                                style="color: red">(*)</span></label>
                                        <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu"
                                               value="{{$vanbandi->chuc_vu}}">
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -15px">
                                        <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                        <select class="form-control show-tick" name="dokhan_id" required>
                                            @foreach ($ds_doKhanCap as $doKhanCap)
                                                <option value="{{ $doKhanCap->id }}"  {{ isset($vanbandi) && $vanbandi->dokhan_id == $doKhanCap->id ? 'selected' : '' }}
                                                >{{ $doKhanCap->ten_muc_khan_cap}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -15px">
                                        <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                        <select class="form-control show-tick " name="dobaomat_id" required>--}}
                                            @foreach ($ds_mucBaoMat as $doBaoMat)
                                                <option {{ isset($vanbandi) && $vanbandi->dobaomat_id == $doBaoMat->id ? 'selected' : '' }}
                                                    value="{{ $doBaoMat->id }}" {{ $doBaoMat->macDinh ? 'selected' : ''  }}
                                                >{{ $doBaoMat->tenmucdobaomat}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12" style="margin-top: -15px">
                                        <label for="sokyhieu" class="col-form-label ">Trích yếu <span
                                                style="color: red">(*)</span></label>
                                        <textarea rows="3" name="vb_trichyeu" class="form-control no-resize"
                                                  placeholder="Nhập nội dung trích yếu ..."
                                                  required>{{ old('vb_trichyeu', isset($vanbandi) ? $vanbandi->vb_trichyeu : '') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12" style="margin-top: -15px">
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
                                    <div class="form-group col-md-12" style="margin-top: -15px">
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
                                    <div class="form-group col-md-3" style="margin-top: -15px">
                                        <label for="sokyhieu" class="col-form-label">Số bản</label>
                                        <input type="text" id="vb_soBan" value="1"
                                               name="vb_soBan" class="form-control" autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top: -15px">
                                        <label for="sokyhieu" class="col-form-label">Số trang</label>
                                        <input type="text" id="vb_soTrang" name="vb_soTrang"
                                               value="1" class="form-control"
                                               autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-md-6" style="margin-top: 25px">
                                        <button type="submit" class="btn btn-info waves-effect waves-light"><i
                                                class="far fa-plus-square mr-1"></i>
                                            <span>Cập nhật</span></button>
                                        <a class="btn btn-danger " role="button"
                                           href="{{route('nhapgiaymoidi')}}"
                                        ><i
                                                class="fa fa-plus"></i> Nhập mới
                                        </a>
                                        <a class="btn btn-success " role="button"
                                           href="{{route('dsgiaymoi')}}"
                                        ><i
                                                class="fas fa-list-alt"></i> Danh sách
                                        </a>
                                    </div>
                                </form>

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

@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script src="{{ asset('modules/quanlyvanban/js/giaymoi.js')}}"></script>
    <script type="text/javascript">

    </script>

@endsection
