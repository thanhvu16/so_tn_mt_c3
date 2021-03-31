
@extends('admin::layouts.master')
@section('page_title', 'Danh sách văn bản đi')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Đổi password</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <div class="row">
                                <form action="{{route('guiXuLy')}}" id="search_vb" method="post">
                                    @csrf
                                    <div class="col-md-3">
                                        <label for="passWord" class="col-form-label">Nhập password:</label>
                                        <input type="text" class="form-control" autofocus value="" name="passWord" >
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <button class="btn btn-primary">Lưu lại</button>
                                    </div>
                                </form>

                            </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>

@endsection
