@can('xem hòm thư công')
<li class="{{ Route::is('dsvanbandentumail') || Route::is('vanbandentumail') ? 'active' : '' }} ">
    <a href="{{route('dsvanbandentumail')}}">
        <i class="fa fa-university" ></i> <span>Hòm thư công</span>
        <span class="pull-right-container">
            </span>
    </a>
</li>
@endcan
<li class="treeview {{ Route::is('van-ban-den.index') || Route::is('van-ban-den.create') || Route::is('van-ban-den.edit')|| Route::is('vanBanDonViGuiSo') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-den.index') ? 'active' : '' }}"><a href="{{ route('van-ban-den.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('van-ban-den.create') ? 'active' : '' }}"><a href="{{ route('van-ban-den.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
        <li class="{{ Route::is('vanBanDonViGuiSo') ? 'active' : '' }}"><a href="{{ route('vanBanDonViGuiSo') }}"><i class="fa fa-circle-o"></i>D/s văn bản đến trong đơn vị</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('giay-moi-den.index') || Route::is('giay-moi-den.create')|| Route::is('guiTinHoanGM') || Route::is('giay-moi-den.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa  fa-hospital-o"></i> <span>Giấy mời đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('giay-moi-den.index') ? 'active' : '' }}"><a href="{{ route('giay-moi-den.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('giay-moi-den.create') ? 'active' : '' }}"><a href="{{ route('giay-moi-den.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
{{--        <li class="{{ Route::is('guiTinHoanGM') ? 'active' : '' }}"><a href="{{ route('guiTinHoanGM') }}"><i class="fa fa-circle-o"></i>Gửi tin nhắn hoãn họp</a></li>--}}
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
        <li class="{{ Route::is('giay-moi-di.create') ? 'active' : '' }}"><a href="{{ route('giay-moi-di.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('van-ban-di.index') || Route::is('in-so-van-ban-di.index')|| Route::is('nhapVanBanDi') ||Route::is('van-ban-di.create') || Route::is('van-ban-di.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đi</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-di.index') ? 'active' : '' }}"><a href="{{ route('van-ban-di.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
{{--        <li class="{{ Route::is('van-ban-di.create') ? 'active' : '' }}"><a href="{{ route('van-ban-di.create') }}"><i class="fa fa-circle-o"></i>Thêm mới trình lãnh đạo</a></li>--}}
        <li class="{{ Route::is('nhapVanBanDi') ? 'active' : '' }}"><a href="{{ route('nhapVanBanDi') }}"><i class="fa fa-circle-o"></i>Thêm mới văn bản</a></li>
        <li class="{{ Route::is('vanbandichoso') ? 'active' : '' }}"><a href="{{ route('vanbandichoso') }}"><i class="fa fa-circle-o"></i> Danh sách chờ số</a></li>
    </ul>
</li>
<li class="{{ Route::is('ho-so-cong-viec.create') || Route::is('ho-so-cong-viec.index') ? 'active' : '' }} ">
    <a href="{{route('ho-so-cong-viec.index')}}">
        <i class="fa fa-folder" ></i> <span>File hồ sơ công việc</span>
        <span class="pull-right-container">

            </span>
    </a>
</li>
<li class="treeview {{ Route::is('danh-gia-can-bo-c2.index') || Route::is('danh-gia-can-bo-c2.create') || Route::is('danh-gia-can-bo-c2.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-users"></i> <span>Đánh giá cán bộ</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('danh-gia-can-bo-c2.index') ? 'active' : '' }}"><a href="{{ route('danh-gia-can-bo-c2.index') }}"><i class="fa fa-circle-o"></i>Cá nhân tự đánh giá</a></li>
        {{--                    <li class="{{ Route::is('danh-gia-can-bo.create') ? 'active' : '' }}"><a href="{{ route('danh-gia-can-bo.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>--}}
        {{--                    <li class="{{ Route::is('vanbandichoso') ? 'active' : '' }}"><a href="{{ route('vanbandichoso') }}"><i class="fa fa-circle-o"></i> Danh sách chờ số</a></li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('bao_cao_thong_ke.index')  ? 'active menu-open' : '' }} ">
    <a href="#">
        <i class="fa fa-pie-chart"></i> <span>Báo cáo thống kê</span>
        <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
    </a>
    <ul class="treeview-menu">
{{--        <li class="{{ Route::is('bao_cao_thong_ke.index') ? 'active' : '' }}"><a href="{{ route('bao_cao_thong_ke.index') }}"><i class="fa fa-circle-o"></i>Biểu đồ thống kê</a></li>--}}
        <li class="{{ Route::is('thongkevbso') ? 'active' : '' }}"><a href="{{ route('thongkevbso') }}"><i class="fa fa-circle-o"></i>Báo cáo thống kê VB đến</a></li>
    </ul>
</li>
