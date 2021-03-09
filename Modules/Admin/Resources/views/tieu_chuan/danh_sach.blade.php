@extends('admin::layouts.master')
@section('page_title', 'Tiêu chuẩn')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tiêu chuẩn</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-3 form-group mt-4">
                        <button type="button" class="btn btn-sm btn-info waves-effect waves-light mb-1"
                                data-toggle="collapse"
                                href="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample">
                            THÊM TIÊU CHUẨN</button>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="collapse " id="collapseExample">
                                <div class="row">
                                    @include('admin::tieu_chuan.index')
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('tieu-chuan.index')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên tiêu chuẩn</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_tieu_chuan')}}"
                                           name="ten_tieu_chuan"
                                           placeholder="Tên tiêu chuẩn">
                                </div>
                                <div class="col-md-3" style="margin-top: 20px">
                                    <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th width="5%" style="vertical-align: middle" class="text-center">STT</th>
                                <th width="" style="vertical-align: middle" class="text-center">Tên tiêu chuẩn</th>
                                <th width="10%" style="vertical-align: middle" class="text-center">Số ngày</th>
                                <th width="8%" style="vertical-align: middle" class="text-center">Mô tả</th>
                                <th width="7%" style="vertical-align: middle" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ds_tieuChuan as $key=>$loaivanban)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$loaivanban->ten_tieu_chuan}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$loaivanban->so_ngay}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$loaivanban->mo_ta}}</td>

                                    <td class="text-center">

                                        @can('sửa tiêu chuẩn')
                                            <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm"
                                               href="{{route('tieu-chuan.edit',$loaivanban->id)}}" role="button"
                                               title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('xóa tiêu chuẩn')
                                            <form method="POST" action="{{route('xoaTieuChuan',$loaivanban->id)}}">
                                                @csrf
                                                <button
                                                    class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                    role="button"
                                                    title="Xóa">
                                                    <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </td>

                                </tr>
                            @empty
                                <td class="text-center" colspan="5" style="vertical-align: middle">Không có dữ liệu !
                                </td>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số tiêu chuẩn: <b>{{ $ds_tieuChuan->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_tieuChuan->appends(['ten_tieu_chuan' => Request::get('ten_tieu_chuan'),'search' =>Request::get('search') ])->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection
