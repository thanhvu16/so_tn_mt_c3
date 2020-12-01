<li class="treeview {{ Route::is('van-ban-den.index') || Route::is('van-ban-den.create') || Route::is('van-ban-den.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-den.index') ? 'active' : '' }}"><a href="{{ route('van-ban-den.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
{{--        <li class="{{ Route::is('van-ban-den.create') ? 'active' : '' }}"><a href="{{ route('van-ban-den.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>--}}
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('giay-moi-den.index') || Route::is('giay-moi-den.create') || Route::is('giay-moi-den.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa  fa-hospital-o"></i> <span>Giấy mời đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('giay-moi-den.index') ? 'active' : '' }}"><a href="{{ route('giay-moi-den.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
{{--        <li class="{{ Route::is('giay-moi-den.create') ? 'active' : '' }}"><a href="{{ route('giay-moi-den.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>--}}
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('giay-moi-di.index') || Route::is('giay-moi-di.create') || Route::is('giay-moi-di.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa  fa-hospital-o"></i> <span>Giấy mời đi</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('giay-moi-di.index') ? 'active' : '' }}"><a href="{{ route('giay-moi-di.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('dacoso') ? 'active' : '' }}"><a href="{{ route('dacoso') }}"><i class="fa fa-circle-o"></i>Danh sách test</a></li>
        <li class="{{ Route::is('giay-moi-di.create') ? 'active' : '' }}"><a href="{{ route('giay-moi-di.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('van-ban-di.index') || Route::is('van-ban-di.create') || Route::is('van-ban-di.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đi</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-di.index') ? 'active' : '' }}"><a href="{{ route('van-ban-di.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('van-ban-di.create') ? 'active' : '' }}"><a href="{{ route('van-ban-di.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
        <li class="{{ Route::is('danh_sach_vb_di_cho_duyet') ? 'active' : '' }}"><a href="{{ route('danh_sach_vb_di_cho_duyet') }}"><i class="fa fa-circle-o"></i> văn bản đi chờ duyệt</a></li>
    </ul>
</li>
<li class="treeview {{ Route::is('du-thao-van-ban.index') || Route::is('Danhsachduthao') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-balance-scale"></i> <span>Dự thảo văn bản đi</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('du-thao-van-ban.index') ? 'active' : '' }}"><a href="{{ route('du-thao-van-ban.index') }}"><i class="fa fa-circle-o"></i>Nhập mới dự thảo</a></li>
        <li class="{{ Route::is('Danhsachduthao') ? 'active' : '' }}"><a href="{{ route('Danhsachduthao') }}"><i class="fa fa-circle-o"></i>Danh sách cá nhân dự thảo</a></li>
        <li class="{{ Route::is('danhsachgopy') ? 'active' : '' }}"><a href="{{ route('danhsachgopy') }}"><i class="fa fa-circle-o"></i>Góp ý văn bản</a></li>
    </ul>
</li>
