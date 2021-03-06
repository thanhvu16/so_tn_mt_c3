@extends('admin::layouts.master')
@section('page_title', 'Danh sách văn bản đi cũ')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách văn bản đi cũ</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12 mt-1 ">

                        <div class="col-md-6">
                            <div class="row">
                                @hasanyrole('văn thư đơn vị|văn thư sở')
                                <a role="button" onclick="showModal()" class="btn btn-primary ">
                                    <span style="color: white;font-size: 14px"><i class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</span></a>
                                @endrole
                                <a class=" btn btn-primary" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i>
                                    <span
                                        style="font-size: 14px">Tìm kiếm văn bản</span>
                                </a>
                            </div>

                        </div>
                        @if(auth::user()->donVi->parent_id == 0)
                            @role('văn thư đơn vị')
                            <div class="col-md-6 text-right">
                                <div class="row">
                                    <div class="col-md-10 text-right">
                                        <select class="form-control  show-tick select2-search"
                                                name="don_vi_van_ban" form="search_vb" onchange="this.form.submit()" id="">
                                            <option value="2" {{Request::get('don_vi_van_ban') == 2 ? 'selected' : ''}}>Văn
                                                bản huyện
                                            </option>
                                            <option value="1" {{Request::get('don_vi_van_ban') == 1 ? 'selected' : ''}}>Văn
                                                bản đơn vị
                                            </option>
                                            <option value="" {{Request::get('don_vi_van_ban') == '' ? 'selected' : ''}}>
                                                -----------Tất cả văn bản------------
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a role="button" href="{{route('in-so-van-ban-di.index')}}"
                                           class="btn btn-success ">
                                        <span style="color: white;font-size: 14px"><i
                                                class="fa  fa-print"></i> In sổ</span></a>
                                    </div>

                                </div>
                            </div>
                            @endrole
                        @endif
                        @role('văn thư sở')
                        <div class="col-md-6 text-right">
                            <a role="button" href="{{route('in-so-van-ban-di.index')}}" class="btn btn-success ">
                                <span style="color: white;font-size: 14px"><i class="fa  fa-print"></i> In sổ</span></a>
                        </div>
                        @endrole

                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <form action="{{route('van-ban-di.index')}}" id="search_vb" method="get">
                                <div
                                    class="col-md-12 collapse {{ Request::get('search') == 1 || Request::get('year') ? 'in' : '' }}"
                                    id="collapseExample">
                                    <div class="row">
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="linhvuc_id" class="col-form-label">Loại văn bản</label>--}}
{{--                                            <select class="form-control show-tick select2-search" autofocus--}}
{{--                                                    name="loaivanban_id" id="loaivanban_id">--}}
{{--                                                <option value="">-- Chọn Loại Văn Bản --</option>--}}
{{--                                                @foreach ($ds_loaiVanBan as $loaiVanBan)--}}
{{--                                                    <option--}}
{{--                                                        value="{{$loaiVanBan->id}}" {{Request::get('loaivanban_id') == $loaiVanBan->id ? 'selected' : ''}}--}}
{{--                                                    >{{$loaiVanBan->ten_loai_van_ban}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi</label>--}}
{{--                                            <select class="form-control show-tick select2-search" name="sovanban_id">--}}
{{--                                                <option value="">-- Chọn Sổ Văn Bản Đi --</option>--}}
{{--                                                @foreach ($ds_soVanBan as $soVB)--}}
{{--                                                    <option--}}
{{--                                                        value="{{$soVB->id}}" {{Request::get('sovanban_id') == $soVB->id ? 'selected' : ''}}--}}
{{--                                                    >{{$soVB->ten_so_van_ban}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                            <input type="text" value="{{Request::get('vb_sokyhieu')}}"
                                                   id="vb_sokyhieu" name="vb_sokyhieu" class="form-control"
                                                   placeholder="Nhập số ký hiệu văn bản đi...">
                                        </div>
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo</label>--}}
{{--                                            <select class="form-control show-tick select2-search"--}}
{{--                                                    name="donvisoanthao_id">--}}
{{--                                                <option value="">Chọn đơn vị</option>--}}
{{--                                                @foreach ($ds_DonVi as $donVi)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $donVi->id }}" {{Request::get('donvisoanthao_id') == $donVi->id ? 'selected' : ''}}--}}
{{--                                                    >{{ $donVi->ten_don_vi }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Nhập từ ngày</label>
                                            <input type="date" name="start_date" class="form-control"
                                                   value="{{Request::get('start_date')}}"
                                                   autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Nhập đến ngày</label>
                                            <input type="date" name="end_date" id="vb_ngaybanhanh" class="form-control"
                                                   value="{{Request::get('end_date')}}"
                                                   autocomplete="off">
                                        </div>
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký</label>--}}
{{--                                            <select class="form-control show-tick select2-search" name="nguoiky_id">--}}
{{--                                                <option value="">-- Chọn Người Ký --</option>--}}
{{--                                                @foreach ($ds_nguoiKy as $nguoiKy)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $nguoiKy->id }}" {{Request::get('nguoiky_id') == $nguoiKy->id ? 'selected' : ''}}--}}
{{--                                                    >{{$nguoiKy->ho_ten}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ</label>--}}
{{--                                            <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu"--}}
{{--                                                   value="{{Request::get('chuc_vu')}}">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-md-3">--}}
{{--                                            <label class="col-form-label">Năm</label>--}}
{{--                                            <select name="year" class="form-control select2">--}}
{{--                                                <option value="">-- Tất cả --</option>--}}
{{--                                                @for($i = 2020; $i <= date('Y'); $i++)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $i }}" {{ $i == Request::get('year') ? 'selected' : '' }}>--}}
{{--                                                        {{ $i }}</option>--}}
{{--                                                @endfor--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                        <div class="form-group col-md-12">
                                            <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                            <textarea rows="3" name="vb_trichyeu" class="form-control no-resize"
                                                      placeholder="Nhập nội dung trích yếu ..."
                                            >{{Request::get('vb_trichyeu')}}</textarea>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" value="1" name="search"><i
                                                    class="fa  fa-search"></i> Tìm kiếm
                                            </button>
                                            @if(request('search') || request('year'))
                                                <a href="{{ route('van-ban-di.index') }}">
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


                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        @include('vanbandi::Du_thao_van_ban_di.error')
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" style="vertical-align: middle" class="text-center">STT</th>
                                <th width="6%" style="vertical-align: middle" class="text-center">Số đi</th>
                                <th width="10%" style="vertical-align: middle" class="text-center">Ngày phát hành</th>
                                <th width="12%" style="vertical-align: middle" class="text-center">Đơn vị phát hành</th>
                                <th width="10%" style="vertical-align: middle" class="text-center">Số ký hiệu</th>
                                <th  style="vertical-align: middle" class="text-center">Trích yếu</th>
                                <th width="15%" style="vertical-align: middle" class="text-center">Đơn vị nhận</th>
                                <th width="12%" style="vertical-align: middle" class="text-center">Người ký</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDi as $key=>$vbDi)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-left"><span class="font-bold color-red" style="font-weight: bold;font-size: 16px">{{$vbDi->stt}}</span></td>
                                    <td class="text-center">{{ date('d-m-Y', strtotime($vbDi->ngay_phat_hanh)) }}</td>
                                    <td class="text-left">{{$vbDi->ten_dv_phathanh}}</td>
                                    <td class="text-left">{{$vbDi->so_ky_hieu}}</td>
                                    <td style="text-align: justify">
                                        {{$vbDi->trich_yeu}}

                                        <div class="text-right " style="pointer-events: auto">
                                            <a class="seen-new-window" target="popup"
                                               href="http://14.177.182.250:9999/cgt/vb/vb/{!! $vbDi->file!!}">[File văn bản đi]</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-height:120px;  overflow:auto">
                                            {{$vbDi->dv_nhan}}
{{--                                            @if ($vbDi->donvinhanvbdi)--}}
{{--                                                @foreach($vbDi->donvinhanvbdi as $key=>$item)--}}
{{--                                                    <p>- {{ $item->laytendonvinhan->ten_don_vi }}</p>--}}
{{--                                                @endforeach--}}
{{--                                            @endif--}}
{{--                                            @if ($vbDi->mailngoaitp)--}}
{{--                                                @foreach($vbDi->mailngoaitp as $key=>$item)--}}
{{--                                                    <p>- {{$item->laytendonvingoai->ten_don_vi ?? ''}}</p>--}}
{{--                                                @endforeach--}}
{{--                                            @endif--}}
                                        </div>


                                    </td>
                                    <td class="text-left">{{$vbDi->nguoidung2->ho_ten ?? ''}}</td>
                                </tr>
                            @empty
                                <td colspan="9" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                                Tổng số văn bản: <b>{{ $ds_vanBanDi->total() }}</b>
                            </div>
                            <div class="col-md-6 text-right">
                                {!! $ds_vanBanDi->appends(['loaivanban_id' => Request::get('loaivanban_id'), 'sovanban_id' => Request::get('sovanban_id')
                                   ,'vb_sokyhieu' => Request::get('vb_sokyhieu'),
                                   'donvisoanthao_id' => Request::get('donvisoanthao_id'),'start_date' => Request::get('start_date'),
                                   'end_date' => Request::get('end_date'),'nguoiky_id' => Request::get('nguoiky_id'),'chuc_vu' => Request::get('chuc_vu'),
                                   'vb_trichyeu' => Request::get('vb_trichyeu'),'search' =>Request::get('search'), 'year' => Request::get('year'), 'phat_hanh_van_ban' => Request::get('phat_hanh_van_ban')])->render() !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->


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
