@if (auth::user()->hasRole([CHUYEN_VIEN]) && auth::user()->donVi->parent_id == 0)
<li class="treeview {{ Route::is('vanBanDonVi')  ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-file-text"></i> <span>Văn bản đến</span>
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
        <i class="fa  fa-hospital-o"></i> <span>Giấy mời đến</span>
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
@if (auth::user()->hasRole([CHUYEN_VIEN]) && auth::user()->donVi->parent_id == 0)
    <li class="treeview {{ Route::is('vanBanLanhDaoSoqt') || Route::is('vanBanqt')  ? 'active menu-open' : '' }} }} ">
        <a href="#">
            <i class="fa fa-file-text"></i> <span>Văn bản quan trọng </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Route::is('vanBanLanhDaoSoqt') ? 'active' : '' }}"><a href="{{ route('vanBanLanhDaoSoqt') }}"><i
                        class="fa fa-circle-o"></i>VB quan trọng lãnh đạo sở</a></li>
            <li class="{{ Route::is('vanBanqt') ? 'active' : '' }}"><a href="{{ route('vanBanqt') }}"><i
                        class="fa fa-circle-o"></i>VB quan trọng đơn vị</a></li>
        </ul>
    </li>
@endif
@unlessrole(CHUYEN_VIEN)
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
        <li class="{{ Route::is('giay-moi-den.index') ? 'active' : '' }}"><a href="{{ route('giay-moi-den.index') }}"><i
                    class="fa fa-circle-o"></i>Danh sách</a></li>
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
        <li class="{{ Route::is('giay-moi-di.index') ? 'active' : '' }}"><a href="{{ route('giay-moi-di.index') }}"><i
                    class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('giay-moi-di.create') ? 'active' : '' }}"><a href="{{ route('giay-moi-di.create') }}"><i
                    class="fa fa-circle-o"></i>Thêm mới</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
@endunless
<li class="treeview {{ Route::is('van-ban-di.index') || Route::is('van-ban-di.create') || Route::is('van-ban-di.edit') ||
 Route::is('danh_sach_vb_di_cho_duyet') || Route::is('vb_di_tra_lai') || Route::is('vb_di_da_duyet') ? 'active menu-open' : '' }} }} ">
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

{{--<!--        <li class="{{ Route::is('danh_sach_vb_di_cho_duyet') ? 'active' : '' }}"><a--}}
{{--                href="{{ route('danh_sach_vb_di_cho_duyet') }}"><i class="fa fa-circle-o"></i> Văn bản đi chờ duyệt</a>--}}
{{--        </li>-->--}}
        <li class="{{ Route::is('vb_di_tra_lai') ? 'active' : '' }}"><a href="{{ route('vb_di_tra_lai') }}"><i
                    class="fa fa-circle-o"></i> Văn bản đi bị trả lại</a></li>
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
        <li class="{{ Route::is('giay-moi-di.index') ? 'active' : '' }}"><a href="{{ route('giay-moi-di.index') }}"><i
                    class="fa fa-circle-o"></i>Danh sách</a></li>
        <li class="{{ Route::is('giay-moi-di.create') ? 'active' : '' }}"><a href="{{ route('giay-moi-di.create') }}"><i
                    class="fa fa-circle-o"></i>Thêm mới</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
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
@if(auth::user()->id == 10538 || auth::user()->id==30599 )
<li class="treeview {{ Route::is('don-thu-khieu-lai.index') || Route::is('don-thu-khieu-lai.create') || Route::is('danhsachgopy') || Route::is('vanBanDiTaoChuaDuyet') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa  fa-gavel"></i> <span>Đơn thư khiếu nại</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('don-thu-khieu-lai.create') ? 'active' : '' }}"><a href="{{ route('don-thu-khieu-lai.create') }}"><i
                    class="fa fa-circle-o"></i>Nhập đơn thư khiếu nại</a></li>
        <li class="{{ Route::is('don-thu-khieu-lai.index') ? 'active' : '' }}"><a
                href="{{ route('don-thu-khieu-lai.index') }}"><i class="fa fa-circle-o"></i>D/s đơn thư khiếu nại</a></li>
    </ul>
</li>
@endif
<li class="treeview {{ Route::is('cong-viec-don-vi.index') || Route::is('cong-viec-don-vi.chuyen-vien-phoi-hop')||
 Route::is('cong-viec-don-vi.dang-xu-ly')|| Route::is('cong-viec-don-vi.da-xu-ly')|| Route::is('cong-viec-don-vi.chuyen-vien-da-phoi-hop') ||
 Route::is('cong-viec-don-vi-phoi-hop.index')|| Route::is('cong-viec-don-vi-phoi-hop.da-xu-ly') ||
 Route::is('cong-viec-don-vi.edit') || Route::is('cong-viec-don-vi.hoan-thanh') || Route::is('congViecDaDeXuat')|| Route::is('congViecDeXuat') ? 'active menu-open' : '' }} }} ">
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
                href="{{ route('cong-viec-don-vi.dang-xu-ly') }}"><i class="fa fa-circle-o"></i>Công việc đang xử lý</a>
        </li>
        <li class="{{ Route::is('cong-viec-don-vi.da-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.da-xu-ly') }}"><i class="fa fa-circle-o"></i> Công việc đã xử lý</a>
        </li>
        <li class="{{ Route::is('cong-viec-don-vi.hoan-thanh') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.hoan-thanh') }}"><i class="fa fa-circle-o"></i>CV hoàn thành </a></li>
        <hr style="border: 1px dashed #fda709;margin-top: 6px;margin-bottom: 6px;">
        <li class="{{ Route::is('cong-viec-don-vi.chuyen-vien-phoi-hop') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.chuyen-vien-phoi-hop') }}"><i class="fa fa-circle-o"></i> CV PH chờ xử
                lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi.chuyen-vien-da-phoi-hop') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi.chuyen-vien-da-phoi-hop') }}"><i class="fa fa-circle-o"></i> CV PH đã
                xử lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.index') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi-phoi-hop.index') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH chờ xử
                lý</a></li>
        <li class="{{ Route::is('cong-viec-don-vi-phoi-hop.da-xu-ly') ? 'active' : '' }}"><a
                href="{{ route('cong-viec-don-vi-phoi-hop.da-xu-ly') }}"><i class="fa fa-circle-o"></i> CV đơn vị PH đã
                xử lý</a></li>
        <hr style="border: 1px dashed #fda709;margin-top: 6px;margin-bottom: 6px;">
        <li class="{{ Route::is('congViecDeXuat') ? 'active' : '' }}"><a
                href="{{ route('congViecDeXuat') }}"><i class="fa fa-circle-o"></i> Tạo đề xuất công việc</a></li>
        <li class="{{ Route::is('congViecDaDeXuat') ? 'active' : '' }}"><a
                href="{{ route('congViecDaDeXuat') }}"><i class="fa fa-circle-o"></i> Công việc đã đề xuất</a></li>

    </ul>
</li>
<li class="{{ Route::is('ho-so-cong-viec.create') || Route::is('ho-so-cong-viec.index') ? 'active' : '' }} ">
    <a href="{{route('ho-so-cong-viec.index')}}">
        <i class="fa fa-folder"></i> <span>File hồ sơ công việc</span>
        <span class="pull-right-container">

            </span>
    </a>
</li>
<li class="treeview {{ Route::is('danh-gia-can-bo.index') || Route::is('danh-gia-can-bo.create') || Route::is('danh-gia-can-bo.edit') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-users"></i> <span>Đánh giá cán bộ</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('danh-gia-can-bo.index') ? 'active' : '' }}"><a
                href="{{ route('danh-gia-can-bo.index') }}"><i class="fa fa-circle-o"></i>Cá nhân tự đánh giá</a></li>
        {{--                    <li class="{{ Route::is('danh-gia-can-bo.create') ? 'active' : '' }}"><a href="{{ route('danh-gia-can-bo.create') }}"><i class="fa fa-circle-o"></i>Thêm mới</a></li>--}}
        {{--                    <li class="{{ Route::is('vanbandichoso') ? 'active' : '' }}"><a href="{{ route('vanbandichoso') }}"><i class="fa fa-circle-o"></i> Danh sách chờ số</a></li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('bao_cao_thong_ke.index') || Route::is('thongkevbphong') ? 'active menu-open' : '' }} ">
    <a href="#">
        <i class="fa fa-pie-chart"></i> <span>Báo cáo thống kê</span>
        <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
    </a>
    <ul class="treeview-menu">
{{--        <li class="{{ Route::is('bao_cao_thong_ke.index') ? 'active' : '' }}"><a href="{{ route('bao_cao_thong_ke.index') }}"><i class="fa fa-circle-o"></i>Biểu đồ thống kê</a></li>--}}
        @can(\App\Common\AllPermission::thongKeVanBanChiCuc())
            <li class="{{ Route::is('thongkevbphong') ? 'active' : '' }}"><a href="{{ route('thongkevbphong') }}"><i class="fa fa-circle-o"></i>Thống kê văn bản phòng</a></li>
        @endcan
    </ul>
</li>
{{--@hasanyrole('phó trưởng phòng|phó tp đơn vị cấp 2')--}}

{{--@endrole--}}
