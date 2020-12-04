<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="font-weight: bold">
            <div class="row">
                <div class="col-7">
                    <i class="btn btn-icon btn-sm fas fa-location-arrow btn-primary"></i>
                    <span>Công việc đơn vị</span>
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
                    <a class="text-title-item" href="{{ route('cong-viec-don-vi.index') }}">
                        <p>
                            Công việc chờ xử lý
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecChoXuLy }}</button>
                        </p>
                    </a>

                    @if (Auth::user()->vai_tro == CHUYEN_VIEN)
                        <a class="text-title-item" href="{{ route('cong-viec-don-vi.da-xu-ly') }}">
                            <p>
                                Công việc đã xử lý
                                <button
                                    class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecDonViDaXuLy }}</button>
                            </p>
                        </a>
                    @endif

                    @if (Auth::user()->vai_tro != CHUYEN_VIEN)
                        <a class="text-title-item" href="{{ route('gia-han-cong-viec.index') }}">
                            <p>
                                Công việc xin gia hạn
                                <button
                                    class="btn br-10 btn-red btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giaHanCongViecDonVi }}</button>
                            </p>
                        </a>

                        <a class="text-title-item" href="{{ route('cong-viec-hoan-thanh.cho-duyet') }}">
                            <p>
                                CV hoàn thành chờ duyệt
                                <button
                                    class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecHoanThanhChoDuyet }}</button>
                            </p>
                        </a>
                    @endif
                    @if (Auth::user()->vai_tro == CAP_PHO)
                        <a class="text-title-item" href="{{ route('cong-viec-don-vi.can-bo-xem-de-biet') }}">
                            <p>
                                CV xem để biết
                                <button
                                    class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecXemDeBiet }}</button>
                            </p>
                        </a>
                    @endif

                    <a class="text-title-item" href="{{ route('cong-viec-don-vi-phoi-hop.index') }}">
                        <p>
                            CV đơn vị PH chờ xử lý
                            <button
                                class="btn br-10 btn-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecDonViPhoiHopChoXuLy }}</button>
                        </p>
                    </a>
                    @if (Auth::user()->vai_tro == CHUYEN_VIEN)
                        <a class="text-title-item" href="{{ route('cong-viec-don-vi.chuyen-vien-phoi-hop') }}">
                            <p>
                                CV chuyên viên PH chờ xử lý
                                <button
                                    class="btn br-10 btn-info btn-info waves-effect waves-light btn-sm pull-right count-item">{{ $congViecChuyenVienPhoiHopChoXuLy }}</button>
                            </p>
                        </a>
                    @endif
                </div>
                <div class="col-md-5 ">
                    <div id="pie-chart-cong-viec-don-vi"></div>
                </div>
            </div>

        </div>
    </div>
</div>
