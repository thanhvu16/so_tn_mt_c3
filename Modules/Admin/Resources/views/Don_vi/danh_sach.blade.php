@extends('admin::layouts.master')
@section('page_title', 'Đơn Vị')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách đơn vị</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-3 form-group mt-4">
                        <button type="button" class="btn btn-sm btn-info waves-effect waves-light mb-1"
                                data-toggle="collapse"
                                href="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample">
                            THÊM ĐƠN VỊ</button>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="collapse " id="collapseExample">
                                <div class="row">
                                    @include('admin::Don_vi.index')
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('danhsachdonvi')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên đơn vị</label>
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
                                    <label for="exampleInputEmail1">Tìm theo mã hành chính</label>
                                    <input type="text" class="form-control" value="{{Request::get('ma_hanh_chinh')}}"
                                           name="ma_hanh_chinh"
                                           placeholder="Mã hành chính">
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
                                <th width="" class="text-center">Tên đơn vị</th>
                                <th width="18%" class="text-center">Nhóm đơn vị</th>
                                <th width="10%" class="text-center">Đơn vị chủ quản</th>
                                <th width="10%" class="text-center">Mã hành chính</th>
                                <th width="10%" class="text-center">Địa chỉ</th>
                                <th width="10%" class="text-center">Điện thoại</th>
                                <th width="10%" class="text-center">Email</th>
                                <th width="10%" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ds_donvi as $key=>$donvi)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-left" style="vertical-align: middle">{{$donvi->ten_don_vi}}</td>
                                    <td class="text-left" style="vertical-align: middle">{{$donvi->nhomDonVi->ten_nhom_don_vi ?? ''}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{ $donvi->getParent->ten_don_vi ?? null }}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$donvi->ma_hanh_chinh}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$donvi->dia_chi}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$donvi->so_dien_thoai}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$donvi->email}}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{route('xoadonvi',$donvi->id)}}">
                                            @csrf
                                            <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm"
                                               href="{{route('don-vi.edit',$donvi->id)}}" role="button" title="Sửa">
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
                                    Tổng số đơn vị: <b>{{ $ds_donvi->total() }}</b>
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
@section('script')
    <script type="text/javascript">
        $('.check_parent').on('click', function () {
            let status = $(this).val();
            if (status == 1) {
                $('.parent-id').removeClass('hide');
            } else {
                $('.parent-id').addClass('hide');
            }
        });
    </script>
@endsection
