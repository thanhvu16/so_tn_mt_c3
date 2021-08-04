@extends('admin::layouts.master')
@section('page_title', 'Ngày nghỉ')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách ngày nghỉ</h3>
                    </div>
                    <div class="col-md-3 form-group mt-4">
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-1"
                                data-toggle="collapse"
                                href="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample">
                             {{ isset($ngayNghi) ? 'CẬP NHẬT' : 'THÊM' }} NGÀY NGHỈ
                        </button>
                    </div>

                    <!-- /.box-header -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="collapse {{ isset($ngayNghi) ? 'in' : null }} " id="collapseExample">
                                <div class="row">
                                    @include('admin::ngay-nghi._form')
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <form action="{{ route('ngay-nghi.index') }}" method="get">

                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_ngay_nghi')}}"
                                           name="ten_ngay_nghi"
                                           placeholder="Tên ngày nghỉ">
                                </div>
{{--                                <div class="col-md-3 form-group">--}}
{{--                                    <label for="exampleInputEmail1">Tìm theo tên viết tắt</label>--}}
{{--                                    <input type="text" class="form-control" value="{{Request::get('ten_viet_tat')}}"--}}
{{--                                           name="ten_viet_tat"--}}
{{--                                           placeholder="Tên viết tắt">--}}
{{--                                </div>--}}
                                <div class="col-md-3" style="margin-top: 20px">
                                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body">

                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th width="20%" class="text-center">Tên ngày nghỉ</th>
                                <th width="20%" class="text-center">Mô tả</th>
                                <th width="20%" class="text-center">Ngày nghỉ</th>
                                <th width="10%" class="text-center">Trạng thái</th>
                                <th width="10%" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($listNgayNghi as $key => $ngayNghi)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{ $key+1 }}</td>
                                    <td class="text-left"
                                        style="vertical-align: middle">{{ $ngayNghi->ten_ngay_nghi }}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{ $ngayNghi->mo_ta }}</td>
                                    <td>
                                        {{ $ngayNghi->ngay_nghi ? date('d/m/Y', strtotime($ngayNghi->ngay_nghi)) : null }}
                                    </td>
                                    <td>
                                        @if ($ngayNghi->trang_thai == 1)
                                            <span class="label label-success">Hoạt động</span>
                                        @else
                                            <span class="label label-danger">Không oạt động</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm"
                                           href="{{ route('ngay-nghi.index', 'id='.$ngayNghi->id) }}" role="button"
                                           title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('ngay-nghi.destroy', $ngayNghi->id) }}"
                                              accept-charset="UTF-8" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                role="button" title="Xóa">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <td class="text-center" colspan="6" style="vertical-align: middle">Không có dữ liệu !
                                </td>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số ngày nghỉ: <b>{{ $listNgayNghi->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $listNgayNghi->appends(['ten_ngay_nghi' => Request::get('ten_ngay_nghi')])->render() !!}
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
