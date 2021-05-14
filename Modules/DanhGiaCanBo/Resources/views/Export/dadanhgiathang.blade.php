<table style='border-collapse: collapse;display: table;border-spacing: 2px;border-color: grey;width: 100%;'>
    <thead>
    <tr>
        <td style="text-align: center;font-size: 14px;font-weight: bold" colspan="10">THỐNG KẾ ĐÁNH GIÁ CÁN BỘ
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
        <th class="text-center" style='border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;'>Mức xếp loại
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
                    {{$data->nguoidung->ho_ten}}
                </td>
                <td style="vertical-align: middle;"
                    class="text-center">{{$data->xep_loai }}
                </td>
                <td class="text-left"
                    style="vertical-align: middle;">{{$data->nhan_xet_pho_phong}}
                </td>
                <td class="text-left"
                    style="vertical-align: middle;">{{$data->nhan_xet_truong_phong }}
                </td>

                <td class="text-center"
                    style="vertical-align: middle;">
                    @if($data->diem_ca_nhan >90)
                        HTXS
                    @elseif($data->diem_ca_nhan > 70 && $data->diem_ca_nhan <90)
                        HTT
                    @elseif($data->diem_ca_nhan > 50 && $data->diem_ca_nhan <70)
                        HT
                    @elseif($data->diem_ca_nhan < 50)
                        KHT
                    @endif
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <b>{{$data->thang}}</b>
                </td>
                <td class="text-center">{{$data->nhan_xet_tranh_vp}}
                </td>
                <td class="text-center" style="color: red;font-weight: bold">
                    {{$data->xep_loai}}

                </td>
                <td>
                    @if($data->xep_loai >90)
                        HTXS
                    @elseif($data->xep_loai > 70 && $data->xep_loai <90)
                        HTT
                    @elseif($data->xep_loai > 50 && $data->xep_loai <70)
                        HT
                    @elseif($data->xep_loai < 50)
                        KHT
                    @endif
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

