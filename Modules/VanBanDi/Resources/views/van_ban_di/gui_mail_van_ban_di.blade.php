@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="far fa-plus-square"></i> Danh sách văn bản đi
                        </a>
                    </li>&emsp;
                    <a class="btn-xs btn btn-primary" data-toggle="collapse"
                       href="#collapseExample"
                       aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i><span
                            style="font-size: 15px">Tìm kiếm văn bản </span>
                    </a> &nbsp;
                    @if(request('loaivanban_id') || request('sovanban_id') || request('vb_sokyhieu') ||
                        request('donvisoanthao_id') || request('start_date') || request('end_date') || request('nguoiky_id')
                        || request('chuc_vu') || request('vb_trichyeu'))
                        <a href="{{ route('van_ban_di.gui_mail_don_vi') }}">
                            <button type="button" class="btn btn-success">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </a>
                    @endif
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <form action="{{route('van_ban_di.gui_mail_don_vi')}}" method="get">
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
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <button type="button"
                                                class="btn btn-sm mt-1 btn-primary waves-effect waves-light print-data pull-right hide btn-duyet-all pull-right btn-sm mb-2"
                                                data-toggle="modal"
                                                data-target="#modal-chuyen-van-ban"
                                                title=""><i class="fa fa-send"></i>
                                            Gửi văn bản
                                        </button>
                                    </div>
                                </div>
                                @include('quanlyvanban::van_ban_di.form_don_vi_nhan')
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th width="2%" class="text-center">STT</th>
                                            <th width="26%" class="text-center">Thông tin</th>
                                            <th width="38%" class="text-center">Trích yếu
                                            </th>
                                            <th width="21%" class="text-center">Đơn vị nhận</th>
                                            <th width="7%" class="text-center">Chọn</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($danhSachVanBanDi as $key => $vbDi)
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
                                                <td>
                                                    <p>
                                                        <a href="{{route('Quytrinhxulyvanbandi',$vbDi->id)}}"
                                                           title="{{$vbDi->vb_trichyeu}}">{{$vbDi->vb_trichyeu}}</a>
                                                    </p>
                                                    <p>
                                                        @if (isset($vbDi->filetrinhky))
                                                            tệp tin: <br>
                                                            @foreach($vbDi->filetrinhky as $key => $file)
                                                                <a href="{{ $file->getUrlFile() }}"
                                                                   target="popup"
                                                                   class="detail-file-name seen-new-window">[{{ cutStr($file->tenfile) }}
                                                                    ]</a>
                                                                @if (count($vbDi->filetrinhky)-1 != $key)
                                                                    &nbsp;|&nbsp;
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </p>
                                                </td>
                                                <td>
                                                    @if (!empty($vbDi->logGuiMailVanBanDi))
                                                        @forelse($vbDi->logGuiMailVanBanDi as $key=>$item)
                                                            <p>
                                                                - {{$item->emailTrongDonVi->ten_don_vi}}
                                                                @if ($item->status == \Modules\QuanLyVanBan\Entities\LogGuiMailVanBanDi::DA_GUI)
                                                                    <br>
                                                                    <label class="label label-success">đã gửi</label>
                                                                @endif
                                                            </p>
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox{{ $vbDi->id }}" type="checkbox"
                                                               name="duyet[{{ $vbDi->id }}]" value="{{ $vbDi->id }}"
                                                               class="duyet sub-check">
                                                        <label for="checkbox{{ $vbDi->id }}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="5" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-md-6 col-12">
                                            Tổng số văn bản: <b>{{ $danhSachVanBanDi->total() }}</b>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            {!! $danhSachVanBanDi->appends(['loaivanban_id' => Request::get('loaivanban_id'), 'sovanban_id' => Request::get('sovanban_id')
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
        // check all
        let allId = [];
        $('.sub-check').on('click', function () {

            let id = $(this).val();

            if ($(this).is(':checked')) {

                if (allId.indexOf(id) === -1) {
                    allId.push(id);
                }
                $('#form-tao-phieu-chuyen-van-ban').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(allId));
            } else {

                var index = allId.indexOf(id);

                if (index > -1) {
                    allId.splice(index, 1);
                }

                $('#form-tao-phieu-chuyen-van-ban').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(allId));
            }

            if (allId.length != 0) {
                $('.btn-duyet-all').removeClass('hide');
            } else {
                $('.btn-duyet-all').addClass('hide');
            }
        });
    </script>
@endsection
