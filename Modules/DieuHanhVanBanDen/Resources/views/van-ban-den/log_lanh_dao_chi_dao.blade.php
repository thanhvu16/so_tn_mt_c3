@if (isset($lanhDaoChiDao) && count($lanhDaoChiDao) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#phong-chu-tri1" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Lãnh đạo chỉ đạo:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="phong-chu-tri1" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row">
                        <th class="text-center" width="4%">STT</th>
                        <th class="text-center" width="15%">Lãnh đạo</th>
                        <th class="text-center">ý kiến</th>
                        <th class="text-center" width="15%">Thời gian</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($lanhDaoChiDao as $key => $lanhDaoChiDaodata)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>Đ/c {{ $lanhDaoChiDaodata->lanhDao->ho_ten ?? '' }}</td>
                            <td>{{ $lanhDaoChiDaodata->y_kien }}</td>
                            <td class="text-center">{{ date('d/m/Y H:i:s', strtotime($lanhDaoChiDaodata->updated_at)) }}</td>
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
