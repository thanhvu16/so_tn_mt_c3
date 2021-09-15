<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold">
            <div class="col-md-7">
                <i class="fa fa-th"></i>
                <span>&ensp;Xử lý giấy mời đến</span>
            </div>
            <div class="col-md-5 text-center panel-bieu-do">
                <span class="text-center">Biểu đồ</span>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
            <div class="col-md-7 pl-1">
                @if (auth::user()->can(\App\Common\AllPermission::thamMuu()) )
                    @if(auth::user()->id == 15 || auth::user()->id == 10551)
                    <a class="text-title-item" href="{{ route('phan_loai_giay_moi','type=1') }}">
                        <p>Giấy mời chờ phân loại
                            <button
                                class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiChoPhanLoai }}</button>
                        </p>
                    </a>
                    @endif
                @endif
                @if( auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]))
                    <a class="text-title-item" href="{{ route('giayMoiLanhDaoXuLy','type=1') }}">
                        <p>GM chờ xử lý
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiChoXuLy }}</button>
                        </p>
                    </a>
                @endif
                @if (auth::user()->can(\App\Common\AllPermission::thamMuu()))

                    @if (auth::user()->donVi->parent_id != 0)
                        <a class="text-title-item" href="{{ route('phan-loai-van-ban-phoi-hop.index') }}">
                            <p>GM phối hợp chờ phân loại
                                <button
                                    class="btn br-10 btn-green-light btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiPhoiHopChoPhanLoai }}</button>
                            </p>
                        </a>
                    @endif
                @endif
                @if(auth::user()->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN]))
                    <a class="text-title-item" href="{{ route('giay_moi_den_don_vi_index','type=1') }}">
                        <p>GM chờ xử lý
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiChoXuLy }}</button>
                        </p>
                    </a>
                @endif

                @if(auth::user()->hasRole([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN]))
                    <a class="text-title-item" href="{{ route('giaHanGiayMoi','type=1') }}">
                        <p>GM xin gia hạn
                            <button
                                class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiXinGiaHan }}</button>
                        </p>
                    </a>
                @endif
                @if(auth::user()->hasRole([PHO_CHANH_VAN_PHONG, PHO_PHONG, PHO_TRUONG_BAN]))
                    <a class="text-title-item" href="{{ route('giay-moi-den-don-vi.xem_de_biet','type=1') }}">
                        <p>GM chỉ đạo, giám sát
                            <button
                                class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiXemDeBiet }}</button>
                        </p>
                    </a>
                @endif

                @if (auth::user()->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN]))
                    @unlessrole(CHUYEN_VIEN)
                    <a class="text-title-item" href="{{ route('duyet-giay-moi-cap-duoi-trinh','type=1') }}">
                        <p>Duyệt GM cấp dưới trình
                            <button
                                class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $duyetGiayMoiCapDuoiTrinh }}</button>
                        </p>
                    </a>
                    @endunlessrole
                    @role(CHUYEN_VIEN)
                    <a class="text-title-item" href="{{ route('giay_moi_den_chuyen_vien.index','type=1') }}">
                        <p>GM chuyên viên PH chờ xử lý
                            <button
                                class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $chuyenVienPhoiHopGM }}</button>
                        </p>
                    </a>
                    @endrole

                    <a class="text-title-item" href="{{ route('giay-moi-den-phoi-hop.index','type=1') }}">
                        <p>GM đơn vị phối hợp chờ xử lý
                            <button
                                class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $donViPhoiHopGM }}</button>
                        </p>
                    </a>
                @endif


                @if(auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]))
                    <a class="text-title-item" href="{{ route('giay-moi-den-don-vi.quan_trong','type=1') }}">
                        <p>GM quan trọng
                            <button
                                class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiQuanTrong }}</button>
                        </p>
                    </a>
                @endif

                <a class="text-title-item" href="{{ route('giay-moi-den-don-vi.dang_xu_ly', 'qua_han=1'.'&type=1') }}">
                    <p>GM quá hạn đang xử lý
                        <button
                            class="btn br-10 btn-yellow btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiQuaHanDangXuLy }}</button>
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
                    <a class="text-title-item" href="{{ route('giay-moi-den-don-vi.xem_de_biet','type=1') }}">
                        <p>GM chỉ đạo, giám sát
                            <button
                                class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiXemDeBiet }}</button>
                        </p>
                    </a>
                    @if (auth::user()->donVi->cap_xa == \Modules\Admin\Entities\DonVi::CAP_XA)
                        <a class="text-title-item" href="{{ route('giay-moi-den-phoi-hop.index','type=1') }}">
                            <p>GM đơn vị phối hợp chờ xử lý
                                <button
                                    class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $donViPhoiHopGM }}</button>
                            </p>
                        </a>
                    @endif
                @endif
            </div>
            <div class="col-md-5 ">
                <div id="pie-chart-giay-moi">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix"></div>

