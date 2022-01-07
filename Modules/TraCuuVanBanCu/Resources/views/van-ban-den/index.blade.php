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
                                <h3 class="box-title">Danh sách văn bản đến cũ</h3>
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

                                <a class=" btn btn-primary" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i> <span
                                        style="font-size: 14px">Tìm kiếm văn bản</span>
                                </a>
                            </div>
                            <div class="col-md-6 text-right">

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
                                <form action="{{route('van-ban-den-cu.index')}}" id="tim_kiem" method="get" >
                                    <div class="row">
{{--                                        <div class="form-group col-md-3" id="loaivanban">--}}
{{--                                            <label for="loai_van_ban_id" class="col-form-label">Loại văn bản</label>--}}
{{--                                            <select class="form-control " name="loai_van_ban_id" id="loai_van_ban_id">--}}
{{--                                                <option value="">Chọn loại văn bản</option>--}}
{{--                                                @foreach ($ds_loaiVanBan as $loaiVanBan)--}}
{{--                                                    <option value="{{ $loaiVanBan->id }}" {{ Request::get('loai_van_ban_id') == $loaiVanBan->id ? 'selected' : '' }}--}}
{{--                                                    >{{ $loaiVanBan->ten_loai_van_ban }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="sokyhieu" class="col-form-label">Số văn bản</label>--}}
{{--                                            <select class="form-control  select-so-van-ban check-so-den-vb"--}}
{{--                                                    name="so_van_ban_id" id="so_van_ban_id">--}}
{{--                                                <option value="">Chọn sổ văn bản</option>--}}
{{--                                                @foreach ($ds_soVanBan as $soVanBan)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $soVanBan->id }}" {{ Request::get('so_van_ban_id') == $soVanBan->id ? 'selected' : '' }}>{{ $soVanBan->ten_so_van_ban }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
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
                                        {{--                                            <div class="form-group col-md-3" >--}}
                                        {{--                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Ngày nhập từ</label>--}}
                                        {{--                                                <div id="">--}}
                                        {{--                                                    <input class="form-control " id="start_date"--}}
                                        {{--                                                           value="{{Request::get('start_date')}}" type="date"--}}
                                        {{--                                                           name="start_date">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        <div class="form-group col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail4">Ngày nhập từ</label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control  datepicker"
                                                           name="start_date" id="start_date" value="{{Request::get('start_date')}}"
                                                           placeholder="dd/mm/yyyy" >
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail4">Nhập đến ngày</label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control  datepicker"
                                                           name="end_date" id="end_date" value="{{Request::get('end_date')}}"
                                                           placeholder="dd/mm/yyyy" >
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--                                            <div class="form-group col-md-3" >--}}
                                        {{--                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Nhập đến ngày</label>--}}
                                        {{--                                                <div id="">--}}
                                        {{--                                                    <input class="form-control " id="end_date"--}}
                                        {{--                                                           value="{{Request::get('end_date')}}" type="date"--}}
                                        {{--                                                           name="end_date">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        <div class="form-group col-md-3" >
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Cơ quan ban
                                                hành</label>
                                            <input type="text" name="co_quan_ban_hanh_id" id="co_quan_ban_hanh_id"
                                                   value="{{Request::get('co_quan_ban_hanh_id')}}"
                                                   class="form-control">
                                        </div>
                                        <div class="row clearfix"></div>
                                        <div class="form-group col-md-3" >
                                            <label for="sokyhieu" class="col-form-label">Người ký</label>
                                            <input type="text" value="{{Request::get('nguoi_ky_id')}}"
                                                   name="nguoi_ky_id" id="nguoi_ky_id"
                                                   class="form-control">
                                        </div>
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="sokyhieu" class="col-form-label">Đơn vị xử lý chính</label>--}}
{{--                                            <select class="form-control select2 show-tick"--}}
{{--                                                    name="don_vi_id" id="don_vi_id">--}}
{{--                                                <option value="">-- Chọn đơn vị xử lý chính --</option>--}}
{{--                                                @foreach ($danhSachDonVi as $donVi)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $donVi->id }}" {{ Request::get('don_vi_id') == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="sokyhieu" class="col-form-label">Đơn vị phối hợp</label>--}}
{{--                                            <select class="form-control select2" id="don_vi_phoi_hop_id"--}}
{{--                                                    name="don_vi_phoi_hop_id">--}}
{{--                                                <option value="">-- Chọn đơn vị phối hợp --</option>--}}
{{--                                                @foreach ($danhSachDonVi as $donVi)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $donVi->id }}" {{ Request::get('don_vi_phoi_hop_id') == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label class="col-form-label">Trạng thái văn bản</label>--}}
{{--                                            <select class="form-control select2"  id="trinh_tu_nhan_van_ban"  name="trinh_tu_nhan_van_ban">--}}
{{--                                                <option value="">-- Chọn trạng thái--</option>--}}
{{--                                                <option value="1" {{ Request::get('trinh_tu_nhan_van_ban') == 1 ? 'selected' : null }}>Chưa phân loại</option>--}}
{{--                                                <option value="2" {{ Request::get('trinh_tu_nhan_van_ban') == 2 ? 'selected' : null }}>Đang xử lý</option>--}}
{{--                                                <option value="10" {{ Request::get('trinh_tu_nhan_van_ban') == 10 ? 'selected' : null }}>Đã hoàn thành</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                        {{--                                            <div class="form-group col-md-3" >--}}
                                        {{--                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Ngày ban hành từ</label>--}}
                                        {{--                                                <div id="">--}}
                                        {{--                                                    <input class="form-control " id="start_date"--}}
                                        {{--                                                           value="{{Request::get('ngay_ban_hanh_date')}}" type="date"--}}
                                        {{--                                                           name="ngay_ban_hanh_date">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        <div class="form-group col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail4">Ngày ban hành từ</label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control  datepicker"
                                                           name="ngay_ban_hanh_date" id="ngay_ban_hanh_date" value="{{Request::get('ngay_ban_hanh_date')}}"
                                                           placeholder="dd/mm/yyyy" >
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail4">đến ngày</label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control  datepicker"
                                                           name="end_ngay_ban_hanh" id="end_ngay_ban_hanh" value="{{Request::get('end_ngay_ban_hanh')}}"
                                                           placeholder="dd/mm/yyyy" >
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--                                            <div class="form-group col-md-3" >--}}
                                        {{--                                                <label for="vb_ngay_ban_hanh" class="col-form-label"> đến ngày</label>--}}
                                        {{--                                                <div id="">--}}
                                        {{--                                                    <input class="form-control " id="end_date"--}}
                                        {{--                                                           value="{{Request::get('end_ngay_ban_hanh')}}" type="date"--}}
                                        {{--                                                           name="end_ngay_ban_hanh">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
{{--                                        <div class="form-group col-md-3" >--}}
{{--                                            <label class="col-form-label">Năm</label>--}}
{{--                                            <select name="year" id="year" class="form-control select2">--}}
{{--                                                <option value="">-- Tất cả --</option>--}}
{{--                                                @for($i = 2020; $i <= date('Y'); $i++)--}}
{{--                                                    <option value="{{ $i }}" {{ $i == Request::get('year') ? 'selected' : '' }}>--}}
{{--                                                        {{ $i }}</option>--}}
{{--                                                @endfor--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

                                        <div class="form-group col-md-12" >
                                            <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                            <textarea rows="3"  class="form-control" placeholder="nội dung"
                                                      name="vb_trich_yeu"
                                                      type="text">{{Request::get('vb_trich_yeu')}}</textarea>
                                        </div>
{{--                                        <div class="col-md-3">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="exampleInputEmail4">Độ Mật</label>--}}
{{--                                                <select class="form-control select2" name="do_mat">--}}
{{--                                                    <option value="">-- Chọn độ mật--</option>--}}
{{--                                                    @foreach($ds_mucBaoMat as $domatds)--}}
{{--                                                        <option value="{{ $domatds->id }}">{{ $domatds->ten_muc_do }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-3">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="exampleInputEmail4">Độ khẩn</label>--}}
{{--                                                <select class="form-control select2" name="do_khan">--}}
{{--                                                    <option value="">-- Chọn độ khẩn --</option>--}}
{{--                                                    @foreach($ds_doKhanCap as $dokhands)--}}
{{--                                                        <option value="{{ $dokhands->id }}">{{ $dokhands->ten_muc_do }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="form-group col-md-3 mt-4" >
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
                                <th width="10%" style="vertical-align: middle" class="text-center">Số ký hiệu</th>
                                <th width="10%" style="vertical-align: middle" class="text-center">Ngày ban hành</th>
                                <th width="15%"  style="vertical-align: middle"class="text-center">Cơ quan ban hành</th>
                                <th width="15%"  style="vertical-align: middle"class="text-center">Đơn vị chủ trì</th>
                                <th width="15%"  style="vertical-align: middle"class="text-center">Đơn vị phối hợp</th>
                                <th width="" style="vertical-align: middle" class="text-center">Trích yếu</th>

                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDen as $key=>$vbDen)
                                <tr >
                                    <td class="text-center">{{$key+1}} </td>
                                    <td style="color: red;font-weight: bold">{{$vbDen->SoDen}}</td>
                                    <td style="text-transform: uppercase" style="">{{$vbDen->SoKyHieu}}</td>
                                    <td class="text-center">
                                        {{ date('d/m/Y', strtotime($vbDen->NgayPhatHanh)) }}
                                    </td>
                                    <td>{{$vbDen->DonViGui}}</td>
                                    <td>
                                        {{$vbDen->tenDonVi->ten_donvi ?? ''}}
                                        @if($vbDen->donViChuTri)
                                            {{$vbDen->donViChuTri->tenDonVi->ten_donvi ?? ''}}
                                            {{$vbDen->tenDonVi->ten_donvi ?? ''}}
                                        @endif

                                    </td>
                                    <td>
                                        @if($vbDen->donViPhoiHop)
                                            {{$vbDen->donViPhoiHop->tenDonVi->ten_donvi ?? ''}}
                                        @endif

                                    </td>
                                    <td style="text-align: justify">
                                        <b>{{$vbDen->TrichYeu}}</b><br>

                                        <span style="font-style: italic">Người nhập : {{$vbDen->NguoiNhap ?? ''}}</span> -
                                        <span style="font-style: italic"> @if($vbDen->NgayNhap != null)Ngày nhập: {{ date('d/m/Y', strtotime($vbDen->NgayNhap)) }}@endif</span><br>
                                        @if($vbDen->HanTraLoi != null)<p style="color: red">(Hạn giải quyết: {{ date('d/m/Y', strtotime($vbDen->HanTraLoi)) }})</p>@endif
                                        <div class="text-right " style="pointer-events: auto">
                                        <a href="http://14.177.182.250:9999/cgt/vb/vb/{!! $vbDen->TepDinhKem!!}" target="popup" class="seen-new-window">
                                          [ File ]
                                        </a>
                                        </div>



{{--                                        <div class="text-right " style="pointer-events: auto">--}}
{{--                                            @if($vbDen->vanBanDenFile)--}}
{{--                                                @forelse($vbDen->vanBanDenFile as $key=>$item)--}}
{{--                                                    <a href="{{$item->TepDinhKem}}" target="popup" class="seen-new-window">--}}
{{--                                                        @if($item->duoi_file == 'pdf')<i--}}
{{--                                                            class="fa fa-file-pdf-o"--}}
{{--                                                            style="font-size:20px;color:red"></i>@elseif($item->duoi_file == 'docx' || $item->duoi_file == 'doc')--}}
{{--                                                            <i class="fa fa-file-word-o"--}}
{{--                                                               style="font-size:20px;color:blue"></i> @elseif($item->duoi_file == 'xlsx' || $item->duoi_file == 'xls')--}}
{{--                                                            <i class="fa fa-file-excel-o"--}}
{{--                                                               style="font-size:20px;color:green"></i> @endif--}}
{{--                                                    </a>@if(count($vbDen->vanBanDenFile) == $key+1) @else &nbsp;--}}
{{--                                                    |&nbsp; @endif--}}
{{--                                                @empty--}}
{{--                                                @endforelse--}}
{{--                                            @endif--}}
{{--                                            @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)--}}
{{--                                                <a title="Cập nhật file" href="{{route('ds_file',$vbDen->vb_den_id)}}"><span role="button">&emsp;<i class="fa  fa-search"></i></span></a>@endif--}}

{{--                                        </div>--}}
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
                                {!! $ds_vanBanDen->appends(['so_van_ban_id' => Request::get('so_van_ban_id'),'loai_van_ban_id' => Request::get('loai_van_ban_id'),'ngay_ban_hanh_date' => Request::get('ngay_ban_hanh_date'),'end_ngay_ban_hanh' => Request::get('end_ngay_ban_hanh'), 'vb_so_den' => Request::get('vb_so_den')
                       ,'vb_so_ky_hieu' => Request::get('vb_so_ky_hieu'),'don_vi_phoi_hop_id' => Request::get('don_vi_phoi_hop_id'),
                       'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),
                       'cap_ban_hanh_id' => Request::get('cap_ban_hanh_id'),'co_quan_ban_hanh_id' => Request::get('co_quan_ban_hanh_id'),'nguoi_ky_id' => Request::get('nguoi_ky_id'),
                       'vb_trich_yeu' => Request::get('vb_trich_yeu'), 'search' =>Request::get('search'), 'year' => Request::get('year'),
                       'don_vi_id' => Request::get('don_vi_id'), 'trinh_tu_nhan_van_ban' => Request::get('trinh_tu_nhan_van_ban')])->render() !!}
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


@endsection













