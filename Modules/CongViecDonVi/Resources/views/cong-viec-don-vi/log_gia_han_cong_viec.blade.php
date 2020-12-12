@if (count($chuyenNhanCongViecDonVi->giaHanCongViecByDonVi($chuyenNhanCongViecDonVi->don_vi_id)) > 0)
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card-box box-panel">
                <div class="table-responsive">
                    <span>
                        <a data-toggle="collapse" href="#gia-han-van-ban"
                           class="color-black">
                            <i class="fa fa-link"></i> Trình tự đề xuất gia hạn:
                            <i class="fa fa-plus pull-right"></i>
                        </a>
                    </span>
                    <div id="gia-han-van-ban" class="panel-collapse collapse">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                            <tr role="row">
                                <th>STT</th>
                                <th>Thời gian chuyển</th>
                                <th>Người chuyển</th>
                                <th>Người nhận</th>
                                <th>Lý do</th>
                                <th>Hạn công việc</th>
                                <th>Hạn đề xuất</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($chuyenNhanCongViecDonVi->giaHanCongViecByDonVi($chuyenNhanCongViecDonVi->don_vi_id) as $key => $giaHan)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($giaHan->created_at)) }}</td>
                                    <td>
                                        Đ/c {{ $giaHan->canBoChuyen->ho_ten ?? '' }}</td>
                                    <td>Đ/c {{ $giaHan->CanBoNhan->ho_ten ?? '' }}</td>
                                    <td>{{ $giaHan->noi_dung }}</td>
                                    <td class="text-center">
                                        {{ !empty($giaHan->han_cu) ? date('d/m/Y', strtotime($giaHan->han_cu)) : null }}
                                    </td>
                                    <td>
                                        {{ date('d/m/Y', strtotime($giaHan->thoi_han_de_xuat)) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không tìm thấy
                                        dữ liệu.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
