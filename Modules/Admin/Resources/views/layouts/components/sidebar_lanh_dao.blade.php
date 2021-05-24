<li class="treeview {{ Route::is('van-ban-lanh-dao-xu-ly.index') || Route::is('phan-loai-van-ban.da_phan_loai')
 || Route::is('gia-han-van-ban.index') || Route::is('van-ban-den-don-vi.dang_xu_ly') ||
  Route::is('van-ban-den-hoan-thanh.index') || Route::is('van-ban-den-don-vi.xem_de_biet') ||
   Route::is('van-ban-den-don-vi.quan_trong') || Route::is('van-ban-den-phoi-hop.index')||
   Route::is('van-ban-den-phoi-hop.da-xu-ly')|| Route::is('van_ban_tra_lai.cho_duyet') ||
   Route::is('van-ban-den-phoi-hop.dang-xu-ly') ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-th" aria-hidden="true"></i> <span>Xử lý văn bản đến</span>
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
        @if (!auth::user()->hasRole(CHU_TICH))
            <li class="{{ Route::is('van_ban_tra_lai.cho_duyet') ? 'active' : '' }}"><a
                    href="{{ route('van_ban_tra_lai.cho_duyet') }}"><i class="fa fa-circle-o"></i>VB đã gửi trả lại</a>
            </li>
        @endif
        @if (auth::user()->hasRole(CHU_TICH) && auth::user()->donVi->cap_xa != 0)
            <li class="{{ Route::is('van_ban_tra_lai.cho_duyet') ? 'active' : '' }}"><a
                    href="{{ route('van_ban_tra_lai.cho_duyet') }}"><i class="fa fa-circle-o"></i>VB đã gửi trả lại</a>
            </li>
        @endif
        <li class="{{ Route::is('van-ban-den-don-vi.dang_xu_ly') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.dang_xu_ly') }}"><i class="fa fa-circle-o"></i>VB đang xử lý</a>
        </li>
        <li class="{{ Route::is('gia-han-van-ban.index') ? 'active' : '' }}"><a
                href="{{ route('gia-han-van-ban.index') }}"><i class="fa fa-circle-o"></i>VB xin gia hạn</a>
        </li>
        <hr class="hr-line">
        <li class="{{ Route::is('van-ban-den-don-vi.xem_de_biet') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.xem_de_biet') }}"><i class="fa fa-circle-o"></i>VB chỉ đạo, giám sát</a>
        </li>

        <li class="{{ Route::is('van-ban-den-don-vi.quan_trong') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-don-vi.quan_trong') }}"><i class="fa fa-circle-o"></i>VB quan trọng</a>
        </li>

        <li class="{{ Route::is('van-ban-den-hoan-thanh.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-den-hoan-thanh.index') }}"><i class="fa fa-circle-o"></i>VB hoàn thành</a>
        </li>
        @if (!empty(auth::user()->donVi->cap_xa))
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
        @endif
    </ul>
</li>
<li class="treeview {{ Route::is('giayMoiLanhDaoXuLy') || Route::is('phan-loai-giay-moi.da_phan_loai')
 || Route::is('giaHanGiayMoi') || Route::is('giay-moi-den-don-vi.dang_xu_ly') ||
  Route::is('giay-moi-den-hoan-thanh.index') || Route::is('giay-moi-den-don-vi.xem_de_biet') ||
   Route::is('giay-moi-den-don-vi.quan_trong') || Route::is('giay-moi-den-phoi-hop.index')||
   Route::is('giay-moi-den-phoi-hop.da-xu-ly')|| Route::is('giay_moi_tra_lai.cho_duyet') ||
   Route::is('giay-moi-den-phoi-hop.dang-xu-ly') ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-th" aria-hidden="true"></i> <span>Xử lý giấy mời đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('giayMoiLanhDaoXuLy','type=1') ? 'active' : '' }}"><a
                href="{{ route('giayMoiLanhDaoXuLy','type=1') }}"><i class="fa fa-circle-o"></i>GM chờ xử lý</a>
        </li>
        <li class="{{ Route::is('phan-loai-giay-moi.da_phan_loai','type=1') ? 'active' : '' }}"><a
                href="{{ route('phan-loai-giay-moi.da_phan_loai','type=1') }}"><i class="fa fa-circle-o"></i>GM đã chỉ đạo</a>
        </li>
        @if (!auth::user()->hasRole(CHU_TICH))
            <li class="{{ Route::is('giay_moi_tra_lai.cho_duyet','type=1') ? 'active' : '' }}"><a
                    href="{{ route('giay_moi_tra_lai.cho_duyet','type=1') }}"><i class="fa fa-circle-o"></i>GM đã gửi trả lại</a>
            </li>
        @endif
        @if (auth::user()->hasRole(CHU_TICH) && auth::user()->donVi->cap_xa != 0)
            <li class="{{ Route::is('giay_moi_tra_lai.cho_duyet','type=1') ? 'active' : '' }}"><a
                    href="{{ route('giay_moi_tra_lai.cho_duyet','type=1') }}"><i class="fa fa-circle-o"></i>GM đã gửi trả lại</a>
            </li>
        @endif
        <li class="{{ Route::is('giay-moi-den-don-vi.dang_xu_ly','type=1') ? 'active' : '' }}"><a
                href="{{ route('giay-moi-den-don-vi.dang_xu_ly','type=1') }}"><i class="fa fa-circle-o"></i>GM đang xử lý</a>
        </li>
        <li class="{{ Route::is('giaHanGiayMoi','type=1') ? 'active' : '' }}"><a
                href="{{ route('giaHanGiayMoi','type=1') }}"><i class="fa fa-circle-o"></i>GM xin gia hạn</a>
        </li>
        <hr class="hr-line">
        <li class="{{ Route::is('giay-moi-den-don-vi.xem_de_biet','type=1') ? 'active' : '' }}"><a
                href="{{ route('giay-moi-den-don-vi.xem_de_biet','type=1') }}"><i class="fa fa-circle-o"></i>GM chỉ đạo, giám sát</a>
        </li>

        <li class="{{ Route::is('giay-moi-den-don-vi.quan_trong','type=1') ? 'active' : '' }}"><a
                href="{{ route('giay-moi-den-don-vi.quan_trong','type=1') }}"><i class="fa fa-circle-o"></i>GM quan trọng</a>
        </li>

        <li class="{{ Route::is('giay-moi-den-hoan-thanh.index','type=1') ? 'active' : '' }}"><a
                href="{{ route('giay-moi-den-hoan-thanh.index','type=1') }}"><i class="fa fa-circle-o"></i>GM hoàn thành</a>
        </li>
        @if (!empty(auth::user()->donVi->cap_xa))
            <hr class="hr-line">
            <li class="{{ Route::is('giay-moi-den-phoi-hop.index','type=1') ? 'active' : '' }}"><a
                    href="{{ route('giay-moi-den-phoi-hop.index','type=1') }}"><i class="fa fa-circle-o"></i>GM đơn vị phối hợp chờ xử lý</a>
            </li>
            <li class="{{ Route::is('giay-moi-den-phoi-hop.dang-xu-ly','type=1') ? 'active' : '' }}"><a
                    href="{{ route('giay-moi-den-phoi-hop.dang-xu-ly', 'chuyen_tiep=1'.'&type=1') }}"><i class="fa fa-circle-o"></i>GM đơn vị phối hợp đang xử lý</a>
            </li>
            <li class="{{ Route::is('giay-moi-den-phoi-hop.da-xu-ly','type=1') ? 'active' : '' }}"><a
                    href="{{ route('giay-moi-den-phoi-hop.da-xu-ly','type=1') }}"><i class="fa fa-circle-o"></i>GM đơn vị phối hợp đã xử lý</a>
            </li>
        @endif
    </ul>
</li>
<li class="treeview {{ Route::is('van-ban-di.index') || Route::is('van-ban-di.create') || Route::is('van-ban-di.edit') || Route::is('danh_sach_vb_di_cho_duyet')
 || Route::is('vb_di_da_duyet') ? 'active menu-open' : '' }} }} ">
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
                href="{{ route('danh_sach_vb_di_cho_duyet') }}"><i class="fa fa-circle-o"></i> Văn bản đi chờ duyệt</a>
        </li>
        <li class="{{ Route::is('vb_di_da_duyet') ? 'active' : '' }}"><a href="{{ route('vb_di_da_duyet') }}"><i
                    class="fa fa-circle-o"></i> Văn bản đi đã duyệt</a></li>
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

<li class="treeview {{ Route::is('bao_cao_thong_ke.index') || Route::is('thongkevbso')|| Route::is('thongkevbchicuc')|| Route::is('thong-ke-cap-duoi-lanh-dao.index') ? 'active menu-open' : '' }} ">
    <a href="#">
        <i class="fa fa-pie-chart"></i> <span>Báo cáo thống kê</span>
        <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('bao_cao_thong_ke.index') ? 'active' : '' }}"><a href="{{ route('bao_cao_thong_ke.index') }}"><i class="fa fa-circle-o"></i>Biểu đồ thống kê</a></li>
        @if(empty(auth::user()->cap_xa))
            <li class="{{ Route::is('thongkevbso') ? 'active' : '' }}"><a href="{{ route('thongkevbso') }}"><i class="fa fa-circle-o"></i>Thống kê văn bản đến Sở</a></li>
        @endif
        @if(auth::user()->cap_xa == 1)
            <li class="{{ Route::is('thongkevbchicuc') ? 'active' : '' }}"><a href="{{ route('thongkevbchicuc') }}"><i class="fa fa-circle-o"></i>Thống kê văn bản đến </a></li>
        @endif
        @if(auth::user()->hasRole([ PHO_CHU_TICH]))
        <li class="{{ Route::is('thong-ke-cap-duoi-lanh-dao.index') ? 'active' : '' }}"><a href="{{ route('thong-ke-cap-duoi-lanh-dao.index') }}"><i class="fa fa-circle-o"></i>Thống kê chi tiết cấp dưới</a></li>
        @endif
    </ul>
</li>
