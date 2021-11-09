@if (count($luuVetVanBanCu) > 0)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#log-van-ban-di" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Lưu vết phân văn bản:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="log-van-ban-di" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row" class="text-center">
                        <th width="4%" class="text-center">STT</th>
                        <th width="15%" class="text-center">Ngày giờ chuyển</th>
                        <th width="14%" class="text-center">Người phân lại</th>
                        <th width="" class="text-center">Phòng cũ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($luuVetVanBanCu as $key=>$data)
                    <tr>
                        <td class="text-center"><span class="color-red text-bold">{{ $key+1 }}</span></td>
                        <td class="text-center">{{  date('d/m/Y H:i:s', strtotime($data->created_at)) }}
                        </td>
                        <td>
                            {{$data->nguoiPhanLai->ho_ten ?? ''}}

                        </td>
                        <td>
                        {{$data->donViVanBan->ten_don_vi ?? ''}}
                        </td>

                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
