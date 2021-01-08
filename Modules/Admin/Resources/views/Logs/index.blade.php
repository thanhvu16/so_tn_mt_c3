@extends('admin::layouts.master')
@section('page_title', 'Nhật ký truy cập')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nhật ký truy cập</h3>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('nhat-ky-truy-cap.index')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm tên hành động</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_hanh_dong')}}"
                                           name="ten_hanh_dong"
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
                                <th width="15%" class="text-center">Người dùng</th>
                                <th width="" class="text-center">Hành động</th>
                                <th width="15%" class="text-center">Ngày tháng</th>
                                <th width="15%" class="text-center">Chi tiết</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $key=>$data)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-left" style="vertical-align: middle">{{$data->TenNguoiDung->ho_ten ?? ''}}</td>
                                    <td class="text-left" style="vertical-align: middle">{{$data->action}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{date_format($data->created_at, 'd-m-Y H:i:s') ?? ''}}</td>
                                    <td class="text-center" style="vertical-align: middle"><a data-toggle="modal" data-target="#myModal-{{ $data->id }}"><i class="fa  fa-cog"></i></a></td>
                                </tr>

                                <div class="modal fade" id="myModal-{{ $data->id }}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title"><i
                                                            class="fa fa-folder-open-o"></i> {{ $data->action }}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            @foreach($data->chuyenDoiData($data->content) as $key => $data)
                                                                <p>{{ $key }}: {{ $data }}</p>
                                                            @endforeach

{{--                                                            <table class="table table-bordered table-striped table-hover">--}}
{{--                                                                <thead>--}}
{{--                                                                <tr>--}}
{{--                                                                    <th class="text-center">STT</th>--}}
{{--                                                                    <th width="15%" class="text-center">Người dùng</th>--}}
{{--                                                                </tr>--}}
{{--                                                                </thead>--}}
{{--                                                                <tbody>--}}
{{--                                                                <tr>--}}

{{--                                                                </tr>--}}
{{--                                                                </tbody>--}}
{{--                                                            </table>--}}
                                                        </div>
                                                        <div class="form-group col-md-4" >
{{--                                                            <button class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Tải lên</button>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <td class="text-center" colspan="5" style="vertical-align: middle">Không có dữ liệu !
                                </td>
                            @endforelse

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        // function showModal() {
        //     $("#myModal").modal('show');
        // }
    </script>
@endsection
