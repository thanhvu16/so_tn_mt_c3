@extends('administrator::layouts.master')
@if (isset($type))
    @section('page_title', 'Công việc đã phối hợp xử lý')
@else
    @section('page_title', 'Công việc phối hợp chờ xử lý')
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
                                        <h4 class="header-title pt-2">{{ isset($type) ? 'Công việc đã phối hợp xử lý' : 'Công việc chờ xử lý' }}</h4>
                                    </div>
                                </div>
                                <!--datatable-->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr role="row" class="text-center">
                                            <th width="2%">STT</th>
                                            <th width="28%">Nội dung công việc</th>
                                            <th width="27%">Nội dung đầu việc đơn vị</th>
                                            <th width="16%">Trình tự xử lý</th>
                                            @if (isset($type))
                                            <th width="20%">Kết quả phối hợp</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($chuyenNhanCongViecDonVi as $congViecDonVi)
                                            <tr class="tr-tham-muu">
                                                <td class="text-center">{{ $order++ }}</td>
                                                <td>
                                                    <p>
                                                        <a href="{{ route('cong-viec-don-vi.show', $congViecDonVi->id.'?type=cv_phoi_hop') }}">{{ $congViecDonVi->congViecDonVi->noi_dung_cuoc_hop }}</a>
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
                                                        <i>( {{ Auth::user()->ho_ten }} phối hợp xử lý)</i>
                                                    @endif
                                                </td>
                                                @if (isset($type))
                                                <td>
                                                    <p>{{ $congViecDonVi->getCaNhanPhoiHop()->noi_dung ?? '' }}</p>
                                                    @if (isset($congViecDonVi->getCaNhanPhoiHop()->phoiHopGiaiQuyetFile))
                                                        @foreach($congViecDonVi->getCaNhanPhoiHop()->phoiHopGiaiQuyetFile as $key => $file)
                                                            <a href="{{ $file->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                            @if (count($congViecDonVi->getCaNhanPhoiHop()->phoiHopGiaiQuyetFile)-1 != $key)
                                                                &nbsp;|&nbsp;
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                @endif

                                            </tr>
                                        @empty
                                            <td colspan="{{ isset($type) ? 5 : 4 }}"
                                                class="text-center">Không tìm
                                                thấy dữ liệu.
                                            </td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-md-6 col-12">
                                            Tổng số công việc: <b>{{ $chuyenNhanCongViecDonVi->total() }}</b>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            @if (Auth::user()->vai_tro != CHUYEN_VIEN)
                                                <button type="button"
                                                        class="btn btn-sm btn-primary btn-submit waves-effect waves-light pull-right btn-duyet-all disabled pull-right btn-sm mb-2"
                                                        form="form-tham-muu"
                                                        title=""><i class="fa fa-check"></i> Duyệt
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        {{ $chuyenNhanCongViecDonVi->appends(['ngay_tao'  => Request::get('ngay_tao'), 'type' => Request::get('type')])->render() }}
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
@section('script')
    <script type="text/javascript">
        $('.btn-gia-han').on('click', function () {
            let id = $(this).data('id');
            let hanCu = $(this).data('han');
            $('#modal-de_xuat_gia_han').find('input[name="cong_viec_don_vi_id"]').val(id);
            $('#modal-de_xuat_gia_han').find('input[name="han_cu"]').val(hanCu);
        });

        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            $('#modal-tra-lai').find('input[name="cong_viec_don_vi_id"]').val(id);
        });
    </script>
@endsection

