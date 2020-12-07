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
                                    <label for="" class="col-md-4">Nơi nhận:</label>
                                    <span>@if(count($vanbandi->donvinhanvbdi)>0)
                                            @forelse($vanbandi->donvinhanvbdi as $key=>$item)

                                                    {{$item->laytendonvinhan->ten_don_vi ?? ''}},

                                            @empty
                                            @endforelse
                                        @endif</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="col-md-4">Số kí hiệu:</label>
                                    <span>{{$vanbandi->so_ky_hieu}}</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="col-md-4">Loại văn bản:</label>
                                    <span>{{$vanbandi->loaiVanBanid->ten_loai_van_ban ?? ''}}</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="col-md-4">Sổ văn bản:</label>
                                    <span>{{$vanbandi->sovanban->ten_so_van_ban ?? ''}}</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="col-md-4">Ngày ký:</label>
                                    <span>{{$vanbandi->ngay_ban_hanh}}</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="col-md-4">Người ký:</label>
                                    <span>{{$vanbandi->nguoidung2->ho_ten ?? ''}}</span>
                                </div>
                                <div class="col-md-12">
                                    <label for="" class="col-md-4">Trích yếu:</label>
                                    <div class="col-md-12">{{$vanbandi->trich_yeu}}</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="col-md-4">Chức vụ:</label>
                                    <span>{{$vanbandi->chuc_vu}}</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="col-md-4">Người nhập:</label>
                                    <span>{{$vanbandi->nguoitao->ho_ten ?? ''}}</span>
                                </div>
                            </div>
                        </div>
                        @if($laytatcaduthao == null)
                        @else
                            <div class="col-md-12 " style="margin: 20px 0px">
                                <div class="col-md-3">
                                    <a class="btn btn-success btn-xs" role="button"  data-toggle="collapse"
                                       href="#collapseExample"
                                       aria-expanded="false" aria-controls="collapseExample"><i
                                            class="fa fa-plus"></i>
                                    </a>
                                    <b class="text-danger"> Hiển thị quá trình dự thảo</b>
                                </div>

                            </div>

                            <div class="col-md-12 mt-2  collapse in"  id="collapseExample">
                                <div class="form-group" style="padding-left:15px;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th class="text-center" style="vertical-align: middle;width: 5%">Lần</th>
                                            <th class="text-center" style="vertical-align: middle;width: 15%">Loại văn bản</th>
                                            <th class="text-center" style="vertical-align: middle;width: 10%">Ký hiệu</th>
                                            <th class="text-center" style="vertical-align: middle;width: 15%">Nơi gửi đến</th>
                                            <th class="text-center" style="vertical-align: middle;width: 30%">Trích yếu</th>
                                            <th class="text-center" style="vertical-align: middle;width: 10%">File</th>
                                            <th class="text-center" style="vertical-align: middle;width: 20%">Người ký</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($laytatcaduthao as $key=>$data)
                                            <tr>
                                                <td class="text-center">{{$data->lan_du_thao}}</td>
                                                <td class="text-center" >{{$data->loaivanban->ten_loai_van_ban}}</td>
                                                <td class="text-center">{{$data->so_ky_hieu}}</td>
                                                <td class="text-center">

                                                </td>
                                                <td><a href="{{route('quytrinhtruyennhangopy',$data->id)}}">{{$data->vb_trich_yeu}}</a></td>
                                                <td>
                                                    @forelse($data->Duthaofile as $key=>$item)
                                                        <a href="{{$item->getUrlFile()}}"class="seen-new-window" target="popup" >
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
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="form-group" style="padding-left:15px;">
                                <label for="">Quá trình xử lý văn bản chính:</label>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <th class="text-center" width="5%" style="vertical-align: middle;">STT</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Thời gian</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Người gửi</th>
                                        <th class="text-center" width="30%" style="vertical-align: middle;">Nội dung</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Người nhận</th>
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
                        </div>
                        <div class="col-md-12 hide">
                            <div class="form-group" style="padding-left:15px;">
                                <label for="">Phối hợp xử lý:</label>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <th class="text-center" width="5%" style="vertical-align: middle;">STT</th>
                                        <th class="text-center" width="15%" style="vertical-align: middle;">Thời gian</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Người gửi</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Người nhận</th>
                                        <th class="text-center" width="30%" style="vertical-align: middle;">Nội dung xử lý</th>
                                        <th class="text-center" width="10%" style="vertical-align: middle;">File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="padding-left:15px;">
                                <label for="">Tệp tin đính kèm:</label>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <th class="text-center" width="10%" style="vertical-align: middle;">STT</th>
                                        <th class="text-center" width="30%" style="vertical-align: middle;">Tên tệp tin</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Tải về</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Ngày nhập</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Người gửi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                    @forelse($file as $key=>$filedata)
                                        <tr>
                                            <td class="text-center"> {{$key+1}}</td>
                                            <td> {{$filedata->ten_file}}</td>
                                            <td  class="text-center"> <a href="{{$filedata->getUrlFile()}}" class="seen-new-window" target="popup">[Tải tài liệu]</a></td>
                                            <td  class="text-center"> {{  date_format($filedata->created_at, 'd-m-Y H:i:s') ?? ''}}</td>
                                            <td  class="text-center"> {{$filedata->nguoiDung->ho_ten ?? ''}}</td>
                                        </tr>
                                        @empty
                                        @endforelse
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if (!empty($vanbandi->vanBanDenDonVi))
                            <div class="col-md-12">
                                <div class="form-group" style="padding-left:15px;">
                                    <label for="">Trả lời cho văn bản :</label>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th width="2%">STT</th>
                                            <th width="4%">Ngày nhập</th>
                                            <th width="8">Số ký hiệu</th>
                                            <th width="15%">Nơi gửi</th>
                                            <th width="30%">Trích yếu - Thông tin</th>
                                            <th width="25%">Trình tự xử lý</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="tr-tham-muu">
                                            <td class="text-center">1</td>
                                            <td>{{  $vanbandi->vanBanDenDonVi->ngay_tao ? $vanbandi->vanBanDenDonVi->ngay_tao->format('m/d/Y') : '' }}</td>
                                            <td>{{ $vanbandi->vanBanDenDonVi->vanBanDen->so_ky_hieu ?? null }}</td>
                                            <td>{{ $vanbandi->vanBanDenDonVi->co_quan_ban_hanh_id ?? null }}</td>
                                            <td class="{{ Request::get('qua_han') ? 'color-red' : null }}">
                                                <p>
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanbandi->vanBanDenDonVi->id) }}">{{ $vanbandi->vanBanDenDonVi->vanBanDen->vb_trich_yeu }}</a>
                                                    @if ($vanbandi->vanBanDenDonVi->so_van_ban_id == SO_VB_GIAY_MOI)
                                                        <br>
                                                        <i>
                                                            (Vào hồi {{ $vanbandi->vanBanDenDonVi->gio_hop_chinh }}
                                                            ngày {{ date('d/m/Y', strtotime($vanbandi->vanBanDenDonVi->ngay_hop_chinh)) }}
                                                            , tại {{ $vanbandi->vanBanDenDonVi->dia_diem_chinh }})
                                                        </i>
                                                    @endif
                                                </p>
                                                @if (!empty($vanbandi->vanBanDenDonVi->noi_dung))
                                                    <p>
                                                        <b>Nội dung:</b> <i>{{ $vanbandi->vanBanDenDonVi->noi_dung }}</i>
                                                    </p>
                                                @endif
                                                @if($vanbandi->vanBanDenDonVi->so_van_ban_id == SO_VB_GIAY_MOI)
                                                    @if (!empty($vanbandi->vanBanDenDonVi->lichCongTac->lanhDao))
                                                        <p>
                                                            <b>- Lãnh đạo dự họp:</b><i>{{ $vanbandi->vanBanDenDonVi->lichCongTac->lanhDao->ho_ten ?? null }}</i>
                                                        </p>
                                                    @endif
                                                @endif
                                                <p class="font-bold">- Cán bộ
                                                    nhập: {{ $vanbandi->vanBanDenDonVi->nguoiDung->ho_ten ?? 'N/A' }}</p>
                                                <p>
                                                    - <b>Hạn xử lý:
                                                        @if(empty($vanbandi->vanBanDenDonVi->han_xu_ly))
                                                            {{ $vanbandi->vanBanDenDonVi->vanBanDen->vb_han_xu_ly ? date('d/m/Y', strtotime($vanbandi->vanBanDenDonVi->vanBanDen->vb_han_xu_ly)) : 'N/A'  }}

                                                        @else
                                                            {{ date('d/m/Y', strtotime($vanbandi->vanBanDenDonVi->han_xu_ly)) }}
                                                        @endif
                                                    </b>
                                                </p>

                                                @if (isset($vanbandi->vanBanDenDonVi->vanBanDen->vanBanDenFile))
                                                    @foreach($vanbandi->vanBanDenDonVi->vanBanDen->vanBanDenFile as  $file)
                                                        <div class="detail-file-name giai-quyet-file">
                                                            <a href="{{ $file->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                ]</a>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            </td>
                                            <td>
                                                @if($vanbandi->vanBanDenDonVi->xuLyChuyenVien)
                                                    @foreach($vanbandi->vanBanDenDonVi->xuLyChuyenVien as $key => $chuyenVienXuLy)
                                                        <p>
                                                            {{ $key+1 }}
                                                            . {{$chuyenVienXuLy->canBoNhan->ho_ten ?? null }}
                                                        </p>
                                                        <hr class="border-dashed {{ count($vanbandi->vanBanDenDonVi->chuyenNhanVanBanDonViChuTri) == 0 && count($vanbandi->vanBanDenDonVi->xuLyChuyenVien)-1 == $key ? 'hide' : 'show' }}">
                                                    @endforeach
                                                @endif

                                                @if($vanbandi->vanBanDenDonVi->chuyenNhanVanBanDonViChuTri)
                                                    @foreach($vanbandi->vanBanDenDonVi->chuyenNhanVanBanDonViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                        <p>
                                                            {{ count($vanbandi->vanBanDenDonVi->xuLyChuyenVien) > 0 ? count($vanbandi->vanBanDenDonVi->xuLyChuyenVien)+($key+1) : $key+1 }}
                                                            . {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                        </p>
                                                        <hr class="border-dashed {{ count($vanbandi->vanBanDenDonVi->chuyenNhanVanBanDonViChuTri)-1 == $key ? 'hide' : 'show' }}">
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="form-group" style="padding-left:15px;">
                                <a class="btn btn-default" href="javascript: history.back(1)" id="backLink" data-original-title="" title="">Quay lại &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
