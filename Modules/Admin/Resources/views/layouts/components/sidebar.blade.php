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
            <li class="{{ Route::is('don-vi.index') || Route::is('danhsachdonvi') ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-university" aria-hidden="true"></i> <span>Đơn Vị</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('danhsachdonvi') ? 'active' : '' }}"><a href="{{route('danhsachdonvi')}}"><i
                                class="fa fa-circle-o"></i> Danh sách</a></li>
                    <li class="{{ Route::is('don-vi.index') ? 'active' : '' }}"><a href="{{route('don-vi.index')}}"><i
                                class="fa fa-circle-o"></i> Thêm mới</a></li>
                </ul>
            </li>

            <li class="{{ Route::is('chuc-vu.index') || Route::is('danhsachchucvu') ? 'active' : '' }} treeview ">
                <a href="#">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i> <span>Chức Vụ</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('danhsachchucvu') ? 'active' : '' }}"><a href="{{route('danhsachchucvu')}}"><i
                                class="fa fa-circle-o"></i> Danh sách</a></li>
                    <li class="{{ Route::is('chuc-vu.index') ? 'active' : '' }}"><a href="{{route('chuc-vu.index')}}"><i
                                class="fa fa-circle-o"></i> Thêm mới</a></li>
                </ul>
            </li>

            <li class="{{ Route::is('so-van-ban.index') || Route::is('danhsachsovanban') ? 'active' : '' }} treeview ">
                <a href="#">
                    <i class="fa fa-book" aria-hidden="true"></i> <span>Sổ văn bản</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ Route::is('danhsachsovanban') ? 'active' : '' }}"><a
                            href="{{route('danhsachsovanban')}}"><i class="fa fa-circle-o"></i> Danh sách</a></li>
                    <li class="{{ Route::is('so-van-ban.index') ? 'active' : '' }}"><a
                            href="{{route('so-van-ban.index')}}"><i class="fa fa-circle-o"></i> Thêm mới</a></li>
                </ul>
            </li>
            <li class="{{ Route::is('loai-van-ban.index') || Route::is('danhsachloaivanban') ? 'active' : '' }} treeview ">
                <a href="#">
                    <i class="fa fa-database" aria-hidden="true"></i> <span>Loại văn bản</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ Route::is('danhsachloaivanban') ? 'active' : '' }}"><a
                            href="{{route('danhsachloaivanban')}}"><i class="fa fa-circle-o"></i> Danh sách</a></li>
                    <li class="{{ Route::is('loai-van-ban.index') ? 'active' : '' }}"><a
                            href="{{route('loai-van-ban.index')}}"><i class="fa fa-circle-o"></i> Thêm mới</a></li>
                </ul>
            </li>
            <li class="{{ Route::is('do-bao-mat.index') || Route::is('danhsachdobaomat') ? 'active' : '' }} treeview ">
                <a href="#">
                    <i class="fa fa-shield" aria-hidden="true"></i> <span>Độ bảo mật</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ Route::is('danhsachdobaomat') ? 'active' : '' }}"><a
                            href="{{route('danhsachdobaomat')}}"><i class="fa fa-circle-o"></i> Danh sách</a></li>
                    <li class="{{ Route::is('do-bao-mat.index') ? 'active' : '' }}"><a
                            href="{{route('do-bao-mat.index')}}"><i class="fa fa-circle-o"></i> Thêm mới</a></li>
                </ul>
            </li>
            <li class="{{ Route::is('do-khan-cap.index') || Route::is('danhsachdokhancap') ? 'active' : '' }} treeview ">
                <a href="#">
                    <i class="fa fa-bolt" aria-hidden="true"></i> <span>Độ khẩn cấp</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ Route::is('danhsachdokhancap') ? 'active' : '' }}"><a
                            href="{{route('danhsachdokhancap')}}"><i class="fa fa-circle-o"></i> Danh sách</a></li>
                    <li class="{{ Route::is('do-khan-cap.index') ? 'active' : '' }}"><a
                            href="{{route('do-khan-cap.index')}}"><i class="fa fa-circle-o"></i> Thêm mới</a></li>
                </ul>
            </li>
            <li class="treeview {{ Route::is('nguoi-dung.index') || Route::is('nguoi-dung.create') || Route::is('chuc-nang.index')
               || Route::is('vai-tro.index') ? 'active menu-open' : '' }} }} ">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Cấu hình hệ thống</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('nguoi-dung.index') ? 'active' : '' }}"><a href="{{ route('nguoi-dung.index') }}"><i class="fa fa-circle-o"></i> Người dùng</a></li>
                    <li class="{{ Route::is('vai-tro.index') ? 'active' : '' }}"><a href="{{ route('vai-tro.index') }}"><i class="fa fa-circle-o"></i>Quyền hạn</a></li>
{{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
                </ul>
            </li>
            @endrole
            @hasanyrole('văn thư đơn vị|văn thư huyện')
            <li class="treeview {{ Route::is('van-ban-den.index') || Route::is('van-ban-den.create') || Route::is('van-ban-den.edit') ? 'active menu-open' : '' }} }} ">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Văn bản đến</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('van-ban-den.index') ? 'active' : '' }}"><a href="{{ route('van-ban-den.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
                    <li class="{{ Route::is('van-ban-den.create') ? 'active' : '' }}"><a href="{{ route('van-ban-den.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
                    {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
                </ul>
            </li>
            @endrole
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

