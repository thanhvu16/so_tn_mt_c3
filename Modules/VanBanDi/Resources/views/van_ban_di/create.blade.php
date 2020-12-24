@extends('admin::layouts.master')
@section('page_title', 'Thêm văn bản đi')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Văn bản đi</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-row"
                              action="{{ route('van-ban-di.store')}}"
                              method="post" enctype="multipart/form-data" id="formCreateDoc">
                            @csrf

                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Loại văn bản <span class="color-red">*</span></label>
                                <select class="form-control show-tick " autofocus name="loaivanban_id" id="loaivanban_id" required>
                                    <option value="">-- Chọn Loại Văn Bản --</option>
                                    @foreach ($ds_loaiVanBan as $loaiVanBan)
                                        <option value="{{$loaiVanBan->id}}"
                                        >{{$loaiVanBan->ten_loai_van_ban}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi <span class="color-red">*</span></label>
                                <select class="form-control show-tick" name="sovanban_id" required>
                                    @foreach ($ds_soVanBan as $data)
                                        <option value="{{ $data->id }}"
                                        >{{ $data->ten_so_van_ban}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Số ký hiệu <span class="color-red">*</span></label>
                                <input type="text" value=""
                                       id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                       placeholder="Nhập ký hiệu văn bản đi..." required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Ngày ban hành <span class="color-red">*</span></label>
                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh" class="form-control"
                                       value=""
                                       autocomplete="off" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span class="color-red">*</span></label>
                                <select class="form-control show-tick select2-search" name="donvisoanthao_id" required>
                                    <option value="">-- Chọn đơn vị soạn thảo --</option>
                                    @foreach ($ds_DonVi as $donVi)
                                        <option value="{{ $donVi->id }}"{{  auth::user()->don_vi_id == $donVi->id ? 'selected' : '' }}
                                        >{{ $donVi->ten_don_vi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($nguoinhan != null)
                                <div class="form-group col-md-3">
                                    <label for="sokyhieu" class="col-form-label">Người duyệt</label>
                                    <select name="nguoi_nhan" id="" class="form-control ">
                                        @foreach ($nguoinhan as $data)
                                            <option value="{{ $data->id }}"
                                            >{{ $data->ho_ten}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif




                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span class="color-red">*</span></label>
                                <select class="form-control show-tick  layidnguoiky" name="nguoiky_id" required>
                                    <option value="">-- Chọn Người Ký --</option>
                                    @foreach ($ds_nguoiKy as $nguoiKy)
                                        <option data-chuc-vu ="{{ $nguoiKy->chucvu->ten_chuc_vu ?? null }}" value="{{ $nguoiKy->id }}"
                                        >{{$nguoiKy->ho_ten}}</option>
                                    @endforeach
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
                            <div class="form-group col-md-12 hidden">
                                <label for="sokyhieu" class="col-form-label">Đơn vị nhận trong thành phố</label>
                                <select name="don_vi_nhan_trong_thanh_php[]" id="don_vi_nhan"
                                        class="form-control select2"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($emailtrongthanhpho as $email)
                                        <option value="{{ $email->id }}"
                                        >{{ $email->ten_don_vi}}</option>
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
                                        <option value="{{ $donVi->id }}"
                                        >{{ $donVi->ten_don_vi }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-12 ">
                                <label for="" class="col-form-label">Đơn vị nhận bên ngoài</label>
                                <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                        class="form-control select2"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($emailngoaithanhpho as $emailngoai)
                                        <option value="{{ $emailngoai->id }}"
                                        >{{ $emailngoai->ten_don_vi}}</option>
                                    @endforeach

                                </select>
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
                                        <label for="sokyhieu" class="col-form-label">File trình ký</label>
                                        <div class="form-line input-group control-group">
                                            <input type="file" id="url-file" name="file_trinh_ky[]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3   mt-1">
                                        <label for="url-file" class="col-form-label">File phiếu trình</label>
                                        <div class="form-line input-group control-group">
                                            <input type="file" id="url-file" name="file_phieu_trinh[]" class="form-control">
                                        </div>
                                    </div>



                                    <div class="row clearfix"></div>
                                    <div class="col-md-offset-9 mt-2 text-right" style="color: white">
                                        <a class="btn btn-primary btn-xs" onclick="duthaovanban()" role="button"
                                        ><i class="fa fa-plus"></i>
                                        </a>
                                        <b class="text-danger"> Thêm file hồ sơ</b>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group col-md-3 mt-1">
                                <button  type="submit" class="btn btn-info waves-effect waves-light"><i class="fa fa-plus-square-o mr-1"></i>
                                    <span>Tạo văn bản</span></button>
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
