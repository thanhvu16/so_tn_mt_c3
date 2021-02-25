<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold">
            <div class="col-md-7">
                <i class="fa fa-th"></i>
                <span>&ensp;Hồ sơ công việc</span>
            </div>
            <div class="col-md-5 text-center panel-bieu-do">
                <span class="text-center">Biểu đồ</span>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
            <div class="col-md-7 pl-1">
                @hasanyrole('chủ tịch|phó chủ tịch')
                <a class="text-title-item" href="{{ route('van-ban-lanh-dao-xu-ly.index') }}">
                    <p>VB chờ xử lý
                        <button
                            class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanChoXuLy }}</button>
                    </p>
                </a>
                @endrole
                @role('chánh văn phòng')
                    <a class="text-title-item" href="{{ route('phan-loai-van-ban.index') }}">
                        <p>VB chờ chờ phân loại
                            <button
                                class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanChoPhanLoai }}</button>
                        </p>
                    </a>
                @endrole
                @hasanyrole('trưởng phòng|phó phòng|phó chánh văn phòng|chánh văn phòng|chuyên viên|trưởng ban|phó trưởng ban')
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.index') }}">
                        <p>VB chờ xử lý
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanChoXuLy }}</button>
                        </p>
                    </a>
                @endrole

                @hasanyrole('trưởng phòng|phó phòng|chủ tịch|phó chủ tịch|phó chánh văn phòng|chánh văn phòng|trưởng ban|phó trưởng ban')
                <a class="text-title-item" href="{{ route('gia-han-van-ban.index') }}">
                    <p>VB xin gia hạn
                        <button
                            class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanXinGiaHan }}</button>
                    </p>
                </a>
                @endrole

                @hasanyrole('phó phòng|phó chánh văn phòng|phó trưởng ban')
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.xem_de_biet') }}">
                        <p>VB giám sát, theo dõi
                            <button
                                class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanXemDeBiet }}</button>
                        </p>
                </a>
                @endrole

                @hasanyrole('trưởng phòng|phó phòng|phó chánh văn phòng|chánh văn phòng|chuyên viên|trưởng ban|phó trưởng ban')
                    <a class="text-title-item" href="{{ route('van-ban-den-hoan-thanh.cho-duyet') }}">
                        <p>VB hoàn thành chờ duyệt
                            <button
                                class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanHoanThanhChoDuyet }}</button>
                        </p>
                    </a>
                    @role('chuyên viên')
                        <a class="text-title-item" href="{{ route('van_ban_den_chuyen_vien.index') }}">
                            <p>VB chuyên viên PH chờ xử lý
                                <button
                                    class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $chuyenVienPhoiHop }}</button>
                            </p>
                        </a>
                    @endrole

                    <a class="text-title-item" href="{{ route('van-ban-den-phoi-hop.index') }}">
                        <p>VB đơn vị phối hợp chờ xử lý
                            <button
                                class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $donViPhoiHop }}</button>
                        </p>
                    </a>
                @endrole


                @hasanyrole('chủ tịch|phó chủ tịch')
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.quan_trong') }}">
                        <p>VB quan trọng
                            <button
                                class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanQuanTrong }}</button>
                        </p>
                    </a>
                @endrole

                <a class="text-title-item" href="{{ route('van-ban-den-don-vi.dang_xu_ly', 'qua_han=1') }}">
                    <p>VB quá hạn đang xử lý
                        <button
                            class="btn br-10 btn-yellow btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanQuaHanDangXuLy }}</button>
                    </p>
                </a>
                @hasanyrole('chủ tịch|phó chủ tịch|trưởng phòng|phó phòng|phó chánh văn phòng|chánh văn phòng|trưởng ban|phó trưởng ban')
                <a class="text-title-item" href="{{ route('lich-cong-tac.index') }}">
                    <p>Lịch công tác
                        <button
                            class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $lichCongTac }}</button>
                    </p>
                </a>
                <a class="text-title-item" href="{{ route('tham-du-cuoc-hop.index') }}">
                    <p>Cuộc họp được mời tham dự
                        <button
                            class="btn br-10 btn-light-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $thamDuCuocHop }}</button>
                    </p>
                </a>
                @endrole
                @role('chuyên viên')
                <a class="text-title-item" href="{{ route('tham-du-cuoc-hop.index') }}">
                    <p>Cuộc họp được mời tham dự
                        <button
                            class="btn br-10 btn-light-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $thamDuCuocHop }}</button>
                    </p>
                </a>
                @endrole
                @hasanyrole('chủ tịch|phó chủ tịch')
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.xem_de_biet') }}">
                        <p>VB giám sát, theo dõi
                            <button
                                class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanXemDeBiet }}</button>
                        </p>
                    </a>
                    @if (auth::user()->donVi->cap_xa == 1)
                    <a class="text-title-item" href="{{ route('van-ban-den-phoi-hop.index') }}">
                        <p>VB đơn vị phối hợp chờ xử lý
                            <button
                                class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $donViPhoiHop }}</button>
                        </p>
                    </a>
                    @endif
                @endrole
            </div>
            <div class="col-md-5 ">
                <div id="pie-chart-ho-so-cong-viec">

                </div>
            </div>
        </div>
    </div>
</div>

