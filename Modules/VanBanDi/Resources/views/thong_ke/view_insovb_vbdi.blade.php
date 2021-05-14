<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<table style='font-family:Arial;border-collapse: collapse;display: table;border-spacing: 2px;border-color: grey;width: 93%;margin-left: 30px;'>
    <thead>
    <tr>
        <td colspan="6" class="text-center"><h4>Báo cáo thống kê văn bản đi</h4></td>
    </tr>

    <tr role="row">
        <th style="font-weight: bold">
            STT
        </th>
        <th  style="font-weight: bold">
            SKH
        </th>
        <th  style="font-weight: bold" class="text-center">
            Trích yếu
        </th>
        <th  style="font-weight: bold">
            Cơ Quan Ban Hành
        </th>
        <th  style="font-weight: bold">
            Ngày Ban hành
        </th>
        <th  style="font-weight: bold">
            Người ký
        </th>
    </tr>

    </thead>
    <tbody>

    @forelse ($ds_vanBanDi as $key=>$data)

        <tr role="row" class="odd">
            <td class="text-center">
                {{$key+1}}
            </td>
            <td>
                {{$data->so_ky_hieu}}
            </td>
            <td>
                {{$data->trich_yeu}}
            </td>
            <td>
                {{$data->dvSoanThao->ten_don_vi ?? ''}}

            </td>
            <td>
                {{ date('d-m-Y', strtotime($data->ngay_ban_hanh))}}
            </td>
            <td>
                {{$data->nguoidung2->ho_ten ?? ''}}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
