@extends('admin::layouts.master')
@section('page_title', 'Sửa văn bản đi')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sửa và cấp số văn bản đi</h3>
                    </div>
                    @include('vanbandi::van_ban_di.form_them_van_ban_den')
                    <div class="box-body">
                        <form class="form-row"
                              action="{{ route('Capsovanbandi',$vanbandi->id)}}"
                              method="post" enctype="multipart/form-data" id="formCreateDoc">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="van_ban_den_id">
                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Sổ văn bản <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2" autofocus name="so_van_ban_id" id="so_van_ban_id" required>
                                    <option value="">-- Chọn Sổ Văn Bản-</option>
                                    @foreach ($ds_soVanBan as $data)
                                        <option value="{{$data->id}}">{{$data->ten_so_van_ban}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" value="1" name="sua_cap_so">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Loại văn bản <span class="color-red">*</span></label>
                                <select class="form-control show-tick loai-van-ban-chanh-vp select2" autofocus name="loaivanban_id" id="loaivanban_id" required>
                                    <option value="">-- Chọn Loại Văn Bản-</option>
                                    @foreach ($ds_loaiVanBan as $data)
                                        <option value="{{$data->id}}"    {{isset($vanbandi) && $vanbandi->loai_van_ban_id == $data->id    ? 'selected ': ''}}
                                        >{{$data->ten_loai_van_ban}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Số ký hiệu </label>
                                <input type="text" value="{{$vanbandi->so_ky_hieu}}" style="text-transform: uppercase "
                                       id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                       placeholder="Nhập ký hiệu văn bản đi..." >
                            </div>

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Ngày ban hành <span class="color-red">*</span></label>
                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh" class="form-control"
                                       value="{{date('Y-m-d')}}"
                                       autocomplete="off" required>
                            </div>
                            @if(auth::user()->hasRole(VAN_THU_HUYEN))
                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2" name="donvisoanthao_id" required>
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
                                    <select class="form-control show-tick select2" name="donvisoanthao_id" required>
                                        <option value="">-- Chọn đơn vị soạn thảo --</option>
                                        @foreach ($ds_DonVi as $donVi)
                                            <option value="{{ $donVi->id }}" {{$vanbandi->van_ban_huyen_ky == $donVi->id ? 'selected' : ''}}
                                            >{{ $donVi->ten_don_vi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2  layidnguoiky" name="nguoiky_id" required>
                                    <option value="">-- Chọn Người Ký --</option>
                                    @foreach ($ds_nguoiKy as $nguoiKy)
                                        <option data-chuc-vu ="{{ $nguoiKy->chucvu->ten_chuc_vu ?? null }}" value="{{ $nguoiKy->id }}" {{$vanbandi->nguoi_ky == $nguoiKy->id ? 'selected' : ''}}
                                        >{{$nguoiKy->ho_ten}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ</label>
                                <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu" value="{{$vanbandi->chuc_vu}}">
                            </div>
                            <div class="row clearfix"></div>

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
                                <label for="" class="col-form-label">Đơn vị nhận ngoài hệ thống</label>
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
                                <label for="sokyhieu" class="col-form-label">Đơn vị nội bộ </label>
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
                            <div class="col-md-3">
                                <label for="exampleInputEmail4">File</label>
                                <input type="file" class="form-control han-xu-ly" name="File" value=""  >
                            </div>
                            <div class="col-md-5 mt-4">
                                <label for="">Trả lời cho văn bản đến:</label>
                                <a class="them-van-ban-den" data-toggle="modal" data-target="#modal-them-van-ban-den">
                                    <span><i class="fa fa-plus-square-o"></i> Thêm văn bản đến</span>
                                </a>
                                <div class="row">
                                    @if ($vanbandi->listVanBanDen)
                                        @foreach($vanbandi->listVanBanDen as $vanBanDen)
                                            <div class="col-md-6 chi-tiet-vb-den">
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
                            <div class="col-md-4 mt-4">
                                <div class="form-group">
                                    <label for="">File văn bản:
                                        @forelse($vanbandi->filechinh as $key=>$filedata)
                                            <a class="seen-new-window" target="popup"
                                               href="{{$filedata->getUrlFile()}}">[File văn bản] &emsp; </a> <a class="btn-remove-item" href="{{route('xoaFileDi',$filedata->id)}}"><i class="fa fa-trash" aria-hidden="true" style="color: red"></i></a> |
                                        @empty
                                        @endforelse</label>
                                </div>
                            </div>
                            <div class="row clearfix"></div>
                            @if(Request::get('sua') == 1)
                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Số đi <span class="color-red">*</span></label>
                                <input type="number" name="so_di"  class="form-control"
                                       value="{{$vanbandi->so_di}}" style="color: red;font-size: 16px;font-weight: bold"
                                       autocomplete="off" required>
                            </div>
                            @endif

                            <div class=" col-md-12 text-center">
                                <button
                                    class="btn btn-danger" type="submit"><i class="fa fa-check mr-1"></i>
                                    <span>Cấp số</span></button>
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
