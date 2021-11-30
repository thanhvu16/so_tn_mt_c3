@if($vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
    <div class="col-md-12 mt-3">
        <div class="table-responsive box-panel">
            <h3>
                <a data-toggle="collapse" href="#log-van-ban-di" class="color-black font-weight-bold">
                    <i class="fa fa-link"></i> Cán bộ dự họp:
                    <i class="fa fa-plus pull-right"></i>
                </a>
            </h3>
            <div id="log-van-ban-di" class="panel-collapse collapse">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr role="row" class="text-center">
                        <th width="4%" class="text-center">STT</th>
                        <th width="" class="text-center">Cán bộ dự họp</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($canBoDuHop as $key=>$data)
                    <tr>
                        <td class="text-center"><span class="color-red text-bold">{{ $key+1 }}</span></td>
                        <td class="text-center">{{  hoTen($data) }}
                        </td>


                    </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
