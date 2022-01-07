@extends('admin::layouts.master')
@section('page_title', 'Kết thúc văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Kết thúc văn bản</h3>
                    </div>
                    @include('vanbandi::van_ban_di.form_them_van_ban_den')
                    <div class="box-body">
                        <form class="form-row"
                              action="{{ route('ket-thuc-van-ban-den.store')}}"
                              method="post" enctype="multipart/form-data" id="formCreateDoc">
                            @csrf
                            <input type="hidden" name="van_ban_den_id">
                            <div class="form-group col-md-3">
                                <label for="sodi" class="col-form-label">Số đi <span class="color-red">*</span></label>
                                <input type="text" value="" style="font-size: 18px;color: red;font-weight: bold"
                                       id="sodi" name="so_di" autofocus class="form-control"
                                       placeholder="Nhập số đi ..." required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Loại văn bản <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2 loai-van-ban-chanh-vp" autofocus name="loaivanban_id" id="loaivanban_id" required>
                                    <option value="">-- Chọn Loại Văn Bản --</option>
                                    @foreach ($ds_loaiVanBan as $loaiVanBan)
                                        <option value="{{$loaiVanBan->id}}"
                                        >{{$loaiVanBan->ten_loai_van_ban}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2" name="sovanban_id" required>
                                    @foreach ($ds_soVanBan as $data)
                                        <option value="{{ $data->id }}"
                                        >{{ $data->ten_so_van_ban}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Ký hiệu <span class="color-red">*</span></label>
                                <input type="text" value="" style="text-transform: uppercase "
                                       id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                       placeholder="Nhập ký hiệu văn bản đi..." required>
                            </div>

                            {{--                            <div class="form-group col-md-3">--}}
                            {{--                                <label for="sokyhieu" class="col-form-label">Ngày ban hành <span class="color-red">*</span></label>--}}
                            {{--                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh" class="form-control"--}}
                            {{--                                       value=""--}}
                            {{--                                       autocomplete="off" required>--}}
                            {{--                            </div>--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vb_ngaybanhanh">Ngày ban hành <span
                                            style="color: red">*</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control  datepicker"
                                               name="vb_ngaybanhanh" id="vb_ngaybanhanh" autocomplete="off"
                                               placeholder="dd/mm/yyyy" required>
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2-search select2" name="donvisoanthao_id" required>
                                    <option value="">-- Chọn đơn vị soạn thảo --</option>
                                    @foreach ($ds_DonVi as $donVi)
                                        <option value="{{ $donVi->id }}"{{  auth::user()->don_vi_id == $donVi->id ? 'selected' : '' }}
                                        >{{ $donVi->ten_don_vi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($nguoinhan != null)
                                <div class="form-group col-md-3">
                                    <label for="sokyhieu" class="col-form-label">Người duyệt <span class="color-red">*</span></label>
                                    <select name="nguoi_nhan" id="" class="form-control select2 ">
                                        @foreach ($nguoinhan as $data)
                                            <option value="{{ $data->id }}"
                                            >{{ $data->ho_ten}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2 layidnguoiky" name="nguoiky_id" id="nguoi_ky_app" required>
                                    <option value="">-- Chọn Người Ký --</option>
                                    @if (!empty($ds_nguoiKy))
                                        @foreach ($ds_nguoiKy as $nguoiKy)
                                            <option data-chuc-vu ="{{ $nguoiKy->chucvu->ten_chuc_vu ?? null }}" value="{{ $nguoiKy->id }}"
                                            >{{$nguoiKy->ho_ten}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ</label>
                                <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu" value="">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="sokyhieu" class="col-form-label ">Trích yếu <span class="color-red">*</span></label>
                                <textarea rows="3" name="vb_trichyeu" class="form-control no-resize" placeholder="Nhập nội dung trích yếu ..."
                                          required></textarea>
                            </div>
                            <div class=" col-md-6 form-group">
                                <label for="exampleInputEmail2">Đơn vị phát hành  <span class="color-red">*</span></label>
                                <select class="form-control select2" name="phong_phat_hanh" id=""  required>
                                    <option value="">Chọn phòng phát hành</option>
                                    @foreach ($ds_DonVi_phatHanh as $DonVi_phatHanh)
                                        <option value="{{ $DonVi_phatHanh->id }}" {{ isset($vanbanduthao) && $vanbanduthao->phong_phat_hanh == $DonVi_phatHanh->id ? 'selected' : '' }} >{{ $DonVi_phatHanh->ten_don_vi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group ">
                                <label for="">Trả lời cho văn bản đến:</label><br>
                                <a class="them-van-ban-den" style="cursor: pointer" data-toggle="modal" data-target="#modal-them-van-ban-den">
                                    <span><i class="fa fa-plus-square-o"></i> Thêm văn bản đến</span>
                                </a>
                                <div class="row main-so-ky-hieu-van-ban-den">

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class=" row duthaovb">
                                    <div class="form-group col-md-3" >
                                        <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                        <select class="form-control show-tick" name="dokhan_id" required>--}}
                                            @foreach ($ds_doKhanCap as $doKhanCap)
                                                <option value="{{ $doKhanCap->id }}"
                                                >{{ $doKhanCap->ten_muc_do}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                        <select class="form-control show-tick " name="dobaomat_id" required>--}}
                                            @foreach ($ds_mucBaoMat as $doBaoMat)
                                                <option value="{{ $doBaoMat->id }}"
                                                >{{ $doBaoMat->ten_muc_do}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3  mt-1 ">
                                        <label for="url-file" class="col-form-label">File văn bản đi</label>
                                        <div class="form-line input-group control-group">
                                            <input type="file" id="url-file" name="file[]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 mt-4">
                                        <button  type="submit" class="btn btn-info waves-effect waves-light"><i class="fa fa-plus-square-o mr-1"></i>
                                            <span>Tạo văn bản</span></button>
                                    </div>


                                </div>
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
    <script src="{{ asset('modules/quanlyvanban/js/noigui.js') }}"></script>
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
