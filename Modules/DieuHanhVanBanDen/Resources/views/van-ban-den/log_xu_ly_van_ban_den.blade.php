@if (isset($xuLyVanBanDen) && count($xuLyVanBanDen) > 0)
    <div class="col-md-12 mt-2">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#xu-ly-van-ban-den" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Trình tự chuyển lãnh đạo chỉ đạo văn bản:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="xu-ly-van-ban-den" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th class="text-center" width="4%">STT</th>
                        <th class="text-center" width="15%">Thời gian chuyển</th>
                        <th class="text-center" width="14%">Chuyển từ</th>
                        <th class="text-center">Nội dung</th>
                        <th class="text-center" width="14%">Chuyển đến</th>
                        <th class="text-center"  width="14%">Hạn xử lý</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($xuLyVanBanDen as $key => $logChuyenVanBanDen)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>{{  date('d/m/Y H:i:s', strtotime($logChuyenVanBanDen->created_at)) }}</td>
                            <td>Đ/c {{ $logChuyenVanBanDen->canBoChuyen->ho_ten ?? null }}</td>
                            <td>
                                <p>{{ $logChuyenVanBanDen->noi_dung }}</p>
                                @if ($logChuyenVanBanDen->status == 1)
                                    <p><span class="label label-danger">VB trả lại</span></p>
                                @endif
                            </td>
                            <td>Đ/c {{ $logChuyenVanBanDen->canBoNhan->ho_ten ?? null }}</td>
                            <td>
                                @if ($logChuyenVanBanDen->han_xu_ly)
                                    <p>Hạn lãnh đạo: {{ date('d/m/Y', strtotime($logChuyenVanBanDen->han_xu_ly)) }}</p>
                                @else
                                    <p>Hạn văn
                                        bản: {{ !empty($vanBanDen->hasChild->han_xu_ly) ? date('d/m/Y', strtotime($vanBanDen->hasChild->han_xu_ly)) : null }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endif
