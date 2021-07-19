<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Báo cáo thống kê</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        h4 {
            text-align: center;
        }
        p {
            text-align: center;
        }

        .main {
            margin: 0 auto;
        } table {
              margin: 0 auto;
          }
    </style>
</head>
<body>


<div class="main">
    <table style='border-collapse: collapse;display: table;border-spacing: 2px;border-color: grey;width: 100%;'>
        <thead >
        <tr>
            <td style="text-align: center;font-size: 14px;font-weight: bold" colspan="8">THÔNG KẾ VĂN BẢN
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" colspan="8">(Ngày {{$day}} tháng {{$month}} năm {{$year}})</td>
        </tr>
        <tr style="background-color: #caced0">
            <th  style='border: 1px solid #caced0 ; background-color: #caced0; text-align: center;font-weight: bold;'>STT</th>
            <th style='border: 1px solid #caced0; text-align: center;background-color: #caced0;font-weight: bold;'>Số đến</th>
            <th style='border: 1px solid #caced0; text-align: center; background-color: #caced0; font-weight: bold; background-color: #caced0'>Cơ quan ban hành</th>
            <th style='border: 1px solid #caced0; text-align: center; background-color: #caced0; font-weight: bold; background-color: #caced0'>Thông tin</th>
            <th style='border: 1px solid #caced0; text-align: center;background-color: #caced0;font-weight: bold;'>Trích yếu</th>
            <th style='border: 1px solid #caced0; text-align: center;background-color: #caced0;font-weight: bold;'>Hạn văn bản</th>
            <th style='border: 1px solid #caced0; text-align: center;background-color: #caced0;font-weight: bold;'>Đơn vị xử lý chính</th>
            <th style='border: 1px solid #caced0; text-align: center;background-color: #caced0;font-weight: bold;'>Cán bộ nhập</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($ds_vanBanDen as $key=>$data)
            <tr role="row" >
                <td style='text-align: center;vertical-align: top;'>{{$key+1}}</td>
                <td style="text-align: center;vertical-align: top;color: red">{{$data->vanBanDen->so_den ?? ''}}</td>
                <td style="text-align: left;vertical-align: top;">{{$data->vanBanDen->co_quan_ban_hanh ?? ''}}</td>

                <td style='text-align: left;vertical-align: top;' >
                    <p>- Số ký hiệu: {{$data->vanBanDen->so_ky_hieu ?? ''}}</p>
                    <p>- Ngày ban
                        hành: @if($data->vanBanDen){{ date('d/m/Y', strtotime($data->vanBanDen->ngay_ban_hanh)) ?? '' }}@endif</p>
                </td>
                <td style="vertical-align: top;">{!! $data->vanBanDen->trich_yeu ?? '' !!}</td>
                <td style="vertical-align: top;">@if($data->vanBanDen){{ date('d/m/Y', strtotime($data->vanBanDen->han_xu_ly)) ?? '' }}@endif</td>
                <td style="vertical-align: top;">
                    @if($data->vanBanDen)
                    @if ($data->vanBanDen->parent_id)
                        @foreach($data->vanBanDen->getParent()->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                            @if (count($data->vanBanDen->getParent()->donViChuTri)-1 == $key)
                                <p>
                                    {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                    <br>
                                    <i>(Cán bộ xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                        )</i>
                                </p>
                            @endif
                        @endforeach
                    @else
                    <!--vb den huyen-->
                        @if($data->vanBanDen->donViChuTri)
                            @foreach($data->vanBanDen->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                @if (count($data->vanBanDen->donViChuTri)-1 == $key)
                                    <p>
                                        {{ $chuyenNhanVanBanDonVi->donVi->ten_don_vi ?? null }}
                                        <br>
                                        <i>(Cán bộ xử lý: {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                            )</i>
                                    </p>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    @endif
                </td>
                <td style="vertical-align: top;">@if($data->vanBanDen){{$data->vanBanDen->nguoiDung->ho_ten ?? ''}}@endif</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Không tìm thấy dữ
                    liệu.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>



</div>
</body>
</html>
