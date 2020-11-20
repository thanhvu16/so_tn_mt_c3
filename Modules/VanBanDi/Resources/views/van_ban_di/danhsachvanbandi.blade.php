@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="{{route('dsvbdi')}}" aria-expanded="false"
                           class="nav-link {{ Route::is('dsvbdi')
        ? 'active'  : '' }}">
                            <i class="fas fa-list-alt"></i> Danh sách văn bản đi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('dsgiaymoi')}}"  aria-expanded="false"
                           class="nav-link {{ Route::is('dsgiaymoi')
        ? 'active'  : '' }}">
                            <i class="fa fa-file-text-o"></i> Danh sách giấy mời đi
                        </a>
                    </li>

                    @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)
                        <a role="button" onclick="showModal()" class="btn btn-primary btn-sm">
                            <span style="color: white;font-size: 16px"><i class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</span></a>
                        @endif &emsp;
                        <a class="btn-xs btn btn-primary" data-toggle="collapse"
                           href="#collapseExample"
                           aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i><span
                                style="font-size: 16px">Tìm kiếm văn bản </span>
                        </a>
                </ul>
                {{--                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">--}}
                {{--                    <div class="modal-dialog modal-xl">--}}
                {{--                        <div class="modal-content" style="max-width: 500px;">--}}
                {{--                            <div class="modal-header">--}}
                {{--                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--}}
                {{--                                <h4 class="modal-title" id="myModalLabel"><i class="fas fa-file-alt btn-color-blue"></i> Upload nhiều file</h4>--}}
                {{--                            </div>--}}
                {{--                            <div class="modal-body">--}}
                {{--                                <div class="col-md-12">--}}
                {{--                                    <div class="row">--}}
                {{--                                        <form class="form-row"  method="post" action="{{route('upload_file_vbdi')}}" enctype="multipart/form-data">--}}
                {{--                                            @csrf--}}
                {{--                                            <div class="form-group col-md-12">--}}
                {{--                                                <label for="sokyhieu" class="col-form-label">Chọn tệp tin</label><br>--}}
                {{--                                                <input type="file" id="url-file" multiple name="ten_file[]">--}}
                {{--                                                <input type="text" id="url-file" value="123" class="hidden" name="txt_file[]">--}}
                {{--                                            </div>--}}
                {{--                                            <div class="form-group col-md-4" style="margin-top: 26px">--}}
                {{--                                                <button class="btn btn-primary">Tải lên</button>--}}
                {{--                                            </div>--}}

                {{--                                        </form>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div><!-- /.modal-content -->--}}
                {{--                    </div><!-- /.modal-dialog -->--}}
                {{--                </div>--}}
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <form action="{{route('dsvbdi')}}" method="get">
                                <div class="col-md-12 collapse in" id="collapseExample">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="linhvuc_id" class="col-form-label">Loại văn bản</label>
                                            <select class="form-control show-tick select2-search" autofocus
                                                    name="loaivanban_id" id="loaivanban_id">
                                                <option value="">-- Chọn Loại Văn Bản --</option>
                                                @foreach ($ds_loaiVanBan as $loaiVanBan)
                                                    <option value="{{$loaiVanBan->id}}"
                                                    >{{$loaiVanBan->ten_loai_van_ban}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="cap_ban_hanh_id" class="col-form-label">Sổ văn bản đi</label>
                                            <select class="form-control show-tick select2-search" name="sovanban_id">
                                                <option value="">-- Chọn Sổ Văn Bản Đi --</option>
                                                @foreach ($ds_soVanBan as $soVB)
                                                    <option value="{{$soVB->ma_id}}"
                                                    >{{$soVB->ten_so_van_ban}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                            <input type="text" value=""
                                                   id="vb_sokyhieu" name="vb_sokyhieu" autofocus class="form-control"
                                                   placeholder="Nhập số ký hiệu văn bản đi...">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo</label>
                                            <select class="form-control show-tick select2-search"
                                                    name="donvisoanthao_id">
                                                <option value="">Chọn đơn vị</option>
                                                @foreach ($ds_DonVi as $donVi)
                                                    <option value="{{ $donVi->ma_id }}"
                                                    >{{ $donVi->ten_don_vi }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3" style="margin-top: -15px">
                                            <label for="sokyhieu" class="col-form-label">Nhập từ ngày</label>
                                            <input type="date" name="start_date" class="form-control"
                                                   value=""
                                                   autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: -15px">
                                            <label for="sokyhieu" class="col-form-label">Nhập đến ngày</label>
                                            <input type="date" name="end_date" id="vb_ngaybanhanh" class="form-control"
                                                   value=""
                                                   autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: -15px">
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký</label>
                                            <select class="form-control show-tick select2-search" name="nguoiky_id">
                                                <option value="">-- Chọn Người Ký --</option>
                                                @foreach ($ds_nguoiKy as $nguoiKy)
                                                    <option value="{{ $nguoiKy->id }}"
                                                    >{{$nguoiKy->ho_ten}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: -15px">
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ</label>
                                            <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu"
                                                   value="">
                                        </div>

                                        <div class="form-group col-md-12" style="margin-top: -15px">
                                            <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                            <textarea rows="3" name="vb_trichyeu" class="form-control no-resize"
                                                      placeholder="Nhập nội dung trích yếu ..."
                                            ></textarea>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" name="search">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th width="2%" style="vertical-align: middle" class="text-center">STT</th>
                                            <th width="26%" style="vertical-align: middle" class="text-center">Thông
                                                tin
                                            </th>
                                            <th width="38%" style="vertical-align: middle" class="text-center">Trích yếu
                                            </th>
                                            <th width="21%" style="vertical-align: middle" class="text-center">Nơi
                                                nhận
                                            </th>
                                            <th width="6%" style="vertical-align: middle" class="text-center">Tác vụ
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($ds_vanBanDi as $key=>$vbDi)
                                            <tr>
                                                <td class="text-center">{{$key+1}}</td>
                                                <td>
                                                    <p>- Số ký hiệu: {{$vbDi->vb_sokyhieu}}</p>
                                                    <p>- Ngày ban
                                                        hành: {{ dateFormat('d/m/Y',$vbDi->vb_ngaybanhanh) }}</p>
                                                    <p>- Loại văn bản: {{$vbDi->loaivanban->ten_loai_van_ban ?? ''}}</p>
                                                    <p>- Số đi: <span
                                                            class="font-bold color-red">{{$vbDi->vb_sothutu}}</span></p>
                                                </td>
                                                <td style="text-align: justify"><a
                                                        href="{{route('Quytrinhxulyvanbandi',$vbDi->id)}}"
                                                        title="{{$vbDi->vb_trichyeu}}">{{$vbDi->vb_trichyeu}}</a>
                                                    <div class="text-right " style="pointer-events: auto">
                                                        {{--                                                        @forelse($vbDi->file as $key=>$item)--}}
                                                        {{--                                                            <a href="{{$item->getUrlFile()}}" target="_blank">--}}
                                                        {{--                                                                --}}
                                                        {{--                                                            </a>@if(count($vbDi->file) == $key+1) @else &nbsp;|&nbsp; @endif--}}
                                                        {{--                                                        @empty--}}
                                                        {{--                                                        @endforelse--}}
                                                        @forelse($vbDi->filetrinhky as $filedata)
                                                            <a class="seen-new-window" target="popup" href="{{$filedata->getUrlFile()}}">[File trình ký]</a>
                                                        @empty
                                                        @endforelse
                                                        @forelse($vbDi->filephieutrinh as $filedata)
                                                            &nbsp; |<a class="seen-new-window" target="popup" href="{{$filedata->getUrlFile()}}"> [File phiếu
                                                                trình]</a>
                                                        @empty
                                                        @endforelse
                                                        @forelse($vbDi->filehoso as $filedata)
                                                            &nbsp; |<a href="{{$filedata->getUrlFile()}}"> [File hồ
                                                                sơ]</a>
                                                        @empty
                                                        @endforelse
                                                        {{--                                                        @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)--}}
                                                        {{--                                                            <a title="Cập nhật file" href="{{route('ds_file_di',$vbDi->id)}}"><span role="button">&emsp;<i class="fa  fa-search"></i></span></a>@endif--}}
                                                    </div>
                                                </td>
                                                <td>
                                                    {{--                                                    {{$vbDi->mailtrongtp}}--}}
                                                    @forelse($vbDi->mailtrongtp as $key=>$item)
                                                        - {{$item->laytendonvi->ten_don_vi}}<br>
                                                    @empty
                                                    @endforelse
                                                    @forelse($vbDi->mailngoaitp as $key=>$item)
                                                        - {{$item->laytendonvingoai->ten_don_vi}}<br>
                                                    @empty
                                                    @endforelse
                                                </td>
                                                <td class="text-center">
                                                    @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)
                                                        <a href="{{route('van_ban_di.edit',$vbDi->id)}}"
                                                           class="btn btn-color-blue btn-icon btn-light" role="button"
                                                           title="Sửa">
                                                            <i class="fas fa-file-signature"></i>
                                                        </a><a href="{{route('van_ban_di.delete',$vbDi->id)}}"
                                                               class="btn btn-color-red btn-icon btn-light btn-remove-item"
                                                               role="button" title="Xóa">
                                                            <i class="far fa-trash-alt" style="color: red"></i></a>

                                                    @else - @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="5" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="exampleModalLabel"><i
                                                                class="fa fa-folder-open-o"></i> Tải nhiều tệp tin </h4>
                                                    </div>
                                                    <form class="form-row" method="post"
                                                          action="{{route('upload_nhieufile_di')}}"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group col-md-12">
                                                            <label for="sokyhieu" class="col-form-label">Chọn tệp tin
                                                                <br>
                                                                <small><i>(Đặt tên file theo định dạng: tên viết tắt
                                                                        loại văn bản + số đi + năm (vd:
                                                                        QD-1-2020.pdf))</i></small>
                                                            </label><br>
                                                            <input type="file" multiple name="ten_file[]"
                                                                   accept=".xlsx,.xls,image/*,.doc, .docx,.txt,.pdf"/>
                                                            <input type="text" id="url-file" value="123" class="hidden"
                                                                   name="txt_file[]">
                                                        </div>
                                                        <div class="form-group col-md-4" style="margin-top: 26px">
                                                            <button class="btn btn-primary">Tải lên</button>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-md-6 col-12">
                                            Tổng số văn bản: <b>{{ $ds_vanBanDi->total() }}</b>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            {!! $ds_vanBanDi->appends(['loaivanban_id' => Request::get('loaivanban_id'), 'sovanban_id' => Request::get('sovanban_id')
                                       ,'vb_sokyhieu' => Request::get('vb_sokyhieu'),
                                       'donvisoanthao_id' => Request::get('donvisoanthao_id'),'start_date' => Request::get('start_date'),
                                       'end_date' => Request::get('end_date'),'nguoiky_id' => Request::get('nguoiky_id'),'chuc_vu' => Request::get('chuc_vu'),
                                       'vb_trichyeu' => Request::get('vb_trichyeu'),'search' =>Request::get('search') ])->render() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
{{--<style type="text/css">--}}
{{--    .modal {--}}
{{--        text-align: center;--}}
{{--        padding: 0!important;--}}
{{--    }--}}

{{--    .modal:before {--}}
{{--        content: '';--}}
{{--        display: inline-block;--}}
{{--        height: 100%;--}}
{{--        vertical-align: middle;--}}
{{--        margin-right: -4px;--}}
{{--    }--}}

{{--    .modal-dialog {--}}
{{--        display: inline-block;--}}
{{--        text-align: left;--}}
{{--        vertical-align: top;--}}
{{--    }--}}
{{--</style>--}}
