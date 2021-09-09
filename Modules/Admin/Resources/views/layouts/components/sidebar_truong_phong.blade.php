@unlessrole(TRUONG_PHONG)
<li class="treeview {{ Route::is('van-ban-den.index') || Route::is('van-ban-den.create') || Route::is('van-ban-den.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đến</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-den.index') ? 'active' : '' }}"><a href="{{ route('van-ban-den.index') }}"><i
                    class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('van-ban-den.create') ? 'active' : '' }}"><a href="{{ route('van-ban-den.create') }}"><i
                    class="fa fa-circle-o"></i>Thêm mới</a></li>
        <li class="{{ Route::is('don-vi-nhan-van-ban-den.index') ? 'active' : '' }}"><a
                href="{{ route('don-vi-nhan-van-ban-den.index') }}"><i class="fa fa-circle-o"></i>Danh sách chờ vào
                sổ</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
@endunless

<li class="treeview {{ Route::is('van-ban-di.index') || Route::is('van-ban-di.create') || Route::is('van-ban-di.edit') ||
Route::is('danh_sach_vb_di_cho_duyet') || Route::is('vb_di_da_duyet') || Route::is('vb_di_tra_lai') || Route::is('danhsachgopy') ? 'active menu-open' : '' }} }} ">
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
        <li class="{{ Route::is('vb_di_tra_lai') ? 'active' : '' }}"><a href="{{ route('vb_di_tra_lai') }}"><i
                    class="fa fa-circle-o"></i> Văn bản đi bị trả lại</a></li>
        <li class="{{ Route::is('danhsachgopy') ? 'active' : '' }}"><a href="{{ route('danhsachgopy') }}"><i
                    class="fa fa-circle-o"></i>Góp ý văn bản</a></li>
    </ul>
</li>
<li class="treeview {{ Route::is('du-thao-van-ban.index') || Route::is('Danhsachduthao') || Route::is('danhsachgopy') || Route::is('vanBanDiTaoChuaDuyet') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-balance-scale"></i> <span>Dự thảo văn bản đi</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('du-thao-van-ban.index') ? 'active' : '' }}"><a
                href="{{ route('du-thao-van-ban.index') }}"><i class="fa fa-circle-o"></i>Nhập mới dự thảo</a></li>
        <li class="{{ Route::is('Danhsachduthao') ? 'active' : '' }}"><a href="{{ route('Danhsachduthao') }}"><i
                    class="fa fa-circle-o"></i>Danh sách cá nhân dự thảo</a></li>
        <li class="{{ Route::is('danhsachgopy') ? 'active' : '' }}"><a href="{{ route('danhsachgopy') }}"><i
                    class="fa fa-circle-o"></i>Góp ý văn bản</a></li>
        <li class="{{ Route::is('vanBanDiTaoChuaDuyet') ? 'active' : '' }}"><a href="{{ route('vanBanDiTaoChuaDuyet') }}"><i
                    class="fa fa-circle-o"></i>Văn bản đã tạo </a></li>
    </ul>
</li>

<li class="treeview {{ Route::is('cong-viec-don-vi.index') || Route::is('cong-viec-don-vi.create')|| Route::is('cong-viec-hoan-thanh.cho-duyet')
|| Route::is('cong-viec-don-vi.hoan-thanh')||Route::is('cong-viec-don-vi-phoi-hop.index')||Route::is('cong-viec-don-vi-phoi-hop.da-xu-ly')||Route::is('cong-viec-don-vi-phoi-hop.dang-xu-ly')||
Route::is('cong-viec-don-vi.dang-xu-ly') || Route::is('gia-han-cong-viec.index')|| Route::is('cong-viec-don-vi.edit')|| Route::is('congViecDeXuatChoXuLy') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-expeditedssl"></i> <span>Công việc phòng ban</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('cong-viec-don-vi.create') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.create') }}"><i class="fa fa-circle-o"></i>Nhập công việc</a>
        </li>
        <li class="{{ Route::is('cong-viec-don-vi.index') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.index') }}"><i class="fa fa-circle-o"></i>Công việc chờ xử lý</a></li>
        <li class="{{ Route::is('congViecDeXuatChoXuLy') ? 'active' : '' }}"><a
                href="{{ route('congViecDeXuatChoXuLy') }}"><i class="fa fa-circle-o"></i>Công việc đề xuất chờ xử lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi.dang-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.dang-xu-ly') }}"><i class="fa fa-circle-o"></i>Công việc đang xử lý</a>
        </li>

        <li class="{{ Route::is('gia-han-cong-viec.index') ? 'active' : '' }}"><a
                href="{{ route('gia-han-cong-viec.index') }}"><i class="fa fa-circle-o"></i>Công việc xin gia hạn</a>
        </li>
        <li class="{{ Route::is('cong-viec-hoan-thanh.cho-duyet') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-hoan-thanh.cho-duyet') }}"><i class="fa fa-circle-o"></i>CV hoàn thành chờ
                duyệt</a></li>
        <li class="{{ Route::is('cong-viec-don-vi.hoan-thanh') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.hoan-thanh') }}"><i class="fa fa-circle-o"></i>CV hoàn thành </a></li>
        <hr style="border: 1px dashed #fda709;margin-top: 8px;margin-bottom: 8px;">
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.index') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi-phoi-hop.index') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH chờ xử
                lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.dang-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi-phoi-hop.dang-xu-ly') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH
                đang xử lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.da-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi-phoi-hop.da-xu-ly') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH đã
                xử lý</a></li>

        {{--        <li class="{{ Route::is('thongkephongthang') ? 'active' : '' }}"><a href="{{ route('thongkephongthang') }}"><i class="fa fa-circle-o"></i> Thống kê đánh giá phòng</a></li>--}}
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
        <li class="{{ Route::is('captrendanhgiac2') ? 'active' : '' }}"><a
                href="{{ route('captrendanhgiac2') }}"><i class="fa fa-circle-o"></i>Cấp trên đánh giá</a></li>
        <li class="{{ Route::is('thongkephongthang') ? 'active' : '' }}"><a href="{{ route('thongkephongthang') }}"><i
                    class="fa fa-circle-o"></i> Thống kê đánh giá phòng</a></li>
    </ul>
</li>
<li class="treeview {{ Route::is('bao_cao_thong_ke.index') || Route::is('thongkevbphong')|| Route::is('thong-ke-cap-duoi-lanh-dao.index')    ? 'active menu-open' : '' }} ">
    <a href="#">
        <i class="fa fa-pie-chart"></i> <span>Báo cáo thống kê</span>
        <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
    </a>
    <ul class="treeview-menu">
{{--        <li class="{{ Route::is('bao_cao_thong_ke.index') ? 'active' : '' }}"><a href="{{ route('bao_cao_thong_ke.index') }}"><i class="fa fa-circle-o"></i>Biểu đồ thống kê</a></li>--}}
{{--        <li class="{{ Route::is('thongkevbphong') ? 'active' : '' }}"><a href="{{ route('thongkevbphong') }}"><i class="fa fa-circle-o"></i>Thống kê văn bản phòng</a></li>--}}
        <li class="{{ Route::is('thong-ke-cap-duoi-lanh-dao.index') ? 'active' : '' }}"><a href="{{ route('thong-ke-cap-duoi-lanh-dao.index') }}"><i class="fa fa-circle-o"></i>Thống kê chi tiết cấp dưới</a></li>
    </ul>
</li>
