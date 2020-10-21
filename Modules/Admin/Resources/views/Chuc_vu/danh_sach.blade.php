@extends('admin::layouts.master')
@section('page_title', 'Chức Vụ')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách đơn vị</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('danhsachchucvu')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên đơn vị</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_chuc_vu')}}"
                                           name="ten_chuc_vu"
                                           placeholder="Tên chức vụ">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên viết tắt</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_viet_tat')}}"
                                           name="ten_viet_tat"
                                           placeholder="Tên viết tắt">
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
                                <th width="5%" class="text-center">STT</th>
                                <th width="" class="text-center">Tên chức vụ</th>
                                <th width="20%" class="text-center">Tên viết tắt</th>
                                <th width="10%" class="text-center">Trạng thái</th>
                                <th width="10%" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ds_chucvu as $key=>$chucvu)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$chucvu->ten_chuc_vu}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$chucvu->ten_viet_tat}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">@if($chucvu->deleted_at == null)<span
                                            class="label label-success">Hoạt động</span>@else @endif</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{route('xoachucvu',$chucvu->id)}}">
                                            @csrf
                                            <a class="btn btn-color-blue btn-icon btn-light"
                                               href="{{route('chuc-vu.edit',$chucvu->id)}}" role="button" title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-color-red btn-icon btn-light" role="button"
                                                    title="Xóa">
                                                <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>
                                            </button>
                                        </form>

                                    </td>

                                </tr>
                            @empty
                                <td class="text-center" colspan="7" style="vertical-align: middle">Không có dữ liệu !
                                </td>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số văn bản: <b>{{ $ds_chucvu->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_chucvu->appends(['ten_chuc_vu' => Request::get('ten_chuc_vu'),
                                       'ten_viet_tat' => Request::get('ten_viet_tat'),'search' =>Request::get('search') ])->render() !!}
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
