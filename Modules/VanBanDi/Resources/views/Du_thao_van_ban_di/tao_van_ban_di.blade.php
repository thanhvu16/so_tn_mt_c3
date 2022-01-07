@extends('admin::layouts.master')
@section('page_title', 'Tạo văn bản đi')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tạo văn bản đi</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-row"
                              action="{{route('tao_van_ban_di')}}"
                              method="post" enctype="multipart/form-data" id="formCreateDoc">
                            @csrf
                            <input type="hidden" name="van_ban_den_don_vi_id" value="{{ isset($vanbanduthao) ? $vanbanduthao->van_ban_den_don_vi_id : null }}" >
                            <div class="form-group col-md-3" id="loaivanban">
                                <label for="linhvuc_id" class="col-form-label">Loại văn bản <span class="color-red">*</span></label>
                                <select class="form-control show-tick dropdown-search loai-van-ban-chanh-vp" name="loaivanban_id"
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
                                <label for="sokyhieu" class="col-form-label">Ngày ban hành</label>
                                <input type="date" name="vb_ngaybanhanh" id="vb_ngaybanhanh"
                                       class="form-control" style="font-size:16px;width: 100%"
                                       value="{{$date}}"
                                       autocomplete="off" required>
                            </div>



                            @if($nguoinhan != null)
                            <div class="form-group col-md-3">
                                <label for="sokyhieu" class="col-form-label">Người nhận <span style="color: red">*</span></label>
                                <select name="nguoi_nhan" id="" class="form-control dropdown-search">
                                    @foreach ($nguoinhan as $data)
                                        <option value="{{ $data->id }}" {{ isset($vanbanduthao) && $vanbanduthao->nguoi_ky == $data->id ? 'selected' : '' }}
                                        >{{ $data->ho_ten}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
{{--                            <div class="form-group col-md-3">--}}
{{--                                <label for="sokyhieu" class="col-form-label">Số ký hiệu <span class="color-red">*</span></label>--}}
{{--                                <input type="text"--}}
{{--                                       value="{{ old('vb_sokyhieu', isset($vanbanduthao) ? $vanbanduthao->so_ky_hieu : '') }}"--}}
{{--                                       id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"--}}
{{--                                       placeholder="Nhập số ký hiệu văn bản đi..." required>--}}
{{--                            </div>--}}



                            <div class="form-group col-md-3" >
                                <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo <span class="color-red">*</span></label>
                                <select class="form-control show-tick dropdown-search" name="donvisoanthao_id"
                                        required>
                                    <option value="">-- Chọn Đơn Vị Soạn Thảo --</option>
                                    @foreach ($ds_DonVi as $donVi)
                                        <option value="{{ $donVi->id }}"
                                            {{  auth::user()->don_vi_id == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{--                                    <div class="form-group col-md-3" >--}}
                            {{--                                        <label for="linhvuc_id" class="col-form-label">Lĩnh vực <span style="color: red">*</span></label>--}}
                            {{--                                        <select class="form-control show-tick dropdown-search" name="linhvuc_id"--}}
                            {{--                                                required>--}}
                            {{--                                            <option value="">-- Chọn Lĩnh Vực --</option>--}}
                            {{--                                            @foreach ($ds_linhVuc as $linhVuc)--}}
                            {{--                                                <option value="{{ $linhVuc->id }}"--}}
                            {{--                                                    {{ isset($vanbanduthao) && $vanbanduthao->linhvuc_id == $linhVuc->id ? 'selected' : '' }}>{{ $linhVuc->ten_linh_vuc_van_ban}}</option>--}}
                            {{--                                            @endforeach--}}
                            {{--                                        </select>--}}
                            {{--                                    </div>--}}
                            <div class="form-group col-md-3" >
                                <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký <span class="color-red">*</span></label>
                                <select class="form-control show-tick dropdown-search" name="nguoiky_id"  id="nguoi_ky_app"
                                        required>
                                    <option value="">-- Chọn Người Ký --</option>
                                    @foreach ($ds_nguoiKy as $nguoiKy)
                                        <option value="{{ $nguoiKy->id }}"
                                            {{ isset($vanbanduthao) && $vanbanduthao->nguoi_ky == $nguoiKy->id ? 'selected' : '' }}>{{$nguoiKy->ho_ten}}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="hidden" name="nguoi_ky_duthao" value="{{$vanbanduthao->nguoi_ky}}">
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="sokyhieu" class="col-form-label">Chức vụ <span class="color-red">*</span></label>
                                <input type="text"
                                       value="{{isset($vanbanduthao) ? $vanbanduthao->chuc_vu : ''}}"
                                       id="chuc_vu" name="chuc_vu"  class="form-control"
                                       placeholder="chức vụ..." required>
                            </div>

                            <div class=" col-md-3" >
                                <label for="loai_van_ban_id" class="col-form-label">Độ khẩn</label>
                                <select class="form-control show-tick dropdown-search" name="dokhan_id"
                                        required>--}}
                                    @foreach ($ds_doKhanCap as $doKhanCap)
                                        <option value="{{ $doKhanCap->id }}"
                                            {{ isset($vanbanduthao) && $vanbanduthao->dokhan_id == $doKhanCap->id ? 'selected' : '' }}>{{ $doKhanCap->ten_muc_do}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                <select class="form-control show-tick dropdown-search" name="dobaomat_id"
                                        required>--}}
                                    @foreach ($ds_mucBaoMat as $doBaoMat)
                                        <option
                                            value="{{ $doBaoMat->id }}" {{ $doBaoMat->macDinh ? 'selected' : ''  }}
                                            {{ isset($vanbanduthao) && $vanbanduthao->dobaomat_id == $doBaoMat->id ? 'selected' : '' }}>{{ $doBaoMat->ten_muc_do}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label for="so-ban" class="col-form-label">Số bản</label>
                                <input type="text" id="so-ban" class="form-control" placeholder="số bản.." name="so_ban" value="">
                            </div>
                            <div class="form-group col-md-12" >
                                <label for="sokyhieu" class="col-form-label ">Trích yếu <span class="color-red">*</span></label>
                                <textarea rows="3"  name="vb_trichyeu" class="form-control no-resize"
                                          placeholder="Nhập nội dung trích yếu ..."
                                          required>{{ old('vb_trichyeu', isset($vanbanduthao) ? $vanbanduthao->vb_trich_yeu : '') }}</textarea>
                            </div>
                            <div class="form-group col-md-12 hidden">
                                <label for="sokyhieu" class="col-form-label">Đơn vị nhận trong thành phố</label>
                                <select name="don_vi_nhan_trong_thanh_php[]" id="don_vi_nhan"
                                        class="form-control select2 select2-hidden-accessible"
                                        multiple="multiple"
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                        @foreach ($emailtrongthanhpho as $email)
                                            <option value="{{ $email->id }}"
                                            >{{ $email->ten_don_vi}}</option>
                                        @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-12 ">
                                <label for="sokyhieu" class="col-form-label">Đơn vị nội bộ </label>
                                <select name="don_vi_nhan_van_ban_di[]" id="don_vi_nhan"
                                        class="form-control select2 select2-hidden-accessible"
                                        multiple required
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                    @foreach ($ds_DonVi_nhan as $donVi)
                                        <option value="{{ $donVi->id }}"
                                        >{{ $donVi->ten_don_vi }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-12 ">
                                <label for="" class="col-form-label">Đơn vị nhận ngoài hệ thống</label>
                                <select name="don_vi_nhan_ngoai_thanh_pho[]" id="don_vi_nhan_ngoai"
                                        class="form-control select2 select2-hidden-accessible"
                                        multiple
                                        data-placeholder=" Chọn đơn vị nhận ...">
                                            @foreach ($emailngoaithanhpho as $emailngoai)
                                                <option value="{{ $emailngoai->id }}"
                                                >{{ $emailngoai->ten_don_vi}}</option>
                                            @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-12 ">
                                <span style="font-style: italic">( <span style="color: red">Có thể thêm mới nơi nhận mail bên ngoài phía dưới</span> )</span><br>

                                <div class="row themnoinhan">

                                    <div class="col-md-4">
                                        <label for="" class="col-form-label">Tên Đơn vị nhận ngoài</label>
                                        <input type="text"
                                               value=""
                                                name="ten_don_vi_them[]"  class="form-control"
                                               placeholder="Tên đơn vị..." >
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email_them" class="col-form-label">Email</label>
                                        <input type="text"
                                               value=""
                                                name="email_them[]"  class="form-control"
                                               placeholder="Email..." >
                                    </div>
                                    <div class="col-md-5 mt-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a class="btn btn-success btn-xs" style="color: white"
                                                   onclick="themnoinhan()"
                                                   role="button"
                                                ><i class="fa fa-plus"></i>
                                                </a>
                                                <b class="text-danger"> Thêm nơi nhận</b> &emsp;

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row clearfix"></div>



{{--                 --}}

                            <div class="col-md-12">
                                <span style="color: red">*</span> <span style="color: black;font-style: italic;">Danh sách file đã upload:&ensp; </span>
                                @foreach($file as $key=>$data)
                                    <a href="{{$data->getUrlFile()}}"
                                       target="_blank">

                                        @if($data->stt == 1)
                                            [file phiếu trình]&ensp;
                                        @elseif($data->stt == 2)
                                            [file trình ký]&ensp;
                                        @elseif($data->stt == 3)
                                            [file hồ sơ]&ensp;

                                            @endif

                                            &ensp;
                                    </a>
                                    <a href="{{route('delete_file_duthao',$data->id)}}" class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item" style="color: red"><i class="fa fa-trash"></i></a> &ensp; &ensp;@if(count($file) == $key+1) @else &nbsp;|&nbsp; @endif&ensp; &ensp;

                                    @endforeach<span style="font-style: italic">(Chú ý: Nếu không chọn file thì file cuối cùng sẽ là file văn bản đi)</span>
                            </div>

                            <div class="form-group col-md-12 mt-4">
                                <div class="row duthaovb">
                                    <div class="col-md-3 form-group">
                                        <label for="sokyhieu" class="col-form-label">File trình ký</label>
                                        <div class="">
                                            <input type="file" id="url-file" name="file_trinh_ky[]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="url-file" class="col-form-label">File phiếu trình</label>
                                        <div class="">
                                            <input type="file" id="url-file" name="file_phieu_trinh[]" class="form-control">
                                        </div>
                                    </div>


                                    <div class=" col-md-3 form-group">
                                        <label for="exampleInputEmail2">Đơn vị phát hành  <span class="color-red">*</span></label>
                                        <select class="form-control select2" name="phong_phat_hanh" id=""  required>
                                            <option value="">Chọn phòng phát hành</option>
                                            @foreach ($ds_DonVi_phatHanh as $DonVi_phatHanh)
                                                <option value="{{ $DonVi_phatHanh->id }}" {{ isset($vanbanduthao) && $vanbanduthao->phong_phat_hanh == $DonVi_phatHanh->id ? 'selected' : '' }} >{{ $DonVi_phatHanh->ten_don_vi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row clearfix"></div>
                                    <div class=" col-md-3 form-group" >
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


                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit"  class="btn btn-primary"><i
                                        class="fa fa-plus mr-1"></i>
                                    <span>{{ isset($vanbandi) ? 'Cập nhật' : 'Thêm mới' }}</span>
                                </button>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script>
        $(document).ready(function () {
            let loai_van_ban = $('[name=loaivanban_id]').val();
            let nguoi_ky_duthao = $('[name=nguoi_ky_duthao]').val();

            $.ajax({
                url: APP_URL + '/lay-nguoi-ky-chanh-vp',
                type: 'POST',
                data: {
                    loai_van_ban: loai_van_ban,
                },


            })
                .done(function (res) {
                    let selectAttributes = res.ds_nguoi_Ky.map((function (attribute) {
                        return `<option value="${attribute.id}" >${attribute.ho_ten}</option>`;
                    }));
                    $('#nguoi_ky_app').html('');
                    $('#nguoi_ky_app').append(selectAttributes);
                    $(`#nguoi_ky_app option[value="${nguoi_ky_duthao}"]`).prop('selected', 'selected');



                });

        });
    </script>
@endsection
