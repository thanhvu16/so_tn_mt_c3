@extends('admin::layouts.master')
@section('page_title', 'Lịch công tác')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Lịch họp</h4>
                            </div>
                        </div>
                    </div>
                    @include('lichcongtac::them_lich._form')
                    <div class="box-body">
                        <div class="row">
                            @can(\App\Common\AllPermission::themLichCongTac())
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#them-moi-lich-hop"><i class="fa fa-plus"></i> Thêm lịch họp
                                    </button>
                                </div>
                            @endcan
                            <form action="{{ route('lich-cong-tac.index') }}" method="get" class="form-row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="lanh_dao_id" class="form-control select2"
                                                onchange="this.form.submit()">
                                            <option value="">-- Lãnh đạo --</option>
                                            @forelse($danhSachLanhDao as $lanhdao)
                                                <option
                                                    value="{{ $lanhdao->id }}" {{ !empty(Request::get('lanh_dao_id')) && Request::get('lanh_dao_id') == $lanhdao->id ? 'selected' :  null }}>{{ $lanhdao->ho_ten }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="form-group">
                                        <a href="{{ route('lich-cong-tac.index','tuan='. date('W') ) }}"
                                           class="btn btn-primary" data-original-title="" title=""><i
                                                class="fa fa-calendar"></i> Tuần hiện tại</a>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center hidden-xs">
                                    <div class="form-group">
                                        <a href="{{ route('lich-cong-tac.index','tuan='.$tuanTruoc. '&year='.Request::get('year')) }}"
                                           class="btn btn-primary"
                                           data-original-title="" title=""><i
                                                class="fa fa-backward"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="tuan" class="form-control select2"
                                                onchange="this.form.submit()">
                                            @for($i = 1; $i <= $totalWeekOfYear; $i++)
                                                <option value="{{ $i < 10 ? '0'.$i : $i }}" {{ $i == $week ? 'selected' : '' }}>
                                                    Tuần {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <div class="form-group">
                                        <a href="{{ route('lich-cong-tac.index','tuan='.$tuanSau .'&year='.Request::get('year')) }}"
                                           class="btn btn-primary" data-original-title="" title=""><i
                                                class="fa fa-forward"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select name="year" class="form-control form-inline select2" onchange="this.form.submit()">
                                        @for($i = 2020; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                                Năm {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered dataTable data-row table-lich-cong-tac">
                                <thead>
                                <tr class="background:#ccc;">
                                    <th width="9%" class="text-center" style="vertical-align: middle;">Thời
                                        gian
                                    </th>
                                    <th width="25%" class="text-center" style="vertical-align: middle;">Nội
                                        dung giấy mời
                                    </th>
                                    <th width="15%" class="text-center" style="vertical-align: middle;">Lãnh
                                        đạo dự họp
                                    </th>
                                    <th width="25%" class="text-center" style="vertical-align: middle;">Chỉ
                                        đạo trước cuộc họp
                                    </th>
                                    <th width="15%" class="text-center" style="vertical-align: middle;">Báo
                                        cáo phục vụ họp
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">Soạn báo cáo
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ngayTuan as $ngayTrongTuan)
                                    <tr>
                                        <td colspan="7" class="text-center bg-table-gray">
                                            <b>{{ $ngayTrongTuan[0] }}
                                                - {{ $ngayTrongTuan[1] }}</b></td>
                                    </tr>
                                    @forelse($danhSachLichCongTac as $lichCongTac)
                                        @if ( $ngayTrongTuan[1] == date('d/m/Y', strtotime($lichCongTac->ngay)))
                                            @if ($lichCongTac->type == 1)
                                                <tr style="background-color: #fff; max-height: 300px; color:black">
                                                    <td>
                                                        <b>{{ $lichCongTac->gio < '12:00' ? 'Buổi sáng' : 'Buổi chiều' }}
                                                            - {{ $lichCongTac->gio }}</b></td>
                                                    <td style="vertical-align: middle;">
                                                        <p class="text-bold">GM đi số:
                                                            <b class="color-red">{{ $lichCongTac->vanBanDi->so_di ?? null }}</b>
                                                        </p>
                                                        <p><b>Nội
                                                                dung: </b>{{ $lichCongTac->noi_dung ??  null  }}
                                                        </p>
                                                        <p></p>
                                                        <p class="text-bold">Địa điểm:
                                                            <b>{{ $lichCongTac->dia_diem ?? null }}</b>
                                                        </p>
                                                        <p>
                                                            @if ($lichCongTac->trang_thai_lich == 2)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch hoãn)</i>
                                                            @endif
                                                            @if ($lichCongTac->trang_thai_lich == 3)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch điều chỉnh)</i>
                                                            @endif
                                                            @if ($lichCongTac->trang_thai_lich == 4)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch phát sinh)</i>
                                                            @endif
                                                        </p>
                                                        @can(\App\Common\AllPermission::suaLichCongTac())
                                                            <a class="btn-edit-calendar" title="Sửa lịch"
                                                               data-id="{{ $lichCongTac->id }}">
                                                                <span> <i class="fa fa-edit"></i> Sửa lịch</span>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                    <td>{{ $lichCongTac->lanhDao->ChucVu->ten_chuc_vu ?? '' }} {{  $lichCongTac->lanhDao->ho_ten ?? null }}</td>
                                                    <td>
                                                        Đơn vị soạn
                                                        thảo: {{ $lichCongTac->vanBanDi->dvSoanThao->ten_don_vi ?? null }}
                                                    </td>
                                                    <td>
                                                        <p>
                                                            @if (isset($lichCongTac->vanBanDi->vanBanDiFile))
                                                                @foreach($lichCongTac->vanBanDi->vanBanDiFile as $key => $file)
                                                                    <a href="{{ $file->getUrlFile() }}"
                                                                       target="popup"
                                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                        ]</a>
                                                                    @if (count($lichCongTac->vanBanDi->vanBanDiFile)-1 != $key)
                                                                        &nbsp;|&nbsp;
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </p>
                                                        <hr style="border:1px dashed red">
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('soan_bao_cao.create', 'lich_cong_tac_id='.$lichCongTac->id) }}"
                                                           target="_blank"
                                                           class="btn btn-primary btn-sm color-white">Soạn
                                                            báo cáo kết quả
                                                            họp</a>
                                                    </td>
                                                </tr>
                                            @elseif($lichCongTac->type == 2)
                                                <tr style="background-color: #fff; max-height: 300px; color:black">
                                                    <td>
                                                        <b>{{ $lichCongTac->gio < '12:00' ? 'Buổi sáng' : 'Buổi chiều' }}
                                                            - {{ $lichCongTac->gio }}</b>
                                                    </td>
                                                    <td style="vertical-align: middle;">
                                                        <p><b>Nội dung:</b> {{ $lichCongTac->noi_dung ??  null  }}
                                                        <p></p>
                                                        <p class="text-bold">Địa điểm:
                                                            <b>{{ $lichCongTac->dia_diem ?? null }}</b>
                                                        </p>
                                                        <p>
                                                            @if ($lichCongTac->trang_thai_lich == 2)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch hoãn)</i>
                                                            @endif
                                                            @if ($lichCongTac->trang_thai_lich == 3)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch điều chỉnh)</i>
                                                            @endif
                                                            @if ($lichCongTac->trang_thai_lich == 4)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch phát sinh)</i>
                                                            @endif
                                                        </p>
                                                        @can(\App\Common\AllPermission::suaLichCongTac())
                                                            <a class="btn-edit-calendar" title="Sửa lịch"
                                                               data-id="{{ $lichCongTac->id }}">
                                                                <span> <i class="fa fa-edit"></i> Sửa lịch</span>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                    <td>{{ $lichCongTac->lanhDao->ChucVu->ten_chuc_vu ?? '' }} {{  $lichCongTac->lanhDao->ho_ten ?? null }}
                                                        <br> <i>({{ $lichCongTac->lanhDao->donVi->ten_don_vi ?? null }})</i>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('soan_bao_cao.create', 'lich_cong_tac_id='.$lichCongTac->id) }}"
                                                           target="_blank"
                                                           class="btn btn-primary btn-sm color-white">Soạn
                                                            báo cáo kết quả
                                                            họp</a>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr style="background-color: #fff; max-height: 300px; color:black">
                                                    <td>
                                                        <b>{{ $lichCongTac->gio < '12:00' ? 'Buổi sáng' : 'Buổi chiều' }}
                                                            - {{ $lichCongTac->gio }}</b></td>
                                                    <td style="vertical-align: middle;">
                                                        <p>
                                                            <b>Cơ quan ban
                                                                hành:</b> {{ $lichCongTac->vanBanDen->co_quan_ban_hanh ?? null }}
                                                        </p>
                                                        <p class="text-bold">GM đến số:
                                                            <b class="color-red">{{ !empty($lichCongTac->vanBanDen->hasChild()) ? $lichCongTac->vanBanDen->hasChild()->so_den : $lichCongTac->vanBanDen->so_den }}</b>
                                                        </p>
                                                        <p><b>Nội
                                                                dung: </b><a href="{{route('chitiethop',$lichCongTac->id)}}">{{ $lichCongTac->noi_dung ??  null  }}</a>
                                                        </p>
                                                        <p></p>
                                                        <p class="text-bold">Địa điểm:
                                                            <b>{{ $lichCongTac->dia_diem ?? null }}</b>
                                                        </p>
                                                        <p>
                                                            @if (isset($lichCongTac->vanBanDen->vanBanDenFile))
                                                                @foreach($lichCongTac->vanBanDen->vanBanDenFile as $key => $file)
                                                                    <a href="{{ $file->getUrlFile() }}"
                                                                       target="popup"
                                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                        ]</a>
                                                                    @if (count($lichCongTac->vanBanDen->vanBanDenFile)-1 != $key)
                                                                        &nbsp;|&nbsp;
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </p>
                                                        <p>
                                                            @if ($lichCongTac->trang_thai_lich == 2)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch hoãn)</i>
                                                            @endif
                                                            @if ($lichCongTac->trang_thai_lich == 3)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                        <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch điều chỉnh)</i>
                                                            @endif
                                                            @if ($lichCongTac->trang_thai_lich == 4)
                                                                @if (!empty($lichCongTac->ghi_chu))
                                                                    <b>Ghi chú:</b> <i>{{ $lichCongTac->ghi_chu }}</i><br>
                                                                @endif
                                                                <i class="text-red">(Lịch phát sinh)</i>
                                                            @endif
                                                        </p>
                                                        @can(\App\Common\AllPermission::suaLichCongTac())
                                                            <a class="btn-edit-calendar" title="Sửa lịch"
                                                               data-id="{{ $lichCongTac->id }}">
                                                                <span> <i class="fa fa-edit"></i> Sửa lịch</span>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                    <td>{{ $lichCongTac->lanhDao->ChucVu->ten_chuc_vu ?? '' }} {{  $lichCongTac->lanhDao->ho_ten ?? null }}</td>
                                                    <td>
                                                        @if($lichCongTac->CanBoChiDao)
                                                            @foreach($lichCongTac->CanBoChiDao as $key => $chuyenVienXuLy)
                                                                <p>
                                                                    - {{$chuyenVienXuLy->noi_dung ?? null }}
                                                                </p>
                                                            @endforeach
                                                        @endif

                                                        @if($lichCongTac->truyenNhanVanBanDonVi)
                                                            @foreach($lichCongTac->truyenNhanVanBanDonVi as $key => $chuyenNhanVanBanDonVi)
                                                                <p>
                                                                    - {{ $chuyenNhanVanBanDonVi->noi_dung ?? null }}
                                                                </p>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <p>
                                                            <a href="{{ !empty($lichCongTac->vanBanDen->id) ? route('van_ban_den_chi_tiet.show', $lichCongTac->vanBanDen->id) : '' }}">{{ !empty($lichCongTac->vanBanDen) ? $lichCongTac->vanBanDen->giaiQuyetVanBanHoanThanh()->noi_dung ?? null : null }}</a>
                                                        </p>

                                                        @if (isset($lichCongTac->vanBanDen) && !empty($lichCongTac->vanBanDen->giaiQuyetVanBanHoanThanh()))
                                                            @foreach($lichCongTac->vanBanDen->giaiQuyetVanBanHoanThanh()->giaiQuyetVanBanFile as $key => $file)
                                                                <a href="{{ $file->getUrlFile() }}"
                                                                   target="popup"
                                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                    ]</a>
                                                                @if (count($lichCongTac->vanBanDen->giaiQuyetVanBanHoanThanh()->giaiQuyetVanBanFile)-1 != $key)
                                                                    &nbsp;|&nbsp;
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!empty($lichCongTac->congViecDonVi) && $lichCongTac->congViecDonVi->giaiQuyetCongViecDonVi)
                                                            <b>kết quả:</b>
                                                            <p>
                                                                <a href="{{ route('cong-viec-hoan-thanh.show', $lichCongTac->congViecDonVi->id) }}">{{ $lichCongTac->congViecDonVi->giaiQuyetCongViecDonVi->noi_dung }}</a>
                                                            </p>
                                                            @if (isset($lichCongTac->congViecDonVi->giaiQuyetCongViecDonVi->giaiQuyetCongViecDonViFile))
                                                                @foreach($lichCongTac->congViecDonVi->giaiQuyetCongViecDonVi->giaiQuyetCongViecDonViFile as $key => $file)
                                                                    <a href="{{ $file->getUrlFile() }}"
                                                                       target="popup"
                                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                        ]</a>
                                                                    @if (count($lichCongTac->congViecDonVi->giaiQuyetCongViecDonVi->giaiQuyetCongViecDonViFile)-1 != $key)
                                                                        &nbsp;|&nbsp;
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            <a href="{{ route('soan_bao_cao.create', 'lich_cong_tac_id='.$lichCongTac->id) }}"
                                                               target="_blank"
                                                               class="btn btn-primary btn-sm color-white">Soạn
                                                                báo cáo kết quả
                                                                họp</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @empty
                                    @endforelse
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="modal fade modal-edit-calendar" role="dialog" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content modal-data-push">

                                </div>
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
        $('.btn-edit-calendar').on('click', function () {
            let id = $(this).data('id');
            $.ajax({
                url: APP_URL + '/lich-cong-tac/edit/' + id,
                type: 'get'
            })
                .done(function (response) {
                    $('.modal-data-push').html(response.html);
                    $('.modal-edit-calendar').modal();
                    $('.time-picker-24h').timepicker({
                        showMeridian: !1,
                        showInputs: false
                    })
                })
                .fail(function (error) {
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });
        });
    </script>
@endsection
