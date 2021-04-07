@extends('admin::layouts.master')
@section('page_title', 'Sửa quyền hạn')
@section('content')
    <section class="content">
    {{--        <div class="box">--}}
    <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="">
                    <a href="{{ route('vai-tro.index') }}">
                        <i class="fa fa-list"></i> Quản lý quyền hạn
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="fa fa-pencil"></i> Chỉnh sửa</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- /.tab-pane -->
                <div class="tab-pane active" id="tab_2">
                    @include('admin::vai-tro._form',
                    ['role' => $role,
                    'permissions' => $permissions,
                    'arrPermisson' => $arrPermisson
                    ])
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).on('change', 'input[name=check_all]', function () {
            if ($(this).is(':checked', true)) {
                $(this).closest('.data-item').find(".sub-check").prop('checked', true);
            } else {
                $(this).closest('.data-item').find(".sub-check").prop('checked', false);
            }
        });
    </script>
@endsection
