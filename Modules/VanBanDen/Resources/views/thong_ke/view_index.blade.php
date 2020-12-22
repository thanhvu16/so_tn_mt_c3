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
    <table style='border-collapse: collapse;display: table;border-spacing: 2px;border-color: grey;width: 93%;margin-left: 30px;'>
        <thead style="background: #caeaef">
        <tr>
            <td class="text-center" colspan="7">
                <h4>Sổ Văn Bản</h4>

                <p>Đơn vi: </p>
            </td>
        </tr>
        <tr>
            <th style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>STT</th>
            <th style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>SKH</th>
            <th style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>SỐ ĐẾN</th>
            <th style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>TRÍCH YẾU</th>
            <th style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>CƠ QUAN BAN HÀNH</th>
            <th style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>NGÀY BAN HÀNH</th>
            <th style='padding: 0.75rem;border: 1px solid #a4b7c1;text-align: inherit;font-weight: bold;display: table-cell;vertical-align: inherit;'>NGƯỜI KÝ</th>
        </tr>
        </thead>
        <tbody style='display: table-row-group;vertical-align: middle;border-color: inherit;'>
        @forelse ($ds_vanBanDen as $key=>$data)
            <tr role="row" class="odd">
                <td class="text-center">
                    {{$key+ 1}}

                </td>
                <td>
                    {{$data->so_ky_hieu}}
                </td>
                <td>
                    {{$data->so_den}}
                </td>
                <td>
                    {{$data->trich_yeu}}
                </td>
                <td>
                    {{$data->co_quan_ban_hanh ?? ''}}

                </td>
                <td>
                    {{ date('d-m-Y', strtotime($data->ngay_ban_hanh))}}
                </td>
                <td>
                    {{$data->nguoi_ky}}
                </td>
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
