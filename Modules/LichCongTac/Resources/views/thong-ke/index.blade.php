@extends('admin::layouts.master')
@section('page_title', 'Thống kê tiêu chí')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thống kê tiêu chí cuộc họp</h3>
                    </div>
                    <div class="box-body">
                            <div class="row mb-2">
                                <form action="{{route('thong-ke-tieu-chi-cuoc-hop.index')}}" method="GET">
                                <div class="col-md-3">
                                    <p style="font-weight: bold">Từ ngày:</p>
                                    <input type="date" class="form-control" value="{{Request::get('tu_ngay')}}" placeholder="Từ ngày" name="tu_ngay">
                                </div>
                                <div class="col-md-3">
                                    <p style="font-weight: bold">Đến ngày:</p>
                                    <input type="date" class="form-control" value="{{Request::get('den_ngay')}}" placeholder="Đến ngày" name="den_ngay">
                                </div>
                                <div class="col-md-3 " style="margin-top: 30px">
                                    <button name="search" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                                </div>
                                </form>

                        </div>
                        <table class="table table-bordered table-hover mt-2">
                            <thead>
                            <tr>
                                <th width="4%" style="vertical-align: middle" class="text-center">STT</th>
                                <th width="" style="vertical-align: middle" class="text-center">Họ Tên</th>
                                <th width="11%" style="vertical-align: middle" class="text-center">Số lượng cuộc họp chủ trì</th>
                                <th width="11%" style="vertical-align: middle" class="text-center">Số lượng cuộc họp có kết luận</th>
                                <th width="11%" style="vertical-align: middle" class="text-center">Số lượng cuộc họp có file tài liệu</th>
                                <th width="11%" style="vertical-align: middle" class="text-center">Số lượng cuộc họp tài liệu chậm</th>
                                <th width="11%" style="vertical-align: middle" class="text-center">Cuộc họp có ý kiến tham gia</th>
                                <th width="11%" style="vertical-align: middle" class="text-center">Số cuộc họp không đạt</th>
                                <th width="11%" style="vertical-align: middle" class="text-center">Số cuộc họp chất lượng tài liệu không đạt</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($chu_tri as $key=>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data->lanhDao->ho_ten ?? ''}}</td>
                                    <td class="text-center"><a href="">{{$data->sLcuocHopChuTri($data->lanh_dao_id)}}</a></td>
                                    <td class="text-center"><a href="">{{$data->sLcuocHopCoKetLuan($data->lanh_dao_id)}}</a></td>
                                    <td class="text-center"><a href="">{{$data->sLcuocHopCoTaiLieu($data->lanh_dao_id) }}</a></td>
                                    <td class="text-center"><a href="">{{$data->sLcuocHopCoTaiLieucham($data->lanh_dao_id)}}</a></td>
                                    <td class="text-center"><a href="">{{$data->sLcuocHopCoYKien($data->lanh_dao_id)}}</a></td>
                                    <td class="text-center"><a href="">{{$data->sLcuocHopKhongDat($data->lanh_dao_id)}}</a></td>
                                    <td class="text-center"><a href="">{{$data->sLcuocHopCoTaiLieuKhongDat($data->lanh_dao_id)}}</a></td>
                                </tr>
                            @empty
                                <td colspan="8" class="text-center">Không có dữ liệu!</td>
                            @endforelse
                            </tbody>
                        </table>
                        {!! $chu_tri->appends(['tu_ngay' => Request::get('tu_ngay'),
                                   'den_ngay' => Request::get('den_ngay'),'search' =>Request::get('search')])->render() !!}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/LichCongTac/app.js') }}"></script>
@endsection
