@extends('admin::layouts.master')
@section('page_title', 'Văn bản xin gia hạn')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Văn bản xin gia hạn</h4>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="3%" class="text-center">STT</th>
                                <th width="30%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="25%" class="text-center">Lý do xin gia hạn</th>
                                <th width="25%" class="text-center">Nội dung giải trình</th>
                                <th width="7%" class="text-center">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($giaHanVanBanDonVi as $giaHanVanBan)
                                <tr class="duyet-gia-han">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        <p>
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $giaHanVanBan->vanBanDen->id) }}">{{ $giaHanVanBan->vanBanDen->trich_yeu ?? null }}</a>
                                        </p>
                                        {{-- @include('dieuhanhvanbanden::van-ban-den.info', ['vanBanDen' => $giaHanVanBan->vanBanDen, 'type'=>'gia_han'])--}}
                                        @include('dieuhanhvanbanden::van-ban-den.thong_tin', ['vanBanDen' => $giaHanVanBan->vanBanDen])
                                    </td>
                                    <td>
                                        <p><i><b>Lý do:</b> {{ $giaHanVanBan->noi_dung }}</i></p>
                                        <p>Hạn đề xuất:
                                            <b>{{ !empty($giaHanVanBan->thoi_han_de_xuat) ? date('d/m/Y', strtotime($giaHanVanBan->thoi_han_de_xuat)) : null }}</b>
                                        </p>
                                        <p>
                                            <i>({{ $giaHanVanBan->canBoChuyen->ho_ten ?? null }}
                                                - {{ date('d/m/Y H:i:s', strtotime($giaHanVanBan->created_at)) }})</i>
                                        </p>
                                    </td>
                                    <td>
                                        <div class="col-md-12 form-group">
                                                        <textarea class="form-control noi-dung" name="noi_dung"
                                                                  rows="3"
                                                                  required>{{ $giaHanVanBan->noi_dung }}</textarea>


                                            <label for="thoiHan" class="col-form-label mt-2">Thời hạn đề
                                                xuất</label>
                                            <input type="date" name="thoi_han_de_xuat"
                                                   id="thoiHan"
                                                   placeholder="Chọn thời hạn công việc.."
                                                   value="{{ $giaHanVanBan->thoi_han_de_xuat }}"
                                                   class="form-control">
                                        </div>
                                    </td>
                                    <td>
                                        <button
                                            class="btn waves-effect btn-primary btn-choose-status btn-sm"
                                            data-id="{{ $giaHanVanBan->id }}" data-type="3"><i class="fa fa-check"></i>
                                            Duyệt
                                        </button>
                                        <br>
                                        <br>
                                        <button
                                            class="btn waves-effect btn-danger btn-choose-status btn-sm"
                                            data-id="{{ $giaHanVanBan->id }}" data-type="2"><i class="fa fa-undo"></i>
                                            Trả lại
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="5" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                                Tổng số loại văn bản: <b>{{ $giaHanVanBanDonVi->total() }}</b>
                            </div>
                            <div class="col-md-6 text-right">
                                {!! $giaHanVanBanDonVi->render() !!}
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
            let noiDung = $(this).parents('.duyet-gia-han').find('.noi-dung').val();
            let thoiHan = $(this).parents('.duyet-gia-han').find('input[name="thoi_han_de_xuat"]').val();
            if (confirm(message)) {
                let status = $(this).data('type');
                let id = $(this).data('id');

                $.ajax({
                    url: APP_URL + '/duyet-gia-han-van-ban',
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
