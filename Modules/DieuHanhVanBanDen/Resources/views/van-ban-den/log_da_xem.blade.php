@if (count($vanBanDaXem) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#log-van-ban-di1" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Cán bộ nhận văn bản:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="log-van-ban-di1" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row" class="text-center">
                        <th width="4%" class="text-center">STT</th>
                        <th width="" class="text-center">Tên cán bộ</th>
                        <th width="15%" class="text-center">Trạng thái</th>
                        <th width="14%" class="text-center">Ngày xem</th>
                        <th width="15%" class="text-center">Giờ xem</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vanBanDaXem as $key=>$data)
                        <tr>
                            <td class="text-center"><span class="color-red text-bold">{{ $key+1 }}</span></td>
                            <td class="text-center">{{  $data->nguoiDung->ho_ten ?? '' }}</td>
                            <td class="text-center">
                                @if($data->ngay_chuyen == null)
                                    <span class="label label-success">Chưa xem</span>
                                @else
                                    <span class="label label-danger">Đã xem</span>

                                @endif
                            </td>
                            <td class="text-center">
                                @if($data->ngay_chuyen)
                                {{  date('d/m/Y', strtotime($data->ngay_chuyen)) }}
                                @endif

                            </td>
                            <td class="text-center">
                                {{ $data->gio_chuyen }}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
