@if (isset($chuyenNhanVanBanDonViPhoiHop) && count($chuyenNhanVanBanDonViPhoiHop) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#phong-phoi-hop" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Trình tự chuyển phòng phối hợp:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="phong-phoi-hop" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th class="text-center">STT</th>
                        <th class="text-center">Thời gian chuyển</th>
                        <th class="text-center">Chuyển từ</th>
                        <th class="text-center">Nội dung chuyển</th>
                        <th class="text-center">Chuyển đến</th>
                        <th class="text-center">Hạn xử lý</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($chuyenNhanVanBanDonViPhoiHop as $key => $ChuyenNhanCongViec)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>{{ date('d/m/Y H:i:s', strtotime($ChuyenNhanCongViec->created_at)) }}</td>
                            <td>Đ/c {{ $ChuyenNhanCongViec->canBoChuyen->ho_ten ?? '' }}</td>
                            <td>{{ $ChuyenNhanCongViec->noi_dung ?? null }}</td>
                            <td>Đ/c {{ $ChuyenNhanCongViec->CanBoNhan->ho_ten ?? '' }}</td>
                            <td>
                                <p>Hạn văn
                                    bản: {{ !empty($vanBanDen->hasChild->han_xu_ly) ? date('d/m/Y', strtotime($vanBanDen->hasChild->han_xu_ly)) : null }}</p>
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
