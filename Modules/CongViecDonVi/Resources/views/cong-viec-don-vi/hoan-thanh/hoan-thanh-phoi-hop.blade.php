@extends('admin::layouts.master')
@section('page_title', 'Công việc hoàn thành')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Công việc hoàn thành</h3>
                    </div>
                    <div class="box-body ">
                        <table class="table table-striped table-bordered dataTable table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="30%" class="text-center">Nội dung - Thông tin</th>
                                <th width="20%" class="text-center">Nội dung đầu việc đơn vị</th>
                                <th width="20%" class="text-center">Trình tự xử lý</th>
                                <th width="20%" class="text-center">Kết quả</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachChuyenNhanCongViecDonVi as  $chuyenNhanCongViecDonVi)
                                <tr class="duyet-vb">
                                    <td class="text-center">{{ $order+1 }}</td>
                                    <td>
                                        <p>
                                            <a href="{{ route('cong-viec-don-vi.show', $chuyenNhanCongViecDonVi->id.'?ph='.Auth::user()->donvi_id) }}">{{ $chuyenNhanCongViecDonVi->congViecDonVi->noi_dung_cuoc_hop }}</a>
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
                                        <p>{{ $chuyenNhanCongViecDonVi->donViPhoiHopDaGiaiQuyet()->noi_dung ?? null }}</p>

                                        @if (isset($chuyenNhanCongViecDonVi->donViPhoiHopDaGiaiQuyet()->phoiHopGiaiQuyetFile))
                                            @foreach($chuyenNhanCongViecDonVi->donViPhoiHopDaGiaiQuyet()->phoiHopGiaiQuyetFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                    ]</a>
                                                @if (count($chuyenNhanCongViecDonVi->donViPhoiHopDaGiaiQuyet()->phoiHopGiaiQuyetFile)-1 != $key)
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
    </section>
@endsection
