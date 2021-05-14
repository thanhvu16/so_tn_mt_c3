@extends('admin::layouts.master')
@section('page_title', 'Công việc hoàn thành chờ duyệt')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Công việc hoàn thành chờ duyệt</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered dataTable table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="30%" class="text-center">Nội dung - Thông tin</th>
                                <th width="20%" class="text-center">Trình tự xử lý</th>
                                <th width="20%" class="text-center">Kết quả</th>
                                <th width="25%" class="text-center">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($giaiQuyetCongViecDonVi as $key => $giaiQuyetCongViec)
                                <tr class="duyet-vb">
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>
                                        <p>
                                            <a href="{{ route('cong-viec-don-vi.show', $giaiQuyetCongViec->chuyen_nhan_cong_viec_don_vi_id) }}">{{ $giaiQuyetCongViec->congViecDonVi->noi_dung_cuoc_hop }}</a>
                                        </p>
                                        @if (!empty($giaiQuyetCongViec->chuyenNhanCongViecDonVi->han_xu_ly))
                                            <p>
                                                - <b>Hạn xử
                                                    lý:
                                                    {{ date('d/m/Y', strtotime($giaiQuyetCongViec->chuyenNhanCongViecDonVi->han_xu_ly)) }}
                                                </b>
                                            </p>
                                        @endif
                                        @if (isset($giaiQuyetCongViec->congViecDonVi->congViecDonViFile))
                                            @foreach($giaiQuyetCongViec->congViecDonVi->congViecDonViFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                    ]</a>
                                                @if (count($giaiQuyetCongViec->congViecDonVi->congViecDonViFile)-1 != $key)
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($giaiQuyetCongViec->chuyenNhanCongViecDonVi ))
                                            @foreach($giaiQuyetCongViec->chuyenNhanCongViecDonVi->getTrinhTuXuLy() as $key => $trinhTuXuLy)
                                                <p>
                                                    {{ $key+1 }}
                                                    . {{ $trinhTuXuLy->canBoNhan->ho_ten ?? null }}
                                                </p>
                                                <hr class="border-dashed {{  count($giaiQuyetCongViec->chuyenNhanCongViecDonVi->getTrinhTuXuLy())-1 == $key ? 'hide' : 'show' }}">
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <p>{{ $giaiQuyetCongViec->chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->noi_dung ?? null }}</p>
{{--                                        {{$giaiQuyetCongViec->chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh}}--}}

                                        @if (!empty($giaiQuyetCongViec->chuyenNhanCongViecDonVi && isset($giaiQuyetCongViec->chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile)))
                                            @foreach($giaiQuyetCongViec->chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                @if (count($giaiQuyetCongViec->chuyenNhanCongViecDonVi->giaiQuyetCongViecHoanThanh()->giaiQuyetCongViecDonViFile)-1 != $key)
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <div class="col-md-12 form-group">
                                                        <textarea class="form-control noi-dung" name="noi_dung"
                                                                  rows="3"
                                                                  required placeholder="nhập nội dung ...."></textarea><br>
                                            <button
                                                class="btn waves-effect btn-primary btn-sm btn-choose-status"
                                                data-id="{{ $giaiQuyetCongViec->id }}" data-type="1"> <i class="fa fa-check"></i> Duyệt
                                            </button>
                                            <button
                                                class="btn waves-effect btn-danger btn-sm btn-choose-status"
                                                data-id="{{ $giaiQuyetCongViec->id }}" data-type="2"><i class="fa fa-refresh"></i> Trả lại
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
                        <div class="row col-md-12 mb-1">
                            <div class="float-left">
                                Tổng số công việc: <b>{{ $giaiQuyetCongViecDonVi->total() }}</b>
                            </div>
                        </div>
                        <div>
                            {{ $giaiQuyetCongViecDonVi->appends(['date'  => Request::get('date'), 'type' => Request::get('type')])->render() }}
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
            let text = $(this).text();
            let message = `Xác nhận ${text}`;
            let noiDung = $(this).parents('.duyet-vb').find('.noi-dung').val();
            if (confirm(message)) {
                let status = $(this).data('type');
                let id = $(this).data('id');

                console.log(status, noiDung);

                $.ajax({
                    url: APP_URL + '/duyet-cong-viec',
                    type: 'POST',
                    data: {
                        id: id,
                        status: status,
                        noiDung: noiDung
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
