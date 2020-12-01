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
            <li class="header">MAIN NAVIGATION</li>

            @role('admin')
            @include('admin::layouts.components.sidebar_admin')
            @endrole
            @hasanyrole('văn thư đơn vị|văn thư huyện')
            @include('admin::layouts.components.sidebar_van_thu')
            @endrole
            @role('tham mưu')
                @include('admin::layouts.components.sidebar_tham_muu')
            @endrole
            @hasanyrole('chủ tịch|phó chủ tịch')
                @include('admin::layouts.components.sidebar_lanh_dao')
            @endrole
            @role('chuyên viên')
                @include('admin::layouts.components.sidebar_chuyen_vien')
            @endrole
            @role('Trưởng phòng')
                @include('admin::layouts.components.sidebar_truong_phong')
            @endrole
            @role('Phó phòng')
                @include('admin::layouts.components.sidebar_pho_phong')
            @endrole
            {{--            @can('thêm dự thảo văn bản')--}}
            {{--            @endcan--}}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

