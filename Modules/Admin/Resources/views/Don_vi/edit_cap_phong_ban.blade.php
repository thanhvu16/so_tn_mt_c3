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
                    <form action="{{route('don-vi.update',$donvi->id)}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3 parent-id">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Chọn đơn vị chủ quản</label>
                                    <select class="form-control select2" name="parent_id">
                                        <option value="">Chọn đơn vị</option>
                                        @foreach($donViCapXa as $data)
                                            <option value="{{ $data->id }}" {{ $donvi->parent_id == $data->id ? 'selected' : null }} >{{ $data->ten_don_vi }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên đơn vị</label>
                                    <input type="text" class="form-control" name="ten_don_vi" id="exampleInputEmail1"
                                           placeholder="Tên đơn vị" value="{{ $donvi->ten_don_vi }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nhóm đơn vị</label>
                                    <select class="form-control select2" name="nhom_don_vi">
                                        @foreach($nhom_don_vi as $data)
                                            <option value="{{$data->id}}"  {{$donvi && $data->id == $donvi->nhom_don_vi ? 'selected' : ''}}>{{$data->ten_nhom_don_vi}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Tên viết tắt</label>
                                    <input type="text" class="form-control" name="ten_viet_tat" id="exampleInputEmail2"
                                           placeholder="Tên viết tắt" value="{{ $donvi->ten_viet_tat }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Mã hành chính</label>
                                    <input type="text" class="form-control" name="ma_hanh_chinh" id="exampleInputEmail3"
                                           placeholder="Mã hành chính" value="{{ $donvi->ma_hanh_chinh }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input type="text" class="form-control" name="dia_chi"
                                           placeholder="Địa chỉ" value="{{ $donvi->dia_chi }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Điện thoại</label>
                                    <input type="text" class="form-control" name="dien_thoai"
                                           placeholder="Điện thoại" value="{{ $donvi->so_dien_thoai }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Email</label>
                                    <input type="text" class="form-control" name="email"
                                           placeholder="Email" value="{{ $donvi->email }}">
                                </div>
                            </div>
                            <input type="hidden" name="dieu_hanh" value="0" checked>
                            <input type="hidden" name="check_parent" class="check_parent" value="1" checked>
                            <div class="col-md-12 mt-2">
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
