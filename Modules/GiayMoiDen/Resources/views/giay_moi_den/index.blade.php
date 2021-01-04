
@extends('admin::layouts.master')
@section('page_title', 'Danh sách giấy mời đến')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách giấy mời đến</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12 mt-1 ">
                        <a class=" btn btn-primary" data-toggle="collapse"
                           href="#collapseExample"
                           aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i> <span
                                style="font-size: 14px">Tìm kiếm văn bản</span>
                        </a>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <form action="{{route('giay-moi-den.index')}}" method="get">
                                <div class="col-md-12 collapse {{ Request::get('search') == 1 ? 'in' : '' }} " id="collapseExample">

                                        <form action="{{route('giay-moi-den.index')}}" method="get">
                                                <div class="row">
                                                    <form action="{{route('giay-moi-den.index')}}" method="get">
                                                        <div class="col-md-12 collapse in" id="collapseExample">
                                                            <div class="row">
                                                                <div class="form-group col-md-3">
                                                                    <label for="vb_so_den" class="col-form-label">Số đến giấy mời</label>
                                                                    <input type="text" name="vb_so_den" class="form-control soden"
                                                                           id="vb_so_den" value="{{Request::get('vb_so_den')}}"
                                                                           placeholder="Số đến văn bản">
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                                                    <input type="text" name="vb_so_ky_hieu"
                                                                           value="{{Request::get('vb_so_ky_hieu')}}"
                                                                           class="form-control file_insert"
                                                                           id="sokyhieu"
                                                                           placeholder="Số ký hiệu">
                                                                </div>
                                                                <div class="form-group col-md-3" id="div_select_cqbh">
                                                                    <label for="co_quan_ban_hanh_id" class="col-form-label">Nơi gửi đến</label>
                                                                    <input type="text" value="{{Request::get('co_quan_ban_hanh_id')}}" class="form-control" name="co_quan_ban_hanh_id">

                                                                </div>

                                                                <div class="form-group col-md-3">
                                                                    <label for="vb_ngay_ban_hanh" class="col-form-label">Ngày ban hành</label>
                                                                    <input class="form-control" id="vb_ngay_ban_hanh"
                                                                           value="{{Request::get('vb_ngay_ban_hanh')}}" type="date"
                                                                           name="vb_ngay_ban_hanh">
                                                                </div>


                                                                <div class="form-group col-md-12" >
                                                                    <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                                                    <textarea rows="3" class="form-control" placeholder="nội dung"
                                                                              name="vb_trich_yeu"
                                                                              type="text">{{Request::get('vb_trich_yeu')}}</textarea>
                                                                </div>

                                                                <div class="col-md-3" >
                                                                    <div class="form-group">
                                                                        <label for="">Ngày họp từ</label>
                                                                        <input type="date" class="form-control" value="{{Request::get('start_date')}}"
                                                                               name="start_date" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3" >
                                                                    <div class="form-group">
                                                                        <label for="">Họp đến ngày</label>
                                                                        <input type="date" class="form-control" value="{{Request::get('end_date')}}"
                                                                               name="end_date" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3" >
                                                                    <div class="form-group">
                                                                        <label for="">Địa điểm</label>
                                                                        <input type="text" class="form-control" value="{{Request::get('dia_diem_chinh')}}"
                                                                               name="dia_diem_chinh" placeholder="Địa điểm">
                                                                    </div>
                                                                </div>


                                                                <div class="form-group col-md-3" >
                                                                    <label for="sokyhieu" class="col-form-label">Người ký</label>
                                                                    <input type="text" class="form-control " value="{{Request::get('nguoi_ky_id')}}" name="nguoi_ky_id">
                                                                </div>
                                                                <div class="form-group col-md-3 " >
                                                                    <button type="submit" name="search" value="1" class="btn btn-primary"> <i class="fa  fa-search"></i> Tìm kiếm
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                        </form>

                                </div>
                            </form>

                        </div>
                    </div>


                    <div class="box-body">
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th style="vertical-align: middle" class="text-center" width="2%">STT</th>
                                <th width="25%" style="vertical-align: middle" class="text-center">Thông
                                    tin
                                </th>
                                <th class="text-center" style="vertical-align: middle" width="">Trích
                                    yếu
                                </th>
                                <th class="text-center" style="vertical-align: middle" width="20%">Đơn vị
                                    xử lý
                                </th>
                                <th class="text-center" style="vertical-align: middle" width="7%">Tác vụ
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDen as $key=>$vbDen)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>
                                        <p>- Số ký hiệu: {{$vbDen->so_ky_hieu}}</p>
                                        <p>- Ngày ban
                                            hành: {{ date('d-m-Y', strtotime($vbDen->ngay_ban_hanh)) }}</p>
                                        <p>- Cơ quan ban hành: {{$vbDen->co_quan_ban_hanh}}</p>
                                        <p>- Số đến: <span
                                                class="font-bold color-red">{{$vbDen->so_den}}</span></p>
                                    </td>
                                    <td style="text-align: justify"><a
                                            href="{{ route('van_ban_den_chi_tiet.show', $vbDen->parent_id ? $vbDen->parent_id : $vbDen->id) }}"
                                            title="{{$vbDen->trich_yeu}}">{{$vbDen->trich_yeu}}</a><br>
                                        @if($vbDen->noi_dung_hop != null)<span
                                            style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vbDen->noi_dung_hop ?? ''}}</span>@if($vbDen->noi_dung_hop != null)
                                            <br>@endif
                                                       (Nội dung: {{$vbDen->noi_dung}}. Vào hồi {{date('H:i', strtotime($vbDen->gio_hop_phu))}}  ngày {{ date('d-m-Y', strtotime($vbDen->ngay_hop_phu)) }} ,tại {{$vbDen->dia_diem_phu}})
                                        | (Hạn xử
                                        lý: {{ date('d-m-Y', strtotime($vbDen->han_xu_ly)) }})<br>
                                        <span
                                            style="font-style: italic">Người nhập : {{$vbDen->nguoiDung->ho_ten ?? ''}}</span>


                                        <div class="text-right">
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
                                        </div>
                                        <p>
                                            <input id="van-ban-don-vi-{{ $vbDen->id }}" type="checkbox"
                                                   name="van_ban-don_vi" value="1" checked>
                                            <label for="van-ban-don-vi-{{ $vbDen->id }}"
                                                   class="color-red font-weight-normal">
                                                {{ $vbDen->loai_van_ban_don_vi == 1 ? 'văn bản đơn vị phối hợp' : 'Văn bản đơn vị chủ trì' }}
                                            </label>
                                        </p>
                                    </td>
                                    <td>
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

                                    <td class="text-center" style="vertical-align: middle">
                                        @hasanyrole('văn thư đơn vị|văn thư huyện')
                                        @if(auth::user()->id == $vbDen->nguoi_tao)
                                            <form method="POST" action="{{route('giaymoidelete',$vbDen->id)}}">
                                                @csrf
                                                <a href="{{route('giay-moi-den.edit',$vbDen->id)}}"
                                                   class="fa fa-edit" role="button"
                                                   title="Sửa">
                                                    <i class="fas fa-file-signature"></i>
                                                </a><br><br>
                                                <button
                                                    class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                    role="button"
                                                    title="Xóa">
                                                    <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>
                                                </button>
                                                <input type="text" class="hidden" value="{{$vbDen->id}}" name="id_vb">
                                            </form>
                                        @else
                                            -
                                        @endif
                                        @endrole
                                    </td>

                                </tr>
                            @empty
                                <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số giấy mời: <b>{{ $ds_vanBanDen->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_vanBanDen->appends(['so_van_ban_id' => Request::get('so_van_ban_id'),'loai_van_ban_id' => Request::get('loai_van_ban_id'), 'vb_so_den' => Request::get('vb_so_den')
                              ,'vb_so_ky_hieu' => Request::get('vb_so_ky_hieu'),
                              'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),
                              'cap_ban_hanh_id' => Request::get('cap_ban_hanh_id'),'co_quan_ban_hanh_id' => Request::get('co_quan_ban_hanh_id'),'nguoi_ky_id' => Request::get('nguoi_ky_id'),
                              'vb_trich_yeu' => Request::get('vb_trich_yeu'),'search' =>Request::get('search') ])->render() !!}
                                </div>
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
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
