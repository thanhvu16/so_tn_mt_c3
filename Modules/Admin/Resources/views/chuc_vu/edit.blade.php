@extends('admin::layouts.master')
@section('page_title', 'Chức Vụ')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cập nhật chức vụ</h3>
                    </div>
                    <form action="{{route('chuc-vu.update',$chucvu->id)}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên chức vụ</label>
                                    <input type="text" class="form-control" value="{{$chucvu->ten_chuc_vu}}"
                                           name="ten_chuc_vu" id="exampleInputEmail1"
                                           placeholder="Tên chức vụ" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Tên viết tắt</label>
                                    <input type="text" class="form-control" value="{{$chucvu->ten_viet_tat}}"
                                           name="ten_viet_tat" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Nhóm đơn vi</label>
                                <select class="form-control select2" name="nhom_don_vi[]" multiple="multiple">

                                    @foreach($nhom_don_vi as $data)
                                        <option value="{{$data->id}}"  {{ isset($lay_nhom_don_vi) && in_array($data->id, $lay_nhom_don_vi->pluck('id_nhom_don_vi')->toArray()) ? 'selected' : '' }}
                                            >{{$data->ten_nhom_don_vi}}</option>
                                    @endforeach
                                </select>
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
