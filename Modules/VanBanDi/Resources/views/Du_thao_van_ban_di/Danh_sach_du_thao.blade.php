@extends('admin::layouts.master')
@section('page_title', 'Danh sách dự thảo')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dự thảo văn bản</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" width="5%">STT</th>
                                <th class="text-center" width="10%"> Ngày dự thảo
                                </th>
                                <th class="text-center" width="">Trích yếu
                                </th>
                                <th class="text-center" width="23%">Người xử lý</th>
                                <th class="text-center" width="23%">Các lần dự thảo</th>
                                <th class="text-center" width="5%">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_duthao as $key=>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td class="text-center">{{ date('d-m-Y', strtotime($data->ngay_thang)) }}</td>

                                    <td style="text-align: justify"><a
                                            href="@if($data->van_ban_den_don_vi_id != null){{route('xu-ly-van-ban.show',$data->van_ban_den_don_vi_id)}}@else#@endif"
                                            title="{{$data->vb_trich_yeu}}">{{$data->vb_trich_yeu}}</a><br>
                                        <span
                                            style="font-style: italic">Người nhập : {{$data->nguoiDung->ho_ten ?? ''}}&emsp;({{date_format($data->created_at, 'd-m-Y H:i:s')}})</span>
                                        <div class="text-right" style="margin: 15px 10px">
                                            @forelse($data->Duthaofile as $key=>$item)
                                                <a href="{{$item->getUrlFile()}}" class="seen-new-window"
                                                   target="popup">
                                                    @if($item->stt == 1)
                                                        [file phiếu trình]
                                                    @elseif($item->stt == 2)
                                                        [file trình ký]
                                                    @elseif($item->stt == 3)
                                                        [file hồ sơ]
                                                    @endif
                                                </a>@if(count($data->Duthaofile) == $key+1) @else &nbsp;|
                                                &nbsp; @endif
                                            @empty
                                            @endforelse
                                        </div>
                                        <a href="{{route('laythongtinduthaocu',$data->id)}}"
                                           role="button" class="btn btn-primary color-white">Tạo dự thảo
                                            lần {{$data -> lan_du_thao + 1}}</a>
                                        <a href="{{route('thongtinvanban',$data->id)}}" role="button"
                                           class="btn btn-success color-white">Tạo văn bản
                                            đi</a>
                                    </td>
                                    <td>
                                        <div class="form-control" style="height: 150px;overflow: auto">
                                            <span><span
                                                    style="color: red">(*)</span> Danh sách cán bộ trong phòng: </span><br>
                                            @forelse($data->canbotrongphong as $key=>$phong)
                                                <span style="font-style: italic">- {{$phong->nguoiDung->ho_ten ?? ''}} : <span
                                                        style="color: black;font-weight: bold">{{$phong->y_kien}}</span> &ensp;
                                                                                            @forelse($phong->gopyFilecanbophong as $key=>$item)
                                                        <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                                                   [Xem file]
                                                                                                </a>
                                                    @empty
                                                    @endforelse <br>
                                                                                        @empty
                                                    @endforelse
                                                                                        <span><span style="color: red">(*)</span> Danh sách cán bộ phòng khác:</span><br>
                                                                                        @forelse($data->canbophongkhac as $key=>$khac)
                                                        <span
                                                            style="font-style: italic">- {{$khac->nguoiDung->ho_ten ?? ''}} : <span
                                                                style="color: black;font-weight: bold">{{$khac->y_kien}}</span>&ensp;
                                                                                                @forelse($khac->gopyFilecanbophongngoai as $key=>$item)
                                                                <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                                                   [Xem file]
                                                                                                </a>
                                                            @empty
                                                            @endforelse <br>
                                            @empty
                                            @endforelse
                                        </div>
                                    <td>
                                        <div class="form-control" style="height: 150px;overflow: auto">
                                            @forelse($data->caclanduthao as $key=>$landuthao)
                                                <span style="color: black;font-weight: bold;font-style: italic">Lần {{$key+1}}: {{$landuthao->y_kien}} </span>
                                                <br>
                                                <span style="font-style: italic"><span style="color: red">(*)</span> Danh sách file: </span>
                                                <br>
                                                @forelse($landuthao->Duthaofile as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="_blank">
                                                        &emsp;<span style="font-style: italic">
                                                                                                                @if($item->stt == 1)
                                                                [file phiếu trình]
                                                            @elseif($item->stt == 2)
                                                                [file trình ký]
                                                            @elseif($item->stt == 3)
                                                                [file hồ sơ]
                                                            @endif
                                                                                                            </span>
                                                    </a>@if(count($data->Duthaofile) == $key+1) @else
                                                        &nbsp; @endif
                                                @empty
                                                @endforelse<br>
                                                <span style="font-style: italic"><span style="color: red">(*)</span> Danh sách cán bộ trong phòng: </span>
                                                <br>
                                                @forelse($landuthao->canbotrongphong as $key=>$phong)
                                                    <span style="font-style: italic">- {{$phong->nguoiDung->ho_ten ?? ''}} : <span
                                                            style="color: black;font-weight: bold">{{$phong->y_kien}}</span> &ensp;
                                                                                                @forelse($phong->gopyFilecanbophong as $key=>$item)
                                                            <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                                                       [Xem file]
                                                                                                    </a>
                                                        @empty
                                                        @endforelse <br>
                                                                                            @empty
                                                        @endforelse
                                                                                                <span><span
                                                                                                        style="color: red">(*)</span> Danh sách cán bộ trong phòng khác: </span><br>
                                                                                                     @forelse($landuthao->canbophongkhac as $key=>$khac)
                                                            <span
                                                                style="font-style: italic">- {{$khac->nguoiDung->ho_ten ?? ''}} : <span
                                                                    style="color: black;font-weight: bold">{{$khac->y_kien}}</span>&ensp;
                                                                                                    @forelse($khac->gopyFilecanbophongngoai as $key=>$item)
                                                                    <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                                                       [Xem file]
                                                                                                    </a>
                                                                @empty
                                                                @endforelse <br>
                                        @empty
                                        @endforelse
                                        @empty
                                        @endforelse

                                    </td>
                                    </td >
                                    @if(auth::user()->id == $data->nguoi_tao)
                                        @if($data->stt == 3)
                                        @else
                                            <td class="text-center" style="vertical-align: middle">
                                                <a href="{{route('du-thao-van-ban.edit',$data->id)}}"
                                                   class="fa fa-edit" role="button"
                                                   title="Sửa">
                                                    <i class="fas fa-file-signature"></i>
                                                </a><br><br>
                                                <a href=""
                                                   class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                   role="button" title="Xóa">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </tr>
                            @empty
                                <td colspan="8" class="text-center">Không có dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
