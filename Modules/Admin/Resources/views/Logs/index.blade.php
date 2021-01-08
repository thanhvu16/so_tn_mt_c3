@extends('admin::layouts.master')
@section('page_title', 'Nhật ký truy cập')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nhật ký hoạt động</h3>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('nhat-ky-truy-cap.index')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label>Tìm theo người dùng</label>
                                    <input type="text" class="form-control" value="{{Request::get('name')}}"
                                           name="name"
                                           placeholder="Nhập tên người dùng">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Tìm tên hành động</label>
                                    <input type="text" class="form-control" value="{{Request::get('action')}}"
                                           name="action"
                                           placeholder="Nhập tên hành động">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Tìm theo ngày</label>
                                    <input type="date" class="form-control" value="{{Request::get('date')}}"
                                           name="date">
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                    @if (!empty(Request::get('name')) || !empty(Request::get('action')) ||
                                                !empty(Request::get('date')))
                                        <a href="{{ route('nhat-ky-truy-cap.index') }}" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                    @endif
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th class="text-center">Người dùng</th>
                                <th width="" class="text-center">Hành động</th>
                                <th class="text-center">Chi tiết</th>
                                <th class="text-center">Thời gian</th>

                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $key=>$data)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-left">{{$data->TenNguoiDung->ho_ten ?? ''}}</td>
                                    <td class="text-left">{{$data->action}}</td>
                                    <td class="text-center">
                                        <a class="btn-action btn btn-color-blue btn-sm btn-view-detail" title="xem chi tiết"
                                                data-id="{{ $data->id }}"><i class="fa fa-eye"></i> xem chi tiết
                                        </a>
                                    </td>
                                    <td class="text-center">{{ date('d/m/Y H:i:s', strtotime($data->created_at)) ?? '' }}</td>
                                </tr>
                            @empty
                                <td class="text-center" colspan="5">Không có dữ liệu !
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                Tổng số: <b>{{ $logs->total() }}</b>
                            </div>
                            <div class="col-md-6 text-right">
                                {!! $logs->appends(['name' => Request::get('name'), 'action' => Request::get('action'), 'date' => Request::get('date')])->render() !!}
                            </div>
                        </div>
                    </div>

                    <!-- chi tiet -->
                    <div class="col-md-10">
                        <div class="modal fade modal-view-datail" role="dialog" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content modal-data-push">

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        $('.btn-view-detail').on('click', function () {
            let id = $(this).data('id');
            $.ajax({
                url: APP_URL + '/nhat-ky-truy-cap/' + id,
                type: 'GET'
            })
                .done(function (response) {
                    $('.modal-data-push').html(response.html);
                    $('.modal-view-datail').modal();
                })
                .fail(function (error) {
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });
        });
    </script>
@endsection
