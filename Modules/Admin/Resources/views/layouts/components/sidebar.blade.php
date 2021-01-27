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

            @role('admin')
            @include('admin::layouts.components.sidebar_admin')
            @endrole
            @hasanyrole('văn thư đơn vị')
            @include('admin::layouts.components.sidebar_van_thu_don_vi')
            @endrole
            @hasanyrole('văn thư huyện')
            @include('admin::layouts.components.sidebar_van_thu_huyen')
            @endrole
            @hasanyrole('tham mưu|chánh văn phòng')
                @include('admin::layouts.components.sidebar_tham_muu')
            @endrole
            @hasanyrole('chủ tịch|phó chủ tịch')
                @include('admin::layouts.components.sidebar_lanh_dao')
            @endrole
            @role(CHUYEN_VIEN)
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_chuyen_vien')
            @endrole
            @hasanyrole('trưởng phòng|trưởng ban')
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_truong_phong')
            @endrole
            @hasanyrole('phó phòng|phó trưởng ban')
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_pho_phong')
            @endrole
            @role('phó chánh văn phòng')
                @include('admin::layouts.components.dieu_hanh_cv')
                @include('admin::layouts.components.sidebar_pho_chanh_van_phong')
                @include('admin::layouts.components.sidebar_chanh_van_phong')
            @endrole
            @role('chánh văn phòng')
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

            @unlessrole(ADMIN)
                <li class="treeview {{ Route::is('lich-cong-tac.index') || Route::is('tham-du-cuoc-hop.index') ? 'active menu-open' : '' }} }} ">
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

