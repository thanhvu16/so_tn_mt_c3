<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý văn bản | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('theme/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('theme/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme/plugins/toastr/toastr.min.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .login-page {
            background-color: #b1d5f4;;
            /*background: url('hanoi-3609871.jpg');*/
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            /*max-width: 432px !important;*/
        }

        .login-box{
            width: 370px !important;
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
        h4, h5{
            color: #0065B3;
        }
        .bg-light-blue{
            background-color: #158af1 !important;
        }

    </style>
</head>
<body class="hold-transition login-page" style="margin-top: -40px">
<div class="login-box">

    <div class="logo">
        <a href="/" class="text-center" style="margin-bottom: 11px">
            <div class="text-center">
                <img src="{{ asset('theme/image/logo-login-hanoi.svg') }}" style="vertical-align: middle" alt="" height="90">
            </div>
            <h4 style="font-weight: bold;font-family: Arial;"  class="text-center">{{ TITLE_APP }}</h4>
            <h5 style="font-weight: bold ;font-family: Arial; " >HỆ THỐNG VĂN PHÒNG ĐIỆN TỬ</h5>
        </a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg" style="padding-bottom: 3px !important;">Quên mật khẩu?</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
{{--                <div style="margin-bottom: 10px" class="input-group">--}}
{{--                     <span class="input-group-addon">--}}
{{--                        <span class="glyphicon glyphicon-envelope" aria-hidden="true" style="color: #ccc;"></span>--}}
{{--                    </span>--}}
{{--                    <input >--}}
{{--                </div>--}}
                <div class="form-group has-feedback">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email"
                           placeholder="Nhập email để reset lại mật khẩu ..."
                           autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                @error('email')
                <div style="margin-bottom: 10px;">
                    <span class="invalid-feedback mb-2" role="alert">
                       <strong>{{ $message }}</strong>
                    </span>
                </div>
                @enderror
                <div class="row">
                    <div class="col-xs-6" style="float:none;margin: auto">
                        <button class="btn btn-block bg-light-blue" type="submit">
                            {{--                            <i class="fa fa-arrow-circle-right"></i>--}}
                            Gửi
                            <i class="fa fa-send" style="font-size: 18px; margin-left: 5px;"></i>
                        </button>
                    </div>
                </div>
            </form>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ url('theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('theme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ url('theme/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ url('theme/plugins/toastr/toastr.min.js') }}"></script>
<script type="text/javascript">
    window.flashMessages = [];

    @if ($message = session('status'))
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
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
</body>
</html>

