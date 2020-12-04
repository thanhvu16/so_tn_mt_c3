@extends('admin::layouts.master')
@section('page_title', 'Văn bản chờ xử lý')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Văn bản chờ xử lý</h4>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('van-ban-den-don-vi.store') }}" method="post"
                                      id="form-tham-muu">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_id" value="">
                                    <input type="hidden" name="van_ban_tra_lai" value="">
                                    <input type="hidden" name="type" value="update">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        @include('dieuhanhvanbanden::van-ban-den.fom_tra_lai', ['active' => $trinhTuNhanVanBan])
                        @include('dieuhanhvanbanden::gia-han.modal_gia_han')
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="25%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="20%" class="text-center">Tóm tắt VB</th>
                                <th width="8%" class="text-center">Trình tự xử lý</th>
                            </tr>
                            </thead>
                            <tbody class="text-justify">
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        <p>
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?xuly=true') }}">{{ $vanBanDen->trich_yeu }}</a>
                                            <br>
                                            @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                <i>
                                                    (Vào hồi {{ $vanBanDen->gio_hop }}
                                                    ngày {{ date('d/m/Y', strtotime($vanBanDen->ngay_hop)) }}
                                                    , tại {{ $vanBanDen->dia_diem }})
                                                </i>
                                            @endif
                                        </p>
                                        @include('dieuhanhvanbanden::van-ban-den.info')
                                    </td>
                                    <td>
                                        <p>
                                            {{ $vanBanDen->tom_tat ?? $vanBanDen->trich_yeu }}
                                        </p>
                                        @if ($vanBanDen->vanBanTraLai)
                                            <p class="color-red"><b>Lý
                                                    do trả
                                                    lại: </b><i>{{ $vanBanDen->vanBanTraLai->noi_dung ?? '' }}</i>
                                            </p>
                                            <p>
                                                (Cán bộ trả
                                                lại: {{ $vanBanDen->vanBanTraLai->canBoChuyen->ho_ten  ?? '' }}
                                                - {{ $vanBanDen->vanBanTraLai->canBoChuyen->donVi->ten_don_vi ?? null }}
                                                - {{ date('d/m/Y h:i:s', strtotime($vanBanDen->vanBanTraLai->created_at)) }}
                                                )</p>
                                        @endif
                                        @if (!empty($vanBanDen->giaHanVanBanTraLai()))
                                            <p>
                                                <i><b>Trả lại gia hạn:</b> {{ $vanBanDen->giaHanVanBanTraLai()->noi_dung }}</i>
                                            </p>
                                            <p>
                                                ({{ $vanBanDen->giaHanVanBanTraLai()->canBoChuyen->ho_ten .' - '. date('d/m/Y', strtotime($vanBanDen->giaHanVanBanTraLai()->created_at))}})
                                            </p>
                                        @endif

                                        <p>
                                            <a class="tra-lai-van-ban" data-toggle="modal" data-target="#modal-tra-lai"
                                               data-id="{{ $vanBanDen->id }}">
                                                <span><i class="fa fa-reply"></i>Trả lại VB</span>
                                            </a>
                                        </p>
                                        @if (empty($vanBanDen->giaHanVanBanLanhDaoDuyet(1)) || !empty($vanBanDen->giaHanVanBanTraLai()))
                                            @if (empty($vanBanDen->giaHanVanBanLanhDaoDuyet(3)))
                                            <div class="form-group mt-1">
                                                <button type="button"
                                                        class="btn btn-danger btn-gia-han waves-effect btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modal-de_xuat_gia_han"
                                                        data-id="{{ $vanBanDen->id }}"
                                                        data-han="{{ isset($vanBanDen) && !empty($vanBanDen->han_xu_ly) ?  $vanBanDen->han_xu_ly : null }}"
                                                        data-whatever="@mdo">
                                                    <i class="fa fa-clock-o"></i> Gia hạn
                                                </button>
                                            </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($vanBanDen->xuLyVanBanDen)
                                            @foreach($vanBanDen->xuLyVanBanDen as $key => $chuyenVienXuLy)
                                                <p>
                                                    {{ $key+1 }}. {{$chuyenVienXuLy->canBoNhan->ho_ten ?? null }}
                                                </p>
                                                <hr class="border-dashed {{ count($vanBanDen->donViChuTri) == 0 && count($vanBanDen->xuLyVanBanDen  )-1 == $key ? 'hide' : 'show' }}">
                                            @endforeach
                                        @endif
                                        @if($vanBanDen->donViChuTri)
                                            @foreach($vanBanDen->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                <p>
                                                    {{ count($vanBanDen->xuLyVanBanDen) > 0 ? count($vanBanDen->xuLyVanBanDen)+($key+1) : $key+1 }}
                                                    . {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                </p>
                                                <hr class="border-dashed {{ count($vanBanDen->donViChuTri)-1 == $key ? 'hide' : 'show' }}">
                                            @endforeach
                                        @endif

                                        @if (!empty($vanBanDen->giaHanVanBanLanhDaoDuyet(1)))
                                            <p>
                                                <i>(<b>Gia hạn thêm chờ
                                                        duyệt:</b> {{ date('d/m/Y', strtotime($vanBanDen->giaHanVanBanLanhDaoDuyet(1)->thoi_han_de_xuat)) }}
                                                    )</i>
                                            </p>
                                        @endif

                                        @if (!empty($vanBanDen->giaHanVanBanLanhDaoDuyet(3)))
                                            <p>
                                                <i>(<b>Gia hạn đã
                                                        duyệt:</b> {{ date('d/m/Y', strtotime($vanBanDen->giaHanVanBanLanhDaoDuyet(3)->thoi_han_de_xuat)) }}
                                                    )</i>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <td colspan="4" class="text-center">Không tìm
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
@section('script')
    <script type="text/javascript">
        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            let traLai = $(this).data('tra-lai');
            $('#modal-tra-lai').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-tra-lai').find('input[name="type"]').val(traLai);
        });

        $('.btn-gia-han').on('click', function () {
            let id = $(this).data('id');
            let hanCu = $(this).data('han');
            let hanFormat = moment(hanCu, "YYYY-MM-DD").format("DD/MM/YYYY");

            $('#modal-de_xuat_gia_han').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-de_xuat_gia_han').find('input[name="thoi_han_cu"]').val(hanFormat);
        });

    </script>
@endsection
