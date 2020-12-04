<li class="treeview {{ Route::is('van-ban-den-don-vi.index') || Route::is('van_ban_don_vi.da_chi_dao')
 || Route::is('gia-han-van-ban.index') || route('van-ban-den-hoan-thanh.cho-duyet') ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-th" aria-hidden="true"></i> <span>Hồ sơ công việc</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-den-don-vi.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.index') }}"><i class="fa fa-circle-o"></i>VB chờ xử lý</a>
        </li>
        @hasanyrole('trưởng phòng|phó phòng')
        <li class="{{ Route::is('van_ban_don_vi.da_chi_dao') ? 'active' : '' }}"><a
                href="{{ route('van_ban_don_vi.da_chi_dao') }}"><i class="fa fa-circle-o"></i>VB đã chỉ đạo</a>
        </li>
        <li class="{{ Route::is('gia-han-van-ban.index') ? 'active' : '' }}"><a
                href="{{ route('gia-han-van-ban.index') }}"><i class="fa fa-circle-o"></i>VB xin gia hạn</a>
        </li>
        @endrole
        <li class="{{ Route::is('van-ban-den-hoan-thanh.cho-duyet') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-hoan-thanh.cho-duyet') }}"><i class="fa fa-circle-o"></i>VB hoàn thành chờ duyệt</a>
        </li>
    </ul>
</li>
