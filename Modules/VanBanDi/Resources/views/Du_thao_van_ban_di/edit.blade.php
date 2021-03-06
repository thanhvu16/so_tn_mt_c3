@extends('admin::layouts.master')
@section('page_title', 'Sửa dự thảo')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chỉnh sửa dự thảo</h3>
                    </div>
                    <form class="form-row"
                          action="{{  route('du-thao-van-ban.update',$duthao->id)}}"
                          method="post" enctype="multipart/form-data" id="formCreateDoc">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                        <div class=" col-md-3">
                            <label for="loai_van_ban_id" class="col-form-label">Loại văn bản</label>
                            <select class="form-control dropdown-search" name="loai_van_ban_id"
                                    id="loai_van_ban_id" autofocus required>
                                <option value="">Chọn loại văn bản</option>
                                @foreach ($ds_loaiVanBan as $loaiVanBan)
                                    <option
                                        value="{{ $loaiVanBan->id }}" {{ isset($duthao) && $duthao->loai_van_ban_id == $loaiVanBan->id ? 'selected' : '' }}>{{ $loaiVanBan->ten_loai_van_ban }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class=" col-md-3">
                            <label for="loai_van_ban_id" class="col-form-label">Lãnh đạo phòng phối
                                hợp</label>
                            <select name="lanh_dao_phong_phoi_hop[]" id="lanh_dao_phong_phoi_hop"
                                    class="form-control select2"
                                    multiple="multiple"
                                    required data-placeholder="Lãnh đạo phối hợp ...">
                                @foreach ($lanhdaotrongphong as $trongphong)
                                    <option
                                        value="{{ $trongphong->id }}" {{ isset($lay_can_bo_phong) && in_array($trongphong->id, $lay_can_bo_phong->pluck('can_bo_id')->toArray()) ? 'selected' : '' }}
                                    >{{ $trongphong->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 ">
                            <label for="loai_van_ban_id" class="col-form-label">Lãnh đạo phòng khác</label>
                            <select name="lanh_dao_phong_khac[]" id="lanh_dao_phong_khac"
                                    class="form-control select2"
                                    multiple="multiple"
                                    required data-placeholder="Lãnh đạo phòng khác  ...">
                                @foreach ($lanhdaokhac as $trongphong)
                                    <option
                                        value="{{ $trongphong->id }}" {{ isset($lay_can_bo_khac) && in_array($trongphong->id, $lay_can_bo_khac->pluck('can_bo_id')->toArray()) ? 'selected' : '' }}
                                        {{ isset($vanbandi) && $vanbandi->donvisoanthao_id == $donVi->ma_id ? 'selected' : '' }}>{{ $trongphong->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="so_ky_hieu" class="col-form-label">Số ký hiệu</label>
                            <input type="text" class="form-control" value="{{$duthao->so_ky_hieu}}"
                                   placeholder="số kí hiệu..."
                                   name="so_ky_hieu">
                        </div>
                        <div class=" col-md-12 mt-1">
                            <label for="sokyhieu" class="col-form-label ">Ý kiến</label>
                            <textarea rows="3" class="form-control" required placeholder="nội dung"
                                      name="y_kien"
                                      type="text">{{ old('y_kien',$duthao->y_kien) }}</textarea>
                        </div>
                        <div class=" col-md-12 mt-1">
                            <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                            <textarea rows="3" class="form-control" required placeholder="nội dung"
                                      name="vb_trich_yeu"
                                      type="text">{{ old('vb_trich_yeu',$duthao->vb_trich_yeu) }}</textarea>
                        </div>
                        <div class=" col-md-3 mt-1">
                            <label for="loai_van_ban_id" class="col-form-label">Người ký</label>
                            <select class="form-control dropdown-search" name="nguoi_ky" id="nguoi_ky"
                                    autofocus required>
                                @foreach ($ds_nguoiKy as $nguoiky)
                                    <option
                                        {{ isset($duthao) && $duthao->nguoi_ky == $nguoiky->id ? 'selected' : '' }} value="{{ $nguoiky->id }}">{{ $nguoiky->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class=" col-md-3 mt-1">
                            <label for="loai_van_ban_id" class="col-form-label">Chức vụ</label>
                            <input type="text" class="form-control"
                                   value="{{ old('vb_trich_yeu',$duthao->chuc_vu) }}" name="chuc_vu"
                                   placeholder="Chức vụ......">
                        </div>

                        <div class=" col-md-3 mt-1">
                            <label for="loai_van_ban_id" class="col-form-label">Ngày tháng</label>
                            <input type="date" class="form-control"
                                   value="{{ old('vb_trich_yeu',$duthao->ngay_thang) }}" name="ngay_thang"
                                   placeholder="......">
                        </div>
                        <div class="col-md-3 hidden">
                            <label for="vb_ngay_ban_hanh" class="col-form-label">Số trang</label>
                            <input class="form-control" id="so_trang"
                                   value="{{isset($duthao)? $duthao->so_trang: '1'}}" type="number"
                                   name="so_trang">
                        </div>
                        <div class="form-group col-md-3 mt-1">
                            <label for="vb_ngay_ban_hanh" class="col-form-label">Hạn xử lý</label>
                            <input class="form-control" id="so_trang"
                                   value="{{isset($duthao)? $duthao->han_xu_ly: ''}}" type="date"
                                   name="han_xu_ly">
                        </div>
                        <div class="col-md-12">
                           <span style="color: red">(*)</span> <span style="color: black;font-style: italic;">Danh sách file đã upload:&ensp; </span>
                                @foreach($file as $key=>$data)
                                   <a href="{{$data->getUrlFile()}}"
                                                             target="_blank">
                                       @if($data->stt == 1)
                                           [file phiếu trình]
                                       @elseif($data->stt == 2)
                                           [file trình ký]
                                           @elseif($data->stt == 3)
                                           [file hồ sơ]
                                           @endif
                                        &ensp;</a>
                                        <a href="{{route('delete_file_duthao',$data->id)}}" class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item" style="color: red"><i class="fa fa-trash"></i></a> &ensp; &ensp;@if(count($file) == $key+1) @else &nbsp;|&nbsp; @endif&ensp; &ensp;

                                @endforeach
                        </div>
                        <div class="col-md-12 mt-1">
                            <div class=" row duthaovb">
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


                                <div class=" col-md-6" style="margin-top: 35px">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a class="btn btn-success btn-xs" style="color: white"
                                               onclick="duthaovanban('ten_file[]')"
                                               role="button"
                                            ><i class="fa fa-plus"></i>
                                            </a>
                                            <b class="text-danger"> Thêm file hồ sơ</b>
                                        </div>
                                        <div class="col-md-8">

                                            <a class="btn btn-primary " role="button"
                                               href="{{route('du-thao-van-ban.index')}}"
                                            ><i
                                                    class="fa fa-plus"></i> Nhập mới
                                            </a>
                                            <a class="btn btn-success " role="button"
                                               href="{{route('Danhsachduthao')}}"
                                            ><i
                                                    class="fa fa-bars"></i> Danh sách
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                            <div class="col-md-12 text-center">
                                <button type="button" onclick="submit();"
                                        class="btn btn-danger"><i
                                        class="fa fa-check"></i>
                                    <span>Cập nhật dự thảo</span></button>
                            </div>
                        </div>
                        <div class="col-md-12"><br></div>
                        <div class="col-md-12"><br></div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection


