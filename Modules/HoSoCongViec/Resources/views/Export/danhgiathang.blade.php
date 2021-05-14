<table style='border-collapse: collapse;display: table;border-spacing: 2px;border-color: grey;width: 100%;'>
    <thead>
    <tr>
        <td style="text-align: center;font-size: 14px;font-weight: bold" colspan="9">THỐNG KẾ ĐÁNH GIÁ CÁN BỘ
        </td>
    </tr>
    <tr>
{{--                <td style="text-align: center;" colspan="9">(tháng {{$month}} năm {{$year}})</td>--}}
    </tr>
    <tr style="background: #4a4a4a">
        <th width="4%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>
            STT
        </th>
        <th width="13%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>Cá
            nhân
        </th>
        <th width="6%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>Số
            điểm
        </th>
        <th width="13%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>Phó
            phòng
        </th>
        <th width="13%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>
            Trưởng
            phòng
        </th>
        <th width="8%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>Mức
            xếp
            loại
        </th>
        <th width="5%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>
            Tháng
        </th>
        <th class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>Đánh giá của
            tránh
            văn
            phòng
        </th>
        <th width="6%" class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>Điểm
        </th>
    </tr>
    </thead>
    <tbody>
    @forelse($newArr as $key =>$item)
        <tr>
            <td class="text-center"><b>{{ soLaMa($key+1) }}</b></td>
            <td colspan="8"><b>{{$item['ten_don_vi']}}</b></td>
        </tr>
        @forelse($item['can_bo'] as $key => $data)

            <tr>
                <td class="text-center"
                    style="vertical-align: middle;">{{$key+1}}</td>
                <td style="vertical-align: middle;">
                    {{$data->nguoidung->ho_ten ?? ''}}
                </td>
                <td style="vertical-align: middle;"
                    class="text-center">{{$data->diem ?? ''}}
                </td>
                <td class="text-left"
                    style="vertical-align: middle;">{{$data->laynhanxetphophong->nhan_xet ?? ''}}
                </td>
                <td class="text-center"
                    style="vertical-align: middle;">{{$data->laynhanxettruongphong->nhan_xet ?? ''}}
                </td>

                <td style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>
                    @if($data->diem >90)
                        HTXS
                    @elseif($data->diem > 70 && $data->diem <90)
                        HTT
                    @elseif($data->diem > 50 && $data->diem <70)
                        HT
                    @elseif($data->diem < 50)
                        KHT
                    @endif
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <b>{{$data->thang}}</b>
                </td>
                <td class="text-center"><textarea class="form-control noi-dung"
                                                  placeholder="nhập ý kiến tại đây"
                                                  name="lanh_dao_danh_gia[]"
                                                  rows="3"
                                                  required>Đồng ý với nhận xét</textarea>
                </td>
                <td>
                    <input type="text" class="form-control"
                           name="diem_captruong[]"
                           value="{{$data->diem}}">

                </td>
            </tr>
        @empty
            <td class="text-center" colspan="9">Chưa có dữ liệu</td>
        @endforelse
    @empty
        <td class="text-center" colspan="9">Chưa có dữ liệu</td>


    @endforelse
    </tbody>
</table>

