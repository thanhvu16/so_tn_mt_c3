<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="font-weight: bold">
            <div class="row">
                <div class="col-7">
                    <i class="btn btn-icon btn-sm fas fa-location-arrow btn-primary"></i>
                    <span>Chỉ đạo điều hành - văn bản đến</span>
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
                    @if (in_array(Auth::user()->donVi->cap_don_vi, [DON_VI_CAP_1, DON_VI_CAP_2]) && in_array(Auth::user()->vai_tro, [CAP_TRUONG, CAP_PHO]))
                        <a class="text-title-item" href="{{route('van-ban-cho-lanh-dao-xu-ly.index')}}">
                            <p>
                                {{ Auth::user()->donVi->cap_don_vi != DON_VI_CAP_3 ? 'VB chờ lãnh đạo xử lý' : 'VB chờ tham mưu' }}
                                <button
                                    class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanDenChoXuLy }}</button>
                            </p>
                        </a>
                        @if (Auth::user()->donVi->cap_don_vi == DON_VI_CAP_2 && Auth::user()->vai_tro == CAP_TRUONG)
                            <a class="text-title-item" href="{{ route('phieu-chuyen-van-ban.index') }}">
                                <p>D/s phiếu chuyển VB chờ ký
                                    <button
                                        class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $danhSachPhieuChuyenChoKy }}</button>
                                </p>
                            </a>
                            <a class="text-title-item" href="{{route('phieu-xu-ly.index')}}">
                                <p>In phiếu xử lý văn bản
                                    <button
                                        class="btn br-10 btn-orange btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $tongPhieuChoIn }}</button>
                                </p>
                            </a>
                        @endif
                        <a class="text-title-item" href="{{ route('van-ban-den-dang-xu-ly.index', 'qua_han=1') }}">
                            <p> VB quá hạn đang xử lý
                                <button
                                    class="btn br-10 btn-danger btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanDenDangXuLy }}</button>
                            </p>
                        </a>
                        <a class="text-title-item" href="{{route('gia-han-van-ban.index')}}">
                            <p>Văn bản xin gia hạn
                                <button
                                    class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanDonViGiaHan }}</button>
                            </p>
                        </a>

                        <a class="text-title-item" href="{{route('van_ban_lanh_dao_phoi_hop.index')}}">
                            <p>Văn bản xem để biết
                                <button
                                    class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanLanhDaoXemDeBiet }}</button>
                            </p>
                        </a>
                        <a class="text-title-item" href="{{route('van-ban-quan-trong.index')}}">
                            <p>Văn bản quan trọng
                                <button
                                    class="btn br-10 btn-blue-dark btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $danhSachVanBanQuanTrong }}</button>
                            </p>
                        </a>
                        <a class="text-title-item" href="{{route('lich-cong-tac.index')}}">
                            <p>Lịch công tác
                                <button
                                    class="btn br-10 btn-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $lichCongTac }}</button>
                            </p>
                        </a>

                    @endif

                    @if (in_array(Auth::user()->donVi->cap_don_vi, [DON_VI_CAP_3]))
                        @if (Auth::user()->quyen_in_phieu)
                            <a class="text-title-item" href="{{route('phieu-xu-ly.index')}}">
                                <p>In phiếu xử lý văn bản
                                    <button
                                        class="btn br-10 btn-orange btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $tongPhieuChoIn }}</button>
                                </p>
                            </a>
                        @endif
                        <a class="text-title-item" href="{{ route('van-ban-moi-nhan-den-don-vi.index') }}">
                            <p> Văn bản đến chờ xử lý
                                <button
                                    class="btn br-10 btn-info btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanDenChoXuLy }}</button>
                            </p>
                        </a>
                        @if (auth::user()->vai_tro == CAP_TRUONG)
                            <a class="text-title-item" href="{{ route('phieu-chuyen-van-ban.index') }}">
                                <p>D/s phiếu chuyển VB chờ ký
                                    <button
                                        class="btn br-10 btn-red btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $danhSachPhieuChuyenChoKy }}</button>
                                </p>
                            </a>
                        @endif
                        @if(auth::user()->vai_tro  != CHUYEN_VIEN)
                            <a class="text-title-item" href="{{ route('van-ban-den-dang-xu-ly.index', 'qua_han=1') }}">
                                <p> Văn bản quá hạn đang xử lý
                                    <button
                                        class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanDenDangXuLy }}</button>
                                </p>
                            </a>
                            <a class="text-title-item" href="{{route('gia-han-van-ban.index')}}">
                                <p>Văn bản cấp dưới xin gia hạn
                                    <button
                                        class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanDonViGiaHan }}</button>
                                </p>
                            </a>
{{--                            <a class="text-title-item" href="{{route('van-ban-quan-trong.index')}}">--}}
{{--                                <p>Văn bản quan trọng--}}
{{--                                    <button--}}
{{--                                        class="btn br-10 btn-blue-dark btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $danhSachVanBanQuanTrong }}</button>--}}
{{--                                </p>--}}
{{--                            </a>--}}
                        @endif
                        @if (auth::user()->vai_tro == CAP_PHO)
                            <a class="text-title-item" href="{{route('van_ban_lanh_dao_phoi_hop.index')}}">
                                <p>Văn bản xem để biết
                                    <button
                                        class="btn br-10 btn-purple btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanLanhDaoXemDeBiet }}</button>
                                </p>
                            </a>
                        @endif

                        @if (auth::user()->vai_tro == CAP_TRUONG)
                            <a class="text-title-item" href="{{route('van-ban-den-hoan-thanh.cho-duyet')}}">
                                <p>VB hoàn thành chờ duyệt
                                    <button
                                        class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanHoanThanhChoDuyet }}</button>
                                </p>
                            </a>
                        @endif

                        <a class="text-title-item" href="{{route('van-ban-den-phoi-hop.index')}}">
                            <p>VB đến đơn vị PH chờ xử lý
                                <button
                                    class="btn br-10 btn-pink btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanDenDonViPhoiHop }}</button>
                            </p>
                        </a>
                        @if (auth::user()->vai_tro == CHUYEN_VIEN)
                            <a class="text-title-item" href="{{route('van_ban_den_chuyen_vien.index')}}">
                                <p>VB đến chuyên viên PH chờ xử lý
                                    <button
                                        class="btn br-10 btn-teal btn-circle waves-effect waves-light btn-sm pull-right count-item">{{  $vanBanDenChuyenVienPhoiHop }}</button>
                                </p>
                            </a>
                        @endif
                    @endif
                </div>
                <div class="col-md-5 ">
                    <div id="piechart"></div>
                </div>
            </div>

        </div>
    </div>
</div>
