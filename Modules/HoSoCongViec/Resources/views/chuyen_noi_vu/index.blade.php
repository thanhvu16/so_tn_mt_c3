@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
    <div class="container-fluid" >
        <div class="row">
            <div class="col-md-12" >
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="fas fa-user-check"></i><span style="font-size: 16px"> Đánh giá cán bộ</span>
                        </a>
                    </li>

                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="row">
                                <div class="col-md-4" style="margin: 10px 0px;">
                                    <form action="{{route('noivuxem')}}" method="get">
                                        <div class="col-md-12">
                                            <select
                                                class="form-control show-tick dropdown-search select-so-van-ban"
                                                data-don-vi="26" onchange="this.form.submit()"  name="thang"
                                                id="thang"
                                                required>
                                                <option value="1">--Tháng đánh giá--</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option
                                                        value="{{ $i }}" {{ empty(Request::get('thang')) && $i == $month ? 'selected' : Request::get('thang') == $i ? 'selected' : '' }} >
                                                        Tháng {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12">
{{--                                <form action="{{route('chitietdanhgia')}}" id="chuyen_noi_vu" method="post">--}}
                                    @csrf
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                        <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th width="4%" class="text-center" style="vertical-align: middle;">STT</th>
                                            <th width="30%" class="text-center" style="vertical-align: middle;">Phòng
                                            </th>
                                            <th width="30%" class="text-center" style="vertical-align: middle;">Cán bộ gửi
                                            </th>
                                            <th width="10%" class="text-center" style="vertical-align: middle;">Tháng
                                            </th>
                                            <th width="15%" class="text-center" style="vertical-align: middle;">Ngày gửi
                                            </th>
                                            <th class="text-center" style="vertical-align: middle;">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($layphongdadanhgia as $key=>$data)
                                            <tr>
                                                <td class="text-center" style="vertical-align: middle;">{{$key+1}}</td>
                                                <td class="text-center"
                                                    style="vertical-align: middle;">
                                                    <a href="{{route('chitietdanhgia',$data->thang.'?phong='.$data->phong)}}">{{$data->laytenphong->ten_don_vi ??''}}</a></td>
                                                <td class="text-center" style="vertical-align: middle;">

                                                {{$data->laytencanbogui->ho_ten ??''}}
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <b>{{$data->thang}}</b></td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    {{date_format($data->created_at, 'd-m-Y H:i:s')}}
                                                    <input type="text" name="thang" class="hidden" value="{{$data->thang}}">
                                                    <input type="text" name="phong" class="hidden" value="{{$data->phong}}">
                                                </td>

                                                <td class="text-center" ></td>
                                            </tr>
                                        @empty
                                            <td class="text-center" colspan="6">Không có dữ liệu</td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <!-- <div><b>Tổng văn bản: </b></div> -->
                                    <div class="text-right">
                                        <input type="text" class="hidden" value="{{Auth::user()->donvi_id}}" name="phong">
                                        <input type="text" class="hidden" value="{{Auth::user()->id}}" name="can_bo_chuyen">
                                        <input type="text" class="hidden" value="@if($thang){{$thang}}@else{{$month}}@endif" name="thang">

                                    </div>
{{--                                </form>--}}
                            </div>
                            <div class="col-md-12">

                                <br>

                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
