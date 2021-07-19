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
        table{
            border: 1px solid;
            border-collapse: collapse;
        }
        tr th{
            background: #eee;
            border: 1px solid;
        }
        tr td{
            border: 1px solid;
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
    <table class="table table-bordered table-striped dataTable mb-0">
        <thead>
        <tr>
            <td colspan="7" style="text-align: center">BÁO CÁO THỐNG KÊ TỔNG HỢP SỐ LIỆU CHỈ ĐẠO VÀ GIẢI QUYẾT VĂN BẢN</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: left">- Thời gian: {{$tu_ngay}} @if($tu_ngay && $den_ngay ) đến @endif  {{$den_ngay}}</td>
        </tr>
{{--        <tr>--}}
{{--            <td colspan="7" style="text-align: left">- Đơn vị kết xuất báo cáo: Văn phòng sở</td>--}}
{{--        </tr>--}}
        <tr>
            <th rowspan="2" style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: middle;'>STT</th>
            <th rowspan="2" style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: center;font-weight: bold;display: table-cell;vertical-align: middle;'>Đơn vị</th>
            <th rowspan="2" style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: middle;'>Tổng số văn bản</th>
            <th colspan="2" style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>Số văn bản đã giải quyết</th>
            <th colspan="2" style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>Số văn bản chưa giải quyết</th>
        </tr>
        <tr>
            <th  style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>Trong hạn</th>
            <th  style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>Quá hạn</th>
            <th  style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>Trong hạn</th>
            <th  style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>Quá Hạn</th>
        </tr>
        </thead>
        <tbody style='display: table-row-group;vertical-align: middle;border-color: inherit;'>
        <tr>
            <td style="font-weight: bold;vertical-align: middle;text-align: center" class="text-center">*</td>
            <td style="font-weight: bold">Tổng số</td>
            <td class="text-center" style="vertical-align: middle;text-align: center"><span class="tongSo text-center" style="color: red;font-weight: bold">{{$tongSoVB}}</span></td>
            <td class="" id="body1"></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @forelse ($danhSachDonVi as $key=>$donVidata)
            <tr role="row" class="odd">
                <td class="text-center" style="vertical-align: middle;text-align: center"> {{$key+1}}</td>
                <td class="text-left" style="vertical-align: middle;font-weight: bold">{{ $donVidata->ten_don_vi  }}</td>
                <td class="text-center" style="vertical-align: middle;text-align: center"><span style="color: red">{{ $donVidata->vanBanDaGiaiQuyet['tong'] }}</span></td>
                <td class="text-center" style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_trong_han'] }}</td>
                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_qua_han'] }}</td>
                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanDaGiaiQuyet['chua_giai_quyet_giai_quyet_trong_han'] }}</td>
                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanDaGiaiQuyet['chua_giai_quyet_giai_quyet_qua_han'] }}</td>
            </tr>
        @empty
            <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
        @endforelse

        </tbody>
    </table>

</div>
</body>
</html>
