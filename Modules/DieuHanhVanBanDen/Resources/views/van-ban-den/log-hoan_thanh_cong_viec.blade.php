@if (count($vanBanDen->giaiQuyetVanBan) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#xu-ly-hoan-thanh-cong-viec" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Hoàn thành công việc - kết quả:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="xu-ly-hoan-thanh-cong-viec" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th class="text-center">STT</th>
                        <th>Thời gian hoàn thành</th>
                        <th>Cán bộ hoàn thành</th>
                        <th>Nội dung</th>
                        <th>Cán bộ duyệt</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vanBanDen->giaiQuyetVanBan as $key => $giaiQuyetVanBan)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>{{ date('d/m/Y H:i:s', strtotime($giaiQuyetVanBan->created_at)) }}</td>
                            <td>Đ/c {{ $giaiQuyetVanBan->canBoChuyen->ho_ten ?? '' }}</td>
                            <td>
                                <p>{{ $giaiQuyetVanBan->noi_dung }}</p>
                                <p>
                                    @if (isset($giaiQuyetVanBan->giaiQuyetVanBanFile))
                                        @foreach($giaiQuyetVanBan->giaiQuyetVanBanFile as $key => $file)
                                            <a href="{{ $file->getUrlFile() }}"
                                               target="popup"
                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                            @if (count($giaiQuyetVanBan->giaiQuyetVanBanFile)-1 != $key)
                                                &nbsp;|&nbsp;
                                            @endif
                                        @endforeach
                                    @endif
                                </p>
                            </td>
                            <td>
                                @if ($giaiQuyetVanBan->noi_dung_nhan_xet)
                                    <p><b>Nội dung: {{ $giaiQuyetVanBan->noi_dung_nhan_xet }}</b></p>
                                    (<i>Đ/c {{ $giaiQuyetVanBan->canBoDuyet->ho_ten ?? '' }}</i>)
                                @endif
                                <p>{!!  $giaiQuyetVanBan->getStatus()  !!}</p>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
