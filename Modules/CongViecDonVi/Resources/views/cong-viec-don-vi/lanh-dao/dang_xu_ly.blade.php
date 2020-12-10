@extends('administrator::layouts.master')
@if (isset($type))
    @section('page_title', 'Công việc đã hoàn thành')
@else
    @section('page_title', 'Công việc đang xử lý')
@endif
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title pt-2">{{ isset($type) ? 'Công việc đã hoàn thành' : 'Công việc đang xử lý' }}</h4>
                                    </div>
                                </div>
                                <!--datatable-->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr role="row" class="text-center">
                                            <th>STT</th>
                                            <th width="30%">Trích yếu - Thông tin</th>
                                            <th width="20%">Nội dung công việc</th>
                                            <th width="20%">Trình tự xử lý</th>
                                            <th width="20%">Kết quả</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($danhSachCongViecDonVi as $congViecDonVi)
                                            <tr class="tr-tham-muu">
                                                <td class="text-center">{{ $order++ }}</td>
                                                <td>
                                                    @if ($congViecDonVi->lichCongTac->vanBanDenDonVi)
                                                        <p>
                                                            <a href="{{ route('xu-ly-van-ban.show', $congViecDonVi->lichCongTac->vanBanDenDonVi->id) }}">{{ $congViecDonVi->lichCongTac->vanBanDenDonVi->vanBanDen->vb_trich_yeu }}</a>
                                                            <br>
                                                            @if ($congViecDonVi->lichCongTac->vanBanDenDonVi->so_van_ban_id == SO_VB_GIAY_MOI)
                                                                <i>
                                                                    (Vào
                                                                    hồi {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->gio_hop_chinh }}
                                                                    ngày {{ date('d/m/Y', strtotime($congViecDonVi->lichCongTac->vanBanDenDonVi->ngay_hop_chinh)) }}
                                                                    ,
                                                                    tại {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->dia_diem_chinh }}
                                                                    )
                                                                </i>
                                                            @endif
                                                        </p>
                                                        @if (!empty($congViecDonVi->lichCongTac->vanBanDenDonVi->noi_dung))
                                                            <p>
                                                                <b>Nội dung:</b>
                                                                <i>{{ $congViecDonVi->lichCongTac->vanBanDenDonVi->noi_dung }}</i>
                                                            </p>
                                                        @endif
                                                        <p>- Nơi gửi
                                                            đến: {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->co_quan_ban_hanh_id ?? null }}</p>
                                                        <p>- Ngày
                                                            nhập: {{  $congViecDonVi->lichCongTac->vanBanDenDonVi->ngay_tao->format('m/d/Y H:i:s') }}</p>
                                                        <i><p class="font-bold">- Cán bộ
                                                                nhập: {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->nguoiDung->ho_ten ?? 'N/A' }}</p>
                                                        </i>
                                                        @foreach($congViecDonVi->lichCongTac->vanBanDenDonVi->vanBanDen->vanBanDenFile as  $file)
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
                                                    <p>
                                                        <a href="{{ route('cong-viec-hoan-thanh.show', $congViecDonVi->id) }}">{{ $congViecDonVi->noi_dung_cuoc_hop }}</a>
                                                    </p>
                                                    @if (!empty($congViecDonVi->han_xu_ly))
                                                        <p>
                                                            - <b>Hạn xử
                                                                lý:
                                                                {{ date('d/m/Y', strtotime($congViecDonVi->han_xu_ly)) }}
                                                            </b>
                                                        </p>
                                                    @endif
                                                    @if (isset($congViecDonVi->congViecDonViFile))
                                                        @foreach($congViecDonVi->congViecDonViFile as $key => $file)
                                                            <a href="{{ $file->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                ]</a>
                                                            @if (count($congViecDonVi->congViecDonViFile)-1 != $key)
                                                                &nbsp;|&nbsp;
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($congViecDonVi->ChuyenNhanCongViecDonViDangXuLy)
                                                        @foreach($congViecDonVi->ChuyenNhanCongViecDonViDangXuLy as $key => $dauViecDonVi)
                                                            <p>
                                                                <b>- Đơn vị chủ
                                                                    trì:</b> {{ $dauViecDonVi->donVi->ten_don_vi }}
                                                            </p>
                                                            <p>
                                                                <b>- Nội dung:</b> {{ $dauViecDonVi->noi_dung }}
                                                            </p>
                                                            @if (count($congViecDonVi->ChuyenNhanCongViecDonViDangXuLy)-1 != $key)
                                                                <hr class="border-dashed">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($congViecDonVi->ChuyenNhanCongViecDonViDangXuLy)
                                                        @foreach($congViecDonVi->ChuyenNhanCongViecDonViDangXuLy as $key => $dauViecDonVi)
                                                            @if (!empty($dauViecDonVi->giaiQuyetCongViecHoanThanh()->noi_dung))
                                                            <p>
                                                                <b>- Đơn vị chủ
                                                                    trì:</b> {{ $dauViecDonVi->donVi->ten_don_vi }}
                                                            </p>
                                                            @endif
                                                            <p>{{ $dauViecDonVi->giaiQuyetCongViecHoanThanh()->noi_dung ?? null }}</p>

                                                            @if (isset($dauViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile))
                                                                @foreach($dauViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile as $key => $file)
                                                                    <a href="{{ $file->getUrlFile() }}"
                                                                       target="popup"
                                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                        ]</a>
                                                                    @if (count($dauViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile)-1 != $key)
                                                                        &nbsp;|&nbsp;
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            @if (count($congViecDonVi->ChuyenNhanCongViecDonViDangXuLy)-1 != $key)
                                                                <hr class="border-dashed">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="5"
                                                class="text-center">Không tìm
                                                thấy dữ liệu.
                                            </td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-md-6 col-12">
                                            Tổng số công việc: <b>{{ $danhSachCongViecDonVi->total() }}</b>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            {{ $danhSachCongViecDonVi->appends(['ngay_tao'  => Request::get('ngay_tao'), 'type' => Request::get('type')])->render() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
