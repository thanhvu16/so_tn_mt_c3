@extends('admin::layouts.master')
@section('page_title', 'Danh sách đến')
@section('content')
    <section class="content" style="font-size: 14px">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12 ">
                        <h5 style="font-weight: bold">- Cán bộ xử lý: {{ $user->ho_ten }}<br><br>
                            - Đơn vị:  {{ $user->donVi->ten_don_vi }}
                        </h5>
                    </div>

                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
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
                            @forelse ($danhSachVanBanDen as $key=>$vanBanDen)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>
                                        <p>- Số ký hiệu: {{$vanBanDen->so_ky_hieu}}</p>
                                        <p>- Ngày ban
                                            hành: {{ date('d/m/Y', strtotime($vanBanDen->ngay_ban_hanh)) }}</p>
                                        <p>- Ban hành: {{$vanBanDen->co_quan_ban_hanh}}</p>
                                        <p>- Số đến: <span
                                                class="font-bold" style="color: red">{{$vanBanDen->so_den}}</span></p>
                                        {{--                                        <p>- Sổ văn bản: {{$vanBanDen->soVanBan->ten_so_van_ban ?? ''}}</p>--}}
                                    </td>
                                    <td style="text-align: justify">
                                        @if ($vanBanDen->loai_van_ban_don_vi == 1)
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->parent_id ? $vanBanDen->parent_id.'?status=1' : $vanBanDen->id .'?status=1') }}"
                                               title="{{$vanBanDen->trich_yeu}}">{{$vanBanDen->trich_yeu}}</a><br>
                                        @else
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->parent_id ? $vanBanDen->parent_id : $vanBanDen->id) }}"
                                               title="{{$vanBanDen->trich_yeu}}">{{$vanBanDen->trich_yeu}}</a><br>
                                        @endif

                                        @if($vanBanDen->noi_dung != null)<span
                                            style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vanBanDen->noi_dung ?? ''}}</span>@if($vanBanDen->noi_dung != null)
                                            <br>@endif
                                        @if($vanBanDen->han_xu_ly == null) (Hạn giải
                                        quyết: {{ date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) }})<br>@endif
                                        <span
                                            style="font-style: italic">Người nhập : {{$vanBanDen->nguoiDung->ho_ten ?? ''}}</span>
                                        -
                                        <span
                                            style="font-style: italic">Ngày nhập : {{ date('d/m/Y', strtotime($vanBanDen->ngay_nhan)) }}</span>
                                        <div class="text-right " style="pointer-events: auto">
                                            @if($vanBanDen->vanBanDenFile)
                                                @forelse($vanBanDen->vanBanDenFile as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="popup"
                                                       class="seen-new-window">
                                                        @if($item->duoi_file == 'pdf')<i
                                                            class="fa fa-file-pdf-o"
                                                            style="font-size:20px;color:red"></i>@elseif($item->duoi_file == 'docx' || $item->duoi_file == 'doc')
                                                            <i class="fa fa-file-word-o"
                                                               style="font-size:20px;color:blue"></i> @elseif($item->duoi_file == 'xlsx' || $item->duoi_file == 'xls')
                                                            <i class="fa fa-file-excel-o"
                                                               style="font-size:20px;color:green"></i> @endif
                                                    </a>@if(count($vanBanDen->vanBanDenFile) == $key+1) @else &nbsp;
                                                    |&nbsp; @endif
                                                @empty
                                                @endforelse
                                            @endif
                                            @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)
                                                <a title="Cập nhật file"
                                                   href="{{route('ds_file',$vanBanDen->vb_den_id)}}"><span
                                                        role="button">&emsp;<i
                                                            class="fa  fa-search"></i></span></a>@endif

                                        </div>
                                        <i style="font-weight: initial">
                                            @if( $vanBanDen->chu_tri_phoi_hop == 1)(<span
                                                style="color: red">*</span> {{ $vanBanDen->chu_tri_phoi_hop == 1 ? 'Là văn bản giao sở' : '' }}
                                            )@endif
                                        </i>


                                    </td>
                                    <td>
                                        <!--vb den don vi-->
                                        @if ($vanBanDen->parent_id)
                                            @foreach($vanBanDen->getParent()->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                @if (count($vanBanDen->getParent()->donViChuTri)-1 == $key)
                                                    <p>
                                                        {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                        <br>
                                                        <i>(Cán bộ xử
                                                            lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                            )</i>
                                                    </p>
                                                @endif
                                            @endforeach
                                        @else
                                        <!--vb den huyen-->
                                            @if($vanBanDen->donViChuTri)
                                                @foreach($vanBanDen->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                    @if (count($vanBanDen->donViChuTri)-1 == $key)
                                                        <p>
                                                            {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                            <br>
                                                            <i>(Cán bộ xử
                                                                lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
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
                                Tổng số văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                            </div>
                            <div class="col-md-6 text-right">
                                {!! $danhSachVanBanDen->appends(['type' => Request::get('type'),'tu_ngay' => Request::get('tu_ngay'),
                                'den_ngay' => Request::get('den_ngay'), 'arr_id' => Request::get('arr_id')])->render() !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection
















