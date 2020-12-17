<li class="treeview {{ Route::is('van-ban-lanh-dao-xu-ly.index') || Route::is('phan-loai-van-ban.da_phan_loai')
 || Route::is('gia-han-van-ban.index') || Route::is('van-ban-den-don-vi.dang_xu_ly') ||
  Route::is('van-ban-den-hoan-thanh.index') || Route::is('van-ban-den-don-vi.xem_de_biet') ||
   Route::is('van-ban-den-don-vi.quan_trong') ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-th" aria-hidden="true"></i> <span>Hồ sơ công việc</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-lanh-dao-xu-ly.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-lanh-dao-xu-ly.index') }}"><i class="fa fa-circle-o"></i>VB chờ xử lý</a>
        </li>
        <li class="{{ Route::is('phan-loai-van-ban.da_phan_loai') ? 'active' : '' }}"><a
                href="{{ route('phan-loai-van-ban.da_phan_loai') }}"><i class="fa fa-circle-o"></i>VB đã chỉ đạo</a>
        </li>
        <li class="{{ Route::is('van-ban-den-don-vi.dang_xu_ly') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.dang_xu_ly') }}"><i class="fa fa-circle-o"></i>VB đang xử lý</a>
        </li>
        <li class="{{ Route::is('gia-han-van-ban.index') ? 'active' : '' }}"><a
                href="{{ route('gia-han-van-ban.index') }}"><i class="fa fa-circle-o"></i>VB xin gia hạn</a>
        </li>

        <li class="{{ Route::is('van-ban-den-don-vi.xem_de_biet') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.xem_de_biet') }}"><i class="fa fa-circle-o"></i>VB xem để biết</a>
        </li>

        <li class="{{ Route::is('van-ban-den-don-vi.quan_trong') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.quan_trong') }}"><i class="fa fa-circle-o"></i>VB quan trọng</a>
        </li>

        <li class="{{ Route::is('van-ban-den-hoan-thanh.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-hoan-thanh.index') }}"><i class="fa fa-circle-o"></i>VB hoàn thành</a>
        </li>
    </ul>
</li>
<li class="treeview {{ Route::is('van-ban-di.index') || Route::is('van-ban-di.create') || Route::is('van-ban-di.edit') || Route::is('danh_sach_vb_di_cho_duyet') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đi</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-di.index') ? 'active' : '' }}"><a href="{{ route('van-ban-di.index') }}"><i
                    class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('van-ban-di.create') ? 'active' : '' }}"><a href="{{ route('van-ban-di.create') }}"><i
                    class="fa fa-circle-o"></i>Thêm mới</a></li>
        <li class="{{ Route::is('danh_sach_vb_di_cho_duyet') ? 'active' : '' }}"><a
                href="{{ route('danh_sach_vb_di_cho_duyet') }}"><i class="fa fa-circle-o"></i> văn bản đi chờ duyệt</a>
        </li>
        <li class="{{ Route::is('vb_di_da_duyet') ? 'active' : '' }}"><a href="{{ route('vb_di_da_duyet') }}"><i
                    class="fa fa-circle-o"></i> văn bản đi đã duyệt</a></li>
    </ul>
</li>
<li class="{{ Route::is('ho-so-cong-viec.create') || Route::is('ho-so-cong-viec.index') ? 'active' : '' }} ">
    <a href="{{route('ho-so-cong-viec.index')}}">
        <i class="fa fa-folder"></i> <span>File hồ sơ công việc</span>
        <span class="pull-right-container">

            </span>
    </a>
</li>
<li class="treeview {{ Route::is('danh-gia-can-bo-c2.index') || Route::is('captrendanhgiac2') || Route::is('thongkephongthang') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-users"></i> <span>Đánh giá cán bộ</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('danh-gia-can-bo-c2.index') ? 'active' : '' }}"><a
                href="{{ route('danh-gia-can-bo-c2.index') }}"><i class="fa fa-circle-o"></i>Cá nhân tự đánh giá</a>
        </li>
        <li class="{{ Route::is('captrendanhgiac2') ? 'active' : '' }}"><a href="{{ route('captrendanhgiac2') }}"><i
                    class="fa fa-circle-o"></i>Cấp trên đánh giá</a></li>
        <li class="{{ Route::is('thongkephongthang') ? 'active' : '' }}"><a href="{{ route('thongkephongthang') }}"><i
                    class="fa fa-circle-o"></i>Thống kê đánh giá phòng</a></li>
    </ul>
</li>
