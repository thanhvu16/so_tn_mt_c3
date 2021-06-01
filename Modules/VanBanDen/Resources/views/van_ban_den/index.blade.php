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
                                <a role="button" onclick="showModal()" class="btn btn-primary ">
                                    <span style="color: white;font-size: 14px"><i class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</span></a>
                                <a class=" btn btn-primary" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i> <span
                                        style="font-size: 14px">Tìm kiếm văn bản</span>
                                </a>
                            </div>
                                <div class="col-md-6 text-right">
                                    {{--                                    <form action method="GET" action="{{ route('thongkevbso') }}" class="form-export">--}}

                                    <input type="hidden" name="type" form="tim_kiem" value="">

                                    <button type="button" data-type="excel" form="tim_kiem"
                                            class="btn btn-success waves-effect waves-light btn-sm btn-export-data"><i
                                            class="fa fa-file-excel-o"></i> Xuất Excel
                                    </button>
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
                                <form action="{{route('van-ban-den.index')}}" id="tim_kiem" method="get" class="form-export">
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
                                                <label for="vb_so_den" class="col-form-label">Số đến văn bản</label>
                                                <input type="text" name="vb_so_den" class="form-control soden" value="{{Request::get('vb_so_den')}}"
                                                       id="vb_so_den"
                                                       placeholder="Số đến văn bản">
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                                <input type="text" name="vb_so_ky_hieu"
                                                       value="{{Request::get('vb_so_ky_hieu')}}"
                                                       class="form-control file_insert"
                                                       id="sokyhieu"
                                                       placeholder="Số ký hiệu">
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Ngày nhập từ</label>
                                                <div id="">
                                                    <input class="form-control " id="vb_ngay_ban_hanh"
                                                           value="{{Request::get('start_date')}}" type="date"
                                                           name="start_date">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Nhập đến ngày</label>
                                                <div id="">
                                                    <input class="form-control " id="vb_ngay_ban_hanh"
                                                           value="{{Request::get('end_date')}}" type="date"
                                                           name="end_date">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="co_quan_ban_hanh_id" class="col-form-label">Cơ quan ban
                                                    hành</label>
                                                <input type="text" name="co_quan_ban_hanh_id"
                                                       value="{{Request::get('co_quan_ban_hanh_id')}}"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label for="sokyhieu" class="col-form-label">Người ký</label>
                                                <input type="text" value="{{Request::get('nguoi_ky_id')}}"
                                                       name="nguoi_ky_id"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Đơn vị xử lý chính</label>
                                                <select class="form-control select2"
                                                        name="don_vi_id" id="so_van_ban_id">
                                                    <option value="">-- Chọn đơn vị xử lý chính --</option>
                                                    @foreach ($danhSachDonVi as $donVi)
                                                        <option
                                                            value="{{ $donVi->id }}" {{ Request::get('don_vi_id') == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Đơn vị phối hợp</label>
                                                <select class="form-control select2"
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
                                                <select class="form-control select2" name="trinh_tu_nhan_van_ban">
                                                    <option value="">-- Chọn trạng thái--</option>
                                                    <option value="1" {{ Request::get('trinh_tu_nhan_van_ban') == 1 ? 'selected' : null }}>Chưa phân loại</option>
                                                    <option value="2" {{ Request::get('trinh_tu_nhan_van_ban') == 2 ? 'selected' : null }}>Đang xử lý</option>
                                                    <option value="10" {{ Request::get('trinh_tu_nhan_van_ban') == 10 ? 'selected' : null }}>Đã hoàn thành</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3" >
                                                <label class="col-form-label">Năm</label>
                                                <select name="year" class="form-control select2">
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
                    <div class="box-body" >
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" class="text-center">STT</th>
                                <th width="26%" class="text-center">Thông tin</th>
                                <th width="44%" class="text-center">Trích yếu</th>
                                <th width="21%" class="text-center">Đơn vị xử lý</th>
                                <th width="7%" class="text-center">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDen as $key=>$vbDen)
                                <tr >
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>
                                        <p>- Số ký hiệu: {{$vbDen->so_ky_hieu}}</p>
                                        <p>- Ngày ban
                                            hành: {{ date('d/m/Y', strtotime($vbDen->ngay_ban_hanh)) }}</p>
                                        <p>- Ban hành: {{$vbDen->co_quan_ban_hanh}}</p>
                                        <p>- Số đến: <span
                                                class="font-bold" style="color: red">{{$vbDen->so_den}}</span></p>
{{--                                        <p>- Sổ văn bản: {{$vbDen->soVanBan->ten_so_van_ban ?? ''}}</p>--}}
                                    </td>
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
                                                    @if($vbDen->han_xu_ly != null) (Hạn giải quyết: {{ date('d/m/Y', strtotime($vbDen->han_xu_ly)) }})<br>@endif
                                        <span style="font-style: italic">Người nhập : {{$vbDen->nguoiDung->ho_ten ?? ''}}</span> -
                                        <span style="font-style: italic">Ngày nhập : {{ date('d/m/Y', strtotime($vbDen->ngay_nhan)) }}</span>
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
                                        <div class="text-right">
                                            @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                <span class="label label-success">Đã hoàn thành</span>
                                            @elseif($vbDen->trinh_tu_nhan_van_ban == null)
                                                <span class="label label-danger">Chưa phân loại</span>
                                            @else
                                                <span class="label label-warning">Đang xử lý</span>
                                            @endif
                                        </div>

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
                                Tổng số văn bản: <b>{{ $ds_vanBanDen->total() }}</b>
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
            $('input[name="type"]').val(type);
            $('.form-export').submit();
            hideLoading();
        });


    </script>

@endsection















