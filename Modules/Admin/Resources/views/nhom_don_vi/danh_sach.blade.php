@extends('admin::layouts.master')
@section('page_title', 'Nhóm đơn Vị')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách nhóm đơn vị</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-3 form-group mt-4">
                        <button type="button" class="btn btn-sm btn-info waves-effect waves-light mb-1"
                                data-toggle="collapse"
                                href="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample">
                            THÊM NHÓM ĐƠN VỊ</button>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="collapse " id="collapseExample">
                                <div class="row">
                                    @include('admin::nhom_don_vi.index')
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('Nhom-don-vi.index')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên nhóm đơn vị</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_nhom_don_vi')}}"
                                           name="ten_nhom_don_vi"
                                           placeholder="Tên nhóm đơn vị">
                                </div>

                                <div class="col-md-3 mt-4" style="margin-top: 20px">
                                    <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th width="" class="text-center">Tên nhóm đơn vị</th>
                                <th width="15%" class="text-center">Mô tả</th>
                                <th width="13%" class="text-center">Ngày tạo</th>
                                <th width="10%" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ds_donvi as $key=>$donvi)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$donvi->ten_nhom_don_vi}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$donvi->mo_ta}}</td>

                                    <td class="text-center" style="vertical-align: middle">{{  date_format($donvi->created_at, 'H:i:s , d/m/Y ') ?? ''}}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{route('xoanhomdonvi',$donvi->id)}}">
                                            @csrf
                                            <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm"
                                               href="{{route('Nhom-don-vi.edit',$donvi->id)}}" role="button" title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item" role="button"
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
                                    Tổng số nhóm đơn vị: <b>{{ $ds_donvi->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_donvi->appends(['ten_don_vi' => Request::get('ten_don_vi'),'ma_hanh_chinh' => Request::get('ma_hanh_chinh'),
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
