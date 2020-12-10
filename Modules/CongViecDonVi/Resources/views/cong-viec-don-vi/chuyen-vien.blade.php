@extends('administrator::layouts.master')
@section('page_title', 'Công việc đơn vị chờ xử lý')
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
                                        <h4 class="header-title pt-2">Công việc chờ xử lý</h4>
                                    </div>
                                </div>
                                <!--datatable-->
                                @include('congviecdonvi::cong-viec-don-vi.form_tra_lai')
                                @include('congviecdonvi::cong-viec-don-vi.gia-han.modal_gia_han')
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr role="row" class="text-center">
                                            <th width="2%">STT</th>
                                            <th width="28%">Nội dung công việc</th>
                                            <th width="27%">Nội dung đầu việc đơn vị</th>
                                            <th width="24%">Chỉ đạo</th>
                                            <th width="16%">Trình tự xử lý</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($chuyenNhanCongViecDonVi as $congViecDonVi)
                                            <tr class="tr-tham-muu">
                                                <td class="text-center">{{ $order++ }}</td>
                                                <td>
                                                    <p>
                                                        @if (!isset($typeDonViPhoiHop))
                                                            <a href="{{ route('cong-viec-don-vi.show', $congViecDonVi->id.'?xuly=true') }}">{{ $congViecDonVi->congViecDonVi->noi_dung_cuoc_hop }}</a>

                                                        @else
                                                            <a href="{{ route('cong-viec-don-vi.show', $congViecDonVi->id.'?type=phoi_hop') }}">{{ $congViecDonVi->congViecDonVi->noi_dung_cuoc_hop }}</a>
                                                        @endif
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
                                                    @if (!empty($congViecDonVi->giaiQuyetCongViecTraLai()))
                                                        <p class="color-red">
                                                            <i><b>Trả lại công việc:</b> {{ $congViecDonVi->giaiQuyetCongViecTraLai()->noi_dung_nhan_xet }}</i>
                                                            <br>
                                                            ({{ $congViecDonVi->giaiQuyetCongViecTraLai()->canBoDuyet->ho_ten .' - '. date('d/m/Y', strtotime($congViecDonVi->giaiQuyetCongViecTraLai()->updated_at))}})
                                                        </p>
                                                    @endif
{{--                                                    <p>--}}
{{--                                                        <a class="tra-lai-van-ban mb-2" data-toggle="modal"--}}
{{--                                                           data-target="#modal-tra-lai"--}}
{{--                                                           data-id="{{ $congViecDonVi->id }}">--}}
{{--                                                            <span><i class="fa fa-reply"></i>Trả lại CV</span>--}}
{{--                                                        </a>--}}
{{--                                                    </p>--}}

                                                    @if (!empty($congViecDonVi->giaHanVanBanTraLai(Auth::user()->id)))
                                                        <p class="color-red">
                                                            <i><b>Trả lại gia hạn:</b> {{ $congViecDonVi->giaHanVanBanTraLai(Auth::user()->id)->noi_dung }}</i>
                                                            <br>
                                                            ({{ $congViecDonVi->giaHanVanBanTraLai(Auth::user()->id)->canBoChuyen->ho_ten .' - '. date('d/m/Y', strtotime($congViecDonVi->giaHanVanBanTraLai(Auth::user()->id)->created_at))}})
                                                        </p>
                                                    @endif

                                                    @if (!isset($typeDonViPhoiHop))
                                                        @if (empty($congViecDonVi->giaHanVanBanChoDuyet(Auth::user()->id)) || !empty($congViecDonVi->giaHanVanBanTraLai(Auth::user()->id)))
                                                        @if (empty($congViecDonVi->giaHanVanBanDaDuyet(Auth::user()->id)))
                                                            <div class="form-group mt-1">
                                                                <button type="button"
                                                                        class="btn btn-danger btn-gia-han waves-effect btn-sm"
                                                                        data-toggle="modal"
                                                                        data-target="#modal-de_xuat_gia_han"
                                                                        data-id="{{ $congViecDonVi->cong_viec_don_vi_id }}"
                                                                        data-han="{{ !empty($congViecDonVi->han_xu_ly) ? $congViecDonVi->han_xu_ly :'' }}"
                                                                        data-whatever="@mdo">
                                                                    <i class="fa fa-clock-o"></i> Gia hạn
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <p>{{ !empty($congViecDonVi->checkCanBoNhan($danhSachChuyenVien->pluck('id')->toArray())) ? $congViecDonVi->checkCanBoNhan($danhSachChuyenVien->pluck('id')->toArray())->noi_dung_chuyen : '' }}</p>
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
                                                    @if (!empty($congViecDonVi->giaHanVanBanChoDuyet(Auth::user()->id)))
                                                        <p>
                                                            <i>(<b>Gia hạn thêm chờ
                                                                    duyệt:</b> {{ date('d/m/Y', strtotime($congViecDonVi->giaHanVanBanChoDuyet(Auth::user()->id)->thoi_han_de_xuat)) }}
                                                                )</i>
                                                        </p>
                                                    @endif
                                                    @if (!empty($congViecDonVi->giaHanVanBanDaDuyet(Auth::user()->id)))
                                                        <p>
                                                            <i>(<b>Gia hạn đã
                                                                    duyệt:</b> {{ date('d/m/Y', strtotime($congViecDonVi->giaHanVanBanDaDuyet(Auth::user()->id)->thoi_han_de_xuat)) }}
                                                                )</i>
                                                        </p>
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

