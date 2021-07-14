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
                    <div class="col-md-12 mt-1 ">
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6 text-right">
                                <form action method="GET" action="{{ route('tongSoVanBanDen') }}" class="form-export">
                                    <input type="hidden" name="type"  value="">
                                    <input type="hidden" name="tu_ngay"  value="{{Request::get('tu_ngay') }}">
                                    <input type="hidden" name="den_ngay"  value="{{Request::get('den_ngay') }}">
                                    <input type="hidden" name="loai_van_ban_id" value="{{Request::get('loai_van_ban_id') }}">
                                    <input type="hidden" name="don_vi_xu_ly_chinh" value="{{Request::get('don_vi_xu_ly_chinh') }}">
                                    <input type="hidden" name="page"  value="{{Request::get('page') }}">
                                    <button type="button" data-type="excel"
                                            class="btn btn-success waves-effect waves-light btn-sm btn-export-data"><i
                                            class="fa fa-file-excel-o"></i> Xuất Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body" >
                        Tổng số văn bản: <b style="font-size: 16px">{{ $ds_vanBanDen->total() }}</b>
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" class="text-center">STT</th>
                                <th width="5%" class="text-center">Số đến</th>
                                <th width="14%" class="text-center">Thông tin</th>
                                <th width="10%" class="text-center">Cơ quan ban hành</th>
                                <th width="" class="text-center">Trích yếu</th>
                                <th width="15%" class="text-center">Đơn vị xử lý chính</th>
                                <th width="15%" class="text-center">Đơn vị phối hợp</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDen as $key=>$vbDen)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td style="color: red;font-weight: bold">{{$vbDen->so_den}}</td>
                                    <td>
                                        <p>- Số ký hiệu: {{$vbDen->so_ky_hieu}}</p>
                                        <p>- Ngày ban
                                            hành: {{ date('d/m/Y', strtotime($vbDen->ngay_ban_hanh)) }}</p>

                                        {{--                                        <p>- Sổ văn bản: {{$vbDen->soVanBan->ten_so_van_ban ?? ''}}</p>--}}
                                    </td>
                                    <td>{{$vbDen->co_quan_ban_hanh}}</td>
                                    <td style="text-align: justify">
                                        @if ($vbDen->loai_van_ban_don_vi == 1)
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id.'?status=1' : $vbDen->id .'?status=1') }}" title="{{$vbDen->trich_yeu}}">{{$vbDen->trich_yeu}}</a><br>
                                        @else
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id : $vbDen->id) }}" title="{{$vbDen->trich_yeu}}">{{$vbDen->trich_yeu}}</a><br>
                                        @endif

                                        @if($vbDen->noi_dung != null)<span style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vbDen->noi_dung ?? ''}}</span>@if($vbDen->noi_dung != null)
                                            <br>@endif
                                        @if($vbDen->han_xu_ly == null) (Hạn giải quyết: {{ date('d/m/Y', strtotime($vbDen->han_xu_ly)) }})<br>@endif
                                        <span style="font-style: italic">Người nhập : {{$vbDen->nguoiDung->ho_ten ?? ''}}</span> -
                                        <span style="font-style: italic">Ngày nhập : {{ date('d/m/Y', strtotime($vbDen->ngay_nhan)) }}</span>
                                        <div class="text-right " style="pointer-events: auto">
                                            @if($vbDen->vbDenFile)
                                                @forelse($vbDen->vbDenFile as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="popup" class="seen-new-window">
                                                        @if($item->duoi_file == 'pdf')<i
                                                            class="fa fa-file-pdf-o"
                                                            style="font-size:20px;color:red"></i>@elseif($item->duoi_file == 'docx' || $item->duoi_file == 'doc')
                                                            <i class="fa fa-file-word-o"
                                                               style="font-size:20px;color:blue"></i> @elseif($item->duoi_file == 'xlsx' || $item->duoi_file == 'xls')
                                                            <i class="fa fa-file-excel-o"
                                                               style="font-size:20px;color:green"></i> @endif
                                                    </a>@if(count($vbDenFile) == $key+1) @else &nbsp;
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
                                                        <i>(Cán bộ xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
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
                                                            <i>(Cán bộ xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                                )</i>
                                                        </p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
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
                                {!! $ds_vanBanDen->appends(['tu_ngay' => Request::get('tu_ngay'),'den_ngay' => Request::get('den_ngay'), 'loai_van_ban_id' => Request::get('loai_van_ban_id')
                       ,'don_vi_xu_ly_chinh' => Request::get('don_vi_xu_ly_chinh')])->render() !!}
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

        $('.btn-export-data').on('click', function () {
            let type = $(this).data('type');
            $('input[name="type"]').val(type);
            $('.form-export').submit();
            hideLoading();
        });


    </script>

@endsection













