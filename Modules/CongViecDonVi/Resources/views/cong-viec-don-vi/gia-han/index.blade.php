@extends('administrator::layouts.master')
@section('page_title', 'Văn bản đến xin gia hạn')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-2">Công việc xin gia hạn</h4>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr role="row" class="text-center">
                                            <th>STT</th>
                                            <th>Nội dung công việc</th>
                                            <th>Lý do xin gia hạn</th>
                                            <th>Nội dung giải trình</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($giaHanCongViecDonVi as $giaHanCongViec)
                                            <tr class="duyet-gia-han">
                                                <td class="text-center">{{ $order++ }}</td>
                                                <td>
                                                    <p>
                                                        <a href="{{ route('cong-viec-don-vi.show', $giaHanCongViec->congViecDonVi->id) }}">{{ $giaHanCongViec->congViecDonVi->noi_dung_cuoc_hop }}</a>
                                                    </p>
                                                    @if ($giaHanCongViec->chuyenNhanCongViecDonVi)
                                                        <p>
                                                            - Nội dung đầu việc: {{ $giaHanCongViec->chuyenNhanCongViecDonVi->noi_dung ?? null }}
                                                        </p>
                                                    @endif
                                                    @if (!empty($giaHanCongViec->han_xu_ly))
                                                        <p>
                                                            - <b>Hạn xử
                                                                lý:
                                                                {{ date('d/m/Y', strtotime($giaHanCongViec->han_xu_ly)) }}
                                                            </b>
                                                        </p>
                                                    @endif
                                                    @if (isset($giaHanCongViec->congViecDonVi->congViecDonViFile))
                                                        @foreach($giaHanCongViec->congViecDonVi->congViecDonViFile as $key => $file)
                                                            <a href="{{ $file->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                ]</a>
                                                            @if (count($giaHanCongViec->congViecDonVi->congViecDonViFile)-1 != $key)
                                                                &nbsp;|&nbsp;
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <p><i><b>Lý do:</b> {{ $giaHanCongViec->noi_dung }}</i></p>
                                                    <p>Hạn đề xuất: <b>{{ !empty($giaHanCongViec->thoi_han_de_xuat) ? date('d/m/Y', strtotime($giaHanCongViec->thoi_han_de_xuat)) : null }}</b></p>
                                                    <p>
                                                        <i>({{ $giaHanCongViec->canBoChuyen->ho_ten ?? null }} - {{ date('d/m/Y H:i:s', strtotime($giaHanCongViec->created_at)) }})</i>
                                                    </p>
                                                </td>
                                                <td>
                                                    <div class="col-md-12 form-group">
                                                        <textarea class="form-control noi-dung" name="noi_dung"
                                                                  rows="3"
                                                                  required>{{ $giaHanCongViec->noi_dung }}</textarea>

                                                        <label for="thoiHan" class="col-form-label">Thời hạn đề
                                                            xuất</label>
                                                        <input type="date" name="thoi_han_de_xuat"
                                                               id="thoiHan"
                                                               placeholder="Chọn thời hạn công việc.."
                                                               value="{{ $giaHanCongViec->thoi_han_de_xuat }}"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>
                                                    <button
                                                        class="btn waves-effect btn-primary btn-choose-status"
                                                        data-id="{{ $giaHanCongViec->id }}" data-type="3">Duyệt
                                                    </button>
                                                    <br>
                                                    <br>
                                                    <button
                                                        class="btn waves-effect btn-danger btn-choose-status"
                                                        data-id="{{ $giaHanCongViec->id }}" data-type="2">Trả lại
                                                    </button>
                                                </td>

                                            </tr>
                                        @empty
                                            <td colspan="6" class="text-center">Không tìm
                                                thấy dữ liệu.
                                            </td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row col-md-12 mb-1">
                                        <div class="float-left">
                                            Tổng số văn bản: <b>{{ $giaHanCongViecDonVi->total() }}</b>
                                        </div>
                                    </div><!--col-->
                                    <div>
                                        {{ $giaHanCongViecDonVi->appends(['date'  => Request::get('date'), 'type' => Request::get('type')])->render() }}
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
        $('.btn-choose-status').on('click', function () {
            let text = $(this).text();
            let message = `Xác nhận ${text}`;
            let noiDung = $(this).parents('.duyet-gia-han').find('.noi-dung').val();
            let thoiHan = $(this).parents('.duyet-gia-han').find('input[name="thoi_han_de_xuat"]').val();

            if (confirm(message)) {
                let status = $(this).data('type');
                let id = $(this).data('id');

                $.ajax({
                    url: '/duyet-gia-han-cong-viec',
                    type: 'POST',
                    beforeSend: showLoading(),
                    data: {
                        id: id,
                        status: status,
                        noiDung: noiDung,
                        thoiHan: thoiHan,
                    },
                    success: function (data) {
                        hideLoading();
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

