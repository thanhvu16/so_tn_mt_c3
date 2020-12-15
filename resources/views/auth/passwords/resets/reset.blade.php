<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Đăng nhập cổng thông tin nội bộ</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('image/ha_noi.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    {{--    font awesome--}}
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">--}}
    <script src="https://kit.fontawesome.com/23dfbd8739.js" crossorigin="anonymous"></script>
    <!-- Bootstrap Core Css -->
    <link href="{{url('/assets/template/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{url('/assets/template/plugins/node-waves/waves.css')}}" rel="stylesheet"/>

    <!-- Animation Css -->
    <link href="{{url('/assets/template/plugins/animate-css/animate.css')}}" rel="stylesheet"/>

    <link href="{{url('/assets/template/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Css -->
    <link href="{{url('/assets/template/css/style.css')}}" rel="stylesheet">
    <style>
        .login-page {
            background-color: #b1d5f4;;
            /*background: url("image/img-login.png");*/
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            max-width: 432px !important;
        }
        .card{
            border-radius: 7px !important;
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .body input:focus {
            outline: 0;
        }

        .body input {
            border-color: white;
            background-color: white;
            border-width: 0px;
        }

        .input-group {
            border-bottom: 1px solid #ccc;
        }
        h4, b{
            color: #0065B3;
        }
        .bg-light-blue{
            background-color: #158af1 !important;
        }

        .card-header .title {
            font-size: 16px;
            font-weight: 700;
            margin-left: 10px;
        }

    </style>
</head>
<body class="login-page">
<div class="login-box">
    <div class="logo" style="margin-bottom: 11px">
        <a href="/" style="font-size: 16px;text-transform: uppercase">
            <img src="{{ asset('administrator/assets/images/logo-login-hanoi.svg') }}" alt="" height="108">
            <h4>VĂN PHÒNG UBND HUYỆN</h4>
            <b>HỆ THỐNG VĂN PHÒNG ĐIỆN TỬ</b>
        </a>
    </div>
    <div class="card">
        <div class="card-header"><span class="title">{{ __('Reset Password') }}</span></div>
        <div class="body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div style="margin-bottom: 15px" class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-envelope" aria-hidden="true" style="color: #ccc;"></span>
{{--                        <i class="fa fa-user-o" aria-hidden="true" style="color: #ccc"></i>--}}
                    </span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror

                </div>
                <div style="margin-bottom: 15px" class="input-group">
                    <span class="input-group-addon"><i class="fas fa-unlock-alt" style="font-size: 15px; color: #ccc; "></i></span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required
                           autocomplete="new-password" placeholder="Nhập mật khẩu mới" autofocus>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror

                </div>
                <div style="margin-bottom: 15px" class="input-group">
                    <span class="input-group-addon"><i class="fas fa-unlock-alt" style="font-size: 15px; color: #ccc; "></i></span>
                    <input id="password-confirm" type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="row">
                    <div class="col-xs-6" style="float:none;margin: auto">
                        <button class="btn btn-block bg-light-blue waves-effect" type="submit">
                            {{--                            <i class="fa fa-arrow-circle-right"></i>--}}
                            Xác nhận
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
{{--    <div class="card">--}}
{{--        <div class="body">--}}
{{--            <form id="mainLogin">--}}
{{--                <div class="form-group">--}}
{{--                    <i class="glyphicon glyphicon-user"></i>--}}
{{--                    <input id="login-username" type="text" class="" name="username" value=""--}}
{{--                                               placeholder="Tên đăng nhập">--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <i class="material-icons">lock_outline</i>--}}
{{--                    <input type="password" class="matkhau" name="password"--}}
{{--                                                   placeholder="Mật khẩu" aria-required="true" required>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

</body>
<!-- Jquery Core Js -->
<script src="{{url('/assets/template/plugins/jquery/jquery.min.js')}}"></script>

<!-- Bootstrap Core Js -->
<script src="{{url('/assets/template/plugins/bootstrap/js/bootstrap.js')}}"></script>

<!-- Waves Effect Plugin Js -->
<script src="{{url('/assets/template/plugins/node-waves/waves.js')}}"></script>

<!-- Validation Plugin Js -->
<script src="{{url('/assets/template/plugins/jquery-validation/jquery.validate.js')}}"></script>

<script src="{{url('/assets/template/plugins/toastr/toastr.min.js') }}"></script>

<!-- Custom Js -->
<script src="{{url('/assets/template/js/admin.js')}}"></script>
<script src="{{url('/assets/template/js/pages/examples/sign-in.js')}}"></script>
<script type="text/javascript">
    window.flashMessages = [];

    @if ($message = session('success'))
    toastr.success("{{ $message }}");

    @elseif ($message = session('warning'))
    toastr.warning("{{ $message }}");

    @elseif ($message = session('error'))
    toastr.error("{{ $message }}");

    @elseif ($message = session('info'))
    toastr.info("{{ $message }}");
    @endif

        toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "9000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
</html>
