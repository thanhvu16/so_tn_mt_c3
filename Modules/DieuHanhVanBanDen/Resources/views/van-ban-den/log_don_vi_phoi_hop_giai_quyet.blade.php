@if (isset($danhSachDonViPhoiHopGiaiQuyet) && count($danhSachDonViPhoiHopGiaiQuyet) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#don-vi-phoi-hop-giai-quyet" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> VB đơn vị phối hợp đã giải quyết
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="don-vi-phoi-hop-giai-quyet" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th class="text-center">STT</th>
                        <th class="text-center">Thời gian hoàn thành</th>
                        <th class="text-center">Cán bộ hoàn thành</th>
                        <th class="text-center">Nội dung</th>
                        <th class="text-center">Tệp tin</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($danhSachDonViPhoiHopGiaiQuyet as $key => $phoiHopGiaiQuyet)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>{{ date('d/m/Y H:i:s', strtotime($phoiHopGiaiQuyet->created_at)) }}</td>
                            <td>Đ/c {{ $phoiHopGiaiQuyet->user->ho_ten ?? '' }}<br>
                                ({{ $phoiHopGiaiQuyet->user->DonVi->ten_don_vi ?? '' }})
                            </td>
                            <td>{{ $phoiHopGiaiQuyet->noi_dung ?? null }}</td>
                            <td>
                                @if (isset($phoiHopGiaiQuyet->phoiHopGiaiQuyetFile))
                                    @foreach($phoiHopGiaiQuyet->phoiHopGiaiQuyetFile as $key => $file)
                                        <a href="{{ $file->getUrlFile() }}"
                                           target="popup"
                                           class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                        @if (count($phoiHopGiaiQuyet->phoiHopGiaiQuyetFile)-1 != $key)
                                            &nbsp;|&nbsp;
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Không tìm thấy dữ liệu.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
