@if (isset($chuyenNhanVanBanDonViChuTri) && count($chuyenNhanVanBanDonViChuTri) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#phong-chu-tri" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Trình tự chuyển phòng Chủ trì:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="phong-chu-tri" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th>STT</th>
                        <th>Thời gian chuyển</th>
                        <th>Chuyển từ</th>
                        <th>Nội dung chuyển</th>
                        <th>Chuyển đến</th>
                        <th>Hạn xủ lý</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($chuyenNhanVanBanDonViChuTri as $key => $ChuyenNhanCongViec)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ date('d/m/Y H:i:s', strtotime($ChuyenNhanCongViec->created_at)) }}</td>
                            <td>Đ/c {{ $ChuyenNhanCongViec->canBoChuyen->ho_ten ?? '' }}</td>
                            <td>{{ $ChuyenNhanCongViec->noi_dung ?? null }}</td>
                            <td>Đ/c {{ $ChuyenNhanCongViec->CanBoNhan->ho_ten ?? '' }}</td>
                            <td>
                                <p>Hạn văn
                                    bản: {{ !empty($vanBanDen->han_xu_ly) ? date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) : null }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endif
