@extends('admin::layouts.master')
@section('page_title', '403 Error')
@section('content')
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"> 403</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Không có quyền truy cập.</h3>

                <p>
                    Bạn không có quyền truy cập trang này, vui lòng
                    ấn <a href="/">vào đây</a> để trở lại trang chủ.
                </p>

                <div class="col-12">
                    <button type="button" class="btn btn-primary go-back"><i class="fa fa-arrow-left"></i> Trở lại</button>
                </div>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
@endsection
