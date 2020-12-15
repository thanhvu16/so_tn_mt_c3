@if (!empty($chuyenNhanCongViecDonVi->getTrinhTuXuLy()))
    <div class="col-md-12 mt-2">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#phong-chu-tri" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Trình tự chuyển nhận công việc:
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
                        <th>Nội dung</th>
                        <th>Chuyển đến</th>
                        <th>Hạn xử lý</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($chuyenNhanCongViecDonVi->getTrinhTuXuLy() as $key => $trinhTuXuLy)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{  date('d/m/Y H:i:s', strtotime($trinhTuXuLy->created_at)) }}</td>
                            <td>
                                Đ/c {{ $trinhTuXuLy->canBoChuyen->ho_ten ?? null }}</td>
                            <td>{{ $trinhTuXuLy->noi_dung_chuyen }}</td>
                            <td>
                                Đ/c {{ $trinhTuXuLy->canBoNhan->ho_ten ?? null }}</td>
                            <td>
                                <p>Hạn công
                                    việc: {{ !empty($trinhTuXuLy->han_xu_ly) ? date('d/m/Y', strtotime($trinhTuXuLy->han_xu_ly)) : null }}</p>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
