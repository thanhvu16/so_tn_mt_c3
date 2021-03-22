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
                    <form role="form" action="{{route('don-vi.store')}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên đơn vị</label>
                                    <input type="text" class="form-control" name="ten_don_vi" id="exampleInputEmail1"
                                           placeholder="Tên đơn vị" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nhóm đơn vi</label>
                                    <select class="form-control select2" name="nhom_don_vi">
                                        @foreach($nhom_don_vi as $data)
                                        <option value="{{$data->id}}">{{$data->ten_nhom_don_vi}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Tên viết tắt</label>
                                    <input type="text" class="form-control" name="ten_viet_tat" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Mã hành chính</label>
                                    <input type="text" class="form-control" name="ma_hanh_chinh" id="exampleInputEmail3"
                                           placeholder="Mã hành chính" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input type="text" class="form-control" name="dia_chi"
                                           placeholder="Địa chỉ" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Điện thoại</label>
                                    <input type="text" class="form-control" name="dien_thoai"
                                           placeholder="Điện thoại" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Email</label>
                                    <input type="text" class="form-control" name="email"
                                           placeholder="Email" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Điều hành</label>
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="dieu_hanh" id="optionsRadios1" value="1">
                                                Có
                                            </label> &emsp;
                                            <label>
                                                <input type="radio" name="dieu_hanh" id="optionsRadios2"
                                                       value="0" checked="">
                                                Không
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3">
                                <label>
                                    <input type="checkbox" name="cap_xa" value="1">
                                        Cấp chi cục
                                </label> &emsp;
                            </div>
{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label >Có phòng ban trong đơn vị</label>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="radio">&emsp;--}}
{{--                                            <label>--}}
{{--                                                <input type="radio" name="check_parent" id="optionsRadios3"--}}
{{--                                                       value="0" checked="" class="check_parent">--}}
{{--                                                Không--}}
{{--                                            </label>--}}
{{--                                            <label>--}}
{{--                                                <input type="radio" name="check_parent" id="optionsRadios4" class="check_parent" value="1">--}}
{{--                                                Có--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3 parent-id hide">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="exampleInputEmail1">Chọn đơn vị</label>--}}
{{--                                    <select class="form-control select2" name="parent_id">--}}
{{--                                        <option value="">Chọn đơn vị</option>--}}
{{--                                        @foreach($donViCapXa as $donVi)--}}
{{--                                            <option value="{{ $donVi->id }}">{{ $donVi->ten_don_vi }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}

{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="col-md-12 mt-2">
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
