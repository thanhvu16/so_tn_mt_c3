<li class="treeview {{ Route::is('van-ban-den.index') || Route::is('van-ban-den.create') || Route::is('van-ban-den.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đến</span>
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
<li class="treeview {{ Route::is('giay-moi-den.index') || Route::is('giay-moi-den.create') || Route::is('giay-moi-den.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa  fa-hospital-o"></i> <span>Giấy mời đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('giay-moi-den.index') ? 'active' : '' }}"><a href="{{ route('giay-moi-den.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('giay-moi-den.create') ? 'active' : '' }}"><a href="{{ route('giay-moi-den.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
