@extends('admin::layouts.master')
@section('page_title', 'Văn bản hoàn thành chờ duyệt')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Văn bản hoàn thành chờ duyệt</h4>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%">STT</th>
                                <th width="22%">Thông tin</th>
                                <th width="30%">Trích yếu - nội dung</th>
                                <th width="20%">Trình tự xử lý</th>
                                <th width="20%">Kết quả</th>
                            </tr>
                            </thead>
                            <tbody class="text-justify">
                            @forelse($danhSachVanBanDen as $vanBanDenDonVi)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        <p>- Ngày
                                            nhập: {{ $vanBanDenDonVi->created_at ? $vanBanDenDonVi->created_at->format('d/m/Y') : '' }}</p>
                                        <p>- Số ký hiệu: {{ $vanBanDenDonVi->so_ky_hieu ?? null }}</p>
                                        <p>- Nơi gửi: {{ $vanBanDenDonVi->co_quan_ban_hanh ?? null }}</p>
                                    </td>
                                    <td>
                                        <p>
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDenDonVi->id) }}">{{ $vanBanDenDonVi->trich_yeu }}</a>
                                            <br>
                                            @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                <i>
                                                    (Vào hồi {{ $vanBanDenDonVi->gio_hop }}
                                                    ngày {{ date('d/m/Y', strtotime($vanBanDenDonVi->ngay_hop)) }}
                                                    , tại {{ $vanBanDenDonVi->dia_diem }})
                                                </i>
                                            @endif
                                        </p>
                                        @if (!empty($vanBanDenDonVi->noi_dung))
                                            <p>
                                                <b>Nội dung:</b> <i>{{ $vanBanDenDonVi->noi_dung }}</i>
                                            </p>
                                        @endif
                                        <p>- Số đến: <b
                                                class="color-red">{{ $vanBanDenDonVi->so_den ?? null }}</b>
                                        </p>
                                        <p>- Cán bộ
                                            nhập: {{ $vanBanDenDonVi->nguoiDung->ho_ten ?? 'N/A' }}</p>
                                        <p>
                                            - <b>Hạn xử lý:
                                                @if(empty($vanBanDenDonVi->han_xu_ly))
                                                    {{ $vanBanDenDonVi->han_xu_ly ? date('d/m/Y', strtotime($vanBanDenDonVi->han_xu_ly)) : 'N/A'  }}

                                                @endif
                                            </b>
                                        </p>
                                        @if (isset($vanBanDenDonVi->vanBanDenFile))
                                            @foreach($vanBanDenDonVi->vanBanDenFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                @if (count($vanBanDenDonVi->vanBanDenFile)-1 != $key)
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($vanBanDenDonVi->xuLyVanBanDen)
                                            @foreach($vanBanDenDonVi->xuLyVanBanDen as $key => $chuyenVienXuLy)
                                                <p>
                                                    {{ $key+1 }}. {{$chuyenVienXuLy->canBoNhan->ho_ten ?? null }}
                                                </p>
                                                <hr class="border-dashed {{ count($vanBanDenDonVi->donViChuTri) == 0 && count($vanBanDenDonVi->xuLyVanBanDen  )-1 == $key ? 'hide' : 'show' }}">
                                            @endforeach
                                        @endif
                                        @if($vanBanDenDonVi->donViChuTri)
                                            @foreach($vanBanDenDonVi->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                <p>
                                                    {{ count($vanBanDenDonVi->xuLyVanBanDen) > 0 ? count($vanBanDenDonVi->xuLyVanBanDen)+($key+1) : $key+1 }}
                                                    . {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                </p>
                                                <hr class="border-dashed {{ count($vanBanDenDonVi->donViChuTri)-1 == $key ? 'hide' : 'show' }}">
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <p>{{ $vanBanDenDonVi->giaiQuyetVanBanHoanThanhChoDuyet()->noi_dung ?? null }}</p>

                                        @if (isset($vanBanDenDonVi->giaiQuyetVanBanHoanThanhChoDuyet()->giaiQuyetVanBanFile))
                                            @foreach($vanBanDenDonVi->giaiQuyetVanBanHoanThanhChoDuyet()->giaiQuyetVanBanFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                @if (count($vanBanDenDonVi->giaiQuyetVanBanHoanThanhChoDuyet()->giaiQuyetVanBanFile)-1 != $key)
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                        <p>
                                            <label for="" class="label label-warning">Chờ duyệt</label>
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="5" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="clearfix">
                            <div class="row">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 text-right">
                                {!! $danhSachVanBanDen->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
