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
                                <th width="30%">Trích yếu - Thông tin</th>
                                <th width="20%">Trình tự xử lý</th>
                                <th width="20%">Kết quả</th>
                                <th width="25%">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody class="text-justify">
                            @forelse($danhSachVanBanDen as $vanBanDen)
                                <tr class="duyet-vb">
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
                                    </td>
                                    <td>
                                        <p>{{ $vanBanDen->giaiQuyetVanBanHoanThanhChoDuyet()->noi_dung ?? null }}</p>

                                        @if (isset($vanBanDen->giaiQuyetVanBanHoanThanhChoDuyet()->giaiQuyetVanBanFile))
                                            @foreach($vanBanDen->giaiQuyetVanBanHoanThanhChoDuyet()->giaiQuyetVanBanFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                @if (count($vanBanDen->giaiQuyetVanBanHoanThanhChoDuyet()->giaiQuyetVanBanFile)-1 != $key)
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <input type="hidden" name="giai_quyet_van_ban_id" value="{{  $vanBanDen->giaiQuyetVanBanHoanThanhChoDuyet()->id }}">
                                        <div class="col-md-12 form-group">
                                                        <textarea class="form-control noi-dung" name="noi_dung"
                                                                  rows="3"
                                                                  required placeholder="nhập nội dung ...."></textarea><br>
                                            <button
                                                class="btn btn-sm waves-effect btn-primary btn-choose-status"
                                                data-id="{{ $vanBanDen->id }}" data-type="1"><i class="fa fa-check"></i> Duyệt
                                            </button>
                                            <button
                                                class="btn btn-sm waves-effect btn-danger btn-choose-status"
                                                data-id="{{ $vanBanDen->id }}" data-type="2"><i class="fa fa-undo"></i> Trả lại
                                            </button>
                                        </div>
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
@section('script')
    <script type="text/javascript">
        $('.btn-choose-status').on('click', function () {
            let text = $(this).text().trim();
            let message = `Xác nhận ${text}`;
            let noiDung = $(this).parents('.duyet-vb').find('.noi-dung').val();
            let giaiQuyetVanBan = $(this).parents('.duyet-vb').find('input[name="giai_quyet_van_ban_id"]').val();
            if (confirm(message)) {
                let status = $(this).data('type');
                let id = $(this).data('id');

                console.log(status, noiDung);

                $.ajax({
                    url: APP_URL + '/duyet-van-ban',
                    type: 'POST',
                    data: {
                        id: id,
                        status: status,
                        noiDung: noiDung,
                        giaiQuyet: giaiQuyetVanBan
                    },
                    success: function (data) {
                        if (data.success) {
                            toastr['success'](data.message, 'Thông báo hệ thống');
                            location.reload();
                        } else {
                            toastr['error'](data.message, 'Thông báo hệ thống');
                        }

                    }
                })
            }
        })
    </script>
@endsection
