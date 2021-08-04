@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="{{route('Danhsachduthao')}}"  aria-expanded="false" class="nav-link {{ Route::is('Danhsachduthao')
                             ? 'active'  : '' }} ">
                            <i class="far fa-plus-square"></i> Danh sách dự thảo
                        </a>
                    </li><li class="nav-item">
                        <a href="{{route('danhsachduthaodagopy')}}"  aria-expanded="false" class="nav-link {{ Route::is('danhsachduthaodagopy')
                             ? 'active'  : '' }}">
                            <i class="far fa-plus-square"></i> Danh sách dự thảo cũ
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">

                            </div>
                            <div class="col-md-12" style=" width: 100%;overflow-x: auto;">

                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center" width="5%" >STT</th>
                                            <th class="text-center" width="9%"> Ngày dự thảo
                                            </th>
                                            <th class="text-center" width="8%">Ký hiệu
                                            </th>
                                            <th class="text-center" width="32%">Trích yếu
                                            </th>
                                            <th class="text-center" width="40%">Người xử lý</th>
                                            <th class="text-center" width="6%">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($ds_duthao as $key=>$data)
                                            <tr>
                                                <td style="vertical-align: middle" class="text-center">{{$key+1}}</td>
                                                <td style="vertical-align: middle" class="text-center">{{dateFormat('d/m/Y',$data->ngay_thang)}}</td>
                                                <td style="vertical-align: middle" class="text-center"> {{$data->so_ky_hieu}}</td>
                                                <td style="text-align: justify"><a href="{{route('du_thao_van_ban.edit',$data->id)}}"
                                                       title="{{$data->vb_trich_yeu}}">{{$data->vb_trich_yeu}}</a><br>
                                                    <span style="font-style: italic">Người nhập : {{$data->nguoiDung->ho_ten ?? ''}}&emsp;({{date_format($data->created_at, 'd-m-Y H:i:s')}})</span>
                                                    <div class="text-right" style="margin: 15px 10px">
                                                        @forelse($data->Duthaofile as $key=>$item)
                                                            <a href="{{$item->getUrlFile()}}" target="_blank">
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
                                                    <div class="col-md-12">
{{--                                                        <a href="{{route('laythongtinduthaocu',$data->id)}}"--}}
{{--                                                           role="button" class="btn btn-primary">Tạo dự thảo--}}
{{--                                                            lần {{$data -> lan_du_thao + 1}}</a>--}}

                                                    </div>
                                                </td>
                                                <td >
                                                    <div class="form-control" style="height: 100px;overflow: auto">
                                                        <span><span style="color: red">(*)</span> Danh sách cán bộ trong phòng: </span><br>
                                                        @forelse($data->canbotrongphong as $key=>$phong)
                                                            <span style="font-style: italic">- {{$phong->nguoiDung->ho_ten ?? ''}} : {{$phong->y_kien}} &ensp;
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
                                                                        style="font-style: italic">- {{$khac->nguoiDung->ho_ten ?? ''}}</span>
                                                                    <br>

                                                        @empty
                                                        @endforelse
                                                    </div>
                                                </td>


                                                <td class="text-center">
                                                    -
{{--                                                    @if($userAuth->id == $data->nguoi_tao)--}}
{{--                                                        @if($data->stt == 3)--}}
{{--                                                        @else--}}
{{--                                                            <a href="{{route('du_thao_van_ban.edit',$data->id)}}"--}}
{{--                                                               class="btn btn-color-blue btn-icon btn-light"--}}
{{--                                                               role="button"--}}
{{--                                                               title="Sửa">--}}
{{--                                                                <i class="fas fa-file-signature"></i>--}}
{{--                                                            </a><a href="{{route('deletegm',$data->id.'?type=2')}}"--}}
{{--                                                                   class="btn btn-color-red btn-icon btn-light btn-remove-item"--}}
{{--                                                                   role="button" title="Xóa">--}}
{{--                                                                <i class="far fa-trash-alt"></i></a>--}}
{{--                                                        @endif--}}
{{--                                                    @else--}}
{{--                                                        ---}}
{{--                                                    @endif--}}
                                                </td>

                                            </tr>
                                        @empty
                                            <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>
                                    </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
