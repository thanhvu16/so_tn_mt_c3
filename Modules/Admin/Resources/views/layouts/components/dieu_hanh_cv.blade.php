<li class="treeview {{ Route::is('van-ban-den-don-vi.index') || Route::is('van_ban_don_vi.da_chi_dao')
 || Route::is('gia-han-van-ban.index') || route::is('van-ban-den-hoan-thanh.cho-duyet') || Route::is('van_ban_den_chuyen_vien.index')
 || Route::is('van-ban-den-hoan-thanh.index') || Route::is('van_ban_den_chuyen_vien.da_xu_ly') || Route::is('van-ban-den-phoi-hop.index')
  || Route::is('van-ban-den-phoi-hop.dang-xu-ly') || Route::is('van-ban-den-phoi-hop.da-xu-ly') || Route::is('van-ban-den-don-vi.dang_xu_ly')
   || Route::is('van-ban-den-don-vi.xem_de_biet') || Route::is('duyet-van-ban-cap-duoi-trinh') || Route::is('van_ban_tra_lai.cho_duyet') ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-th" aria-hidden="true"></i> <span>Xử lý văn bản đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-den-don-vi.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.index') }}"><i class="fa fa-circle-o"></i>VB chờ xử lý</a>
        </li>
        <li class="{{ Route::is('van_ban_tra_lai.cho_duyet') ? 'active' : '' }}"><a
                href="{{ route('van_ban_tra_lai.cho_duyet') }}"><i class="fa fa-circle-o"></i>VB đã gửi trả lại</a>
        </li>
        @hasanyrole('trưởng phòng|phó trưởng phòng|tp đơn vị cấp 2|phó tp đơn vị cấp 2')
        <li class="{{ Route::is('van_ban_don_vi.da_chi_dao') ? 'active' : '' }}"><a
                href="{{ route('van_ban_don_vi.da_chi_dao') }}"><i class="fa fa-circle-o"></i>VB đã chỉ đạo</a>
        </li>
        <li class="{{ Route::is('gia-han-van-ban.index') ? 'active' : '' }}"><a
                href="{{ route('gia-han-van-ban.index') }}"><i class="fa fa-circle-o"></i>VB xin gia hạn</a>
        </li>
        @endrole
        @hasanyrole('phó trưởng phòng|phó tp đơn vị cấp 2')
        <li class="{{ Route::is('van-ban-den-don-vi.xem_de_biet') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.xem_de_biet') }}"><i class="fa fa-circle-o"></i>VB chỉ đạo, giám sát</a>
        </li>
        @endrole
        <li class="{{ Route::is('van-ban-den-don-vi.dang_xu_ly') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.dang_xu_ly') }}"><i class="fa fa-circle-o"></i>VB đang xử lý</a>
        </li>
        <hr class="hr-line">
        @hasanyrole('trưởng phòng|tp đơn vị cấp 2|chánh văn phòng|phó trưởng phòng|phó tp đơn vị cấp 2|phó chánh văn phòng')
        <li class="{{ Route::is('duyet-van-ban-cap-duoi-trinh') ? 'active' : '' }}"><a
                href="{{ route('duyet-van-ban-cap-duoi-trinh') }}"><i class="fa fa-circle-o"></i>Duyệt VB cấp dưới trình</a>
        </li>
        @endrole

        @hasanyrole('phó trưởng phòng|phó tp đơn vị cấp 2|phó chánh văn phòng|chuyên viên')
        <li class="{{ Route::is('van-ban-den-hoan-thanh.cho-duyet') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-hoan-thanh.cho-duyet') }}"><i class="fa fa-circle-o"></i>VB hoàn thành chờ duyệt</a>
        </li>
        @endrole

        <li class="{{ Route::is('van-ban-den-hoan-thanh.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-hoan-thanh.index') }}"><i class="fa fa-circle-o"></i>VB hoàn thành</a>
        </li>
        @role('chuyên viên')
        <hr class="hr-line">
        <li class="{{ Route::is('van_ban_den_chuyen_vien.index') ? 'active' : '' }}"><a
                href="{{ route('van_ban_den_chuyen_vien.index') }}"><i class="fa fa-circle-o"></i>VB đến chuyên viên PH</a>
        </li>

        <li class="{{ Route::is('van_ban_den_chuyen_vien.da_xu_ly') ? 'active' : '' }}"><a
                href="{{ route('van_ban_den_chuyen_vien.da_xu_ly', 'status=1') }}"><i class="fa fa-circle-o"></i>VB chuyên viên PH hoàn thành</a>
        </li>
        @endrole
        <hr class="hr-line">
        <li class="{{ Route::is('van-ban-den-phoi-hop.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-phoi-hop.index') }}"><i class="fa fa-circle-o"></i>VB đơn vị phối hợp chờ xử lý</a>
        </li>
        <li class="{{ Route::is('van-ban-den-phoi-hop.dang-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-phoi-hop.dang-xu-ly', 'chuyen_tiep=1') }}"><i class="fa fa-circle-o"></i>VB đơn vị phối hợp đang xử lý</a>
        </li>
        <li class="{{ Route::is('van-ban-den-phoi-hop.da-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-phoi-hop.da-xu-ly') }}"><i class="fa fa-circle-o"></i>VB đơn vị phối hợp đã xử lý</a>
        </li>
    </ul>
</li>
