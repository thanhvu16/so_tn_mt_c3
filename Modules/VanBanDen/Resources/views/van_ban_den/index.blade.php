@extends('admin::layouts.master')
@section('page_title', 'Danh sách đến')
@section('content')
    <section class="content" style="font-size: 14px">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="col-md-6">
                            <div class="row">
                                <h3 class="box-title">Danh sách văn bản đến</h3>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
{{--                            <i>(STT mà đỏ: <span style="color: red">văn bản đang xử lý</span>; STT màu xanh: <span style="color: blue">Văn bản chưa được phân</span>; STT màu đen: <span style="color: black">Văn bản đã hoàn thành</span>)</i>--}}
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12 mt-1 ">
                        <div class="row">
                            <div class="col-md-6">
                                @if(auth::user()->hasRole([VAN_THU_HUYEN, VAN_THU_DON_VI]))
                                <a role="button" onclick="showModal()" class="btn btn-primary ">
                                    <span style="color: white;font-size: 14px"><i class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</span></a>
                                @endif
                                <a class=" btn btn-primary" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i> <span
                                        style="font-size: 14px">Tìm kiếm văn bản</span>
                                </a>
                            </div>
                                <div class="col-md-6 text-right">
                                    @if(auth::user()->hasRole([VAN_THU_HUYEN, VAN_THU_DON_VI]))
                                    <form action method="GET" action="{{ route('van-ban-den.index') }}" class="form-export">
                                        <input type="hidden" name="type"  value="">
                                        <input type="hidden" name="loai_van_ban_id"  value="{{Request::get('loai_van_ban_id') }}">
                                        <input type="hidden" name="so_van_ban_id"  value="{{Request::get('so_van_ban_id') }}">
                                        <input type="hidden" name="vb_so_den" value="{{Request::get('vb_so_den') }}">
                                        <input type="hidden" name="vb_so_den" value="{{Request::get('vb_so_den_end') }}">
                                        <input type="hidden" name="vb_so_ky_hieu"  value="{{Request::get('vb_so_ky_hieu') }}">
                                        <input type="hidden" name="don_vi_phoi_hop_id" value="{{Request::get('don_vi_phoi_hop_id') }}">
                                        <input type="hidden" name="start_date"  value="{{Request::get('start_date') }}">
                                        <input type="hidden" name="end_date" value="{{Request::get('end_date') }}">
                                        <input type="hidden" name="cap_ban_hanh_id"  value="{{Request::get('cap_ban_hanh_id') }}">
                                        <input type="hidden" name="co_quan_ban_hanh_id" value="{{Request::get('co_quan_ban_hanh_id') }}">
                                        <input type="hidden" name="nguoi_ky_id"  value="{{Request::get('nguoi_ky_id') }}">
                                        <input type="hidden" name="vb_trich_yeu"  value="{{Request::get('vb_trich_yeu') }}">
                                        <input type="hidden" name="year"  value="{{Request::get('year') }}">
                                        <input type="hidden" name="don_vi_id" value="{{Request::get('don_vi_id') }}">
                                        <input type="hidden" name="trinh_tu_nhan_van_ban" value="{{Request::get('trinh_tu_nhan_van_ban') }}">
                                        <input type="hidden" name="page" value="{{Request::get('page') }}">

                                        <button type="button" data-type="excel"
                                                class="btn btn-success waves-effect waves-light btn-sm btn-export-data"><i
                                                class="fa fa-file-excel-o"></i> Xuất Excel
                                        </button>
                                        <button type="button" data-type="word"
                                                class="btn btn-info waves-effect waves-light btn-sm btn-export-data"><i
                                                class="fa fa-file-word-o"></i> Xuất Word
                                        </button>
                                    </form>
                                    @endif
                                </div>

{{--                            @can('in sổ văn bản đơn vị')--}}
{{--                           --}}
{{--                            <div class="col-md-6 text-right">--}}
{{--                                <a role="button" href="{{route('in-so-van-ban-den.index')}}"  class="btn btn-success ">--}}
{{--                                    <span style="color: white;font-size: 14px"><i class="fa  fa-print"></i> In sổ</span></a>--}}
{{--                            </div>--}}
{{--                                @endcan--}}
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">

                            <div class="col-md-12 collapse {{ Request::get('search') == 1 || Request::get('year') ? 'in' : '' }} " id="collapseExample">
                                <form action="{{route('van-ban-den.index')}}" id="tim_kiem" method="get" >
                                        <div class="row">
                                            <div class="form-group col-md-3" id="loaivanban">
                                                <label for="loai_van_ban_id" class="col-form-label">Loại văn bản</label>
                                                <select class="form-control " name="loai_van_ban_id" id="loai_van_ban_id">
                                                    <option value="">Chọn loại văn bản</option>
                                                    @foreach ($ds_loaiVanBan as $loaiVanBan)
                                                        <option value="{{ $loaiVanBan->id }}" {{ Request::get('loai_van_ban_id') == $loaiVanBan->id ? 'selected' : '' }}
                                                        >{{ $loaiVanBan->ten_loai_van_ban }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Số văn bản</label>
                                                <select class="form-control  select-so-van-ban check-so-den-vb"
                                                        name="so_van_ban_id" id="so_van_ban_id">
                                                    <option value="">Chọn sổ văn bản</option>
                                                    @foreach ($ds_soVanBan as $soVanBan)
                                                        <option
                                                            value="{{ $soVanBan->id }}" {{ Request::get('so_van_ban_id') == $soVanBan->id ? 'selected' : '' }}>{{ $soVanBan->ten_so_van_ban }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="vb_so_den" class="col-form-label">Số đến từ</label>
                                                <input type="text" name="vb_so_den" class="form-control soden" value="{{Request::get('vb_so_den')}}"
                                                       id="vb_so_den"
                                                       placeholder="Số đến từ">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="vb_so_den_end" class="col-form-label">Đến số</label>
                                                <input type="text" name="vb_so_den_end" class="form-control soden" value="{{Request::get('vb_so_den_end')}}"
                                                       id="vb_so_den_end"
                                                       placeholder="Đến số">
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                                <input type="text" name="vb_so_ky_hieu"
                                                       value="{{Request::get('vb_so_ky_hieu')}}"
                                                       class="form-control file_insert"
                                                       id="vb_so_ky_hieu"
                                                       placeholder="Số ký hiệu">
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Ngày nhập từ</label>
                                                <div id="">
                                                    <input class="form-control " id="start_date"
                                                           value="{{Request::get('start_date')}}" type="date"
                                                           name="start_date">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Nhập đến ngày</label>
                                                <div id="">
                                                    <input class="form-control " id="end_date"
                                                           value="{{Request::get('end_date')}}" type="date"
                                                           name="end_date">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="co_quan_ban_hanh_id" class="col-form-label">Cơ quan ban
                                                    hành</label>
                                                <input type="text" name="co_quan_ban_hanh_id" id="co_quan_ban_hanh_id"
                                                       value="{{Request::get('co_quan_ban_hanh_id')}}"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="sokyhieu" class="col-form-label">Người ký</label>
                                                <input type="text" value="{{Request::get('nguoi_ky_id')}}"
                                                       name="nguoi_ky_id" id="nguoi_ky_id"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Đơn vị xử lý chính</label>
                                                <select class="form-control select2 show-tick"
                                                        name="don_vi_id" id="don_vi_id">
                                                    <option value="">-- Chọn đơn vị xử lý chính --</option>
                                                    @foreach ($danhSachDonVi as $donVi)
                                                        <option
                                                            value="{{ $donVi->id }}" {{ Request::get('don_vi_id') == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Đơn vị phối hợp</label>
                                                <select class="form-control select2" id="don_vi_phoi_hop_id"
                                                        name="don_vi_phoi_hop_id">
                                                    <option value="">-- Chọn đơn vị phối hợp --</option>
                                                    @foreach ($danhSachDonVi as $donVi)
                                                        <option
                                                            value="{{ $donVi->id }}" {{ Request::get('don_vi_phoi_hop_id') == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="col-form-label">Trạng thái văn bản</label>
                                                <select class="form-control select2"  id="trinh_tu_nhan_van_ban"  name="trinh_tu_nhan_van_ban">
                                                    <option value="">-- Chọn trạng thái--</option>
                                                    <option value="1" {{ Request::get('trinh_tu_nhan_van_ban') == 1 ? 'selected' : null }}>Chưa phân loại</option>
                                                    <option value="2" {{ Request::get('trinh_tu_nhan_van_ban') == 2 ? 'selected' : null }}>Đang xử lý</option>
                                                    <option value="10" {{ Request::get('trinh_tu_nhan_van_ban') == 10 ? 'selected' : null }}>Đã hoàn thành</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label class="col-form-label">Năm</label>
                                                <select name="year" id="year" class="form-control select2">
                                                    <option value="">-- Tất cả --</option>
                                                    @for($i = 2020; $i <= date('Y'); $i++)
                                                        <option value="{{ $i }}" {{ $i == Request::get('year') ? 'selected' : '' }}>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="form-group col-md-12" >
                                                <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                                <textarea rows="3"  class="form-control" placeholder="nội dung"
                                                          name="vb_trich_yeu"
                                                          type="text">{{Request::get('vb_trich_yeu')}}</textarea>
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <button type="submit" value="1" class="btn btn-primary" name="search">Tìm kiếm
                                                </button>
                                                @if(request('search') || request('year'))
                                                    <a href="{{ route('van-ban-den.index') }}">
                                                        <button type="button" class="btn btn-success">
                                                            <i class="fa fa-refresh"></i>
                                                        </button>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        Tổng số văn bản: <b style="font-size: 16px">{{ $ds_vanBanDen->total() }}</b>
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" style="vertical-align: middle" class="text-center">STT</th>
                                <th width="5%" style="vertical-align: middle" class="text-center">Số đến</th>
                                <th width="14%" style="vertical-align: middle" class="text-center">Thông tin</th>
                                <th width="12%"  style="vertical-align: middle"class="text-center">Cơ quan ban hành</th>
                                <th width="" style="vertical-align: middle" class="text-center">Trích yếu</th>
                                <th width="10%" style="vertical-align: middle" class="text-center">Đơn vị xử lý chính</th>
                                <th width="10%" style="vertical-align: middle" class="text-center">Đơn vị phối hợp</th>
                                <th width="5%" style="vertical-align: middle" class="text-center">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDen as $key=>$vbDen)
                                <tr >
                                    <td class="text-center">{{$key+1}} </td>
                                    <td style="color: red;font-weight: bold">{{$vbDen->so_den}}</td>
                                    <td>
                                    <p>- Số ký hiệu: {{$vbDen->so_ky_hieu}}  {{$vbDen->trinh_tu_nhan_van_ban}}</p>
                                    <p>- Ngày ban
                                            hành: {{ date('d/m/Y', strtotime($vbDen->ngay_ban_hanh)) }}</p>
{{--                                    <p>- Số đến: <span--}}
{{--                                                class="font-bold" style="color: red">{{$vbDen->so_den}}</span></p>--}}
                                    {{--                                        <p>- Sổ văn bản: {{$vbDen->soVanBan->ten_so_van_ban ?? ''}}</p>--}}
                                    </td>
                                    <td>{{$vbDen->co_quan_ban_hanh}}</td>
                                    <td style="text-align: justify">
                                        @if ($vbDen->loai_van_ban_don_vi == 1)
                                            @if ($vbDen->parent_id)

                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id.'?status=1' : $vbDen->id .'?status=1') }}" title="{{$vbDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>
                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id.'?status=1' : $vbDen->id .'?status=1') }}" title="{{$vbDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>
                                                @endif
                                            @else
                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id.'?status=1' : $vbDen->id .'?status=1') }}" title="{{$vbDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>
                                                @elseif($vbDen->trinh_tu_nhan_van_ban == 1 || $vbDen->trinh_tu_nhan_van_ban == null )
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id.'?status=1' : $vbDen->id .'?status=1') }}" title="{{$vbDen->trich_yeu}}"><span style="color: red;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>
                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id.'?status=1' : $vbDen->id .'?status=1') }}" title="{{$vbDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>
                                                @endif
                                            @endif
                                        @else
                                            @if ($vbDen->parent_id)

                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id : $vbDen->id) }}" title="{{$vbDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>

                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id : $vbDen->id) }}" title="{{$vbDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>

                                                @endif
                                            @else
                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id : $vbDen->id) }}" title="{{$vbDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>

                                                @elseif($vbDen->trinh_tu_nhan_van_ban == 1 || $vbDen->trinh_tu_nhan_van_ban == null )
                                                    <a   href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id : $vbDen->id) }}" title="{{$vbDen->trich_yeu}}"><span style="color: red;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>

                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id : $vbDen->id) }}" title="{{$vbDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->trich_yeu}}</span></a><br>
                                                @endif
                                            @endif
                                        @endif

                                        @if($vbDen->noi_dung != null)<span style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vbDen->noi_dung ?? ''}}</span>@if($vbDen->noi_dung != null)
                                            <br>@endif
                                                    @if($vbDen->han_xu_ly != null)<p style="color: red">(Hạn giải quyết: {{ date('d/m/Y', strtotime($vbDen->han_xu_ly)) }})</p>@endif
                                        <span style="font-style: italic">Người nhập : {{$vbDen->nguoiDung->ho_ten ?? ''}}</span> -
                                        <span style="font-style: italic"> @if($vbDen->ngay_nhan != null)Ngày nhập: {{ date('d/m/Y', strtotime($vbDen->ngay_nhan)) }}@endif</span>
                                        <div class="text-right " style="pointer-events: auto">
                                            @if($vbDen->vanBanDenFile)
                                            @forelse($vbDen->vanBanDenFile as $key=>$item)
                                                <a href="{{$item->getUrlFile()}}" target="popup" class="seen-new-window">
                                                    @if($item->duoi_file == 'pdf')<i
                                                        class="fa fa-file-pdf-o"
                                                        style="font-size:20px;color:red"></i>@elseif($item->duoi_file == 'docx' || $item->duoi_file == 'doc')
                                                        <i class="fa fa-file-word-o"
                                                           style="font-size:20px;color:blue"></i> @elseif($item->duoi_file == 'xlsx' || $item->duoi_file == 'xls')
                                                        <i class="fa fa-file-excel-o"
                                                           style="font-size:20px;color:green"></i> @endif
                                                </a>@if(count($vbDen->vanBanDenFile) == $key+1) @else &nbsp;
                                                |&nbsp; @endif
                                            @empty
                                            @endforelse
                                            @endif
                                            @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)
                                                <a title="Cập nhật file" href="{{route('ds_file',$vbDen->vb_den_id)}}"><span role="button">&emsp;<i class="fa  fa-search"></i></span></a>@endif

                                        </div>
                                            <i style="font-weight: initial">
                                                @if( $vbDen->chu_tri_phoi_hop == 1)(<span style="color: red">*</span> {{ $vbDen->chu_tri_phoi_hop == 1 ? 'Là văn bản giao sở' : '' }})@endif
                                            </i>


                                    </td>
                                    <td>
                                        <!--vb den don vi-->
                                        @if ($vbDen->parent_id)
                                            @foreach($vbDen->getParent()->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                @if (count($vbDen->getParent()->donViChuTri)-1 == $key)
                                                    <p>
                                                        {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                        <br>
                                                        <i>(C/B xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                            )</i>
                                                    </p>
                                                @endif
                                            @endforeach
                                        @else
                                        <!--vb den huyen-->
                                            @if($vbDen->donViChuTri)
                                                @foreach($vbDen->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                    @if (count($vbDen->donViChuTri)-1 == $key)
                                                        <p>
                                                            {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                            <br>
                                                            <i>(C/B xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                                )</i>
                                                        </p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                        <div class="text-right">
                                            @if ($vbDen->parent_id)
                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <span class="label label-primary">Đã hoàn thành</span>
                                                @else
                                                    <span class="label label-default">Đang xử lý</span>
                                                @endif
                                            @else
                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <span class="label label-success">Đã hoàn thành</span>
                                                @elseif($vbDen->trinh_tu_nhan_van_ban == 1 || $vbDen->trinh_tu_nhan_van_ban == null )
                                                    <span class="label label-danger">Chưa phân loại</span>
                                                @else
                                                    <span class="label label-default">Đang xử lý</span>
                                                @endif
                                            @endif
                                        </div>

                                    </td>
                                    <td>
                                        <!--vb den don vi-->
                                        @if ($vbDen->parent_id)
                                            @foreach($vbDen->getParent()->donViPhoiHop as $key => $chuyenNhanVanBanDonVi)
                                                @if (count($vbDen->getParent()->donViPhoiHop)-1 == $key)
                                                    <p>
                                                        {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                        <br>
                                                        <i>(C/B xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                            )</i>
                                                    </p>
                                                @endif
                                            @endforeach
                                        @else
                                        <!--vb den huyen-->
                                            @if($vbDen->donViPhoiHop)
                                                @foreach($vbDen->donViPhoiHop as $key => $chuyenNhanVanBanDonVi)
                                                    @if (count($vbDen->donViPhoiHop)-1 == $key)
                                                        <p>
                                                            {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                            <br>
                                                            <i>(C/B xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                                )</i>
                                                        </p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif


                                    </td>

                                    <td class="text-center" style="vertical-align: middle">
                                        @hasanyrole('văn thư đơn vị|văn thư sở')
                                        <form method="POST" action="{{route('delete_vb_den')}}">
                                            @csrf
                                            <a href="{{route('chi_tiet_van_ban_den',$vbDen->id)}}"
                                               class="fa fa-edit" role="button"
                                               title="Sửa">
                                                <i class="fas fa-file-signature"></i>
                                            </a><br><br>
                                            <button class="btn btn-action btn-color-red btn-icon btn-ligh btn-remove-item" role="button"
                                                    title="Xóa">
                                                <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>
                                            </button>
                                            <input type="text" class="hidden" value="{{$vbDen->id}}" name="id_vb">
                                        </form>
                                        @endrole
                                    </td>

                                </tr>



                            @empty
                                <td colspan="5" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">

                            </div>
                            <div class="col-md-6 text-right">
                                {!! $ds_vanBanDen->appends(['so_van_ban_id' => Request::get('so_van_ban_id'),'loai_van_ban_id' => Request::get('loai_van_ban_id'), 'vb_so_den' => Request::get('vb_so_den')
                       ,'vb_so_ky_hieu' => Request::get('vb_so_ky_hieu'),'don_vi_phoi_hop_id' => Request::get('don_vi_phoi_hop_id'),
                       'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),
                       'cap_ban_hanh_id' => Request::get('cap_ban_hanh_id'),'co_quan_ban_hanh_id' => Request::get('co_quan_ban_hanh_id'),'nguoi_ky_id' => Request::get('nguoi_ky_id'),
                       'vb_trich_yeu' => Request::get('vb_trich_yeu'), 'search' =>Request::get('search'), 'year' => Request::get('year'),
                       'don_vi_id' => Request::get('don_vi_id'), 'trinh_tu_nhan_van_ban' => Request::get('trinh_tu_nhan_van_ban')])->render() !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('multiple_file') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title"><i
                                                class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="sokyhieu" class="">Chọn tệp
                                                    tin<br><small><i>(Đặt tên file theo định dạng: số đến (vd:
                                                            1672.pdf))</i></small>
                                                </label>

                                                <input type="file" multiple name="ten_file[]"
                                                       accept=".xlsx,.xls,.doc,.docx,.txt,.pdf"/>
                                                <input type="text" id="url-file" value="123"
                                                       class="hidden" name="txt_file[]">
                                            </div>
                                            <div class="form-group col-md-4" >
                                                <button class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Tải lên</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script type="text/javascript">
        function showModal() {
            console.log(1);
            $("#myModal").modal('show');
        }
        $(document).ready(function() {
            // show the alert
            console.log(1);
            // setTimeout(function() {
            //     $(".alert").alert('close');
            // }, 2000);
        });
        $('.btn-export-data').on('click', function () {
            let type = $(this).data('type');
            // let loai_van_ban= $('[name=loai_van_ban_id]').val();
            // let so_van_ban_id= $('[name=so_van_ban_id]').val();
            // let vb_so_den= $('[name=vb_so_den]').val();
            // let vb_so_ky_hieu= $('[name=vb_so_ky_hieu]').val();
            // let don_vi_phoi_hop_id= $('[name=don_vi_phoi_hop_id]').val();
            // let start_date= $('[name=start_date]').val();
            // let end_date= $('[name=end_date]').val();
            // let cap_ban_hanh_id= $('[name=cap_ban_hanh_id]').val();
            // let co_quan_ban_hanh_id= $('[name=co_quan_ban_hanh_id]').val();
            // let nguoi_ky_id= $('[name=nguoi_ky_id]').val();
            // let vb_trich_yeu= $('[name=vb_trich_yeu]').val();
            // let year= $('[name=year]').val();
            // let don_vi_id= $('[name=don_vi_id]').val();
            // let trinh_tu_nhan_van_ban= $('[name=trinh_tu_nhan_van_ban]').val();
            $('input[name="type"]').val(type);
            $('.form-export').submit();
            hideLoading();
        });


    </script>

@endsection













