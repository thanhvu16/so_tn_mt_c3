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
                                <h3 class="box-title">Danh sách văn bản chuyển đơn vị</h3>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                        </div>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        Tổng số văn bản: <b style="font-size: 16px">{{ $vanBanChuyenPhong->total() }}</b>
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" style="vertical-align: middle" class="text-center">STT</th>
                                <th width="5%" style="vertical-align: middle" class="text-center">Số đến</th>
                                <th width="14%" style="vertical-align: middle" class="text-center">Thông tin</th>
                                <th width="12%"  style="vertical-align: middle"class="text-center">Cơ quan ban hành</th>
                                <th width="" style="vertical-align: middle" class="text-center">Trích yếu</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($vanBanChuyenPhong as $key=>$vbDen)
                                <tr >
                                    <td class="text-center">{{$key+1}} </td>
                                    <td style="color: red;font-weight: bold">{{$vbDen->vanBanDen->so_den}}</td>
                                    <td>
                                    <p>- Số ký hiệu: <span style="text-transform: uppercase">{{$vbDen->vanBanDen->so_ky_hieu}}</span></p>
                                    <p>- Ngày ban
                                            hành: {{ date('d/m/Y', strtotime($vbDen->vanBanDen->ngay_ban_hanh)) }}</p>
{{--                                    <p>- Số đến: <span--}}
{{--                                                class="font-bold" style="color: red">{{$vbDen->so_den}}</span></p>--}}
                                    {{--                                        <p>- Sổ văn bản: {{$vbDen->soVanBan->ten_so_van_ban ?? ''}}</p>--}}
                                    </td>
                                    <td>{{$vbDen->vanBanDen->co_quan_ban_hanh}}</td>
                                    <td style="text-align: justify">
                                        @if ($vbDen->vanBanDen->loai_van_ban_don_vi == 1)
                                            @if ($vbDen->vanBanDen->parent_id)

                                                @if ($vbDen->vanBanDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->parent_id.'?status=1' : $vbDen->vanBanDen->id .'?status=1') }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>
                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id.'?status=1' : $vbDen->vanBanDen->id .'?status=1') }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>
                                                @endif
                                            @else
                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id.'?status=1' : $vbDen->vanBanDen->id .'?status=1') }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>
                                                @elseif($vbDen->trinh_tu_nhan_van_ban == 1 || $vbDen->trinh_tu_nhan_van_ban == null )
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id.'?status=1' : $vbDen->vanBanDen->id .'?status=1') }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: red;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>
                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id.'?status=1' : $vbDen->vanBanDen->id .'?status=1') }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>
                                                @endif
                                            @endif
                                        @else
                                            @if ($vbDen->parent_id)

                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id : $vbDen->vanBanDen->id) }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>

                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id : $vbDen->vanBanDen->id) }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>

                                                @endif
                                            @else
                                                @if ($vbDen->vanBanDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id : $vbDen->vanBanDen->id) }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: blue;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>

                                                @elseif($vbDen->vanBanDen->trinh_tu_nhan_van_ban == 1 || $vbDen->vanBanDen->trinh_tu_nhan_van_ban == null )
                                                    <a   href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id : $vbDen->vanBanDen->id) }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: red;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>

                                                @else
                                                    <a  href="{{ route('van_ban_den_chi_tiet.show', $vbDen->vanBanDen->parent_id ? $vbDen->vanBanDen->parent_id : $vbDen->vanBanDen->id) }}" title="{{$vbDen->vanBanDen->trich_yeu}}"><span style="color: black;font-weight: bold">{{$vbDen->vanBanDen->trich_yeu}}</span></a><br>
                                                @endif
                                            @endif
                                        @endif

                                        @if($vbDen->vanBanDen->noi_dung != null)<span style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vbDen->vanBanDen->noi_dung ?? ''}}</span>@if($vbDen->vanBanDen->noi_dung != null)
                                            <br>@endif
                                        @if($vbDen->vanBanDen->han_xu_ly != null)<p style="color: red">(Hạn giải quyết: {{ date('d/m/Y', strtotime($vbDen->vanBanDen->han_xu_ly)) }})</p>@endif
                                        <span style="font-style: italic">Người nhập : {{$vbDen->vanBanDen->nguoiDung->ho_ten ?? ''}}</span> -
                                        <span style="font-style: italic"> @if($vbDen->vanBanDen->ngay_nhan != null)Ngày nhập: {{ date('d/m/Y', strtotime($vbDen->vanBanDen->ngay_nhan)) }}@endif</span>
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
                                {!! $vanBanChuyenPhong->appends(['so_van_ban_id' => Request::get('so_van_ban_id')])->render() !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection











