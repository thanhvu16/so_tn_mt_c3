@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
    <div class="container-fluid" >
        <div class="row">
            <div class="col-md-12" >
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="fas fa-user-check"></i><span style="font-size: 16px"> Đánh giá cán bộ cấp trưởng</span>
                        </a>
                    </li>

                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="row">
                                <div class="col-md-4" style="margin: 10px 0px;">
                                    <form action="{{route('captrendanhgiac2')}}" method="get">
                                        <select
                                            class="form-control show-tick dropdown-search select-so-van-ban"
                                            data-don-vi="26" onchange="this.form.submit()" name="thang"
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


                            <div class="table-responsive">
                                <table class=" table-bordered table-striped dataTable mb-0">
                                    <thead>
                                    <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <td widtd="3%" class="text-center" rowspan="3"
                                            style="vertical-align: middle;">TT
                                        </td>
                                        <td widtd="5%" class="text-left text-center" rowspan="3"
                                            style="vertical-align: middle;">Cán Bộ
                                        </td>
                                        <td widtd="2%" class="text-center" rowspan="3"
                                            style="vertical-align: middle;">Điểm Tối Đa
                                        </td>
                                        <th widtd="35%" colspan="9" class="text-center"
                                            style="vertical-align: middle;">Ý THỨC TỔ CHỨC KỶ LUẬT - tối
                                            đa:
                                            20 điểm
                                        </th>
                                        <th widtd="40%" colspan="14" class="text-center"
                                            style="vertical-align: middle;">KẾT QUẢ THỰC HIỆN NHIỆM VỤ -
                                            tối
                                            đa: 70
                                            điểm
                                        </th>
                                        <th widtd="15%" class="text-center" rowspan="2" colspan="3"
                                            style="vertical-align: middle;">
                                            ĐIỂM THƯỞNG <br> tối đa 10 điểm
                                        </th>
                                    </tr>
                                    <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <td widtd="15%" colspan="3" class="text-center"
                                            style="vertical-align: middle">Ý thức tổ
                                            chức kỷ luật, phẩm chất...
                                        </td>
                                        <td widtd="20%" colspan="6" class="text-center"
                                            style="vertical-align: middle">Thực hiện
                                            quy tắc ứng xử của cán bộ, ...
                                        </td>
                                        <td widtd="25%" colspan="10" class="text-center"
                                            style="vertical-align: middle">Năng lực
                                            và kỹ năng lãnh đạo, điều hành
                                        </td>
                                        <td widtd="20%" colspan="4" class="text-center"
                                            style="vertical-align: middle">Thực hiện
                                            nhiệm vụ theo kế hoạch, lịch công tác và các nhiệm...
                                        </td>


                                    </tr>
                                    <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                        <td style="vertical-align: middle;">Gương mẫu, tự giác chấp
                                            hành...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Tác
                                            phong, lề lối làm việc...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Giữ gìn
                                            đoàn kết nội bộ,...
                                        </td>


                                        <td class="text-center" style="vertical-align: middle;">Thực
                                            hiện
                                            tốt văn hoá công sở...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Chấp
                                            hành
                                            kỷ luật, kỷ cương ...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Đeo thẻ
                                            chức danh trong giờ ...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Có thái
                                            độ phục vụ nhân ...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Xây dựng
                                            hình ảnh, giữ gìn...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Giữ gìn
                                            bí mật của cơ ...
                                        </td>


                                        <td class="text-center" style="vertical-align: middle;">Chủ động
                                            nghiên cứu, cập nhật...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Xây dựng
                                            kế hoạch công tác ...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Chỉ đạo,
                                            điều hành, kiểm soát...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Triển
                                            khai, phân công nhiệm vụ...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Kiểm
                                            tra,
                                            bao quát, đôn đốc...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Có năng
                                            lực tập hợp CBCCVC...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Phối
                                            hợp,
                                            tạo lập mối quan...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Sử dụng
                                            thành thạo các phần...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Các văn
                                            bản ban hành thuộc...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">tham mưu
                                            tổ chức, chủ trì...
                                        </td>

                                        <td class="text-center" style="vertical-align: middle;">Hoàn
                                            thành từ 90%-100% công...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Hoàn
                                            thành từ 80% đến dưới 90% công...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Hoàn
                                            thành từ 70% - dưới 80% công...
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Hoàn
                                            thành dưới 70% công việc..
                                        </td>


                                        <td class="text-center" style="vertical-align: middle;">tham
                                            mưu,
                                            đề xuất giải pháp..
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">tham mưu
                                            có hiệu quả đối ..
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">Chủ
                                            động,
                                            sáng tạo,cải tiến..
                                        </td>


                                    </tr>
                                    </thead>


                                    @forelse($thongtincanhancham as $key=>$data)
                                        <form action="{{route('danhgiacaptrenc2')}}" method="post">
                                            @csrf
                                            <tbody>
                                            @if($data->trang_thai == 2 )
                                                <tr>
                                                    <td style="vertical-align: middle;" rowspan="7"
                                                        class="text-center">{{$key+1}}</td>
                                                    <td style="vertical-align: middle;">

                                                        <b style="color: red">Điểm tối đa <br>100</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            100
                                                        </b>

                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            2
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>3</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>3</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>

                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2<!--  --></b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>2</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>2</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>45-50<!--  --></b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>40-<45</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>35-<40</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b><35</b>
                                                        </b></td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>10</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>10</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>10<!--  --></b>
                                                    </td>


                                                </tr>

                                                <tr>
                                                    <td style="vertical-align: middle;">
                                                        <b style="color: blue"> {{$data->nguoidung->ho_ten}}   </b><i
                                                            style="color: blue">
                                                            ({{date_format($data->created_at, 'd-m-Y H:i:s')}})
                                                        </i><br>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b
                                                            style="color: blue">
                                                            {{$data->laychitietdanhgia->field_28 ?? ''}}
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b
                                                            style="color: blue">
                                                            {{$data->laychitietdanhgia->field_3 ?? ''}}
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_4 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_5 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">

                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_7 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_8 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_9 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_10 ?? ''}}</b>
                                                    </td>

                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_11 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_12 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_15 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_16 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_17 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_18 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_19 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_20 ?? ''}}<!--  --></b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b style="color: blue">{{$data->laychitietdanhgia->field_21 ?? ''}}</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b style="color: blue">{{$data->laychitietdanhgia->field_22 ?? ''}}</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_23 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_24 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" colspan="4" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_25 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" colspan="3" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_27 ?? ''}}</b>
                                                    </td>


                                                </tr>
                                                <tr  style="text-align: justify">
                                                    <td colspan="9"><i>Nhận xét cá nhân : {{$data->laydanhgia($data->id_dau_tien)->nhan_xet}}</i></td>
                                                    <td colspan="9">
                                                        {{--                                                    {{isset($laydanhgiaphophong) ? 'Nhận xét của cấp phó : '. $laydanhgiaphophong->nhan_xet : ''}} --}}
                                                        {{--                                                        <i>Nhận xét của cấp phó :{{$data->nhan_xet}}</i>--}}
                                                    </td>
                                                    <td colspan="10">
                                                        {{--                                                        <i>Nhận xét cấp trưởng : {{$data->laydanhgiacuoi($data->id_dau_tien)->nhan_xet}}</i>--}}
                                                    </td>


                                                </tr>


                                                <tr class="diem-chi-tiet">
                                                    <td rowspan="2" style="vertical-align: middle;"><a
                                                            href=""
                                                            data-container="body" data-toggle="popover"
                                                            data-trigger="hover"
                                                            data-placement="top"
                                                            data-content="Click chuột để xem chi tiết"
                                                            data-original-title="" title="">
                                                            <b>{{Auth::user()->ho_ten}}</b>
                                                        </a>

                                                        <br>
                                                        <b style="color: red"
                                                           id="tong_tat_{{ $data->id }}"> {{$data->diem}}
                                                            Điểm</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <input type="text" style="width: 100%"
                                                                   name="tong_canhan_tongdiem"
                                                                   readonly=""
                                                                   value="{{$data->diem}}"
                                                                   class=" tong_canhan_tongdiem"
                                                                   data-parent-id="{{$data->id}}">
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <input type="text" name="tong_canhan_ythuctochuckyluat"
                                                               readonly=""
                                                               value="20"
                                                               class="form-control hidden ">
                                                        <input type="text" name="tong_canhan_ythuc" readonly=""
                                                               value="6"
                                                               class="form-control hidden ">
                                                        <select name="canhan_ythuc1"
                                                                class="tong_select_1  select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option selected
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_3 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_3 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_3 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_3 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_3 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_ythuc2"
                                                                class="tong_select_1 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option selected
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_4 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_4 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_4 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_4 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_4 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>

                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_ythuc3"
                                                                class="tong_select_1 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_5 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_5 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_5 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_5 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_5 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <input type="text" name="tong_canhan_thuchien" readonly=""
                                                               value="14"
                                                               class="form-control hidden ">
                                                        <select name="canhan_thuchien1"
                                                                class="tong_select_2 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="3" {{ isset($data) && $data->laychitietdanhgia->field_7 == 3 ? 'selected' : '' }}>
                                                                3
                                                            </option>
                                                            <option
                                                                value="2.5" {{ isset($data) && $data->laychitietdanhgia->field_7 == 2.5 ? 'selected' : '' }}>
                                                                2.5
                                                            </option>
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_7 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_7 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_7 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_7 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_7 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_thuchien2"
                                                                class="tong_select_2 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="3" {{ isset($data) && $data->laychitietdanhgia->field_8 == 3 ? 'selected' : '' }}>
                                                                3
                                                            </option>
                                                            <option
                                                                value="2.5" {{ isset($data) && $data->laychitietdanhgia->field_8 == 2.5 ? 'selected' : '' }}>
                                                                2.5
                                                            </option>
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_8 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_8 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_8 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_8 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_8 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_thuchien3"
                                                                class="tong_select_2 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_9 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_9 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_9 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_9 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_9 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_thuchien4"
                                                                class=" tong_select_2 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_10 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_10 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_10 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_10 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_10 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_thuchien5"
                                                                class="tong_select_2 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_11 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_11 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_11 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_11 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_11 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_thuchien6"
                                                                class="tong_select_2 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_12 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_12 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_12 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_12 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_12 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <input type="text" name="tong_canhan_ketquathuchiennhiemvu"
                                                               readonly=""
                                                               value="70"
                                                               class="form-control hidden tong_canhan_ketquathuchiennhiemvu">
                                                        <input type="text" name="tong_canhan_nangluc" readonly=""
                                                               value="20"
                                                               class="form-control hidden tong_3">
                                                        <select name="canhan_nangluc1"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_15 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_15 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_15 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_15 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_15 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc2"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_16 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_16 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_16 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_16 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_16 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc3"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_17 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_17 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_17 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_17 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_17 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc4"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_18 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_18 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_18 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_18 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_18 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc5"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_19 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_19 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_19 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_19 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_19 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc6"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_20 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_20 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_20 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_20 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_20 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <select name="canhan_nangluc7"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_21 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_21 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_21 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_21 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_21 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc8"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_22 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_22 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_22 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_22 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_22 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc9"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_23 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_23 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_23 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_23 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_23 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <select name="canhan_nangluc10"
                                                                class="tong_select_3 select-value"
                                                                data-id="{{ $data->id }}">
                                                            <option
                                                                value="2" {{ isset($data) && $data->laychitietdanhgia->field_24 == 2 ? 'selected' : '' }}>
                                                                2
                                                            </option>
                                                            <option
                                                                value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_24 == 1.5 ? 'selected' : '' }}>
                                                                1.5
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($data) && $data->laychitietdanhgia->field_24 == 1 ? 'selected' : '' }}>
                                                                1
                                                            </option>
                                                            <option
                                                                value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_24 == 0.5 ? 'selected' : '' }}>
                                                                0.5
                                                            </option>
                                                            <option
                                                                value="0" {{ isset($data) && $data->laychitietdanhgia->field_24 == 0 ? 'selected' : '' }}>
                                                                0
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td class="text-center" colspan="4" style="vertical-align: middle;">
                                                        <b>
                                                            <select name="tong_canhan_thuchiennhiemvu"
                                                                    class="tong_select_4 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                @for($i = 50; $i > 0; $i = $i-0.5)
                                                                    <option
                                                                        value="{{$i}}" {{ isset($data) && $data->laychitietdanhgia->field_25 == $i ? 'selected' : '' }}>{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                        </b></td>

                                                    <td class="text-center" colspan="3" style="vertical-align: middle;">
                                                        <input type="text" name="tong_canhan_diemthuong" readonly=""
                                                               value="10"
                                                               class="form-control hidden tong_canhan_diemthuong">
                                                        <select name="canhan_diemthuong1"
                                                                class=" select_thuong  select-value"
                                                                data-id="{{ $data->id }}">
                                                            @for($i = 10; $i > 0; $i = $i-0.5)
                                                                <option
                                                                    value="{{$i}}" {{ isset($data) && $data->laychitietdanhgia->field_27 == $i ? 'selected' : '' }}>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                        <input type="text" class="hidden"
                                                               value="{{$data->laychitietdanhgia->mau_chi_tieu}}"
                                                               name="mau_chi_tieu">
                                                        <input type="text" class="hidden"
                                                               value="{{$data->can_bo_chuyen}}" name="can_bo_goc">
                                                        <input type="text" class="hidden" value="{{$data->thang}}"
                                                               name="thang">
                                                        <input type="text" class="hidden" value="{{$data->id}}"
                                                               name="id_danh_gia">
                                                    </td>


                                                </tr>
                                                <tr>
                                                    <td colspan="22" style="vertical-align: middle;">

                                                        <textarea class="form-control" name="nhan_xet"
                                                                  style=" font-size: 16px" rows="5"
                                                                  placeholder="Nhận xét của trưởng đơn vị"></textarea>
                                                    </td>
                                                    <td colspan="5" class="text-center" style="vertical-align: middle;">
                                                        @if(Auth::user()->vai_tro == 2)
                                                        @else
                                                            <select name="lanhdao"
                                                                    class="form-control select2-search">
                                                                {{--                                                            <option value="">-- Chọn lãnh đạo phụ trách --</option>--}}
                                                                @foreach($nguoinhan as $nguoi_nhan)
                                                                    <option
                                                                        value="{{$nguoi_nhan->id}}">{{$nguoi_nhan->ho_ten}}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                        <div style="margin-top: 10px">
                                                            <button type="submit" name="chamdiem" value=""
                                                                    class="btn btn-primary" title=""><i
                                                                    class="fas fa-pen-alt"></i> Chấm điểm
                                                            </button>
                                                        </div>

                                                    </td>
                                                </tr>

                                            @else

                                                <tr>
                                                    <td style="vertical-align: middle;" rowspan="8"
                                                        class="text-center">{{$key+1}}</td>
                                                    <td style="vertical-align: middle;">

                                                        <b style="color: red">Điểm tối đa <br>100</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            100
                                                        </b>

                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            2
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>3</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>3</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>

                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2<!--  --></b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>2</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>2</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>2</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>45-50<!--  --></b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>40-<45</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b>35-<40</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b><35</b>
                                                        </b></td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>10</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>10</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>10<!--  --></b>
                                                    </td>


                                                </tr>

                                                @if($data->nguoidung->id == $data->nguoidung5($data->id_dau_tien))
                                                @else
                                                    {{--                                                    đây là phần đánh giá của chuyên viên--}}
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->nguoidung2($data->id_dau_tien)}} </b><i
                                                                style="color: blue">
                                                                ({{date_format($data->ngaydanhgia($data->id_dau_tien), 'd-m-Y H:i:s')}}
                                                                )
                                                            </i><br>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b
                                                                style="color: blue">
                                                                {{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_28 ?? ''}}
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;"><b
                                                                style="color: blue">
                                                                {{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_3 ?? ''}}
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_4 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_5 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" style="vertical-align: middle;">

                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_7 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_8 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_9 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_10 ?? ''}}</b>
                                                        </td>

                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_11 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_12 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_15 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_16 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_17 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_18 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_19 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_20 ?? ''}}<!--  --></b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_21 ?? ''}}</b>
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_22 ?? ''}}</b>
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_23 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_24 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" colspan="4"
                                                            style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_25 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" colspan="3"
                                                            style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgia($data->id_dau_tien)->laychitietdanhgia->field_27 ?? ''}}</b>
                                                        </td>


                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td style="vertical-align: middle;">
                                                        <b style="color: blue"> {{$data->nguoidung->ho_ten}}   </b><i
                                                            style="color: blue">
                                                            ({{date_format($data->created_at, 'd-m-Y H:i:s')}})
                                                        </i><br>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b
                                                            style="color: blue">
                                                            {{$data->laychitietdanhgia->field_28 ?? ''}}
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b
                                                            style="color: blue">
                                                            {{$data->laychitietdanhgia->field_3 ?? ''}}
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_4 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_5 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">

                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_7 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_8 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_9 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_10 ?? ''}}</b>
                                                    </td>

                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_11 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_12 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_15 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_16 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_17 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_18 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_19 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_20 ?? ''}}<!--  --></b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b style="color: blue">{{$data->laychitietdanhgia->field_21 ?? ''}}</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;"><b>
                                                            <b style="color: blue">{{$data->laychitietdanhgia->field_22 ?? ''}}</b>
                                                        </b></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_23 ?? ''}}</b>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_24 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" colspan="4" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_25 ?? ''}}</b>
                                                    </td>


                                                    <td class="text-center" colspan="3" style="vertical-align: middle;">
                                                        <b style="color: blue">{{$data->laychitietdanhgia->field_27 ?? ''}}</b>
                                                    </td>


                                                </tr>

                                                @if($data->trang_thai == 4)

                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->nguoidung3($data->id_dau_tien)}} </b><i
                                                                style="color: blue">
                                                                ({{date_format($data->ngaydanhgiacuoi($data->id_dau_tien), 'd-m-Y H:i:s')}}
                                                                )
                                                            </i><br>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b
                                                                style="color: blue">
                                                                {{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_28 ?? ''}}
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;"><b
                                                                style="color: blue">
                                                                {{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_3 ?? ''}}
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_4 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_5 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" style="vertical-align: middle;">

                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_7 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_8 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_9 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_10 ?? ''}}</b>
                                                        </td>

                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_11 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_12 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_15 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_16 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_17 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_18 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_19 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_20 ?? ''}}<!--  --></b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_21 ?? ''}}</b>
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_22 ?? ''}}</b>
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_23 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_24 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" colspan="4"
                                                            style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_25 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" colspan="3"
                                                            style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->laydanhgiacuoi($data->id_dau_tien)->laychitietdanhgia->field_27 ?? ''}}</b>
                                                        </td>


                                                    </tr>
                                                    <tr  style="text-align: justify">
                                                        <td colspan="9"><i>Nhận xét cá nhân : {{$data->laydanhgia($data->id_dau_tien)->nhan_xet}}</i></td>
                                                        <td colspan="9">
                                                            {{--                                                    {{isset($laydanhgiaphophong) ? 'Nhận xét của cấp phó : '. $laydanhgiaphophong->nhan_xet : ''}} --}}
                                                            @if($data->canbodanhgia->vai_tro == 3 )
                                                            @else
                                                                @if($data->laydanhgia($data->id_dau_tien)->nguoinhan->vai_tro == 3 )
                                                                    <i>Nhận xét của cấp phó :{{$data->nhan_xet}}</i>  {{$data->laydanhgia($data->id_dau_tien)->nguoinhan->vai_tro}}
                                                                @else
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td colspan="10"> <i>Nhận xét cấp trưởng : {{$data->laydanhgiacuoi($data->id_dau_tien)->nhan_xet}}</i></td>


                                                    </tr>
                                                @elseif(($data->trang_thai == 3 || $data->trang_thai == 4) && Auth::user()->vai_tro == 3)

                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layhotenphophongdanhgia($data->id_dau_tien,$data->can_bo_chuyen)}}  </b><i
                                                                style="color: blue">
                                                                ({{date_format($data->ngaydanhgiacuoi($data->id_dau_tien), 'd-m-Y H:i:s')}}
                                                                )
                                                            </i><br>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b
                                                                style="color: blue">
                                                                {{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_28 ?? ''}}
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;"><b
                                                                style="color: blue">
                                                                {{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_3 ?? ''}}
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_4 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_5 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" style="vertical-align: middle;">

                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_7 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_8 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_9 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_10 ?? ''}}</b>
                                                        </td>

                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_11 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_12 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_15 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_16 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_17 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_18 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_19 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_20 ?? ''}}<!--  --></b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_21 ?? ''}}</b>
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_22 ?? ''}}</b>
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_23 ?? ''}}</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_24 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" colspan="4"
                                                            style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_25 ?? ''}}</b>
                                                        </td>


                                                        <td class="text-center" colspan="3"
                                                            style="vertical-align: middle;">
                                                            <b style="color: blue">{{$data->layphophongdanhgia($data->id_dau_tien,$data->can_bo_nhan)->laychitietdanhgia->field_27 ?? ''}}</b>
                                                        </td>


                                                    </tr>
                                                @else
                                                    <tr  style="text-align: justify">
                                                        <td colspan="9"><i>nhận xét cá nhân : {{$data->laydanhgia($data->id_dau_tien)->nhan_xet}}</i></td>
                                                        <td colspan="9">
                                                            {{--                                                    {{isset($laydanhgiaphophong) ? 'Nhận xét của cấp phó : '. $laydanhgiaphophong->nhan_xet : ''}} --}}
                                                            <i>Nhận xét của cấp phó :{{$data->nhan_xet}}</i>
                                                        </td>
                                                        <td colspan="10"> {{isset($laydanhgiatruongphong) ? 'Nhận xét của cấp trưởng : '. $laydanhgiatruongphong->nhan_xet : ''}}</td>


                                                    </tr>
                                                    <tr class="diem-chi-tiet">
                                                        <td rowspan="2" style="vertical-align: middle;"><a
                                                                href=""
                                                                data-container="body" data-toggle="popover"
                                                                data-trigger="hover"
                                                                data-placement="top"
                                                                data-content="Click chuột để xem chi tiết"
                                                                data-original-title="" title="">
                                                                <b>{{Auth::user()->ho_ten}}</b>
                                                            </a>

                                                            <br>
                                                            <b style="color: red"
                                                               id="tong_tat_{{ $data->id }}"> {{$data->diem}}
                                                                Điểm</b>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <input type="text" style="width: 100%"
                                                                       name="tong_canhan_tongdiem"
                                                                       readonly=""
                                                                       value="{{$data->diem}}"
                                                                       class=" tong_canhan_tongdiem"
                                                                       data-parent-id="{{$data->id}}">
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <input type="text" name="tong_canhan_ythuctochuckyluat"
                                                                   readonly=""
                                                                   value="20"
                                                                   class="form-control hidden ">
                                                            <input type="text" name="tong_canhan_ythuc" readonly=""
                                                                   value="6"
                                                                   class="form-control hidden ">
                                                            <select name="canhan_ythuc1"
                                                                    class="tong_select_1  select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option selected
                                                                        value="2" {{ isset($data) && $data->laychitietdanhgia->field_3 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_3 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_3 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_3 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_3 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>

                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_ythuc2"
                                                                    class="tong_select_1 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option selected
                                                                        value="2" {{ isset($data) && $data->laychitietdanhgia->field_4 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_4 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_4 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_4 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_4 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>

                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_ythuc3"
                                                                    class="tong_select_1 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_5 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_5 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_5 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_5 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_5 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <input type="text" name="tong_canhan_thuchien" readonly=""
                                                                   value="14"
                                                                   class="form-control hidden ">
                                                            <select name="canhan_thuchien1"
                                                                    class="tong_select_2 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="3" {{ isset($data) && $data->laychitietdanhgia->field_7 == 3 ? 'selected' : '' }}>
                                                                    3
                                                                </option>
                                                                <option
                                                                    value="2.5" {{ isset($data) && $data->laychitietdanhgia->field_7 == 2.5 ? 'selected' : '' }}>
                                                                    2.5
                                                                </option>
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_7 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_7 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_7 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_7 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_7 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_thuchien2"
                                                                    class="tong_select_2 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="3" {{ isset($data) && $data->laychitietdanhgia->field_8 == 3 ? 'selected' : '' }}>
                                                                    3
                                                                </option>
                                                                <option
                                                                    value="2.5" {{ isset($data) && $data->laychitietdanhgia->field_8 == 2.5 ? 'selected' : '' }}>
                                                                    2.5
                                                                </option>
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_8 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_8 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_8 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_8 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_8 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_thuchien3"
                                                                    class="tong_select_2 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_9 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_9 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_9 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_9 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_9 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_thuchien4"
                                                                    class=" tong_select_2 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_10 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_10 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_10 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_10 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_10 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>

                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_thuchien5"
                                                                    class="tong_select_2 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_11 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_11 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_11 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_11 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_11 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_thuchien6"
                                                                    class="tong_select_2 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_12 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_12 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_12 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_12 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_12 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <input type="text" name="tong_canhan_ketquathuchiennhiemvu"
                                                                   readonly=""
                                                                   value="70"
                                                                   class="form-control hidden tong_canhan_ketquathuchiennhiemvu">
                                                            <input type="text" name="tong_canhan_nangluc" readonly=""
                                                                   value="20"
                                                                   class="form-control hidden tong_3">
                                                            <select name="canhan_nangluc1"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_15 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_15 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_15 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_15 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_15 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc2"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_16 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_16 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_16 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_16 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_16 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc3"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_17 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_17 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_17 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_17 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_17 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc4"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_18 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_18 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_18 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_18 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_18 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc5"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_19 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_19 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_19 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_19 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_19 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc6"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_20 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_20 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_20 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_20 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_20 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;"><b>
                                                                <select name="canhan_nangluc7"
                                                                        class="tong_select_3 select-value"
                                                                        data-id="{{ $data->id }}">
                                                                    <option
                                                                        value="2" {{ isset($data) && $data->laychitietdanhgia->field_21 == 2 ? 'selected' : '' }}>
                                                                        2
                                                                    </option>
                                                                    <option
                                                                        value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_21 == 1.5 ? 'selected' : '' }}>
                                                                        1.5
                                                                    </option>
                                                                    <option
                                                                        value="1" {{ isset($data) && $data->laychitietdanhgia->field_21 == 1 ? 'selected' : '' }}>
                                                                        1
                                                                    </option>
                                                                    <option
                                                                        value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_21 == 0.5 ? 'selected' : '' }}>
                                                                        0.5
                                                                    </option>
                                                                    <option
                                                                        value="0" {{ isset($data) && $data->laychitietdanhgia->field_21 == 0 ? 'selected' : '' }}>
                                                                        0
                                                                    </option>
                                                                </select>
                                                            </b></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc8"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_22 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_22 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_22 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_22 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_22 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc9"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_23 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_23 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_23 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_23 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_23 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <select name="canhan_nangluc10"
                                                                    class="tong_select_3 select-value"
                                                                    data-id="{{ $data->id }}">
                                                                <option
                                                                    value="2" {{ isset($data) && $data->laychitietdanhgia->field_24 == 2 ? 'selected' : '' }}>
                                                                    2
                                                                </option>
                                                                <option
                                                                    value="1.5" {{ isset($data) && $data->laychitietdanhgia->field_24 == 1.5 ? 'selected' : '' }}>
                                                                    1.5
                                                                </option>
                                                                <option
                                                                    value="1" {{ isset($data) && $data->laychitietdanhgia->field_24 == 1 ? 'selected' : '' }}>
                                                                    1
                                                                </option>
                                                                <option
                                                                    value="0.5" {{ isset($data) && $data->laychitietdanhgia->field_24 == 0.5 ? 'selected' : '' }}>
                                                                    0.5
                                                                </option>
                                                                <option
                                                                    value="0" {{ isset($data) && $data->laychitietdanhgia->field_24 == 0 ? 'selected' : '' }}>
                                                                    0
                                                                </option>
                                                            </select>
                                                        </td>

                                                        <td class="text-center" colspan="4"
                                                            style="vertical-align: middle;">
                                                            <b>
                                                                <select name="tong_canhan_thuchiennhiemvu"
                                                                        class="tong_select_4 select-value"
                                                                        data-id="{{ $data->id }}">
                                                                    @for($i = 50; $i > 0; $i = $i-0.5)
                                                                        <option
                                                                            value="{{$i}}" {{ isset($data) && $data->laychitietdanhgia->field_25 == $i ? 'selected' : '' }}>{{$i}}</option>
                                                                    @endfor
                                                                </select>
                                                            </b></td>

                                                        <td class="text-center" colspan="3"
                                                            style="vertical-align: middle;">
                                                            <input type="text" name="tong_canhan_diemthuong" readonly=""
                                                                   value="10"
                                                                   class="form-control hidden tong_canhan_diemthuong">
                                                            <select name="canhan_diemthuong1"
                                                                    class=" select_thuong  select-value"
                                                                    data-id="{{ $data->id }}">
                                                                @for($i = 10; $i > 0; $i = $i-0.5)
                                                                    <option
                                                                        value="{{$i}}" {{ isset($data) && $data->laychitietdanhgia->field_27 == $i ? 'selected' : '' }}>{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                            <input type="text" class="hidden"
                                                                   value="{{$data->laychitietdanhgia->mau_chi_tieu}}"
                                                                   name="mau_chi_tieu">
                                                            <input type="text" class="hidden"
                                                                   value="{{$data->can_bo_chuyen}}" name="can_bo_goc">
                                                            <input type="text" class="hidden" value="{{$data->thang}}"
                                                                   name="thang">
                                                            <input type="text" class="hidden" value="{{$data->id}}"
                                                                   name="id_danh_gia">

                                                        </td>


                                                    </tr>
                                                @endif


                                                @if ((Auth::user()->vai_tro == 2 )&& ($data->trang_thai == 3))
                                                    <tr>
                                                        <td colspan="23" style="vertical-align: middle;">

                                                        <textarea class="form-control" name="nhan_xet"
                                                                  style=" font-size: 16px" rows="5"
                                                                  placeholder="Nhận xét của trưởng đơn vị"></textarea>
                                                        </td>
                                                        <td colspan="5" class="text-center"
                                                            style="vertical-align: middle;">
                                                            @if(Auth::user()->vai_tro == 2)
                                                            @else
                                                                <select name="lanhdao"
                                                                        class="form-control select2-search">
{{--                                                                    <option value="">-- Chọn lãnh đạo phụ trách ----}}
{{--                                                                    </option>--}}
                                                                    @foreach($nguoinhan as $nguoi_nhan)
                                                                        <option
                                                                            value="{{$nguoi_nhan->id}}">{{$nguoi_nhan->ho_ten}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                            <div style="margin-top: 10px">
                                                                <button type="submit" name="chamdiem" value="1"
                                                                        class="btn btn-primary luulai" title=""><i
                                                                        class="fas fa-pen-alt"></i> Chấm điểm
                                                                </button>
                                                                <div class="gmoi hidden">
                                                                    <button type="button"  id="btnSubmit" disabled  class="btn btn-primary pull-right  " >Chấm điểm
                                                                    </button>
                                                                </div>
                                                            </div>


                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif

                                            </tbody>
                                        </form>
                                    @empty
                                        <div class="col-md-12 text-center">
                                            <td colspan="29" class="text-center">Cán bộ cấp dưới chưa đánh giá !
                                            </td>
                                        </div>
                                    @endforelse

                                </table>
                            </div>


                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var total = 0;
        let $this = null;

        $('.luulai').click(function () {
            var luulai = $('.luulai').val();
            if (luulai == 1) {
                $('.luulai').addClass('hidden');
                $('.gmoi').removeClass('hidden');
            }else {

            }
        });

        $('.select-value').on('change', function () {
            total = 0;
            let id = $(this).data('id');

            $(this).parents('.diem-chi-tiet').find('.select-value').each(function () {
                let diem = parseFloat($(this).val());
                if (!isNaN(diem)) {
                    total += diem;
                }
            });

            $(this).parents('.diem-chi-tiet').find('input[name="tong_canhan_tongdiem"]').val(total);
            $(this).parents('.diem-chi-tiet').find('#tong_tat_' + id).text(total);

        });
    </script>
@endsection
