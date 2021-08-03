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

                        <div class="col-md-6">
                            <div class="row">
                                <a role="button" onclick="showModal()" class="btn btn-primary ">
                                    <span style="color: white;font-size: 14px"><i class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</span></a>
                                <a class=" btn btn-primary" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i> <span
                                        style="font-size: 14px">Tìm kiếm văn bản</span>
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <form action="{{route('giay-moi-di.index')}}" method="get">
                                <div
                                    class="col-md-12 collapse {{ Request::get('search') == 1 || Request::get('year') ? 'in' : '' }}"
                                    id="collapseExample">
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
                                            <input type="text" value="{{Request::get('vb_sokyhieu')}}"
                                                   id="vb_sokyhieu" name="vb_sokyhieu" class="form-control"
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


                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Giờ họp </label>

                                                <div class="input-group">
                                                    <input type="text" name="gio_hop"
                                                           value="{{Request::get('gio_hop')}}" class="form-control "
                                                           placeholder="ví dụ: 22:30">

                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                        <div class="row clearfix"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Ngày họp từ</label>
                                                <input type="date" class="form-control ngaybanhanh2"
                                                       value="{{Request::get('start_date')}}"
                                                       name="start_date" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Họp đến ngày</label>
                                                <input type="date" class="form-control ngaybanhanh2"
                                                       value="{{Request::get('end_date')}}"
                                                       name="end_date" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Địa điểm </label>
                                                <input type="text" class="form-control"
                                                       value="{{Request::get('dia_diem')}}"
                                                       name="dia_diem" placeholder="Địa điểm">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
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
                                        <div class="form-group col-md-3">
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ </label>
                                            <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu"
                                                   value="{{Request::get('chuc_vu')}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo </label>
                                            <select class="form-control show-tick select2-search"
                                                    name="donvisoanthao_id">
                                                <option value="">--Chọn đơn vị soạn thảo--</option>
                                                @foreach ($ds_DonVi as $donVi)
                                                    <option
                                                        value="{{ $donVi->id }}" {{Request::get('donvisoanthao_id') == $donVi->id ? 'selected' : ''}}
                                                    >{{ $donVi->ten_don_vi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="col-form-label">Năm</label>
                                            <select name="year" class="form-control select2">
                                                <option value="">-- Tất cả --</option>
                                                @for($i = 2020; $i <= date('Y'); $i++)
                                                    <option
                                                        value="{{ $i }}" {{ $i == Request::get('year') ? 'selected' : '' }}>
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: 25px">
                                            <button name="search" value="1" class="btn btn-primary"><i
                                                    class="fa  fa-search"></i> Tìm kiếm
                                            </button>
                                            @if(request('search') || request('year'))
                                                <a href="{{ route('giay-moi-di.index') }}">
                                                    <button type="button" class="btn btn-success">
                                                        <i class="fa fa-refresh"></i>
                                                    </button>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>


                    <div class="box-body table-responsive">
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
                                        <p>- Số ký hiệu: {{$vbDi->so_ky_hieu}}</p>
                                        <p>- Ngày nhập: {{ date('d-m-Y', strtotime($vbDi->ngay_ban_hanh)) }}</p>
                                        <p>- Loại văn bản: Giấy mời</p>
                                        <p>- Số đi: <span
                                                class="font-bold color-red">{{$vbDi->so_di}}</span></p>
                                    </td>

                                    <td style="text-align: justify"><a
                                            href="{{ route('Quytrinhxulyvanbandi',$vbDi->id) }}">{{$vbDi->trich_yeu}}</a><br>
                                        (Vào hồi {{date('H:i', strtotime($vbDi->gio_hop))}}
                                        ngày {{ date('d-m-Y', strtotime($vbDi->ngay_hop)) }},
                                        tại {{$vbDi->dia_diem}})

                                        <br><i>(Người
                                            ký: {{$vbDi->nguoidung2->ho_ten ?? ''}})</i>
                                        <br>
                                        <div class="text-right " style="pointer-events: auto">

                                            @forelse($vbDi->filetrinhky as $filedata)
                                                <a href="{{$filedata->getUrlFile()}}" target="popup" class="seen-new-window">[File trình ký]</a>
                                            @empty
                                            @endforelse
                                            @forelse($vbDi->filephieutrinh as $filedata)
                                                &nbsp; |<a href="{{$filedata->getUrlFile()}}" target="popup" class="seen-new-window"> [File phiếu
                                                    trình]</a>
                                            @empty
                                            @endforelse
                                            @forelse($vbDi->filehoso as $filedata)
                                                &nbsp; |<a href="{{$filedata->getUrlFile()}}" target="popup" class="seen-new-window"> [File hồ
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
                                        @if(auth::user()->id == $vbDi->nguoi_tao || auth::user()->hasRole(VAN_THU_HUYEN)|| auth::user()->hasRole(VAN_THU_DON_VI))
                                            <form method="POST" action="{{route('giaymoididelete',$vbDi->id)}}">
                                                @csrf
                                                <a href="{{route('giay-moi-di.edit',$vbDi->id)}}"
                                                   class="fa fa-edit" role="button"
                                                   title="Sửa">
                                                    <i class="fas fa-file-signature"></i>
                                                </a><br><br>
                                                <button
                                                    class="btn btn-action btn-color-red btn-icon btn-ligh btn-remove-item"
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
                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('multiple_file_di') }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title"><i
                                                    class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="sokyhieu" class="col-form-label">Chọn tệp tin
                                                        <br>
                                                        <small><i>(Đặt tên file theo định dạng: tên viết tắt
                                                                loại văn bản + số đi + năm (vd:
                                                                QD-1-2020.pdf))</i></small>
                                                    </label><br>
                                                    <input type="file" multiple name="ten_file[]"
                                                           accept=".xlsx,.xls,.doc, .docx,.txt,.pdf"/>
                                                    <input type="text" id="url-file" value="123" class="hidden"
                                                           name="txt_file[]">
                                                    <input type="hidden" name="type" value="GM">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <button class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Tải
                                                        lên
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                                Tổng số giấy mời: <b>{{ $ds_vanBanDi->total() }}</b>
                            </div>
                            <div class="col-md-6 text-right">
                                {!! $ds_vanBanDi->appends(['sovanban_id' => Request::get('sovanban_id'), 'chuc_vu' => Request::get('chuc_vu')
                        ,'vb_sokyhieu' => Request::get('vb_sokyhieu'),
                        'donvisoanthao_id' => Request::get('donvisoanthao_id'),'nguoiky_id' => Request::get('nguoiky_id'),
                        'dia_diem' => Request::get('dia_diem'),'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),
                        'gio_hop' => Request::get('gio_hop'),'vb_ngaybanhanh_start' => Request::get('vb_ngaybanhanh_start'),
                        'vb_ngaybanhanh_end' => Request::get('vb_ngaybanhanh_end'),'search' =>Request::get('search'), 'year' =>Request::get('year')])->render() !!}
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
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
