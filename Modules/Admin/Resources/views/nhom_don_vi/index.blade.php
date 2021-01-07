{{--@extends('admin::layouts.master')--}}
{{--@section('page_title', 'Đơn Vị')--}}
{{--@section('content')--}}
{{--    <section class="content">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="box box-primary">--}}
{{--                    <div class="box-header with-border">--}}
{{--                        <h3 class="box-title">Thêm đơn vị</h3>--}}
{{--                    </div>--}}
                    <form role="form" action="{{route('Nhom-don-vi.store')}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên nhóm đơn vị</label>
                                    <input type="text" class="form-control" name="ten_nhom_don_vi" id="exampleInputEmail1"
                                           placeholder="Tên đơn vị" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Mô tả</label>
                                    <input type="text" class="form-control" name="mo_ta" id="exampleInputEmail2"
                                           placeholder="Mô tả" >
                                </div>
                            </div>

                            <div class="col-md-3 mt-4">
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
