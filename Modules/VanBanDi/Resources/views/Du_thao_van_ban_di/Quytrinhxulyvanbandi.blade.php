@extends('admin::layouts.master')
@section('page_title', 'Quá trình xử lý văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quá trình xử lý văn bản</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 mt-2"></div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Nơi nhận:</b>
                                        @if(count($vanbandi->donvinhanvbdi)>0)
                                            @forelse($vanbandi->donvinhanvbdi as $key=>$item)

                                                {{$item->laytendonvinhan->ten_don_vi ?? ''}},

                                            @empty
                                            @endforelse
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Số kí hiệu:</b>
                                        {{$vanbandi->so_ky_hieu}}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Loại văn bản:</b>
                                    {{$vanbandi->loaiVanBanid->ten_loai_van_ban ?? ''}}
                                    <p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Sổ văn bản:</b>
                                        {{$vanbandi->sovanban->ten_so_van_ban ?? ''}}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Ngày ký:</b>
                                        {{$vanbandi->ngay_ban_hanh}}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Người ký:</b>
                                        {{$vanbandi->nguoidung2->ho_ten ?? ''}}
                                    </p>
                                </div>
                                <div class="col-md-12">
                                    <p>
                                        <b>Trích yếu:</b>
                                        {{$vanbandi->trich_yeu}}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Chức vụ:</b>
                                        {{$vanbandi->chuc_vu}}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <b>Người nhập:</b>
                                        {{$vanbandi->nguoitao->ho_ten ?? ''}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @if($laytatcaduthao == null)
                        @else
                            <div class="col-md-12 " style="margin: 20px 0px">
                                <div class="col-md-3">
                                    <a class="btn btn-success btn-xs" role="button" data-toggle="collapse"
                                       href="#collapseExample"
                                       aria-expanded="false" aria-controls="collapseExample"><i
                                            class="fa fa-plus"></i>
                                    </a>
                                    <b class="text-danger"> Hiển thị quá trình dự thảo</b>
                                </div>

                            </div>

                            <div class="col-md-12 mt-2  collapse in" id="collapseExample">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <th class="text-center" style="vertical-align: middle;width: 5%">Lần</th>
                                        <th class="text-center" style="vertical-align: middle;width: 15%">Loại văn
                                            bản
                                        </th>
                                        <th class="text-center" style="vertical-align: middle;width: 10%">Ký hiệu
                                        </th>
                                        <th class="text-center" style="vertical-align: middle;width: 15%">Nơi gửi
                                            đến
                                        </th>
                                        <th class="text-center" style="vertical-align: middle;width: 30%">Trích
                                            yếu
                                        </th>
                                        <th class="text-center" style="vertical-align: middle;width: 10%">File</th>
                                        <th class="text-center" style="vertical-align: middle;width: 20%">Người ký
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($laytatcaduthao as $key=>$data)
                                        <tr>
                                            <td class="text-center">{{$data->lan_du_thao}}</td>
                                            <td class="text-center">{{$data->loaivanban->ten_loai_van_ban}}</td>
                                            <td class="text-center">{{$data->so_ky_hieu}}</td>
                                            <td class="text-center">

                                            </td>
                                            <td>
                                                <a href="{{route('quytrinhtruyennhangopy',$data->id)}}">{{$data->vb_trich_yeu}}</a>
                                            </td>
                                            <td>
                                                @forelse($data->Duthaofile as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" class="seen-new-window"
                                                       target="popup">
                                                        [File dự thảo {{$key+1}}] <br>
                                                    </a>

                                                @empty
                                                @endforelse
                                            </td>
                                            <td>{{$data->nguoidung2->ho_ten ?? ''}}</td>
                                        </tr>

                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <label for="">Quá trình xử lý văn bản chính:</label>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                    <th class="text-center" width="5%" style="vertical-align: middle;">STT</th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Thời gian
                                    </th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Người gửi
                                    </th>
                                    <th class="text-center" width="30%" style="vertical-align: middle;">Nội dung
                                    </th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Người nhận
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($quatrinhtruyennhan as $key=>$data)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td> {{date_format($data->created_at, 'd-m-Y H:i:s') ?? ''}}</td>
                                        <td>{{$data->canbochuyen->ho_ten ?? ''}}</td>
                                        <td>{{$data->y_kien_gop_y}}</td>
                                        <td>{{$data->canbonhan->ho_ten ?? ''}} </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 hide">
                            <label for="">Phối hợp xử lý:</label>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                    <th class="text-center" width="5%" style="vertical-align: middle;">STT</th>
                                    <th class="text-center" width="15%" style="vertical-align: middle;">Thời gian
                                    </th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Người gửi
                                    </th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Người nhận
                                    </th>
                                    <th class="text-center" width="30%" style="vertical-align: middle;">Nội dung xử
                                        lý
                                    </th>
                                    <th class="text-center" width="10%" style="vertical-align: middle;">File</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <label for="">Tệp tin đính kèm:</label>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                    <th class="text-center" width="10%" style="vertical-align: middle;">STT</th>
                                    <th class="text-center" width="30%" style="vertical-align: middle;">Tên tệp
                                        tin
                                    </th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Tải về</th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Ngày nhập
                                    </th>
                                    <th class="text-center" width="20%" style="vertical-align: middle;">Người gửi
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($file as $key=>$filedata)
                                    <tr>
                                        <td class="text-center"> {{$key+1}}</td>
                                        <td> {{$filedata->ten_file}}</td>
                                        <td class="text-center"><a href="{{$filedata->getUrlFile()}}"
                                                                   class="seen-new-window" target="popup">[Tải tài
                                                liệu]</a></td>
                                        <td class="text-center"> {{  date_format($filedata->created_at, 'd-m-Y H:i:s') ?? ''}}</td>
                                        <td class="text-center"> {{$filedata->nguoiDung->ho_ten ?? ''}}</td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if (!empty($vanbandi->listVanBanDen))
                            <div class="col-md-12">
                                <label for="">Trả lời cho văn bản :</label>
                                <table class="table table-bordered table-striped dataTable mb-0">
                                    <thead>
                                    <tr>
                                        <th width="2%" class="text-center">STT</th>
                                        <th width="26%" class="text-center">Thông tin</th>
                                        <th width="44%" class="text-center">Trích yếu</th>
                                        <th width="21%" class="text-center">Đơn vị xử lý</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vanbandi->listVanBanDen as  $key => $vanBanDen)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td>
                                                <p>- Số ký hiệu: {{$vanBanDen->so_ky_hieu}}</p>
                                                <p>- Ngày ban
                                                    hành: {{ date('d-m-Y', strtotime($vanBanDen->ngay_ban_hanh)) }}</p>
                                                <p>- Cơ quan ban
                                                    hành: {{$vanBanDen->co_quan_ban_hanh}}</p>
                                                <p>- Số đến: <span
                                                        class="font-bold"
                                                        style="color: red">{{$vanBanDen->so_den}}</span>
                                                </p>
                                                <p>- Sổ văn
                                                    bản: {{$vanBanDen->soVanBan->ten_so_van_ban ?? ''}}</p>
                                            </td>
                                            <td style="text-align: justify">
                                                @if ($vanBanDen->loai_van_ban_don_vi == 1)
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->parent_id ? $vanBanDen->parent_id.'?status=1' : $vanBanDen->id .'?status=1') }}"
                                                       title="{{$vanBanDen->trich_yeu}}">{{$vanBanDen->trich_yeu}}</a>
                                                    <br>
                                                @else
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->parent_id ? $vanBanDen->parent_id : $vanBanDen->id) }}"
                                                       title="{{$vanBanDen->trich_yeu}}">{{$vanBanDen->trich_yeu}}</a>
                                                    <br>
                                                @endif

                                                @if($vanBanDen->noi_dung != null)<span
                                                    style="font-weight: bold;">Nội dung:</span>@endif
                                                <span
                                                    style="font-style: italic">{{$vanBanDen->noi_dung ?? ''}}</span>@if($vanBanDen->noi_dung != null)
                                                    <br>@endif
                                                     (Hạn giải quyết: {{ date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) }})
                                                <br>
                                                <span
                                                    style="font-style: italic">Người nhập : {{$vanBanDen->nguoiDung->ho_ten ?? ''}}</span>
                                                <div class="text-right " style="pointer-events: auto">
                                                    @if($vanBanDen->vanBanDenFile)
                                                        @forelse($vanBanDen->vanBanDenFile as $key=>$item)
                                                            <a href="{{$item->getUrlFile()}}" target="popup"
                                                               class="seen-new-window">
                                                                @if($item->duoi_file == 'pdf')<i
                                                                    class="fa fa-file-pdf-o"
                                                                    style="font-size:20px;color:red"></i>@elseif($item->duoi_file == 'docx' || $item->duoi_file == 'doc')
                                                                    <i class="fa fa-file-word-o"
                                                                       style="font-size:20px;color:blue"></i> @elseif($item->duoi_file == 'xlsx' || $item->duoi_file == 'xls')
                                                                    <i class="fa fa-file-excel-o"
                                                                       style="font-size:20px;color:green"></i> @endif
                                                            </a>@if(count($vanBanDen->vanBanDenFile) == $key+1) @else
                                                                &nbsp;
                                                                |&nbsp; @endif
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                    @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)
                                                        <a title="Cập nhật file"
                                                           href="{{route('ds_file',$vanBanDen->vb_den_id)}}"><span
                                                                role="button">&emsp;<i
                                                                    class="fa  fa-search"></i></span></a>@endif
                                                </div>
                                            </td>
                                            <td>
                                                <!--vb den don vi-->
                                                @if ($vanBanDen->parent_id)
                                                    @foreach($vanBanDen->getParent()->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                        @if (count($vanBanDen->getParent()->donViChuTri)-1 == $key)
                                                            <p>
                                                                {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                                <br>
                                                                <i>(Cán bộ xử
                                                                    lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                                    )</i>
                                                            </p>
                                                        @endif
                                                    @endforeach
                                                @else
                                                <!--vb den huyen-->
                                                    @if($vanBanDen->donViChuTri)
                                                        @foreach($vanBanDen->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                            @if (count($vanBanDen->donViChuTri)-1 == $key)
                                                                <p>
                                                                    {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                                                    <br>
                                                                    <i>(Cán bộ xử
                                                                        lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                                        )</i>
                                                                </p>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="col-md-12 mt-2">
                            <a class="btn btn-default" href="javascript: history.back(1)" id="backLink"
                               data-original-title="" title="">Quay lại &gt;&gt;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
