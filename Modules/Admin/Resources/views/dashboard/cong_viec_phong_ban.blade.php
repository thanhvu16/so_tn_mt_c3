<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold">
            <div class="col-md-7">
                <i class="fa fa-cubes"></i>
                <span>&ensp;Công việc nội bộ</span>
            </div>
            <div class="col-md-5 text-center panel-bieu-do">
                <span class="text-center">Biểu đồ</span>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
            <div class="col-md-7 pl-1">
                <a class="text-title-item" href="{{ route('cong-viec-don-vi.index') }}">
                    <p>CV chờ xử lý
                        <button
                            class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecDonViChoXuLy }}</button>
                    </p>
                </a>
                @unlessrole('chuyên viên')
                <a class="text-title-item" href="{{ route('gia-han-cong-viec.index') }}">
                    <p>CV xin gia hạn
                        <button
                            class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giaHanCongViecDonVi }}</button>
                    </p>
                </a>
                <a class="text-title-item" href="{{ route('cong-viec-hoan-thanh.cho-duyet') }}">
                    <p>CV hoàn thành chờ duyệt
                        <button
                            class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecDonViHoanThanhChoDuyet }}</button>
                    </p>
                </a>
                @endunlessrole

                @role(CHUYEN_VIEN)
                    <a class="text-title-item" href="{{ route('cong-viec-don-vi.da-xu-ly') }}">
                        <p>CV đã xử lý
                            <button
                                class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecChuyenVienDaXuLy }}</button>
                        </p>
                    </a>
                    <a class="text-title-item" href="{{ route('cong-viec-don-vi.chuyen-vien-phoi-hop') }}">
                        <p>CV phối hợp chờ xử lý
                            <button
                                class="btn br-10 btn-danger btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecChuyenVienPhoiHopChoXuLy }}</button>
                        </p>
                    </a>
                @endrole

                <a class="text-title-item" href="{{ route('cong-viec-don-vi-phoi-hop.index') }}">
                    <p>CV đơn vị phối hợp chờ xử lý
                        <button
                            class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $congViecDonViPhoiHopChoXuLy }}</button>
                    </p>
                </a>
            </div>
            <div class="col-md-5 ">
                <div id="pie-chart-cong-viec-phong-ban">

                </div>
            </div>
        </div>
    </div>
</div>

