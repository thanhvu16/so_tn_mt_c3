@extends('admin::layouts.master')
@section('page_title', 'Chỉnh sửa người dùng')
@section('content')
    <section class="content">
    {{--        <div class="box">--}}
    <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="">
                    <a href="{{ route('nguoi-dung.index') }}">
                        <i class="fa fa-user"></i> Quản lý người dùng
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
                    @include('admin::nguoi-dung._form', ['user' => $user])
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->

    {{--            <div class="box-header with-border">--}}
    {{--                <h3 class="box-title">Quản lý người dùng</h3>--}}
    {{--            </div>--}}
    <!-- /.box-header -->

        {{--        </div>--}}
    </section>
@endsection
