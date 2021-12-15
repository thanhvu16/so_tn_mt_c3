<header class="main-header">
    <a href="#" class="sidebar-toggle sidebar-customize" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle sidebar-mobile" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <a href="/" class="logo-customize">
            <img src="{{ asset('images/logo-hanoi.svg') }}" alt="" class="brand-logo">
            <div class="logo-text">
                <span class="above-text lg-text text-uppercase">{{ TITLE_APP }}</span>
                <span class="text-uppercase">HỆ THỐNG VĂN PHÒNG ĐIỆN TỬ</span>
            </div>
        </a>


        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img
                            src="{{ !empty(auth::user()->anh_dai_dien) ? getUrlFile(auth::user()->anh_dai_dien) : asset('images/default-user.png') }}"
                            class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ auth::user()->ho_ten ?? 'N/A' }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img
                                src="{{ !empty(auth::user()->anh_dai_dien) ? getUrlFile(auth::user()->anh_dai_dien) : asset('images/default-user.png') }}"
                                class="img-circle" alt="User Image">

                            <p>
                                {{ auth::user()->ho_ten }} ({{ auth::user()->chucVu->ten_viet_tat ?? 'N/A' }})
                                - {{ auth::user()->donVi->ten_don_vi ?? '' }}
                                <small>{{ date('d/m') }}. {{ date('Y') }}</small>
                            </p>
                            <p>
                               @if (auth::user()->can(\App\Common\AllPermission::VanThuChuyenTrach()))
                                    <a href="" class="color-white swith-other-user" data-toggle="modal"
                                       data-target="#modal-switch-user"><i class="fa fa-refresh"></i> chuyển qua tài khoản lãnh
                                        đạo</a>
                                @endif
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                @if(session()->has('origin_user'))
                                    <a href="{{ route('user.stop_switch_user') }}"
                                       class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Trở về văn thư</a>
                                @else
                                    <a href="{{ route('nguoi-dung.edit', auth::user()->id) }}"
                                       class="btn btn-default btn-flat">Thông tin</a>
                                @endif
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat" id="sso-logout" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Đăng xuất</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="modal fade" id="modal-switch-user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-data-push">
            <form action="{{ route('user.switch_user') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Chọn lãnh đạo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @if (count($users) > 0)
                                @foreach($users as $user)
                                    <div class="col-sm-6">
                                        <div class="form-group customize-checkbox">
                                            <input type="radio" id="md_radio_{{ $user->id }}" name="user_id" value="{{ $user->id }}"
                                                   class="filled-in chk-col-light-blue">
                                            <label for="md_radio_{{ $user->id }}">{{ $user->ho_ten }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary btn-sm">Chuyển
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
