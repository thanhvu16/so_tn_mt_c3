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
                        <a role="button" data-toggle="modal" data-target="#myModal2" class="btn btn-primary btn-sm">
                            <span style="color: white;font-size: 16px"><i class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</span></a>
                        @endif &emsp;
                        <a class="btn-xs btn btn-primary" data-toggle="collapse"
                           href="#collapseExample"
                           aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i><span
                                style="font-size: 16px">Tìm kiếm văn bản </span>
                        </a>
                </ul>


                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <form action="{{route('dsgiaymoi')}}" method="get">
                                <div class="col-md-12 collapse in" id="collapseExample">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="cap_ban_hanh_id" autofocus class="col-form-label">Sổ văn bản đi
                                            </label>
                                            <select class="form-control show-tick" name="sovanban_id">
                                                <option value="">-- Chọn Sổ Văn Bản Đi --</option>
                                                <option value="1">Sổ Ủy ban</option>
                                                <option value="2">Sổ văn phòng</option>
                                                <option value="3">Sổ khác</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Ký hiệu </label>
                                            <input type="text" value=""
                                                   id="vb_sokyhieu" name="vb_sokyhieu" class="form-control"
                                                   placeholder="Nhập số ký hiệu văn bản đi...">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Ngày ban hành từ </label>
                                            <input type="date" name="vb_ngaybanhanh_start" id="vb_ngaybanhanh"
                                                   class="form-control"
                                                   value=""
                                                   autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="sokyhieu" class="col-form-label">Nhập đến ngày </label>
                                            <input type="date" name="vb_ngaybanhanh_end" id="vb_ngaybanhanh"
                                                   class="form-control"
                                                   value=""
                                                   autocomplete="off">
                                        </div>


                                        <div class="col-md-3" style="margin-top: -5px">
                                            <div class="form-group">
                                                <label for="">Giờ họp </label>
                                                <input type="time" class="form-control" value=""
                                                       name="gio_hop">
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="margin-top: -5px">
                                            <div class="form-group">
                                                <label for="">Ngày họp từ</label>
                                                <input type="date" class="form-control ngaybanhanh2" value=""
                                                       name="start_date" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="margin-top: -5px">
                                            <div class="form-group">
                                                <label for="">Họp đến ngày</label>
                                                <input type="date" class="form-control ngaybanhanh2" value=""
                                                       name="end_date" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="margin-top: -5px">
                                            <div class="form-group">
                                                <label for="">Địa điểm </label>
                                                <input type="text" class="form-control" value=""
                                                       name="dia_diem" placeholder="Địa điểm">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3" style="margin-top: -15px">
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký </label>
                                            <select class="form-control show-tick  layidnguoiky" name="nguoiky_id"
                                            >
                                                <option value="">-- Chọn Người Ký --</option>
                                                @foreach ($ds_nguoiKy as $nguoiKy)
                                                    <option data-chuc-vu="{{ $nguoiKy->chucvu->ten_chuc_vu ?? null }}"
                                                            value="{{ $nguoiKy->id }}"
                                                    >{{$nguoiKy->ho_ten}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: -15px">
                                            <label for="co_quan_ban_hanh_id" class="col-form-label">Chức vụ </label>
                                            <input type="text" class="form-control" placeholder="chức vụ" name="chuc_vu"
                                                   value="">
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: -15px">
                                            <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo </label>
                                            <select class="form-control show-tick select2-search"
                                                    name="donvisoanthao_id">
                                                @foreach ($ds_DonVi as $donVi)
                                                    <option value="{{ $donVi->ma_id }}"
                                                    >{{ $donVi->ten_don_vi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" style="margin-top: 25px">
                                            <button name="search" class="btn btn-primary">Tìm kiếm</button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th width="2%" style="vertical-align: middle" class="text-center">STT</th>
                                            <th width="25%" style="vertical-align: middle" class="text-center">Thông
                                                tin
                                            </th>
                                            <th width="33%" style="vertical-align: middle" class="text-center">Trích
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
                                                    <p>- Số ký hiệu: {{$vbDi->vb_sokyhieu}}</p>
                                                    <p>- Ngày nhập: {{ dateFormat('d/m/Y',$vbDi->vb_ngaybanhanh) }}</p>
                                                    <p>- Loại văn bản: Giấy mời</p>
                                                    <p>- Số đi: <span
                                                            class="font-bold color-red">{{$vbDi->vb_sothutu}}</span></p>
                                                </td>

                                                <td style="text-align: justify"><a
                                                        href="{{ route('Quytrinhxulyvanbandi',$vbDi->id) }}" class="tin"
                                                        data-original-title=""
                                                        title="">{{$vbDi->vb_trichyeu}}</a><br>
                                                    (Vào hồi {{$vbDi->gio_hop}}
                                                    ngày {{dateFormat('d/m/Y',$vbDi->ngay_hop)}},
                                                    tại {{$vbDi->dia_diem_hop}}) | (Số trang:{{$vbDi->vb_soTrang}})

                                                    <br><i>(Người
                                                        ký: {{$vbDi->nguoidung2->ho_ten}})</i>
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

                                                    @forelse($vbDi->mailtrongtp as $key=>$item)
                                                        - {{$item->laytendonvi->ten_don_vi}}<br>
                                                    @empty
                                                    @endforelse
                                                    @forelse($vbDi->mailngoaitp as $key=>$item)
                                                        - {{$item->laytendonvingoai->ten_don_vi}}<br>
                                                    @empty
                                                    @endforelse

                                                    <br>
                                                </td>
                                                <td class="text-center" style="color: red; vertical-align: middle;">-
                                                </td>
                                                <td class="text-center">
                                                    <p><a style="color: #0E0EFF"
                                                          href="{{route('Quytrinhxulyvanbandi',$vbDi->id)}}"
                                                          data-toggle="tooltip" data-placement="top" title=""
                                                          class="btn btn-default" data-original-title="Sửa dữ liệu"><i
                                                                class="fa fa-edit"></i></a></p>
                                                    <a style="color: red" href="{{route('xoagiaymoidi',$vbDi->id)}} "
                                                       onclick="return confirm('Bạn có muốn xóa văn bản này không?')"
                                                       class="btn btn-default btn-color-red"
                                                       data-original-title="Xóa dữ liệu"><i
                                                            class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="7" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>
                                        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog"
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
                                                          action="{{route('upload_nhieufile_gm_di')}}"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group col-md-12">
                                                            <label for="sokyhieu" class="col-form-label">Chọn tệp tin
                                                                <br>
                                                                <small><i>(Đặt tên file theo định dạng: tên viết tắt
                                                                        loại văn bản + số đi + năm (vd:
                                                                        GM-1-2020.pdf))</i></small>
                                                            </label>
                                                            <br>
                                                            <input type="file" id="url-file" multiple name="ten_file[]">
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
                                            Tổng số giấy mời: <b>{{ $ds_vanBanDi->total() }}</b>
                                        </div>
                                        <div class="col-md-6 col-12">
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
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>

@endsection
{{--@section('script')--}}
{{--    <script type="text/javascript">--}}
{{--        function showModal3() {--}}
{{--            $("#myModal2").modal('show');--}}
{{--        }--}}
{{--    </script>--}}
{{--@endsection--}}

