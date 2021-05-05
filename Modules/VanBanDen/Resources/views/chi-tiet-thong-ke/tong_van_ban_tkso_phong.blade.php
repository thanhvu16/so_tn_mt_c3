@extends('admin::layouts.master')
@section('page_title', 'Danh sách đến')
@section('content')
    <section class="content" style="font-size: 14px">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách văn bản</h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body" >
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" class="text-center">STT</th>
                                <th width="26%" class="text-center">Thông tin</th>
                                <th width="44%" class="text-center">Trích yếu</th>
                                <th width="21%" class="text-center">Đơn vị xử lý</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDen as $key=>$vbDen)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>
                                        <p>- Số ký hiệu: {{$vbDen->vanBanDen->so_ky_hieu}}</p>
                                        <p>- Ngày ban
                                            hành: {{ date('d/m/Y', strtotime($vbDen->vanBanDen->ngay_ban_hanh)) }}</p>
                                        <p>- Ban hành: {{$vbDen->vanBanDen->co_quan_ban_hanh}}</p>
                                        <p>- Số đến: <span
                                                class="font-bold" style="color: red">{{$vbDen->so_den}}</span></p>
                                        {{--                                        <p>- Sổ văn bản: {{$vbDen->soVanBan->ten_so_van_ban ?? ''}}</p>--}}
                                    </td>
                                    <td style="text-align: justify">
                                        @if ($vbDen->vanBanDen->loai_van_ban_don_vi == 1)
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id.'?status=1' : $vbDen->vanBanDen->id .'?status=1') }}" title="{{$vbDen->vanBanDen->trich_yeu}}">{{$vbDen->vanBanDen->trich_yeu}}</a><br>
                                        @else
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id : $vbDen->vanBanDen->id) }}" title="{{$vbDen->vanBanDen->trich_yeu}}">{{$vbDen->vanBanDen->trich_yeu}}</a><br>
                                        @endif

                                        @if($vbDen->vanBanDen->noi_dung != null)<span style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vbDen->vanBanDen->noi_dung ?? ''}}</span>@if($vbDen->vanBanDen->noi_dung != null)
                                            <br>@endif
                                        @if($vbDen->vanBanDen->han_xu_ly == null) (Hạn giải quyết: {{ date('d/m/Y', strtotime($vbDen->vanBanDen->han_xu_ly)) }})<br>@endif
                                        <span style="font-style: italic">Người nhập : {{$vbDen->vanBanDen->nguoiDung->ho_ten ?? ''}}</span> -
                                        <span style="font-style: italic">Ngày nhập : {{ date('d/m/Y', strtotime($vbDen->vanBanDen->ngay_nhan)) }}</span>
                                        <div class="text-right " style="pointer-events: auto">
                                            @if($vbDen->vanBanDen->vanBanDenFile)
                                                @forelse($vbDen->vanBanDen->vanBanDenFile as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="popup" class="seen-new-window">
                                                        @if($item->duoi_file == 'pdf')<i
                                                            class="fa fa-file-pdf-o"
                                                            style="font-size:20px;color:red"></i>@elseif($item->duoi_file == 'docx' || $item->duoi_file == 'doc')
                                                            <i class="fa fa-file-word-o"
                                                               style="font-size:20px;color:blue"></i> @elseif($item->duoi_file == 'xlsx' || $item->duoi_file == 'xls')
                                                            <i class="fa fa-file-excel-o"
                                                               style="font-size:20px;color:green"></i> @endif
                                                    </a>@if(count($vbDen->vanBanDen->vanBanDenFile) == $key+1) @else &nbsp;
                                                    |&nbsp; @endif
                                                @empty
                                                @endforelse
                                            @endif
                                            @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)
                                                <a title="Cập nhật file" href="{{route('ds_file',$vbDen->vanBanDen->vb_den_id)}}"><span role="button">&emsp;<i class="fa  fa-search"></i></span></a>@endif

                                        </div>
                                        <i style="font-weight: initial">
                                            @if( $vbDen->vanBanDen->chu_tri_phoi_hop == 1)(<span style="color: red">*</span> {{ $vbDen->vanBanDen->chu_tri_phoi_hop == 1 ? 'Là văn bản giao sở' : '' }})@endif
                                        </i>


                                    </td>
                                    <td>
                                        <!--vb den don vi-->
                                        @if ($vbDen->vanBanDen->parent_id)
                                            @foreach($vbDen->vanBanDen->getParent()->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                @if (count($vbDen->getParent()->donViChuTri)-1 == $key)
                                                    <p>
                                                        {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                        <br>
                                                        <i>(Cán bộ xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                            )</i>
                                                    </p>
                                                @endif
                                            @endforeach
                                        @else
                                        <!--vb den huyen-->
                                            @if($vbDen->vanBanDen->donViChuTri)
                                                @foreach($vbDen->vanBanDen->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                    @if (count($vbDen->vanBanDen->donViChuTri)-1 == $key)
                                                        <p>
                                                            {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                            <br>
                                                            <i>(Cán bộ xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                                )</i>
                                                        </p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </td>



                                </tr>



                            @empty
                                <td colspan="5" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                                Tổng số văn bản: <b>{{ $ds_vanBanDen->count() }}</b>
                            </div>
                            <div class="col-md-6 text-right">
{{--                                {!! $ds_vanBanDen->appends(['so_van_ban_id' => Request::get('so_van_ban_id'),'loai_van_ban_id' => Request::get('loai_van_ban_id'), 'vb_so_den' => Request::get('vb_so_den')--}}
{{--                       ,'vb_so_ky_hieu' => Request::get('vb_so_ky_hieu'),--}}
{{--                       'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),--}}
{{--                       'cap_ban_hanh_id' => Request::get('cap_ban_hanh_id'),'co_quan_ban_hanh_id' => Request::get('co_quan_ban_hanh_id'),'nguoi_ky_id' => Request::get('nguoi_ky_id'),--}}
{{--                       'vb_trich_yeu' => Request::get('vb_trich_yeu'), 'search' =>Request::get('search'), 'year' =>Request::get('year')])->render() !!}--}}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection
















