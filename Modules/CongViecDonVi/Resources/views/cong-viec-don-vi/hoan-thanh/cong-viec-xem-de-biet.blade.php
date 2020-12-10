@extends('administrator::layouts.master')
@section('page_title', 'Công việc hoàn thành')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-2">Công việc hoàn thành</h4>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr role="row" class="text-center">
                                            <th width="2%">STT</th>
                                            <th width="30%">Nội dung - Thông tin</th>
                                            <th width="20%">Nội dung đầu việc đơn vị</th>
                                            <th width="20%">Trình tự xử lý</th>
                                            <th width="20%">Kết quả</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($danhSachChuyenNhanCongViecDonVi as  $chuyenNhanCongViecDonVi)
                                            <tr class="duyet-vb">
                                                <td class="text-center">{{ $order+1 }}</td>
                                                <td>
                                                    <p>
                                                        <a href="{{ route('cong-viec-don-vi.show', $chuyenNhanCongViecDonVi->id) }}">{{ $chuyenNhanCongViecDonVi->congViecDonVi->noi_dung_cuoc_hop }}</a>
                                                    </p>
                                                    @if (!empty($chuyenNhanCongViecDonVi->han_xu_ly))
                                                        <p>
                                                            - <b>Hạn xử
                                                                lý:
                                                                {{ date('d/m/Y', strtotime($chuyenNhanCongViecDonVi->han_xu_ly)) }}
                                                            </b>
                                                        </p>
                                                    @endif
                                                    @if (isset($chuyenNhanCongViecDonVi->congViecDonVi->congViecDonViFile))
                                                        @foreach($chuyenNhanCongViecDonVi->congViecDonVi->congViecDonViFile as $key => $file)
                                                            <a href="{{ $file->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                ]</a>
                                                            @if (count($chuyenNhanCongViecDonVi->congViecDonVi->congViecDonViFile)-1 != $key)
                                                                &nbsp;|&nbsp;
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $chuyenNhanCongViecDonVi->noi_dung }}
                                                </td>
                                                <td>
                                                    @if (!empty($chuyenNhanCongViecDonVi->getTrinhTuXuLy()))
                                                        @foreach($chuyenNhanCongViecDonVi->getTrinhTuXuLy() as $key => $trinhTuXuLy)
                                                            <p>
                                                                {{ $key+1 }}
                                                                . {{ $trinhTuXuLy->canBoNhan->ho_ten ?? null }}
                                                            </p>
                                                            <hr class="border-dashed {{  count($chuyenNhanCongViecDonVi->getTrinhTuXuLy())-1 == $key ? 'hide' : 'show' }}">
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <p>{{ $chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->noi_dung ?? null }}</p>

                                                    @if (isset($chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile))
                                                        @foreach($chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile as $key => $file)
                                                            <a href="{{ $file->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                ]</a>
                                                            @if (count($chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile)-1 != $key)
                                                                &nbsp;|&nbsp;
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="5" class="text-center">Không tìm
                                                thấy dữ liệu.
                                            </td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row col-md-12 mb-1">
                                        <div class="float-left">
                                            Tổng số công việc: <b>{{ $danhSachChuyenNhanCongViecDonVi->total() }}</b>
                                        </div>
                                    </div>
                                    <div>
                                        {{ $danhSachChuyenNhanCongViecDonVi->appends(['date'  => Request::get('date'), 'type' => Request::get('type')])->render() }}
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
