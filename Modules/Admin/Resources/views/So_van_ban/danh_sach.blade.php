@extends('admin::layouts.master')
@section('page_title', 'Sổ Văn Bản')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách sổ văn bản</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('danhsachdonvi')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên sổ văn bản</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_don_vi')}}"
                                           name="ten_don_vi"
                                           placeholder="Tên đơn vị">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên viết tắt</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_viet_tat')}}"
                                           name="ten_viet_tat"
                                           placeholder="Tên viết tắt">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo loại sổ</label>
                                    <input type="text" class="form-control" value="{{Request::get('ma_hanh_chinh')}}"
                                           name="ma_hanh_chinh"
                                           placeholder="Loại">
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
                                <th width="" class="text-center">Tên sổ đơn vị</th>
                                <th width="10%" class="text-center">Tên viết tắt</th>
                                <th width="10%" class="text-center">Mô tả</th>
                                <th width="20%" class="text-center">Loại sổ</th>
                                <th width="10%" class="text-center">Điện thoại</th>
                                <th width="10%" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ds_sovanban as $key=>$sovanban)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$sovanban->ten_so_van_ban}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$sovanban->ten_viet_tat}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$sovanban->mo_ta}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$sovanban->loai_so}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$sovanban->so_don_vi}}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{route('xoadonvi',$sovanban->id)}}">
                                            @csrf
                                            <a class="btn btn-color-blue btn-icon btn-light"
                                               href="{{route('so-van-ban.edit',$sovanban->id)}}" role="button"
                                               title="Sửa">
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
                                    Tổng số văn bản: <b>{{ $ds_sovanban->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_sovanban->appends(['ten_don_vi' => Request::get('ten_don_vi'),'ma_hanh_chinh' => Request::get('ma_hanh_chinh'),
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
