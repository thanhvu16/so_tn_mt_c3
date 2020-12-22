@extends('admin::layouts.master')
@section('page_title', 'Chức Vụ')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cập nhật mức độ bảo mật</h3>
                    </div>
                    <form action="{{route('do-bao-mat.update',$mucdo->id)}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên mức độ</label>
                                    <input type="text" class="form-control" value="{{$mucdo->ten_muc_do}}"
                                           name="ten_muc_do" id="exampleInputEmail1"
                                           placeholder="Tên mức độ" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Mô tả</label>
                                    <input type="text" class="form-control" value="{{$mucdo->mo_ta}}"
                                           name="mo_ta" id="exampleInputEmail2"
                                           placeholder="Mô tả" required>
                                </div>
                            </div>
                            <div class=" col-md-3 form-group mt-4">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="mac_dinh"  id="optionsRadios1" value="2" {{isset($mucdo->mac_dinh) && $mucdo->mac_dinh == 2 ? 'checked' : ''}} >
                                        Mặc định
                                    </label>
                                    <label>
                                        <input type="radio" name="mac_dinh" id="optionsRadios2" value="1" {{isset($mucdo->mac_dinh) && $mucdo->mac_dinh == 1 ? 'checked' : ''}}>
                                        Không
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3 text-left" style="margin-top: 20px">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
