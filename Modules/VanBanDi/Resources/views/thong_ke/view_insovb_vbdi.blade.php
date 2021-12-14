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
        <td colspan="6" class="text-center"><h4>SỔ ĐĂNG KÝ VĂN BẢN ĐI</h4></td>
    </tr>
    <tr>
        <td colspan="6" class="text-center"><h4><b>Năm: {{date('Y')}}</b></h4></td>
    </tr>


    <tr>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >STT</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold">Số ký hiệu</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Ngày ban hành</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Loại văn bản và Trích yếu</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Người ký</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Nơi nhận</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Người nhận bản lưu</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Số bản</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold">Ngày chuyển</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Ký nhận</th>
        <th class="text-center" style="vertical-align: middle;font-weight: bold" >Ghi chú</th>
    </tr>

    </thead>
    <tbody>

    @forelse ($ds_vanBanDi as $key=>$vbDi)
        <tr role="row" class="odd">
            <td class="text-center" style="vertical-align: middle"> {{$key+1}}</td>
            <td class="text-center" style="vertical-align: middle">{{$vbDi->so_ky_hieu}}</td>
            <td class="text-center" style="vertical-align: middle">{{ date('d-m-Y', strtotime($vbDi->ngay_ban_hanh))}}</td>
            <td >({{ $vbDi->loaivanban->ten_loai_van_ban ?? ''}}) - {!!  $vbDi->trich_yeu!!}</td>
            <td style="vertical-align: middle;text-align: center">{{$vbDi->nguoidung2->ho_ten ?? ''}}</td>
            <td class="text-left" style="vertical-align: middle" >
                    @forelse($vbDi->donvinhanvbdi as $key=>$item)
                        <p>
                            - {{$item->laytendonvinhan->ten_don_vi ?? ''}}
                        </p>
                    @empty
                    @endforelse
                    @forelse($vbDi->mailngoaitp as $key=>$item)
                        <p>
                            - {{$item->laytendonvingoai->ten_don_vi ?? ''}}
                        </p>
                    @empty
                    @endforelse
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @empty
        <tr>
            <td colspan="11" class="text-center">
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
