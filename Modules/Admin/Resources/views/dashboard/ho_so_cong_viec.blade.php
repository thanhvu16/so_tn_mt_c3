<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold">
            <div class="col-md-7">
                <i class="fa fa-th"></i>
                <span>&ensp;Xử lý văn bản đến</span>
            </div>
            <div class="col-md-5 text-center panel-bieu-do">
                <span class="text-center">Biểu đồ</span>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
            <div class="col-md-7 pl-1">
                @if( auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]) && auth::user()->donVi->cap_xa == null)
                <a class="text-title-item" href="{{ route('lanh-dao-chi-dao.index') }}">
                    <p>VB xin ý kiến
                        <button
                                class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanChoYKien }}</button>
                    </p>
                </a>
                @endif
                @if( auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]))
                <a class="text-title-item" href="{{ route('van-ban-lanh-dao-xu-ly.index') }}">
                    <p>VB chờ xử lý
                        <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanChoXuLy }}</button>
                    </p>
                </a>
                @endif
                @if (auth::user()->can(\App\Common\AllPermission::thamMuu()))
                    <a class="text-title-item" href="{{ route('phan-loai-van-ban.index') }}">
                        <p>Văn bản chờ phân loại
                            <button
                                class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanChoPhanLoai }}</button>
                        </p>
                    </a>
                    @if (auth::user()->donVi->parent_id != 0)
                        <a class="text-title-item" href="{{ route('phan-loai-van-ban-phoi-hop.index') }}">
                            <p>VB phối hợp chờ  phân loại
                                <button
                                    class="btn br-10 btn-green-light btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanPhoiHopChoPhanLoai }}</button>
                            </p>
                        </a>
                    @endif
                @endif
                @if(auth::user()->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN]))
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.index') }}">
                        <p>VB chờ xử lý
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanChoXuLy }}</button>
                        </p>
                    </a>
                @endif

                @if(auth::user()->hasRole([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN]))
                    <a class="text-title-item" href="{{ route('gia-han-van-ban.index') }}">
                        <p>VB xin gia hạn
                            <button
                                class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanXinGiaHan }}</button>
                        </p>
                    </a>
                @endif
                @if(auth::user()->hasRole([PHO_CHANH_VAN_PHONG, PHO_PHONG, PHO_TRUONG_BAN]))
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.xem_de_biet') }}">
                        <p>VB chỉ đạo, giám sát
                            <button
                                class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanXemDeBiet }}</button>
                        </p>
                    </a>
                @endif

                @if (auth::user()->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN]))
                    @unlessrole(CHUYEN_VIEN)
                        <a class="text-title-item" href="{{ route('duyet-van-ban-cap-duoi-trinh') }}">
                            <p>Duyệt VB cấp dưới trình
                                <button
                                    class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $duyetVanBanCapDuoiTrinh }}</button>
                            </p>
                        </a>
                    @endunlessrole
                    @role(CHUYEN_VIEN)
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
                @endif


                @if(auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]))
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.quan_trong') }}">
                        <p>VB quan trọng
                            <button
                                class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanQuanTrong }}</button>
                        </p>
                    </a>
                @endif

                <a class="text-title-item" href="{{ route('van-ban-den-don-vi.dang_xu_ly', 'qua_han=1') }}">
                    <p>VB quá hạn đang xử lý
                        <button
                            class="btn br-10 btn-yellow btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanQuaHanDangXuLy }}</button>
                    </p>
                </a>
                @if(auth::user()->hasRole([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN]))
                <a class="text-title-item" href="{{ route('lich-cong-tac.index') }}">
                    <p>Lịch công tác
                        <button
                            class="btn br-10 btn-blue-dark btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $lichCongTac }}</button>
                    </p>
                </a>
                <a class="text-title-item" href="{{ route('tham-du-cuoc-hop.index') }}">
                    <p>Cuộc họp được mời tham dự
                        <button
                            class="btn br-10 btn-light-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $thamDuCuocHop }}</button>
                    </p>
                </a>
                @endif
                @role(CHUYEN_VIEN)
                <a class="text-title-item" href="{{ route('tham-du-cuoc-hop.index') }}">
                    <p>Cuộc họp được mời tham dự
                        <button
                            class="btn br-10 btn-light-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $thamDuCuocHop }}</button>
                    </p>
                </a>
                @endrole
                @if(auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]))
                    <a class="text-title-item" href="{{ route('van-ban-den-don-vi.xem_de_biet') }}">
                        <p>VB chỉ đạo, giám sát
                            <button
                                class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanXemDeBiet }}</button>
                        </p>
                    </a>

                    @if (auth::user()->cap_xa == \Modules\Admin\Entities\DonVi::CAP_XA)
                    <a class="text-title-item" href="{{ route('van-ban-den-phoi-hop.index') }}">
                        <p>VB đơn vị phối hợp chờ xử lý
                            <button
                                class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $donViPhoiHop }}</button>
                        </p>
                    </a>
                    @endif
                @endif
            </div>
            <div class="col-md-5 ">
                <div id="pie-chart-ho-so-cong-viec">

                </div>
            </div>
        </div>
    </div>
</div>

