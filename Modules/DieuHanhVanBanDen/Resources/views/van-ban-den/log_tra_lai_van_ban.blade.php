@if (isset($xuLyVanBanDen) && count($xuLyVanBanDen) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#xu-ly-tra-lai-van-ban-den" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Trình tự trả lại văn bản:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="xu-ly-tra-lai-van-ban-den" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th class="text-center">STT</th>
                        <th class="text-center">Thời gian chuyển</th>
                        <th class="text-center">Chuyển từ</th>
                        <th class="text-center">Nội dung</th>
                        <th class="text-center">Chuyển đến</th>
                        <th class="text-center">Hạn xử lý</th>
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
                                <div class="col-md-12 text-right">
                                    @if($logChuyenVanBanDen->fileChuyen($logChuyenVanBanDen->van_ban_den_id,$logChuyenVanBanDen->can_bo_chuyen_id))
                                        <a href="{{$logChuyenVanBanDen->fileChuyen($logChuyenVanBanDen->van_ban_den_id,$logChuyenVanBanDen->can_bo_chuyen_id)->getUrlFile()}}">[File lý do]</a>
                                    @endif
                                </div>
                                @if ($logChuyenVanBanDen->status == 1)
                                    <p><span class="label label-danger">VB trả lại</span></p>
                                @endif
                            </td>
                            <td>Đ/c {{ $logChuyenVanBanDen->canBoNhan->ho_ten ?? null }}</td>
                            <td>
                                <p>Hạn văn
                                    bản: {{ !empty($vanBanDen->hasChild->han_xu_ly) ? date('d/m/Y', strtotime($vanBanDen->hasChild->han_xu_ly)) : null }}</p>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endif
