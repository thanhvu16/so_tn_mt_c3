@extends('admin::layouts.master')
@section('page_title', 'Thêm chức năng')
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
                    <a href="{{ route('chuc-nang.create') }}">
                        <i class="fa fa-plus"></i> Thêm mới</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- /.tab-pane -->
                <div class="tab-pane active" id="tab_2">
                    @include('admin::chuc-nang._form')
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </section>
@endsection
