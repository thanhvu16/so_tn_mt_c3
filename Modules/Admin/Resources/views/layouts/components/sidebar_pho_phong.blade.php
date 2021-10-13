
@if (auth::user()->hasRole([PHO_PHONG]) && auth::user()->donVi->parent_id == 0)
    <li class="treeview {{ Route::is('vanBanDonVi')  ? 'active menu-open' : '' }} }} ">
        <a href="#">
            <i class="fa fa-file-text"></i> <span>Văn bản đến đơn vi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Route::is('vanBanDonVi') ? 'active' : '' }}"><a href="{{ route('vanBanDonVi') }}"><i
                        class="fa fa-circle-o"></i>Danh sách</a></li>
        </ul>
    </li>
    <li class="treeview {{ Route::is('giayMoiDonVi')  ? 'active menu-open' : '' }} }} ">
        <a href="#">
            <i class="fa  fa-hospital-o"></i> <span>Giấy mời đến đơn vi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Route::is('giayMoiDonVi') ? 'active' : '' }}"><a href="{{ route('giayMoiDonVi') }}"><i
                        class="fa fa-circle-o"></i>Danh sách</a></li>
        </ul>
    </li>
@endif
<li class="treeview {{ Route::is('van-ban-di.index') || Route::is('van-ban-di.create') || Route::is('van-ban-di.edit') || Route::is('danh_sach_vb_di_cho_duyet')
|| Route::is('vb_di_tra_lai') || Route::is('danhsachgopy') || Route::is('vb_di_da_duyet') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đi</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-di.index') ? 'active' : '' }}"><a href="{{ route('van-ban-di.index') }}"><i class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('van-ban-di.create') ? 'active' : '' }}"><a href="{{ route('van-ban-di.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>
        <li class="{{ Route::is('danh_sach_vb_di_cho_duyet') ? 'active' : '' }}"><a href="{{ route('danh_sach_vb_di_cho_duyet') }}"><i class="fa fa-circle-o"></i> Văn bản đi chờ duyệt</a></li>
        <li class="{{ Route::is('vb_di_da_duyet') ? 'active' : '' }}"><a href="{{ route('vb_di_da_duyet') }}"><i class="fa fa-circle-o"></i> Văn bản đi đã duyệt</a></li>
        <li class="{{ Route::is('vb_di_tra_lai') ? 'active' : '' }}"><a href="{{ route('vb_di_tra_lai') }}"><i class="fa fa-circle-o"></i> Văn bản đi bị trả lại</a></li>
        <li class="{{ Route::is('danhsachgopy') ? 'active' : '' }}"><a href="{{ route('danhsachgopy') }}"><i class="fa fa-circle-o"></i>Góp ý văn bản</a></li>
    </ul>
</li>
<li class="treeview {{ Route::is('cong-viec-don-vi.index') || Route::is('cong-viec-don-vi.dang-xu-ly')
|| Route::is('cong-viec-don-vi.can-bo-xem-de-biet')|| Route::is('cong-viec-don-vi-phoi-hop.index')|| Route::is('cong-viec-don-vi-phoi-hop.dang-xu-ly')
|| Route::is('cong-viec-don-vi.hoan-thanh')|| Route::is('cong-viec-hoan-thanh.cho-duyet')|| Route::is('gia-han-cong-viec.index')
 || Route::is('cong-viec-don-vi.edit')|| Route::is('cong-viec-don-vi-phoi-hop.da-xu-ly') || Route::is('cong-viec-don-vi.da-xu-ly')|| Route::is('congViecDaDeXuat')|| Route::is('congViecDeXuat') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-expeditedssl"></i> <span>Công việc phòng ban</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('cong-viec-don-vi.index') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.index') }}"><i class="fa fa-circle-o"></i>Công việc chờ xử lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi.dang-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.dang-xu-ly') }}"><i class="fa fa-circle-o"></i>Công việc đang xử lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi.da-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.da-xu-ly') }}"><i class="fa fa-circle-o"></i> Công việc đã xử lý</a>
        </li>
        <li class="{{ Route::is('gia-han-cong-viec.index') ? 'active' : '' }}"><a
                href="{{ route('gia-han-cong-viec.index') }}"><i class="fa fa-circle-o"></i>Công việc xin gia hạn</a></li>
        <li class="{{ Route::is('cong-viec-hoan-thanh.cho-duyet') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-hoan-thanh.cho-duyet') }}"><i class="fa fa-circle-o"></i>CV hoàn thành chờ duyệt</a></li>
        <li class="{{ Route::is('cong-viec-don-vi.can-bo-xem-de-biet') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.can-bo-xem-de-biet') }}"><i class="fa fa-circle-o"></i>CV Xem để biết </a></li>
        <li class="{{ Route::is('cong-viec-don-vi.hoan-thanh') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.hoan-thanh') }}"><i class="fa fa-circle-o"></i>CV hoàn thành </a></li>
        <hr style="border: 1px dashed #fda709;margin-top: 8px;margin-bottom: 8px;">
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.index') ? 'active' : '' }}"><a href="{{ route('cong-viec-don-vi-phoi-hop.index') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH chờ xử lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.dang-xu-ly') ? 'active' : '' }}"><a href="{{ route('cong-viec-don-vi-phoi-hop.dang-xu-ly') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH đang xử lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.da-xu-ly') ? 'active' : '' }}"><a href="{{ route('cong-viec-don-vi-phoi-hop.da-xu-ly') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH đã xử lý</a></li>
        {{--        <li class="{{ Route::is('thongkephongthang') ? 'active' : '' }}"><a href="{{ route('thongkephongthang') }}"><i class="fa fa-circle-o"></i> Thống kê đánh giá phòng</a></li>--}}
        <hr style="border: 1px dashed #fda709;margin-top: 6px;margin-bottom: 6px;">
        <li class="{{ Route::is('congViecDeXuat') ? 'active' : '' }}"><a
                href="{{ route('congViecDeXuat') }}"><i class="fa fa-circle-o"></i> Tạo đề xuất công việc</a></li>
        <li class="{{ Route::is('congViecDaDeXuat') ? 'active' : '' }}"><a
                href="{{ route('congViecDaDeXuat') }}"><i class="fa fa-circle-o"></i> Công việc đã đề xuất</a></li>
    </ul>
</li>
<li class="{{ Route::is('ho-so-cong-viec.create') || Route::is('ho-so-cong-viec.index') ? 'active' : '' }} ">
    <a href="{{route('ho-so-cong-viec.index')}}">
        <i class="fa fa-folder" ></i> <span>File hồ sơ công việc</span>
        <span class="pull-right-container">

            </span>
    </a>
</li>
<li class="treeview {{ Route::is('danh-gia-can-bo.index') || Route::is('captrendanhgia') || Route::is('thongkephongthang') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-users"></i> <span>Đánh giá cán bộ</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('danh-gia-can-bo.index') ? 'active' : '' }}"><a
                href="{{ route('danh-gia-can-bo.index') }}"><i class="fa fa-circle-o"></i>Cá nhân tự đánh giá</a>
        </li>
        <li class="{{ Route::is('captrendanhgia') ? 'active' : '' }}"><a
                href="{{ route('captrendanhgia') }}"><i class="fa fa-circle-o"></i>Cấp trên đánh giá</a></li>
        <li class="{{ Route::is('thongkephongthang') ? 'active' : '' }}"><a href="{{ route('thongkephongthang') }}"><i class="fa fa-circle-o"></i> Thống kê đánh giá phòng</a></li>
    </ul>
</li>
<li class="treeview {{ Route::is('bao_cao_thong_ke.index') || Route::is('thongkevbphong')|| Route::is('thong-ke-cap-duoi-lanh-dao.index')  ? 'active menu-open' : '' }} ">
    <a href="#">
        <i class="fa fa-pie-chart"></i> <span>Báo cáo thống kê</span>
        <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
    </a>
    <ul class="treeview-menu">
{{--        <li class="{{ Route::is('bao_cao_thong_ke.index') ? 'active' : '' }}"><a href="{{ route('bao_cao_thong_ke.index') }}"><i class="fa fa-circle-o"></i>Biểu đồ thống kê</a></li>--}}
        @can(\App\Common\AllPermission::thongKeVanBanChiCuc())
{{--            <li class="{{ Route::is('thongkevbphong') ? 'active' : '' }}"><a href="{{ route('thongkevbphong') }}"><i class="fa fa-circle-o"></i>Thống kê văn bản phòng</a></li>--}}
        @endcan
{{--        <li class="{{ Route::is('thong-ke-cap-duoi-lanh-dao.index') ? 'active' : '' }}"><a href="{{ route('thong-ke-cap-duoi-lanh-dao.index') }}"><i class="fa fa-circle-o"></i>Thống kê chi tiết cấp dưới</a></li>--}}
    </ul>
</li>
