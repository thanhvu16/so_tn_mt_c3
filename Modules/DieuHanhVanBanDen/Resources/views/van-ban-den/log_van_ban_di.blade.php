@if (!empty($vanBanDen->vanBanDi))
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#log-van-ban-di" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Văn bản đi:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="log-van-ban-di" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row" class="text-center">
                        <th width="4%" class="text-center">Số đi</th>
                        <th width="18%" class="text-center">Ngày ban hành</th>
                        <th width="20%" class="text-center">Thông tin</th>
                        <th width="30%" class="text-center">Trích yếu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center"><span class="color-red text-bold">{{ $vanBanDen->vanBanDi->so_di }}</span></td>
                        <td>
                            <p>{{ date('d/m/Y', strtotime($vanBanDen->vanBanDi->ngay_ban_hanh)) }}</p>
                        </td>
                        <td>
                            <p><b>Số ký hiệu:</b> {{ $vanBanDen->vanBanDi->so_ky_hieu }}</p>
                            <p><b>Loại văn bản:</b> {{ $vanBanDen->vanBanDi->loaivanban->ten_loai_van_ban ?? '' }}</p>
                        </td>
                        <td>
                            <p><a href="{{ route('Quytrinhxulyvanbandi',$vanBanDen->vanBanDi->id) }}" class="color-black">{{ $vanBanDen->vanBanDi->trich_yeu }}</a></p>
                            <p>
                                @if (isset($vanBanDen->vanBanDi->filechinh))
                                    tệp tin: <br>
                                    @foreach($vanBanDen->vanBanDi->filechinh as $key => $file)
                                        <a href="{{ $file->getUrlFile() }}"
                                           target="popup"
                                           class="detail-file-name seen-new-window">[{{ cutStr($file->ten_file) }}]</a>
                                        @if (count($vanBanDen->vanBanDi->filechinh)-1 != $key)
                                            &nbsp;|&nbsp;
                                        @endif
                                    @endforeach
                                @endif
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
