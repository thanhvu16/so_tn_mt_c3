@if (isset($danhSachPhoiHopGiaiQuyet) && count($danhSachPhoiHopGiaiQuyet) > 0)
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card-box box-panel">
                <div class="table-responsive">
                    <h3>
                        <a data-toggle="collapse" href="#don-vi-phoi-hop-giai-quyet" class="color-black">
                            <i class="fas fa-link"></i> CV đơn vị phối hợp đã giải quyết
                            <i class="fa fa-plus pull-right"></i>
                        </a>
                    </h3>
                    <div id="don-vi-phoi-hop-giai-quyet" class="panel-collapse collapse">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                            <tr role="row">
                                <th>STT</th>
                                <th>Thời gian hoàn thành</th>
                                <th>Cán bộ hoàn thành</th>
                                <th>Nội dung</th>
                                <th>Tệp tin</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachPhoiHopGiaiQuyet as $key => $phoiHopGiaiQuyet)
                                <tr>
                                    <td>{{ $key+1 }}</td>
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
        </div>
    </div>
@endif
