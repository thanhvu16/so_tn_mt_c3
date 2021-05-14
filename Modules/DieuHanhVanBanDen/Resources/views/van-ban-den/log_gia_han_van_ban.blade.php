@if (isset($giaHanVanBanDonVi) && count($giaHanVanBanDonVi) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#xu-ly-gia-han-van-ban" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Trình tự đề xuất gia hạn:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="xu-ly-gia-han-van-ban" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th class="text-center">STT</th>
                        <th class="text-center">Thời gian chuyển</th>
                        <th class="text-center">Người chuyển</th>
                        <th class="text-center">Người nhận</th>
                        <th class="text-center">Lý do</th>
                        <th class="text-center">Hạn văn bản</th>
                        <th class="text-center">Hạn đề xuất</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($giaHanVanBanDonVi as $key => $giaHan)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($giaHan->created_at)) }}</td>
                                <td>Đ/c {{ $giaHan->canBoChuyen->ho_ten ?? '' }}</td>
                                <td>Đ/c {{ $giaHan->CanBoNhan->ho_ten ?? '' }}</td>
                                <td>{{ $giaHan->noi_dung }}
                                    @if ($giaHan->status == 2 && $giaHan->lanh_dao_duyet == 1)
                                        <p><span class="label label-danger">Gia  trả lại</span></p>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ !empty($giaHan->thoi_han_cu) ? date('d/m/Y', strtotime($giaHan->thoi_han_cu)) : null }}
                                </td>
                                <td>
                                    {{ date('d/m/Y', strtotime($giaHan->thoi_han_de_xuat)) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không tìm thấy dữ liệu.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endif
