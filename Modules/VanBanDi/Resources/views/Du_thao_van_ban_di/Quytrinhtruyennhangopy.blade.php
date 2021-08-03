@extends('admin::layouts.master')
@section('page_title', 'Quá trình xử lý văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quá trình xử lý văn bản</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="col-md-12">
                            <div class="form-group" style="padding-left:15px;">
                                <label for="">Cán bộ trong phòng góp ý:</label>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <th class="text-center" width="5%" style="vertical-align: middle;">STT</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Thời gian</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Cán bộ góp ý</th>
                                        <th class="text-center" width="40%" style="vertical-align: middle;">Nội dung</th>
                                        <th class="text-center" width="15%" style="vertical-align: middle;">File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($quatrinhtruyennhanphong as $key=>$data)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td> {{date_format($data->created_at, 'd-m-Y H:i:s') ?? ''}}</td>
                                            <td>{{$data->nguoiDung->ho_ten ?? ''}}</td>
                                            <td>{{$data->y_kien}}</td>
                                            <td>
                                                @forelse($data->gopyFilecanbophong as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="_blank">
                                                        [Xem file]
                                                    </a>
                                                @empty
                                                @endforelse <br>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="padding-left:15px;">
                                <label for="">Cán bộ phòng khác góp ý:</label>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="background: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <th class="text-center" width="5%" style="vertical-align: middle;">STT</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Thời gian</th>
                                        <th class="text-center" width="20%" style="vertical-align: middle;">Cán bộ góp ý</th>
                                        <th class="text-center" width="40%" style="vertical-align: middle;">Nội dung</th>
                                        <th class="text-center" width="15%" style="vertical-align: middle;">File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($quatrinhtruyennhankhac as $key=>$data)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td> {{date_format($data->created_at, 'd-m-Y H:i:s') ?? ''}}</td>
                                            <td>{{$data->nguoiDung->ho_ten ?? ''}}</td>
                                            <td>{{$data->y_kien}}</td>
                                            <td>
                                                @forelse($data->gopyFilecanbophongngoai as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="_blank">
                                                        [Xem file]
                                                    </a>
                                                @empty
                                                @endforelse <br>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="padding-left:15px;">
                                <a class="btn btn-default" href="javascript: history.back(1)" id="backLink" data-original-title="" title="">Quay lại &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
