@extends('admin::layouts.master')
@section('page_title', 'Công việc đã xử lý')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Công việc đã xử lý</h3>
                    </div>
                    <div class="box-body">
                        @include('congviecdonvi::cong-viec-don-vi.form_tra_lai')
                        @include('congviecdonvi::cong-viec-don-vi.gia-han.modal_gia_han')
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dataTable table-hover data-row">
                                <thead>
                                <tr role="row" class="text-center">
                                    <th width="4%" class="text-center">STT</th>
                                    <th class="text-center">Nội dung công việc</th>
                                    <th width="20%" class="text-center">Nội dung đầu việc đơn vị</th>
                                    <th width="20%" class="text-center" class="text-center">Trình tự xử lý</th>
                                    <th width="20%" class="text-center">Kết quả</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($chuyenNhanCongViecDonVi as $congViecDonVi)
                                    <tr class="tr-tham-muu">
                                        <td class="text-center">{{ $order++ }}</td>
                                        <td>
                                            <p>
                                                <a href="{{ route('cong-viec-don-vi.show', $congViecDonVi->id) }}">{{ $congViecDonVi->congViecDonVi->noi_dung_cuoc_hop }}</a>
                                            </p>
                                            @if (!empty($congViecDonVi->han_xu_ly))
                                                <p>
                                                    - <b>Hạn xử
                                                        lý:
                                                        {{ date('d/m/Y', strtotime($congViecDonVi->han_xu_ly)) }}
                                                    </b>
                                                </p>
                                            @endif
                                            @if (isset($congViecDonVi->congViecDonVi->congViecDonViFile))
                                                @foreach($congViecDonVi->congViecDonVi->congViecDonViFile as $key => $file)
                                                    <a href="{{ $file->getUrlFile() }}"
                                                       target="popup"
                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                        ]</a>
                                                    @if (count($congViecDonVi->congViecDonVi->congViecDonViFile)-1 != $key)
                                                        &nbsp;|&nbsp;
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <p>
                                                {{ $congViecDonVi->noi_dung }}
                                            </p>
                                        </td>
                                        <td>
                                            @if (!empty($congViecDonVi->getTrinhTuXuLy()))
                                                @foreach($congViecDonVi->getTrinhTuXuLy() as $key => $trinhTuXuLy)
                                                    <p>
                                                        {{ $key+1 }}
                                                        . {{ $trinhTuXuLy->canBoNhan->ho_ten ?? null }}
                                                    </p>
                                                    <hr class="border-dashed {{  count($congViecDonVi->getTrinhTuXuLy())-1 == $key ? 'hide' : 'show' }}">
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <p>{{ $congViecDonVi->giaiQuyetCongViecHoanThanh()->noi_dung ?? null }}</p>

                                            @if (isset($congViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile))
                                                @foreach($congViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile as $key => $file)
                                                    <a href="{{ $file->getUrlFile() }}"
                                                       target="popup"
                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                    @if (count($congViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile)-1 != $key)
                                                        &nbsp;|&nbsp;
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
                            @if (Auth::user()->hasRole(CHUYEN_VIEN) == false)
                                <div class="clearfix">
                                    <button type="button"
                                            class="btn btn-sm btn-primary btn-submit waves-effect waves-light pull-right btn-duyet-all disabled pull-right btn-sm mb-2"
                                            form="form-tham-muu"
                                            title=""><i class="fa fa-check"></i> Duyệt
                                    </button>
                                </div>
                            @endif
                            <div class="row mb-1">
                                <div class="col-md-6 col-12">
                                    Tổng số công việc: <b>{{ $chuyenNhanCongViecDonVi->total() }}</b>
                                </div>
                                <div class="col-md-6 col-12">
                                    {{ $chuyenNhanCongViecDonVi->appends(['ngay_tao'  => Request::get('ngay_tao'), 'type' => Request::get('type')])->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
