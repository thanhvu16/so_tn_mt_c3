{{--@extends('admin::layouts.master')--}}
{{--@section('page_title', 'Chức Vụ')--}}
{{--@section('content')--}}
{{--    <section class="content">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="box box-primary">--}}
{{--                    <div class="box-header with-border">--}}
{{--                        <h3 class="box-title">Thêm chức vụ</h3>--}}
{{--                    </div>--}}
                    <form role="form" action="{{route('chuc-vu.store')}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên chức vụ</label>
                                    <input type="text" class="form-control" name="ten_chuc_vu" id="exampleInputEmail1"
                                           placeholder="Tên chức vụ" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Tên viết tắt</label>
                                    <input type="text" class="form-control" name="ten_viet_tat" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" required>
                                </div>
                            </div>

                            <div class="col-md-3 text-left" style="margin-top: 20px">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                </div>
                            </div>
                        </div>
                    </form>
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--@endsection--}}
