@extends('admin::layouts.master')
@section('page_title', 'Đơn Vị')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cập nhật đơn vị</h3>
                    </div>
                    <div class="box-body">
                        @include('admin::don-vi-ngoai-he-thong._form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

