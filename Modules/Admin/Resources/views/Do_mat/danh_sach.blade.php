@extends('admin::layouts.master')
@section('page_title', 'Độ bảo mật')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách độ bảo mật</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-3 form-group mt-4">
                        <button type="button" class="btn btn-sm btn-info waves-effect waves-light mb-1"
                                data-toggle="collapse"
                                href="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample">
                            THÊM MỨC ĐỘ BẢO MẬT</button>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="collapse " id="collapseExample">
                                <div class="row">
                                    @include('admin::Do_mat.index')
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('danhsachdobaomat')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm tên theo mức độ</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_muc_do')}}"
                                           name="ten_muc_do"
                                           placeholder="Tên mức độ">
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
                                <th width="" class="text-center">Tên mức độ</th>
                                <th width="10%" class="text-center">Mặc định</th>
                                <th width="10%" class="text-center">Trạng thái</th>
                                <th width="10%" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ds_mucdo as $key=>$mucdo)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$mucdo->ten_muc_do}}</td>
                                    <td class="text-center" style="vertical-align: middle">@if($mucdo->mac_dinh == 2)<span>Mặc định</span>@else @endif</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">@if($mucdo->deleted_at == null)<span
                                            class="label label-success">Hoạt động</span>@else @endif</td>
                                    <td class="text-center">
                                        @can('sửa độ mật')

                                            <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm"
                                               href="{{route('do-bao-mat.edit',$mucdo->id)}}" role="button"
                                               title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('xoá độ mật')
                                            <form method="POST" action="{{route('xoadobaomat',$mucdo->id)}}">
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
                                    Tổng số độ mật: <b>{{ $ds_mucdo->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_mucdo->appends(['ten_muc_do' => Request::get('ten_muc_do'),
                                       'search' =>Request::get('search') ])->render() !!}
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
