
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



                    <div class="col-md-6">
                        Tổng số giấy mời: <b>{{ $ds_vanBanDen->total() }}</b>
                    </div>
                    <div class="row clearfix"></div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
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
                                        <p>- Số ký hiệu: <span style="text-transform: uppercase">{{$vbDen->so_ky_hieu}}</span></p>
                                        <p>- Ngày ban
                                            hành: {{ date('d/m/Y', strtotime($vbDen->ngay_ban_hanh)) }}</p>
                                        <p>- Ban hành: {{$vbDen->co_quan_ban_hanh}}</p>
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
                                                       (Nội dung: {{$vbDen->noi_dung}}. Vào hồi {{date('H:i', strtotime($vbDen->gio_hop_phu))}}  @if($vbDen->ngay_hop_phu)ngày {{ date('d/m/Y', strtotime($vbDen->ngay_hop_phu)) }} @endif ,tại {{$vbDen->dia_diem_phu}})
                                        @if($vbDen->han_xu_ly == null)@else | (Hạn xử
                                        lý: {{ date('d-m-Y', strtotime($vbDen->han_xu_ly)) }})@endif<br>
                                        <span
                                            style="font-style: italic">Người nhập : {{$vbDen->nguoiDung->ho_ten ?? ''}}</span> -
                                        @if($vbDen->ngay_nhan)<span style="font-style: italic">- Ngày nhập : {{ date('d/m/Y', strtotime($vbDen->ngay_nhan)) }}</span>@endif


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
                                            @hasanyrole('văn thư sở')
                                            <div class="text-right">
                                                @if ($vbDen->trinh_tu_nhan_van_ban == \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                                                    <span class="label label-success">Đã hoàn thành</span>
                                                @elseif($vbDen->trinh_tu_nhan_van_ban == null)
                                                    <span class="label label-danger">Chưa phân loại</span>
                                                @else
                                                    <span class="label label-warning">Đang xử lý</span>
                                                @endif
                                            </div>
                                            @endrole
                                    </td>

                                    <td class="text-center" style="vertical-align: middle">
                                        @hasanyrole('văn thư đơn vị|văn thư sở')
{{--                                        @if(auth::user()->id == $vbDen->nguoi_tao)--}}
                                            <form method="POST" action="{{route('giaymoidelete',$vbDen->id)}}">
                                                @csrf
                                                <a href="{{route('giay-moi-den.edit',$vbDen->id)}}"
                                                   class="fa fa-edit" role="button"
                                                   title="Sửa">
                                                    <i class="fas fa-file-signature"></i>
                                                </a><br><br>
                                                <button
                                                    class="btn btn-action btn-color-red btn-icon btn-ligh  btn-remove-item"
                                                    role="button"
                                                    title="Xóa">
                                                    <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>
                                                </button>
                                                <input type="text" class="hidden" value="{{$vbDen->id}}" name="id_vb">
                                            </form>
{{--                                        @else--}}
{{--                                            ---}}
{{--                                        @endif--}}
                                        @endrole
                                    </td>

                                </tr>
                            @empty
                                <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                            </div>
                            <div class="col-md-6 text-right">
                                {!! $ds_vanBanDen->appends(['so_van_ban_id' => Request::get('so_van_ban_id'),'loai_van_ban_id' => Request::get('loai_van_ban_id'), 'vb_so_den' => Request::get('vb_so_den')
                          ,'vb_so_ky_hieu' => Request::get('vb_so_ky_hieu'),'ngay_ban_hanh_date' => Request::get('ngay_ban_hanh_date'),'end_ngay_ban_hanh' => Request::get('end_ngay_ban_hanh'),
                          'end_date' => Request::get('end_date'),'start_date1' => Request::get('start_date1'),'end_date1' => Request::get('end_date1'),'start_date' => Request::get('start_date'),
                          'cap_ban_hanh_id' => Request::get('cap_ban_hanh_id'),'co_quan_ban_hanh_id' => Request::get('co_quan_ban_hanh_id'),'nguoi_ky_id' => Request::get('nguoi_ky_id'),
                          'vb_trich_yeu' => Request::get('vb_trich_yeu'),'search' =>Request::get('search'), 'year' => Request::get('year'), 'don_vi_id' => Request::get('don_vi_id')
                          , 'trinh_tu_nhan_van_ban' => Request::get('trinh_tu_nhan_van_ban')])->render() !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
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
                                                    tin<br><small><i>(Đặt tên file theo định dạng: số đến + năm ban hành  (vd:
                                                            16-2020.pdf))</i></small>
                                                </label>

                                                <input type="file" multiple name="ten_file[]"
                                                       accept=".xlsx,.xls,.doc, .docx,.txt,.pdf"/>
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

                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
