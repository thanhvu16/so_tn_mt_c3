@if (count($chuyenNhanCongViecDonVi->giaiQuyetCongViec) > 0)
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card-box box-panel">
                <div class="table-responsive">
                    <h3>
                        <a data-toggle="collapse" href="#van-ban-hoan-thanh"
                           class="color-black">
                            <i class="fas fa-link"></i> Hoàn thành công việc - kết quả:
                            <i class="fa fa-plus pull-right"></i>
                        </a>
                    </h3>
                    <div id="van-ban-hoan-thanh" class="panel-collapse collapse">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                            <tr role="row">
                                <th>STT</th>
                                <th>Thời gian hoàn thành</th>
                                <th>Cán bộ hoàn thành</th>
                                <th>Nội dung</th>
                                <th>Cán bộ duyệt</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($chuyenNhanCongViecDonVi->giaiQuyetCongViec as $key => $giaiQuyetCongViecDonVi)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($giaiQuyetCongViecDonVi->created_at)) }}</td>
                                    <td>
                                        Đ/c {{ $giaiQuyetCongViecDonVi->canBoChuyen->ho_ten ?? '' }}</td>
                                    <td>
                                        <p>{{ $giaiQuyetCongViecDonVi->noi_dung }}</p>
                                        <p>
                                            @if (isset($giaiQuyetCongViecDonVi->giaiQuyetCongViecDonViFile))
                                                @foreach($giaiQuyetCongViecDonVi->giaiQuyetCongViecDonViFile as $key => $file)
                                                    <a href="{{ $file->getUrlFile() }}"
                                                       target="popup"
                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                        ]</a>
                                                    @if (count($giaiQuyetCongViecDonVi->giaiQuyetCongViecDonViFile)-1 != $key)
                                                        &nbsp;|&nbsp;
                                                    @endif
                                                @endforeach
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        @if ($giaiQuyetCongViecDonVi->noi_dung_nhan_xet)
                                            <p><b>Nội dung trả
                                                    lại: {{ $giaiQuyetCongViecDonVi->noi_dung_nhan_xet }}</b>
                                            </p>
                                        @endif
                                        <p>{!!  $giaiQuyetCongViecDonVi->getStatus()  !!}</p>
                                        (<i>Đ/c {{ $giaiQuyetCongViecDonVi->canBoDuyet->ho_ten ?? '' }}</i>)
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
