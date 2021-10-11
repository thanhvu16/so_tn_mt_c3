    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('page_title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('theme/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ url('theme/dist/css/skins/_all-skins.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ url('theme/plugins/iCheck/all.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- toastr -->
    <link rel="stylesheet" href="{{ url('theme/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="icon" href="{{ asset('images/ha_noi.png') }}" type="image/x-icon">
    <link href="{{ url('theme/plugins/loadingModal/css/jquery.loadingModal.css')}}" rel="stylesheet" />
    <link href="{{ url('theme/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet" />
    <link href="{{ url('theme/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <!-- Morris Chart Css-->
    <link href="{{ url('theme/bower_components/morris.js/morris.css') }}" rel="stylesheet" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('css')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
{{--<body class="skin-blue sidebar-mini sidebar-collapse">--}}
<div class="wrapper">

   @include('admin::layouts.components.header')
    <!-- Left side column. contains the logo and sidebar -->
   @include('admin::layouts.components.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('admin::layouts.components.footer')

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->

<script src="{{ url('theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('theme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ url('theme/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('theme/dist/js/adminlte.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ url('theme/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap  -->
<script src="{{ url('theme/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ url('theme/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ url('theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ url('theme/bower_components/chart.js/Chart.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('theme/dist/js/demo.js') }}"></script>
<script src="{{ url('theme/plugins/toastr/toastr.min.js') }}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ url('theme/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ url('theme/dist/js/pages/charts/loader.js') }}"></script>
<!-- Select2 -->
<script src="{{ url('theme/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{url('theme/plugins/loadingModal/js/jquery.loadingModal.js')}}"></script>
<script src="{{url('theme/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<!---moment js-->
<script src="{{ url('theme/bower_components/moment/min/moment.min.js') }}"></script>
<!-- Morris Chart js-->
<script src="{{ url('theme/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ url('theme/bower_components/morris.js/morris.min.js') }}"></script>
<!--chart js-->
<script src="{{ url('theme/plugins/chartjs/Chart.bundle.js') }}"></script>

<script src="{{ url('theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ url('theme/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    var APP_URL = {!! json_encode(url('/')) !!}

    $("#sso-logout").off('click').on('click', function () {
        SSO.logout();
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
<script src="{{ url('js/script.js') }}"></script>

@yield('script')
</body>
</html>
