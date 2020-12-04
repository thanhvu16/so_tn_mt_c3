@extends('admin::layouts.master')
@section('page_title', 'Dự thảo văn bản')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dự thảo văn bản</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @include('vanbandi::Du_thao_van_ban_di.form_du_thao')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
