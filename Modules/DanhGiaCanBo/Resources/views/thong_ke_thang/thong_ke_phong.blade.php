@extends('admin::layouts.master')
@section('page_title', 'Đánh giá cán bộ')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Đánh giá cán bộ</h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-4">
                                <form action="{{route('thongkephongthang')}}" method="get">
                                        <select
                                            class="form-control  show-tick dropdown-search select-so-van-ban"
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

                                </form>
                            </div>
                        </div>
                        <form action="{{route('chuyennoivu')}}" id="chuyen_noi_vu" method="post">
                            @csrf
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                    <th width="2%" class="text-center" style="vertical-align: middle;">STT</th>
                                    <th width="12%" class="text-center" style="vertical-align: middle;">Cán bộ
                                    </th>
                                    {{--                                            <th width="12%" class="text-center" style="vertical-align: middle;">Phòng--}}
                                    {{--                                            </th>--}}
                                    <th width="5%" class="text-center" style="vertical-align: middle;">Tháng
                                    </th>
                                    <th width="8%" class="text-center" style="vertical-align: middle;">Cá nhân chấm
                                    </th><th width="18%" class="text-center" style="vertical-align: middle;">Cá nhân tự nhân xét
                                    </th>
                                    <th width="18%" class="text-center" style="vertical-align: middle;">Đánh giá
                                        của
                                        phó đơn vị
                                    </th>
                                    <th width="9%" class="text-center" style="vertical-align: middle;">Điểm cấp phó chấm
                                    </th>
                                    <th width="" class="text-center" style="vertical-align: middle;">Đánh giá
                                        của
                                        trưởng đơn vị
                                    </th>
                                    <th width="9%" class="text-center" style="vertical-align: middle;">Điểm cấp trưởng chấm
                                    </th>

                                </tr>
                                </thead>
                                <tbody>
                                @forelse($allcanbophong as $key=>$data)
                                    <tr>
                                        <td class="text-center" style="vertical-align: middle;">{{$key+1}}</td>
                                        <td style="vertical-align: middle;">
                                            <a href=""
                                               target="_blank" data-container="body" data-toggle="popover"
                                               data-trigger="hover" data-placement="top"
                                               data-content="Click chuột để xem chi tiết" data-original-title=""
                                               title="">
                                                <a href="{{route('chitietcanhan',$data->can_bo_goc.'?thangdanhgia='.$data->thang)}}">{{$data->canbodanhgia->ho_ten ??''}}</a>
                                            </a></td>
                                        {{--                                                <td class="text-left"--}}
                                        {{--                                                    style="vertical-align: middle;">{{$data->donvi->ten_don_vi ??''}}</td>--}}
                                        <td class="text-center" style="vertical-align: middle;">
                                            <b>{{$data->thang}}</b></td>
                                        <td class="text-center"
                                            style="vertical-align: middle;">{{$data->diem}}</td>
                                        <td class="text-left" style="vertical-align: middle;">
                                            {{$data->laynhanxetcanhan->nhan_xet ?? ''}}
                                        </td>
                                        <td class="text-left"> {{$data->laynhanxetphophong->nhan_xet ?? ''}}</td>
                                        <td class="text-center"> {{$data->laynhanxetphophong->diem ?? ''}}</td>
                                        <td class="text-left"> {{$data->nhan_xet ?? ''}}</td>
                                        <td class="text-center" style="font-weight: bold;color: red">{{$data->diem}}</td>

                                    </tr>
                                @empty
                                    <td class="text-center" colspan="9">Không có dữ liệu</td>
                                @endforelse
                                </tbody>
                            </table>
                            <!-- <div><b>Tổng văn bản: </b></div> -->
                            <div class="text-right">
                                <input type="text" class="hidden" value="{{Auth::user()->donvi_id}}" name="phong">
                                <input type="text" class="hidden" value="{{Auth::user()->id}}" name="can_bo_chuyen">
                                <input type="text" class="hidden" value="{{Auth::user()->id}}" name="id_cap_danh_gia">
                                <input type="text" class="hidden" value="@if($thang){{$thang}}@else{{$month}}@endif" name="thang">
                                @if($layphongdachuyennoivu == null && Auth::user()->vai_tro==2 && count($allcanbophong)>0 )
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fas fa-chalkboard-teacher"></i> Chuyển tổng hợp
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
