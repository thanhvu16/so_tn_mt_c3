@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="far fa-plus-square"></i> Quá trình xử lý văn bản
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12 mt-2"></div>
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Nơi nhận:</label>--}}
{{--                                                <span>1</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Số kí hiệu:</label>--}}
{{--                                                <span>{{$vanbandi->vb_sokyhieu}}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Loại văn bản:</label>--}}
{{--                                                <span>{{$vanbandi->loaiVanBanid->ten_loai_van_ban ?? ''}}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Sổ văn bản:</label>--}}
{{--                                                <span>{{$vanbandi->sovanban->ten_so_van_ban ?? ''}}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Ngày ký:</label>--}}
{{--                                                <span>{{$vanbandi->vb_ngaybanhanh}}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Người ký:</label>--}}
{{--                                                <span>{{$vanbandi->nguoidung2->ho_ten ?? ''}}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-12">--}}
{{--                                                <label for="" class="col-md-4">Trích yếu:</label>--}}
{{--                                                <div class="col-md-12">{{$vanbandi->vb_trichyeu}}</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Chức vụ:</label>--}}
{{--                                                <span>{{$vanbandi->chuc_vu}}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label for="" class="col-md-4">Người nhập:</label>--}}
{{--                                                <span>{{$vanbandi->nguoitao->ho_ten ?? ''}}</span>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
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

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
