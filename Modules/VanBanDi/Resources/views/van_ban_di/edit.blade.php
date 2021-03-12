@extends('admin::layouts.master')
@section('page_title', 'Sửa văn bản đi')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sửa văn bản đi</h3>
                    </div>
                    @include('vanbandi::van_ban_di.form_them_van_ban_den')
                    <div class="box-body">
                        <form class="form-row"
                              action="{{ route('van-ban-di.update',$vanbandi->id)}}"
                              method="post" enctype="multipart/form-data" id="formCreateDoc">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="van_ban_den_id">
                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Loại văn bản <span class="color-red">*</span></label>
                                <select class="form-control show-tick " autofocus name="loaivanban_id" id="loaivanban_id" required>
                                    <option value="">-- Chọn Loại Văn Bản-</option>
                                    @foreach ($ds_loaiVanBan as $data)
                                        <option value="{{$data->id}}"    {{isset($vanbandi) && $vanbandi->loai_van_ban_id == $data->id    ? 'selected ': ''}}
                                        >{{$data->ten_loai_van_ban}}</option>
                                    @endforeach
                                </select>
                            </div>
{{--                            <div class="form-group col-md-3">--}}
{{--                                <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi <span class="color-red">*</span></label>--}}
{{--                                <select class="form-control show-tick" name="sovanban_id" required>--}}
{{--                                    @foreach ($ds_soVanBan as $data)--}}
{{--                                        <option value="{{ $data->id }}"  {{$vanbandi->so_van_ban_id == $data->id ? 'selected' : ''}}--}}
{{--                                        >{{ $data->ten_so_van_ban}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Số ký hiệu <span class="color-red">*</span></label>
                                <input type="text" value="{{$vanbandi->so_ky_hieu}}"
                                       id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                       placeholder="Nhập ký hiệu văn bản đi..." required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Ngày ban hành <span class="color-red">*</span></label>
                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh" class="form-control"
                                       value="{{$vanbandi->ngay_ban_hanh}}"
                                       autocomplete="off" required>
                            </div>
                            @if(auth::user()->hasRole(VAN_THU_HUYEN))
                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2-search" name="donvisoanthao_id" required>
                                    <option value="">-- Chọn đơn vị soạn thảo --</option>
                                    @if($vanbandi->donvisoanthao_id == null && $vanbandi->van_ban_huyen_ky == null)
{{--                                        //đây là văn bản của huyện--}}
                                        @foreach ($ds_DonVi as $donVi)
                                            <option value="{{ $donVi->id }}" {{$vanbandi->nguoitao->donVi->id == $donVi->id ? 'selected' : ''}}
                                            >{{ $donVi->ten_don_vi }}</option>
                                        @endforeach
                                    @elseif($vanbandi->donvisoanthao_id == null && $vanbandi->van_ban_huyen_ky != null)
{{--                                        đây là văn bản của huyện do đơn vị soạn thảo--}}
                                        @foreach ($ds_DonVi as $donVi)
                                            <option value="{{ $donVi->id }}" {{$vanbandi->van_ban_huyen_ky == $donVi->id ? 'selected' : ''}}
                                            >{{ $donVi->ten_don_vi }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @else
                                <div class="form-group col-md-3">
                                    <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span class="color-red">*</span></label>
                                    <select class="form-control show-tick select2-search" name="donvisoanthao_id" required>
                                        <option value="">-- Chọn đơn vị soạn thảo --</option>
                                        @foreach ($ds_DonVi as $donVi)
                                            <option value="{{ $donVi->id }}" {{$vanbandi->don_vi_soan_thao == $donVi->id ? 'selected' : ''}}
                                            >{{ $donVi->ten_don_vi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            {{--                            <div class="form-group col-md-3">--}}
                            {{--                                <label for="sokyhieu" class="col-form-label">Người duyệt</label>--}}
                            {{--                                <select name="nguoi_nhan" id="" class="form-control ">--}}
                            {{--                                    @foreach ($nguoinhan as $data)--}}
                            {{--                                        <option value="{{ $data->id }}"--}}
                            {{--                                        >{{ $data->ho_ten}}</option>--}}
                            {{--                                    @endforeach--}}
                            {{--                                </select>--}}
                            {{--                            </div>--}}




                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span class="color-red">*</span></label>
                                <select class="form-control show-tick  layidnguoiky" name="nguoiky_id" required>
                                    <option value="">-- Chọn Người Ký --</option>
                                    @foreach ($ds_nguoiKy as $nguoiKy)
                                        <option data-chuc-vu ="{{ $nguoiKy->chucvu->ten_chuc_vu ?? null }}" value="{{ $nguoiKy->id }}" {{$vanbandi->nguoi_ky == $nguoiKy->id ? 'selected' : ''}}
                                        >{{$nguoiKy->ho_ten}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3" >
                                <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                <select class="form-control show-tick" name="dokhan_id" required>--}}
                                    @foreach ($ds_doKhanCap as $doKhanCap)
                                        <option value="{{ $doKhanCap->id }}" {{$vanbandi->do_khan_cap_id == $doKhanCap->id ? 'selected' : ''}}
                                        >{{ $doKhanCap->ten_muc_do}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                <select class="form-control show-tick " name="dobaomat_id" required>--}}
                                    @foreach ($ds_mucBaoMat as $doBaoMat)
                                        <option value="{{ $doBaoMat->id }}" {{$vanbandi->do_bao_mat_id == $doBaoMat->id ? 'selected' : ''}}
                                        >{{ $doBaoMat->ten_muc_do}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ</label>
                                <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu" value="{{$vanbandi->chuc_vu}}">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="sokyhieu" class="col-form-label ">Trích yếu <span class="color-red">*</span></label>
                                <textarea rows="3" name="vb_trichyeu" class="form-control no-resize" placeholder="Nhập nội dung trích yếu ..."
                                          required>{{$vanbandi->trich_yeu}}</textarea>
                            </div>
                            <div class="form-group col-md-12 hidden">
                                <label for="sokyhieu" class="col-form-label">Đơn vị nhận trong thành phố</label>
                                <select name="don_vi_nhan_trong_thanh_php[]" id="don_vi_nhan"
                                        class="form-control select2"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($emailtrongthanhpho as $email)
                                        <option value="{{ $email->id }}" {{  in_array($email->id, $lay_emailtrongthanhpho->pluck('email')->toArray()) ? 'selected' : '' }}
                                        >{{ $email->ten_don_vi}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-12 ">
                                <label for="" class="col-form-label">Đơn vị nhận ngoài thành phố</label>
                                <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                        class="form-control select2"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($emailngoaithanhpho as $emailngoai)
                                        <option value="{{ $emailngoai->id }}" {{  in_array($emailngoai->id, $lay_emailngoaithanhpho->pluck('email')->toArray()) ? 'selected' : '' }}
                                        >{{ $emailngoai->ten_don_vi}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-12 ">
                                <label for="sokyhieu" class="col-form-label">Đơn vị nhận </label>
                                <select name="don_vi_nhan_van_ban_di[]" id="don_vi_nhan"
                                        class="form-control select2"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($ds_DonVi_nhan as $donVi)
                                        <option value="{{ $donVi->id }}"{{  in_array($donVi->id, $lay_noi_nhan_van_ban_di->pluck('don_vi_id_nhan')->toArray()) ? 'selected' : '' }}
                                        >{{ $donVi->ten_don_vi }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="">Trả lời cho văn bản đến:</label>
                                <a class="them-van-ban-den" data-toggle="modal" data-target="#modal-them-van-ban-den">
                                    <span><i class="fa fa-plus-square-o"></i> Thêm văn bản đến</span>
                                </a>
                                <div class="row">
                                    @if ($vanbandi->listVanBanDen)
                                        @foreach($vanbandi->listVanBanDen as $vanBanDen)
                                            <div class="col-md-3 chi-tiet-vb-den">
                                                <p>
                                                    số ký hiệu: <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id) }}" target="_blank">{{ $vanBanDen->so_ky_hieu }}</a>
                                                    <i class="fa fa-trash rm-van-ban-den" data-id="{{ $vanBanDen->id }}" data-van-ban-di="{{ $vanbandi->id }}"></i>
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row main-so-ky-hieu-van-ban-den">

                                </div>
                            </div>

                            <div class="form-group col-md-12 text-center">
                                <button
                                    class="btn btn-danger" type="submit"><i class="fa fa-check mr-1"></i>
                                    <span>Cập nhật</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
