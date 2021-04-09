<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="font-weight: bold">
            <div class="row">
                <div class="col-7">
                    <i class="btn btn-icon btn-sm far fas fa-file-alt btn-primary"></i>
                    <span>Phân loại văn bản</span>
                </div>
                <div class="col-5 text-center panel-bieu-do">
                    <span class="text-center">Biểu đồ</span>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-7 pl-1">
                    <br>
                    <br>
                    @if (Auth::user()->quyen_tham_muu)
                        <a class="text-title-item" href="{{route('phan-loai-van-ban.index')}}">
                            <p>Văn bản chờ phân loại
                                <button
                                    class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanChoPhanLoai }}</button>
                            </p>
                        </a>

                        <a class="text-title-item" href="{{route('phan-loai-van-ban.da_phan_loai')}}">
                            <p>Văn bản đã phân loại
                                <button
                                    class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanDenDaPhanLoai }}</button>
                            </p>
                        </a>

                        <a class="text-title-item" href="{{route('phan-loai-van-ban.tra_lai')}}">

                            <p>Văn bản bị trả lại
                                <button
                                    class="btn br-10 btn-danger btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanDenTraLai }}</button>
                            </p>
                        </a>

                        <a class="text-title-item" href="{{ route('chuyen-van-ban.create') }}">

                            <p>Văn bản chờ chuyển đơn vị
                                <button
                                    class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanCanChuyen }}</button>
                            </p>
                        </a>
                        @if (Auth::user()->vai_tro == CAP_TRUONG)
                            <a class="text-title-item" href="{{ route('phieu-chuyen-van-ban.index') }}">
                                <p>D/s phiếu chuyển VB chờ ký
                                    <button
                                        class="btn br-10 btn-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $danhSachPhieuChuyenChoKy }}</button>
                                </p>
                            </a>
                        @endif
                    @endif
                </div>
                <div class="col-md-5 ">
                    <div id="pie-chart-phan-loai-van-ban">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
