<li class="{{ Route::is('don-vi.index') || Route::is('danhsachdonvi') ? 'active' : '' }} ">
    <a href="{{route('danhsachdonvi')}}">
        <i class="fa fa-university" ></i> <span>Đơn Vị</span>
        <span class="pull-right-container">
{{--              <i class="fa fa-angle-left pull-right"></i>--}}
            </span>
    </a>
</li>
<li class="{{ Route::is('Nhom-don-vi.index')  ? 'active' : '' }} ">
    <a href="{{route('Nhom-don-vi.index')}}">
        <i class="fa fa-user-plus"></i> <span>Nhóm đơn Vị</span>
        <span class="pull-right-container">
{{--              <i class="fa fa-angle-left pull-right"></i>--}}
            </span>
    </a>
</li>

<li class="{{ Route::is('chuc-vu.index') || Route::is('danhsachchucvu') ? 'active' : '' }}  ">
    <a href="{{route('danhsachchucvu')}}">
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> <span>Chức Vụ</span>
        <span class="pull-right-container">
{{--              <i class="fa fa-angle-left pull-right"></i>--}}
            </span>
    </a>
</li>

<li class="{{ Route::is('so-van-ban.index') || Route::is('danhsachsovanban') ? 'active' : '' }}  ">
    <a href="{{route('danhsachsovanban')}}">
        <i class="fa fa-book" aria-hidden="true"></i> <span>Sổ văn bản</span>
        <span class="pull-right-container">

            </span>
    </a>
</li>
<li class="{{ Route::is('loai-van-ban.index') || Route::is('danhsachloaivanban') ? 'active' : '' }}  ">
    <a href="{{route('danhsachloaivanban')}}">
        <i class="fa fa-database" aria-hidden="true"></i> <span>Loại văn bản</span>
        <span class="pull-right-container">
{{--              <i class="fa fa-angle-left pull-right"></i>--}}
            </span>
    </a>
</li>
<li class="{{ Route::is('do-bao-mat.index') || Route::is('danhsachdobaomat') ? 'active' : '' }}  ">
    <a href="{{route('danhsachdobaomat')}}">
        <i class="fa fa-shield" aria-hidden="true"></i> <span>Độ bảo mật</span>
        <span class="pull-right-container">
{{--              <i class="fa fa-angle-left pull-right"></i>--}}
            </span>
    </a>
</li>
<li class="{{ Route::is('do-khan-cap.index') || Route::is('danhsachdokhancap') ? 'active' : '' }}  ">
    <a href="{{route('danhsachdokhancap')}}">
        <i class="fa fa-bolt" aria-hidden="true"></i> <span>Độ khẩn cấp</span>
        <span class="pull-right-container">
{{--              <i class="fa fa-angle-left pull-right"></i>--}}
            </span>
    </a>
</li>
<li class="treeview {{ Route::is('nguoi-dung.index') || Route::is('nguoi-dung.create') || Route::is('chuc-nang.index')
               || Route::is('vai-tro.index') || Route::is('ngay-nghi.index') || Route::is('sao-luu-du-lieu.index') ? 'active menu-open' : '' }} }} ">
    <a href="#">
        <i class="fa fa-cogs"></i> <span>Cấu hình hệ thống</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('nguoi-dung.index') ? 'active' : '' }}"><a href="{{ route('nguoi-dung.index') }}"><i class="fa fa-circle-o"></i> Người dùng</a></li>
        <li class="{{ Route::is('vai-tro.index') ? 'active' : '' }}"><a href="{{ route('vai-tro.index') }}"><i class="fa fa-circle-o"></i>Quyền hạn</a></li>
        <li class="{{ Route::is('ngay-nghi.index') ? 'active' : '' }}"><a href="{{ route('ngay-nghi.index') }}"><i class="fa fa-circle-o"></i>Ngày nghỉ</a></li>
        <li class="{{ Route::is('sao-luu-du-lieu.index') ? 'active' : '' }}"><a href="{{ route('sao-luu-du-lieu.index') }}"><i class="fa fa-circle-o"></i>Sao lưu dữ liệu</a></li>
        {{--                    <li class="{{ Route::is('chuc-nang.index') ? 'active' : '' }}"><a href="{{ route('chuc-nang.index') }}"><i class="fa fa-circle-o"></i> Chức năng</a></li>--}}
    </ul>
</li>
