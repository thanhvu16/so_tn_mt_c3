<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ !empty(auth::user()->anh_dai_dien) ? getUrlFile(auth::user()->anh_dai_dien) : asset('images/default-user.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth::user()->ho_ten ?? 'N/A' }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU CHỨC NĂNG</li>

            @role(QUAN_TRI_HT)
            @include('admin::layouts.components.sidebar_admin')
            @endrole
            @role(VAN_THU_DON_VI)
            @include('admin::layouts.components.sidebar_van_thu_don_vi')
            @endrole
            @role(VAN_THU_HUYEN)
            @include('admin::layouts.components.sidebar_van_thu_huyen')
            @endrole
            @if(auth::user()->hasRole([CHANH_VAN_PHONG]))
                @include('admin::layouts.components.sidebar_tham_muu')
            @endif
            @if(auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]))
                @include('admin::layouts.components.sidebar_lanh_dao')
            @endif
            @role(CHUYEN_VIEN)
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_chuyen_vien')
            @endrole
            @if(auth::user()->hasRole([TRUONG_PHONG, TRUONG_BAN]))
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_truong_phong')
            @endif
            @if(auth::user()->hasRole([PHO_PHONG, PHO_TRUONG_BAN]))
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_pho_phong')
            @endif
            @role(PHO_CHANH_VAN_PHONG)
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_pho_chanh_van_phong')
                @include('admin::layouts.components.sidebar_chanh_van_phong')
            @endrole
            @role(CHANH_VAN_PHONG)
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_chanh_van_phong')
            @endrole


{{--            @can(\App\Common\AllPermission::xemLichCongTac())--}}
{{--            <li class="{{ Route::is('lich-cong-tac.index') }}">--}}
{{--                <a href="{{ route('lich-cong-tac.index') }}">--}}
{{--                    <i class="fa fa-calendar"></i> <span>Lịch công tác</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            @endcan--}}

            @unlessrole(QUAN_TRI_HT)

                <li class="treeview {{ Route::is('lich-cong-tac.index') || Route::is('tham-du-cuoc-hop.index')|| Route::is('thong-ke-tieu-chi-cuoc-hop.index') ? 'active menu-open' : '' }} }} ">
                    <a href="#">
                        <i class="fa fa-calendar"></i> <span>Lịch công tác</span>
                        <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::is('lich-cong-tac.index') ? 'active' : '' }}"><a href="{{ route('lich-cong-tac.index') }}"><i class="fa fa-circle-o"></i>Lịch công tác cá nhân</a></li>
                        <li class="{{ Route::is('tham-du-cuoc-hop.index') ? 'active' : '' }}"><a href="{{ route('tham-du-cuoc-hop.index') }}"><i class="fa fa-circle-o"></i>Cuộc họp được mời tham dự</a></li>
                        <li class="{{ Route::is('thong-ke-tieu-chi-cuoc-hop.index') ? 'active' : '' }}"><a href="{{ route('thong-ke-tieu-chi-cuoc-hop.index') }}"><i class="fa fa-circle-o"></i>Tk các tiêu chí cuộc họp</a></li>
                    </ul>
                </li>

                <li class="{{ Route::is('bao_cao_thong_ke.index') }}">
                    <a href="{{ route('bao_cao_thong_ke.index') }}">
                        <i class="fa fa-pie-chart"></i> <span>Báo cáo thống kê</span>
                  </a>
                </li>
            @endunlessrole
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

