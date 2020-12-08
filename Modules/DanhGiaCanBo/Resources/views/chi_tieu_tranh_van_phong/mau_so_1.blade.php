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
                            <div class="col-md-6">
                                <form action="{{route('danh-gia-can-bo-c2.index')}}"  id="myFormId" method="get">
                                    <select
                                        class="form-control show-tick dropdown-search select-so-van-ban lay-thang"
                                        data-don-vi="26" name="thang_danh_gia" id="thang"
                                        onchange="this.form.submit()"
                                        required>
                                        <option value="1">--Tháng đánh giá--</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option
                                                value="{{ $i }}" {{ empty(Request::get('thang_danh_gia')) && $i == $month ? 'selected' : Request::get('thang_danh_gia') == $i ? 'selected' : '' }} >
                                                Tháng {{ $i }}</option>
                                        @endfor

                                    </select>
                                </form>
                            </div>
                        </div>
                        <form action="{{route('danh-gia-can-bo-c2.store')}}" method="post">
                            @csrf

                                <table id="" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" width="5%" style="vertical-align: middle"
                                            class="text-center vertical">TT
                                        </th>
                                        <th rowspan="2" width="55%" style="vertical-align: middle"
                                            class="text-center vertical">Nội dung đánh giá
                                        </th>
                                        <th rowspan="2" width="10%" style="vertical-align: middle"
                                            class="text-center vertical">Điểm tối đa
                                        </th>
                                        <th colspan="3" width="30%" class="text-center vertical">Kết quả đánh giá
                                        </th>
                                    </tr>
                                    <tr>
                                        <th width="10%" class="text-center vertical">Điểm cá nhân chấm</th>
                                        <th width="10%" class="text-center vertical" style="vertical-align: middle">Điểm cấp phó </th>
                                        <th width="10%" class="text-center vertical" style="vertical-align: middle">Điểm cấp trưởng </th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th class="text-center vertical">I</th>
                                        <th>Ý THỨC TỔ CHỨC KỶ LUẬT</th>
                                        <th class="text-center">20</th>


                                        <th class="vertical text-center" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <input type="text"  name="tong_canhan_ythuctochuckyluat"
                                                       readonly=""
                                                       value="20"
                                                       class="form-control tong_I">
                                            @else
                                                <b class="text-center">{{ isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_1 : ''}}</b>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_1 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_1 : ''}}</th>


                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">1</td>
                                        <td>Ý thức tổ chức kỷ luật; phẩm chất đạo đức; lối sống, tác phong, lề lối
                                            làm
                                            việc chuẩn mực, lành mạnh. Đoàn kết, thực hiện nguyên tắc tập trung dân
                                            chủ
                                            trong cơ quan, đơn vị.
                                        </td>
                                        <th class="text-center vertical"><i>6</i></th>
                                        <th class="vertical text-center" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <input type="text" name="tong_canhan_ythuc" readonly=""
                                                       value="6"
                                                       class="form-control total1">
                                            @else
                                                <span class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_2 : ''}}</span>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_2 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_2 : ''}}</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Gương mẫu, tự giác chấp hành tốt đường lối, chủ trương của Đảng,
                                                chính
                                                sách pháp luật của Nhà nước; nội quy, quy chế của cơ quan trong thực
                                                thi
                                                công vụ; gương mẫu về đạo đức, lối sống.</i></td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_ythuc1"
                                                        class="form-control tong_select_1 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_3 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_3 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_3 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Tác phong, lề lối làm việc chuẩn mực, tận tụy, trung thực trong công
                                                việc, sẵn sàng nhận nhiệm vụ.</i></td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_ythuc2"
                                                        class="form-control tong_select_1 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_4 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_4 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_4 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Giữ gìn đoàn kết nội bộ, xây dựng môi trường làm việc dân chủ, kỷ
                                                cương.</i></td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_ythuc3"
                                                        class="form-control tong_select_1 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_5 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_5 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_5 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">2</td>
                                        <td>Thực hiện quy tắc ứng xử của cán bộ, công chức, viên chức, lao động hợp
                                            đồng
                                            trong các cơ quan thuộc thành phố Hà Nội.
                                        </td>
                                        <th class="text-center vertical"><i>14</i></th>
                                        <th class="vertical text-center" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <input type="text" name="tong_canhan_thuchien"
                                                       readonly=""
                                                       value="14"
                                                       class="form-control total2 tong_canhan_thuchien">
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_6 : ''}}</h4>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_6 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_6 : ''}}</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Thực hiện tốt văn hoá công sở; Giao tiếp thân thiện, lịch sự, ngôn
                                                ngữ
                                                chuẩn mực, rõ ràng. Mặc trang phục lịch sự, phù hợp với hoàn cảnh,
                                                tính
                                                chất công việc, đúng quy định của cơ quan, đơn vị.</i></td>
                                        <td class="text-center vertical"><i>3</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_thuchien1"
                                                        class="form-control tong_select_2 select2-search">
                                                    <option value="3">3</option>
                                                    <option value="2.5">2.5</option>
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_7 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_7 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_7 : ''}}</th>

                                    </tr>

                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Chấp hành kỷ luật, kỷ cương hành chính, sử dụng hiệu quả thời gian
                                                làm
                                                việc; </i></td>
                                        <td class="text-center vertical"><i>3</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_thuchien2"
                                                        class="form-control tong_select_2 select2-search">
                                                    <option value="3">3</option>
                                                    <option value="2.5">2.5</option>
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_8 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_8 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_8 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Đeo thẻ chức danh trong giờ làm việc. Sắp xếp, bài trí chỗ làm việc
                                                gọn
                                                gàng, ngăn nắp.</i></td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_thuchien3"
                                                        class="form-control tong_select_2 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{ isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_9 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_9 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_9 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Có thái độ phục vụ nhân dân đúng mực; không hách dịch, cửa quyền,
                                                phiền
                                                hà, tiêu cực trong thực hiện công vụ. Chấp hành quy định ra, vào cơ
                                                quan; quản lý bảo vệ tài sản, trang thiết bị của cơ quan, đơn vị,
                                                phòng
                                                làm việc.</i></td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_thuchien4"
                                                        class="form-control tong_select_2 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_10 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_10 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_10 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Xây dựng hình ảnh, giữ gìn uy tín cho bản thân, cơ quan, đơn vị và
                                                đồng
                                                nghiệp.</i></td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_thuchien5"
                                                        class="form-control tong_select_2 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_11 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_11 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_11 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td><i>Giữ gìn bí mật của cơ quan, đơn vị và thực hiện nghiêm kỷ luật phát
                                                ngôn.</i></td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_thuchien6"
                                                        class="form-control tong_select_2 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_12 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_12 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle"> {{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_12 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical"></td>
                                        <td>
                                            <p><b>Nội dung bị trừ điểm:</b></p>
                                            <p><i>+ Có phản ảnh, kiến nghị của nhân dân về thái độ, chất lượng phục
                                                    vụ
                                                    (xác minh sự việc là đúng)</i></p>
                                            <p><i>+ Thực hiện không nghiêm quy định về văn hóa công sở; Mặc trang
                                                    phục
                                                    không phù hợp và không đúng Nội quy cơ quan</i></p>
                                            <p><i>+ Đi làm muộn, về sớm, không có mặt tại vị trí làm việc (không có
                                                    lý
                                                    do)</i></p>
                                            <p><i>+ Đi họp muộn hoặc không đi họp mà không xin phép; Không kịp thời
                                                    báo
                                                    cáo Lãnh đạo nội dung cuộc họp được phân công dự họp</i></p>
                                            <p><i>+ Không đeo thẻ chức danh trong giờ làm việc</i></p>
                                            <p><i>+ Có hành vi (lời nói, việc làm) gây ảnh hưởng tới uy tín của bản
                                                    thân, đồng nghiệp, cơ quan</i></p>
                                            <p><i>+ Phát ngôn làm lộ bí mật của cơ quan hoặc phát ngôn không đúng sự
                                                    thật, làm ảnh hưởng tới cơ quan.</i></p>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center vertical">II</th>
                                        <th>KẾT QUẢ THỰC HIỆN NHIỆM VỤ</th>
                                        <th class="text-center vertical">70</th>
                                        <th class="vertical text-center" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <input type="text"
                                                       name="tong_canhan_ketquathuchiennhiemvu"
                                                       readonly=""
                                                       value="70"
                                                       class="form-control tong_canhan_ketquathuchiennhiemvu">
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_13 : ''}}</h4>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_13 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_13 : ''}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center vertical">1</th>
                                        <th>Năng lực và kỹ năng lãnh đạo, điều hành</th>
                                        <th class="text-center vertical">20</th>
                                        <th class="vertical text-center" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <input type="text" name="tong_canhan_nangluc"
                                                       readonly=""
                                                       value="20"
                                                       class="form-control tong_3">
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_14 : ''}}</h4>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_14 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_14 : ''}}</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Chủ động nghiên cứu, cập nhật kịp thời các kiến thức pháp luật và chuyên
                                            môn
                                            nghiệp vụ; tham mưu đầy đủ, có chất lượng các văn bản phục vụ công tác
                                            chỉ
                                            đạo, điều hành của đơn vị/bộ phận theo chỉ đạo của lãnh đạo và kế hoạch
                                            công
                                            tác.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc1"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_15 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_15 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_15 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Xây dựng kế hoạch công tác của đơn vị theo lĩnh vực được phân công và kế
                                            hoạch công tác của cá nhân rõ nội dung, tiến độ thực hiện.
                                        </td>
                                        <td class="text-center vertical"><i>2</i></td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc2"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_16 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_16 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_16 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Chỉ đạo, điều hành, kiểm soát việc thực hiện nhiệm vụ của đơn vị/bộ phận
                                            đảm
                                            bảo kịp thời, không bỏ sót nhiệm vụ. Giải quyết công việc đúng quy trình
                                            quy
                                            định.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc3"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_17 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_17 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_17 : ''}}</th>


                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Triển khai, phân công nhiệm vụ và điều phối công việc cho cấp dưới linh
                                            hoạt, có chỉ đạo định hướng, hướng dẫn.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc4"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_18 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_18 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_18 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Kiểm tra, bao quát, đôn đốc việc thực hiện nhiệm vụ của CBCCVC trong đơn
                                            vị/bộ phận và giải quyết kịp thời những khó khăn, vướng mắc theo thẩm
                                            quyền.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc5"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_19 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_19 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_19 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Có năng lực tập hợp CBCCVC, xây dựng đơn vị/bộ phận đoàn kết, thống
                                            nhất.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc6"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_20 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_20 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_20 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Phối hợp, tạo lập mối quan hệ tốt với cá nhân, tổ chức có liên quan
                                            trong
                                            thực hiện nhiệm vụ.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc7"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_21 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_21 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_21 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Sử dụng thành thạo các phần mềm, ứng dụng CNTT đáp ứng yêu cầu quản lý,
                                            điều
                                            hành, giải quyết công việc.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc8"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_22 : ''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_22 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_22 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Các văn bản ban hành thuộc lĩnh vực phụ trách đảm bảo đúng thể thức, quy
                                            trình, thủ tục, không có sai sót. 100% văn bản trên phần mềm quản lý văn
                                            bản
                                            và điều hành tác nghiệp thuộc trách nhiệm được xử lý kịp thời, đúng quy
                                            trình.
                                        </td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc9"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_23 : ''}}</h4>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_23 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_23 : ''}}</th>
                                        @endif
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Tham mưu tổ chức, chủ trì, điều hành cuộc họp hiệu quả.</td>
                                        <td class="text-center vertical">2</td>
                                        <td class="vertical" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_nangluc10"
                                                        class="form-control tong_select_3 select2-search">
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_24 :''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_24 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_24 : ''}}</th>

                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>
                                            <p><b>Nội dung bị trừ điểm:</b></p>
                                            <p><i>+ Có văn bản đơn vị/bộ phận tham mưu UBND Quận ký ban hành bị sai
                                                    sót</i></p>
                                            <p><i>+ Xây dựng Kế hoạch công tác của đơn vị/ cá nhân chưa hợp lý về
                                                    các
                                                    nội dung công việc, bỏ sót nhiệm vụ</i></p>
                                            <p><i>+ Còn công việc của CBCC thuộc phạm vị phụ trách chưa đạt kết quả
                                                    theo
                                                    yêu cầu.</i></p>
                                            <p><i>+ Tham mưu, giải quyết công việc không đúng quy trình.</i></p>
                                            <p><i>+ Chưa thực hiện tốt việc định hướng, hướng dẫn khi giao việc cho
                                                    cấp
                                                    dưới hoặc chưa giải quyết kịp thời những khó khăn, vướng mắc
                                                    theo
                                                    báo cáo, đề xuất của cấp dưới.</i></p>
                                            <p><i>- Hàng ngày không thực hiện việc tự kiểm việc theo lịch công tác
                                                    tuần
                                                    của cá nhân/bộ phận/đơn vị; Hàng tháng chậm đánh giá kết quả
                                                    thực
                                                    hiện nhiệm vụ theo KH công tác.</i></p>
                                            <p><i>+ Phối hợp với cá nhân tổ chức có liên quan trong giải quyết công
                                                    việc
                                                    không hiệu quả, tạo căng thẳng, bức xúc</i></p>
                                            <p><i>+ Chưa sử dụng thành thạo ứng dụng CNTT đáp ứng yêu cầu công
                                                    việc </i>
                                            </p>
                                            <p><i>+ Tham mưu UBND Quận tổ chức cuộc họp chưa đảm bảo chất lượng về
                                                    tài
                                                    liệu họp, thành phần dự họp...</i></p>
                                        </td>
                                        <td class="text-center vertical"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class=" vertical">2</th>
                                        <th>Thực hiện nhiệm vụ theo kế hoạch, lịch công tác và các nhiệm vụ đột xuất
                                            phát sinh đảm bảo tiến độ, chất lượng
                                        </th>
                                        <th class="text-center vertical">50</th>
                                        <th class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="tong_canhan_thuchiennhiemvu"
                                                        class="form-control tong_select_4 select2-search">
                                                    <option value="50">50</option>
                                                    <option value="49.5">49.5</option>
                                                    <option value="49">49</option>
                                                    <option value="48.5">48.5</option>
                                                    <option value="48">48</option>
                                                    <option value="47.5">47.5</option>
                                                    <option value="47">47</option>
                                                    <option value="46.5">46.5</option>
                                                    <option value="46">46</option>
                                                    <option value="45.5">45.5</option>
                                                    <option value="45">45</option>
                                                    <option value="44.5">44.5</option>
                                                    <option value="44">44</option>
                                                    <option value="43.5">43.5</option>
                                                    <option value="43">43</option>
                                                    <option value="42.5">42.5</option>
                                                    <option value="42">42</option>
                                                    <option value="41.5">41.5</option>
                                                    <option value="41">41</option>
                                                    <option value="40.5">40.5</option>
                                                    <option value="40">40</option>
                                                    <option value="39.5">39.5</option>
                                                    <option value="39">39</option>
                                                    <option value="38.5">38.5</option>
                                                    <option value="38">38</option>
                                                    <option value="37.5">37.5</option>
                                                    <option value="37">37</option>
                                                    <option value="36.5">36.5</option>
                                                    <option value="36">36</option>
                                                    <option value="35.5">35.5</option>
                                                    <option value="35">35</option>
                                                    <option value="34.5">34.5</option>
                                                    <option value="34">34</option>
                                                    <option value="33.5">33.5</option>
                                                    <option value="33">33</option>
                                                    <option value="32.5">32.5</option>
                                                    <option value="32">32</option>
                                                    <option value="31.5">31.5</option>
                                                    <option value="31">31</option>
                                                    <option value="30.5">30.5</option>
                                                    <option value="30">30</option>
                                                    <option value="29.5">29.5</option>
                                                    <option value="29">29</option>
                                                    <option value="28.5">28.5</option>
                                                    <option value="28">28</option>
                                                    <option value="27.5">27.5</option>
                                                    <option value="27">27</option>
                                                    <option value="26.5">26.5</option>
                                                    <option value="26">26</option>
                                                    <option value="25.5">25.5</option>
                                                    <option value="25">25</option>
                                                    <option value="24.5">24.5</option>
                                                    <option value="24">24</option>
                                                    <option value="23.5">23.5</option>
                                                    <option value="23">23</option>
                                                    <option value="22.5">22.5</option>
                                                    <option value="22">22</option>
                                                    <option value="21.5">21.5</option>
                                                    <option value="21">21</option>
                                                    <option value="20.5">20.5</option>
                                                    <option value="20">20</option>
                                                    <option value="19.5">19.5</option>
                                                    <option value="19">19</option>
                                                    <option value="18.5">18.5</option>
                                                    <option value="18">18</option>
                                                    <option value="17.5">17.5</option>
                                                    <option value="17">17</option>
                                                    <option value="16.5">16.5</option>
                                                    <option value="16">16</option>
                                                    <option value="15.5">15.5</option>
                                                    <option value="15">15</option>
                                                    <option value="14.5">14.5</option>
                                                    <option value="14">14</option>
                                                    <option value="13.5">13.5</option>
                                                    <option value="13">13</option>
                                                    <option value="12.5">12.5</option>
                                                    <option value="12">12</option>
                                                    <option value="11.5">11.5</option>
                                                    <option value="11">11</option>
                                                    <option value="10.5">10.5</option>
                                                    <option value="10">10</option>
                                                    <option value="9.5">9.5</option>
                                                    <option value="9">9</option>
                                                    <option value="8.5">8.5</option>
                                                    <option value="8">8</option>
                                                    <option value="7.5">7.5</option>
                                                    <option value="7">7</option>
                                                    <option value="6.5">6.5</option>
                                                    <option value="6">6</option>
                                                    <option value="5.5">5.5</option>
                                                    <option value="5">5</option>
                                                    <option value="4.5">4.5</option>
                                                    <option value="4">4</option>
                                                    <option value="3.5">3.5</option>
                                                    <option value="3">3</option>
                                                    <option value="2.5">2.5</option>
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_25 : ''}}</h4>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_25 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_25 : ''}}</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Hoàn thành từ 90%-100% công việc theo kế hoạch, lịch công tác và các
                                            công
                                            việc, nhiệm vụ đột xuất phát sinh đảm bảo tiến độ và chất lượng
                                        </td>
                                        <td class="text-center vertical">45-50</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Hoàn thành từ 80% đến dưới 90% công việc theo kế hoạch, lịch công tác và
                                            các
                                            công việc, nhiệm vụ đột xuất phát sinh đảm bảo tiến độ và chất lượng
                                        </td>
                                        <td class="text-center vertical">40-&lt;45</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Hoàn thành từ 70% - dưới 80% công việc theo kế hoạch, lịch công tác và
                                            các
                                            công việc, nhiệm vụ đột xuất phát sinh đảm bảo tiến độ và chất lượng
                                        </td>
                                        <td class="text-center vertical">35-&lt;40</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>Hoàn thành dưới 70% công việc theo kế hoạch, lịch công tác và các công
                                            việc,
                                            nhiệm vụ đột xuất phát sinh đảm bảo tiến độ và chất lượng
                                        </td>
                                        <td class="text-center vertical">&lt;35</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical">+</td>
                                        <td>
                                            <p><b>Nội dung bị trừ điểm:</b></p>
                                            <p><i>- Không báo cáo Lãnh đạo đơn vị công việc trước khi trình Lãnh đạo
                                                    quận </i></p>
                                            <p><i>- Không hoàn thành công việc theo kế hoạch hoặc phát sinh đảm bảo
                                                    tiến
                                                    độ, chất lượng </i></p>
                                            <p><i>- Không tham mưu kịp thời xử lý, giải quyết dứt điểm các khiếu
                                                    nại, tố
                                                    cáo, phản ánh, kiến nghị của công dân, tổ chức; giải quyết hồ sơ
                                                    hành chính chậm tiến độ (không có lý do chính đáng)</i></p>
                                            <p><i>- Báo cáo với cấp trên không kịp thời dẫn tới ảnh hưởng tới kết
                                                    quả
                                                    giải quyết công việc</i></p>
                                            <p>...................</p>

                                        </td>
                                        <td class="text-center vertical"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center vertical">III</th>
                                        <th>ĐIỂM THƯỞNG</th>
                                        <th class="text-center vertical">10</th>
                                        <th class="vertical text-center" style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <input type="text" name="tong_canhan_diemthuong"
                                                       readonly=""
                                                       value="10"
                                                       class="form-control tong_canhan_diemthuong">
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ?$laydanhgiacanhan->laychitietdanhgia->field_26:''}}</h4>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_26 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_26 : ''}}</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center vertical"></td>
                                        <td>
                                            <p>Có sáng kiến, giải pháp hiệu quả thuộc một trong các trường hợp
                                                sau:</p>
                                            <p><i>+ Tham mưu, đề xuất giải pháp, mô hình mới đảm bảo chất lượng và
                                                    tiến
                                                    độ, được cấp có thẩm quyền phê duyệt.</i></p>
                                            <p><i>+ Tham mưu có hiệu quả đối với các nhiệm vụ mới, khó, phức tạp
                                                    theo
                                                    phân công được lãnh đạo cơ quan, đơn vị ghi nhận.</i></p>
                                            <p><i>+ Chủ động, sáng tạo, cải tiến phương pháp làm việc, nâng cao hiệu
                                                    quả
                                                    công việc, hoặc hoàn thành khối lượng lớn công việc đảm bảo chất
                                                    lượng theo phân công. </i></p>
                                            <p><b style="text-decoration: underline;">Lưu ý:</b> Trong Phiếu đánh
                                                giá
                                                phải nêu rõ nội dung công việc đạt điểm thưởng. Thủ trưởng đơn vị
                                                thẩm
                                                định và quyết định số điểm thưởng.</p>
                                        </td>
                                        <td class="vertical text-center" style="vertical-align: middle">10</td>
                                        <td class="vertical " style="vertical-align: middle">
                                            @if(empty($laydanhgiacanhan))
                                                <select name="canhan_diemthuong1"
                                                        class="form-control select_thuong select2-search">
                                                    <option value="10">10</option>
                                                    <option value="9.5">9.5</option>
                                                    <option value="9">9</option>
                                                    <option value="8.5">8.5</option>
                                                    <option value="8">8</option>
                                                    <option value="7.5">7.5</option>
                                                    <option value="7">7</option>
                                                    <option value="6.5">6.5</option>
                                                    <option value="6">6</option>
                                                    <option value="5.5">5.5</option>
                                                    <option value="5">5</option>
                                                    <option value="4.5">4.5</option>
                                                    <option value="4">4</option>
                                                    <option value="3.5">3.5</option>
                                                    <option value="3">3</option>
                                                    <option value="2.5">2.5</option>
                                                    <option value="2">2</option>
                                                    <option value="1.5">1.5</option>
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                    <option value="0">0</option>
                                                </select>
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{ isset($laydanhgiacanhan) ?$laydanhgiacanhan->laychitietdanhgia->field_27:''}}</h4>
                                            @endif
                                        </td>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_27 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_27 : ''}}</th>


                                    </tr>
                                    <tr>
                                        <th class="text-center vertical"></th>
                                        <th class="text-center">Tổng điểm</th>
                                        <th class="text-center vertical">

                                            100

                                        </th>
                                        <th class="vertical text-center">
                                            @if(empty($laydanhgiacanhan))
                                                <input type="text" name="tong_canhan_tongdiem"
                                                       readonly=""
                                                       value="100"
                                                       class="form-control tong_canhan_tongdiem">
                                            @else
                                                <h4 style="font-size: 16px" class="text-center">{{isset($laydanhgiacanhan) ? $laydanhgiacanhan->laychitietdanhgia->field_28: ''}}</h4>
                                            @endif
                                        </th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiaphophong) ? $laydanhgiaphophong->laychitietdanhgia->field_28 : ''}}</th>
                                        <th class="vertical text-center"
                                            style="vertical-align: middle">{{ isset($laydanhgiatruongphong) ? $laydanhgiatruongphong->laychitietdanhgia->field_28 : ''}}</th>

                                    </tr>
                                    </tbody>
                                </table>
                            @if( $laydanhgiacanhan != null)

                            @else
                                <div class="col-md-12">
                                    <div class="form-group">
                                            <textarea name="nhanxet" id="" placeholder="Nhập nhận xét - đánh giá"
                                                      rows="10" class="form-control"></textarea>
                                        <input type="text" name="mau_van_ban" class="hidden" value="mau_so_1">
                                        <input type="text" class="form-control hide" name="thang_danh_gia"
                                               value="@if(empty(Request :: get('thang_danh_gia') )){{$month}}@else {{Request :: get('thang_danh_gia')}} @endif">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                @if(Auth::user()->vai_tro != 2)
                                                    <select name="lanhdao"
                                                            class="form-control  select2-search" required>
                                                        <option value="">-- Chọn lãnh đạo phụ trách --</option>
                                                        @foreach($nguoinhan as $nguoi_nhan)
                                                            <option
                                                                value="{{$nguoi_nhan->id}}">{{$nguoi_nhan->ho_ten}}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="lanhdao"
                                                            class="form-control  select2-search " required>
                                                        <option value="">-- Chọn tránh văn phòng phụ trách --
                                                        </option>
                                                        @foreach($nguoinhan as $nguoi_nhan)
                                                            <option
                                                                value="{{$nguoi_nhan->id}}">{{$nguoi_nhan->ho_ten}}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <button type="submit" name="luulai"  value="1" id="btnSubmit"  class="btn btn-primary pull-right luulai " >Đánh giá
                                            </button>
                                            <div class="gmoi hidden">
                                                <button type="button"  id="btnSubmit" disabled  class="btn btn-primary pull-right  " >Đánh giá
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">

        $('.luulai').click(function () {
            var luulai = $('.luulai').val();
            if (luulai == 1) {
                $('.luulai').addClass('hidden');
                $('.gmoi').removeClass('hidden');
            }else {

            }
        });

        $('body').on('change', function (e) {
            var total1 = 0;
            var total2 = 0;
            var total3 = 0;
            var total4 = 0;
            var total5 = 0;
            var total = 0;
            var total_p2 = 0;
            var total_p3 = 0;
            var Tongtatcadiem = 0;


            $(".tong_select_1").each(function () {
                quantity = parseFloat($(this).val());
                if (!isNaN(quantity)) {
                    total1 += quantity;
                }
            });
            $('.total1').val(total1);
            $(".tong_select_2").each(function () {
                quantity = parseFloat($(this).val());
                if (!isNaN(quantity)) {
                    total2 += quantity;
                }
            });
            $('.total2').val(total2);
            total = total1 + total2;
            $('.tong_I').val(total);


            $(".tong_select_3").each(function () {
                quantity = parseFloat($(this).val());
                if (!isNaN(quantity)) {
                    total3 += quantity;
                }
            });
            // console.log(total3);

            $(".tong_select_4").each(function () {
                quantity = parseFloat($(this).val());
                if (!isNaN(quantity)) {
                    total4 += quantity;
                }
                $('.tong_3').val(total3);
            });
            total_p2 = total3 + total4;
            $('.tong_canhan_ketquathuchiennhiemvu').val(total_p2);

            $(".select_thuong").each(function () {
                quantity = parseFloat($(this).val());
                if (!isNaN(quantity)) {
                    total5 += quantity;
                }
                // $('.tong_3').val(total2);
            });
            $('.tong_canhan_diemthuong').val(total5);

            Tongtatcadiem = total + total_p2 + total5;
            $('.tong_canhan_tongdiem').val(Tongtatcadiem);
        });
        $(document).ready(function () {
            $('.lay-thang').change(function () {
                var thang = $('#thang').val();

            });
        })



    </script>
@endsection






