@extends('admin::layouts.master')
@section('page_title', 'Sửa chức năng')
@section('content')
    <section class="content">
    {{--        <div class="box">--}}
    <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="">
                    <a href="{{ route('chuc-nang.index') }}">
                        <i class="fa fa-list"></i> Quản lý chức năng
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
                    @include('admin::chuc-nang._form', ['permission' => $permission])
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </section>
@endsection
