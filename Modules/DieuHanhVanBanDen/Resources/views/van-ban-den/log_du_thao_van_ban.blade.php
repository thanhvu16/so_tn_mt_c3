@if (count($vanBanDen->duThaoVanBan) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#log-du-thao-van-ban" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Dự thảo văn bản:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="log-du-thao-van-ban" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row" class="text-center">
                        <th width="2%" class="text-center">Lần</th>
                        <th width="18%" class="text-center">Cán bộ dự thảo</th>
                        <th width="30%" class="text-center">Ý kiến</th>
                        <th width="20%" class="text-center">Thông tin</th>
                        <th width="30%" class="text-center">Trích yếu - Nội dung</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vanBanDen->duThaoVanBan as $key => $duThaoVanBan)
                        <tr>
                            <td class="text-center">{{ $duThaoVanBan->lan_du_thao }}</td>
                            <td>
                                <p>Đ/c {{ $duThaoVanBan->nguoiDung->ho_ten ?? '' }}</p>
                                <p><i>({{ date('d/m/Y H:i:s', strtotime($duThaoVanBan->created_at)) }})</i></p>
                            </td>
                            <td>{{ $duThaoVanBan->y_kien }}</td>
                            <td>
                                <p><b>Số ký hiệu:</b> {{ $duThaoVanBan->so_ky_hieu }}</p>
                                <p><b>Loại văn bản:</b> {{ $duThaoVanBan->loaivanban->ten_loai_van_ban ?? '' }}</p>
                            </td>
                            <td>
                                <p><a href="{{ route('Danhsachduthao') }}" class="color-black">{{ $duThaoVanBan->vb_trich_yeu }}</a></p>
                                @if ($duThaoVanBan->phieuTrinhVanBanDi)
                                <p>
                                   - <a href="{{ $duThaoVanBan->phieuTrinhVanBanDi->getUrlFile() }}"
                                       target="popup" class="detail-file-name seen-new-window color-black">Phiếu trình văn bản dự thảo</a>
                                </p>
                                @endif
                                @if ($duThaoVanBan->fileTrinhKyVanBanDi)
                                    <p>
                                        - <a href="{{ $duThaoVanBan->fileTrinhKyVanBanDi->getUrlFile() }}"
                                             target="popup" class="detail-file-name seen-new-window color-black">File trình ký văn bản dự thảo</a>
                                    </p>
                                @endif
                                {{--                                File:--}}
{{--                                <p>--}}
{{--                                    @if (isset($duThaoVanBan->Duthaofile))--}}
{{--                                        @foreach($duThaoVanBan->Duthaofile as $key => $file)--}}
{{--                                            <a href="{{ $file->getUrlFile() }}"--}}
{{--                                               target="popup"--}}
{{--                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>--}}
{{--                                            @if (count($duThaoVanBan->Duthaofile)-1 != $key)--}}
{{--                                                &nbsp;|&nbsp;--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </p>--}}
                                @if ($duThaoVanBan->stt == 3)
                                    <label class="label label-success">Đã duyệt</label>
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
