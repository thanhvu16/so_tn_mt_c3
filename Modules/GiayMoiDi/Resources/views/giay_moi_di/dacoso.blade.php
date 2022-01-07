
@extends('admin::layouts.master')
@section('page_title', 'Danh sách giấy mời đi')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách giấy mời đi</h3>
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
                            <form action="{{route('dacoso')}}" method="get">
                                <div class="col-md-12 collapse {{ Request::get('search') == 1 ? 'in' : '' }}" id="collapseExample">
                                    <div class="row">
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="cap_ban_hanh_id" autofocus class="col-form-label">Sổ văn bản đi--}}
{{--                                            </label>--}}

{{--                                            <select class="form-control show-tick" name="sovanban_id">--}}
{{--                                                <option value="">--Chọn sổ văn bản--</option>--}}
{{--                                                @foreach ($ds_soVanBan as $data)--}}
{{--                                                    <option value="{{ $data->id }}" {{ Request::get('sovanban_id') == $data->id ? 'selected' : ''}}--}}
{{--                                                    >{{ $data->ten_so_van_ban}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Ký hiệu </label>
                                            <input type="text" value="{{Request::get('vb_sokyhieu')}}" style="text-transform: uppercase "
                                                   id="vb_sokyhieu" name="vb_sokyhieu"  class="form-control"
                                                   placeholder="Nhập số ký hiệu văn bản đi...">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Ngày ban hành từ </label>
                                            <input type="date" name="vb_ngaybanhanh_start" id="vb_ngaybanhanh"
                                                   class="form-control"
                                                   value="{{Request::get('vb_ngaybanhanh_start')}}"
                                                   autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Nhập đến ngày </label>
                                            <input type="date" name="vb_ngaybanhanh_end" id="vb_ngaybanhanh"
                                                   class="form-control"
                                                   value="{{Request::get('vb_ngaybanhanh_end')}}"
                                                   autocomplete="off">
                                        </div>


                                        <div class="col-md-3" >

                                            <div class="form-group">
                                                <label>Giờ họp </label>

                                                <div class="input-group">
                                                    <input type="text" name="gio_hop" value="{{Request::get('gio_hop')}}" class="form-control " placeholder="ví dụ: 22:30">

                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                        <div class="row clearfix"></div>
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label for="">Ngày họp từ</label>
                                                <input type="date" class="form-control ngaybanhanh2" value="{{Request::get('start_date')}}"
                                                       name="start_date" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label for="">Họp đến ngày</label>
                                                <input type="date" class="form-control ngaybanhanh2" value="{{Request::get('end_date')}}"
                                                       name="end_date" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label for="">Địa điểm </label>
                                                <input type="text" class="form-control" value="{{Request::get('dia_diem')}}"
                                                       name="dia_diem" placeholder="Địa điểm">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3" >
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký </label>
                                            <select class="form-control show-tick  layidnguoiky" name="nguoiky_id"
                                            >
                                                <option value="">-- Chọn Người Ký --</option>
                                                @foreach ($ds_nguoiKy as $nguoiKy)
                                                    <option
                                                            value="{{ $nguoiKy->id }}" {{Request::get('nguoiky_id') == $nguoiKy->id ? 'selected' : ''}}
                                                    >{{$nguoiKy->ho_ten}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" >
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ </label>
                                            <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu"
                                                   value="{{Request::get('chuc_vu')}}">
                                        </div>
                                        <div class="form-group col-md-3" >
                                            <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo </label>
                                            <select class="form-control show-tick select2-search"
                                                    name="donvisoanthao_id">
                                                <option value="">--Chọn đơn vị soạn thảo--</option>
                                                @foreach ($ds_DonVi as $donVi)
                                                    <option value="{{ $donVi->id }}"  {{Request::get('donvisoanthao_id') == $donVi->id ? 'selected' : ''}}
                                                    >{{ $donVi->ten_don_vi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: 25px">
                                            <button name="search" value="1" class="btn btn-primary"><i class="fa  fa-search"></i> Tìm kiếm</button>
                                        </div>

                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>


                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                <th width="2%" style="vertical-align: middle" class="text-center">STT</th>
                                <th width="25%" style="vertical-align: middle" class="text-center">Thông
                                    tin
                                </th>
                                <th width="" style="vertical-align: middle" class="text-center">Trích
                                    yếu
                                </th>
                                <th width="17%" style="vertical-align: middle"
                                    class="text-center visible-lg">
                                    Nơi nhận
                                </th>
                                <th width="10%" style="vertical-align: middle" class="text-center">Trả lời
                                    VB số
                                    đến
                                </th>
                                <th width="8%" style="vertical-align: middle" class="text-center">Tác vụ
                                </th>
                                <th style="vertical-align: middle" class="hide text-center">Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDi as $key=>$vbDi)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>
                                        <p>- Số ký hiệu: <span style="text-transform: uppercase ">{{$vbDi->so_ky_hieu}}</span></p>
                                        <p>- Ngày nhập: {{ date('d-m-Y', strtotime($vbDi->ngay_ban_hanh)) }}</p>
                                        <p>- Loại văn bản: Giấy mời</p>
                                        <p>- Số đi: <span
                                                class="font-bold color-red">{{$vbDi->so_di}}</span></p>
                                    </td>

                                    <td style="text-align: justify"><a
                                            href="" class="tin"
                                            data-original-title=""
                                            title="">{{$vbDi->trich_yeu}}</a><br>
                                        (Vào hồi {{ date( "H:i", strtotime($vbDi->gio_hop)) }}
                                        ngày {{ date('d-m-Y', strtotime($vbDi->ngay_hop)) }},
                                        tại {{$vbDi->dia_diem}})

                                        <br><i>(Người
                                            ký: {{$vbDi->nguoidung2->ho_ten ?? ''}})</i>
                                        <br>
                                        <div class="text-right " style="pointer-events: auto">

                                            @forelse($vbDi->filetrinhky as $filedata)
                                                <a href="{{$filedata->getUrlFile()}}">[File trình ký]</a>
                                            @empty
                                            @endforelse
                                            @forelse($vbDi->filephieutrinh as $filedata)
                                                &nbsp; |<a href="{{$filedata->getUrlFile()}}"> [File phiếu
                                                    trình]</a>
                                            @empty
                                            @endforelse
                                            @forelse($vbDi->filehoso as $filedata)
                                                &nbsp; |<a href="{{$filedata->getUrlFile()}}"> [File hồ
                                                    sơ]</a>
                                            @empty
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="visible-lg">

                                        @forelse($vbDi->donvinhanvbdi as $key=>$item)
                                            <p>
                                                - {{$item->laytendonvinhan->ten_don_vi ?? ''}}
                                            </p>
                                        @empty
                                        @endforelse

                                        <br>
                                    </td>
                                    <td class="text-center" style="color: red; vertical-align: middle;">-
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">
                                        @hasanyrole('văn thư đơn vị|văn thư sở')
                                        @if(auth::user()->id == $vbDi->nguoi_tao)
                                            <form method="POST" action="{{route('giaymoididelete',$vbDi->id)}}">
                                                @csrf
                                                <a href="{{route('giay-moi-di.edit',$vbDi->id)}}"
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
                                                <input type="text" class="hidden" value="{{$vbDi->id}}" name="id_vb">
                                            </form>
                                        @else
                                            -
                                        @endif
                                        @endrole
                                    </td>
                                </tr>
                            @empty
                                <td colspan="7" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>

                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số giấy mời: <b>{{ $ds_vanBanDi->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_vanBanDi->appends(['sovanban_id' => Request::get('sovanban_id'), 'chuc_vu' => Request::get('chuc_vu')
                            ,'vb_sokyhieu' => Request::get('vb_sokyhieu'),
                            'donvisoanthao_id' => Request::get('donvisoanthao_id'),'nguoiky_id' => Request::get('nguoiky_id'),
                            'dia_diem' => Request::get('dia_diem'),'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),
                            'gio_hop' => Request::get('gio_hop'),'vb_ngaybanhanh_start' => Request::get('vb_ngaybanhanh_start'),
                            'vb_ngaybanhanh_end' => Request::get('vb_ngaybanhanh_end'),'search' =>Request::get('search') ])->render() !!}
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
