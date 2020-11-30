@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="far fa-plus-square"></i> Danh sách văn bản đi chờ số
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">

                            </div>
                            <div class="col-md-12">
                                @include('quanlyvanban::Du_thao_van_ban_di.error')
                                @include('quanlyvanban::Du_thao_van_ban_di.form_them_noi_nhan')
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th style="width: 2%; vertical-align: middle;" class="text-center">STT</th>
                                            <th width="25%" class="text-center">Thông tin</th>
                                            <th  class="text-center">Trích yếu</th>
                                            <th width="20%" class="text-center">Nơi nhận
                                            </th>
                                            <th width="5%" class="text-center">Tác vụ
                                            </th>
                                            <th width="12%" class="text-center">Duyệt
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($vanbandichoso as $key=>$data)
                                            <form method="post"
                                                  action="{{route('Capsovanbandi',$data->van_ban_di_id)}}">
                                                @csrf
                                                <tr>
                                                    <td
                                                        class="text-center"> {{$key+1}}</td>
                                                    <td>
                                                        <p>- Số ký hiệu: {{$data->vanbandi->vb_sokyhieu ?? ''}}</p>
                                                        <p>- Loại văn
                                                            bản: {{$data->vanbandi->loaivanban->ten_loai_van_ban ?? null }}</p>
                                                        <p>- Ngày tháng: <br>
                                                            <input type="date" name="ngay_ban_hanh" class="ngay-ban-hanh-{{$data->van_ban_di_id}}" value="{{$date}}">
                                                            <input type="text" value="{{$data->van_ban_di_id}}"
                                                                   class="hidden van-ban-di-{{$data->van_ban_di_id}}" name="van_ban_di_id">
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <a href="{{route('Quytrinhxulyvanbandi',$data->van_ban_di_id)}}">{{$data->vanbandi->vb_trichyeu ?? ''}}</a><br>
                                                        <span
                                                            style="font-style: italic">(Người ký: {{$data->vanbandi->nguoidung2->ho_ten ?? ''}})</span><br>
                                                        <span style="color: black;font-weight: normal">(Ngày nhập: {{date('d/m/Y', strtotime($data->vanbandi->vb_ngaybanhanh))}})
                                                           </span>
                                                        <p>
                                                            @if (isset($data->vanbandi->filetrinhky))
                                                                - Tệp tin:
                                                                @foreach($data->vanbandi->filetrinhky as $key => $filedata)
                                                                    <a href="{{ $filedata->getUrlFile() }}"
                                                                       target="popup"
                                                                       class="detail-file-name seen-new-window">[file_trinh_ky]</a>

                                                                @endforeach
                                                            @endif

                                                        </p>
                                                    </td>
                                                    <td>
                                                        {{--                                                        <div class="form-control" style="height: 100px;overflow: auto">--}}
                                                        @forelse($data->vanbandi->mailtrongtp as $key=>$item)
                                                            <p>
                                                                - {{$item->laytendonvi->ten_don_vi}}
                                                            </p>
                                                        @empty
                                                        @endforelse
                                                        @forelse($data->vanbandi->mailngoaitp as $key=>$item)
                                                            <p>
                                                                - {{$item->laytendonvingoai->ten_don_vi}}
                                                            </p>
                                                        @empty
                                                        @endforelse
                                                        {{--                                                        </div>--}}
                                                        <p>
                                                            <a class="them-noi-nhan" data-toggle="modal"
                                                               data-target="#modal-them-noi-nhan"
                                                               data-id="{{ $data->vanbandi->id }}">
                                                                <span><i class="fa fa-plus-square-o"></i> Thêm nơi nhận</span>
                                                            </a>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{route('suavanbandichocapso',$data->van_ban_di_id)}}"
                                                            class="btn btn-color-blue btn-icon btn-light" role="button"
                                                            title="Sửa">
                                                            <i class="fas fa-file-signature"></i>
                                                        </a>
                                                        <a
                                                            href="{{route('xoavanbandichocapso',$data->van_ban_di_id)}}"
                                                            class="btn btn-color-red btn-icon btn-light btn-remove-item"
                                                            role="button" title="Xóa">
                                                            <i class="far fa-trash-alt"></i></a>
                                                    </td>
                                                    <td class="text-center">
{{--                                                        <button type="submit"--}}
{{--                                                                onclick="return confirm('Bạn có chắc muốn duyệt văn bản này?');"--}}
{{--                                                                data-original-title="Duyệt văn bản"--}}
{{--                                                                class="btn btn-primary btn-sm"><i--}}
{{--                                                                class="fa  fa-check-square-o"></i></button>--}}
                                                        @if (isset($data->vanbandi->filetrinhky))

                                                            @foreach($data->vanbandi->filetrinhky as $key => $filedata)
                                                                @if ($filedata->trangthai ==2)
                                                                    <br>
                                                                    <button type="button"
                                                                            onclick="exc_sign_issued('{{ $filedata->getUrlFile() }}',100,'{{ date('d-m-Y') }}',{{$data->van_ban_di_id}});"
                                                                            value="{{ $data->van_ban_di_id }}"
                                                                            type="button" class="btn btn-primary mt-2 btn-xs"><i
                                                                            class="fa fa-pencil-square-o"
                                                                            aria-hidden="true"></i> Đóng dấu
                                                                        phát hành
                                                                    </button>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                            </form>
                                        @empty
                                            <td colspan="9" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
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
        $('.btn-choose-status').on('click', function () {
            let message = `Xác nhận gửi`;
            if (confirm(message)) {
                let status = $(this).data('type');
                let id = $(this).data('id');

                $.ajax({
                    url: APP_URL + '/phieu-chuyen-van-ban',
                    type: 'POST',
                    beforeSend: showLoading(),
                    data: {
                        id: id,
                        status: status
                    },
                    success: function (data) {
                        hideLoading();
                        if (data.success) {
                            toastr['success'](data.message, 'Thông báo hệ thống');
                            location.reload();
                        } else {
                            toastr['error'](data.message, 'Thông báo hệ thống');
                        }

                    }
                })
            }
        });

        function callback(rv) {
            var obj = JSON.parse(rv);
            if (obj.Status == 0) {
                document.getElementById("file2").value = obj.FileServer;
            } else {
                document.getElementById("file2").value = obj.Message;
            }

            $('#LicenseDetailModal').modal('toggle');
        }

        function exc_sign_sim(url, maVB) {
            if (document.getElementById("kyruoi_" + maVB).checked == true) {
                kyruoi = 1;
            } else {
                kyruoi = 0;
            }

            $.ajax({
                url: APP_URL + '/ky-dien-tu-qua-sim',
                type: 'POST',
                dataType: 'json',
                data: {
                    kydientu: 1,
                    FileName: url,
                    PK_iMaVBDi: maVB,
                    kyruoi: kyruoi,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend: showLoading(),

            })
                .done(function (response) {
                    hideLoading();
                    callback(response);
                })
                .fail(function (error) {
                    hideLoading();
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });

        }

        var kyDienTu = null;

        function exc_sign_approved(url, idkydt) {
            var prms = {};
            prms["FileUploadHandler"] = APP_URL + "/kydientu.php";
            prms["SessionId"] = "";
            prms["FileName"] = url;
            kyDienTu = idkydt;
            //console.log(prms);
            var json_prms = JSON.stringify(prms);
            //console.log(json_prms);
            vgca_sign_approved(json_prms, SignFileCallBack1);

        }
        let VbId = null;
        // van thu phat hanh
        function exc_sign_issued(file, socv, ngayphathanh,vanbanid) {
            var prms = {};

            //prms["FileUploadHandler"] = "https://vpdt.sotaichinh.hanoi.gov.vn/dieuhanhnoibo/vanthuphathanh";
            prms["FileUploadHandler"] = APP_URL + "/kydientu.php";
            prms["SessionId"] = "";
            prms["FileName"] = file;
            prms["DocNumber"] = "100";//socv;//socv;
            prms["IssuedDate"] = "2019-03-20T10:00:00+07:00";//ngayphathanh;// ngayphathanh;
            VbId = vanbanid;
            var json_prms = JSON.stringify(prms);
            console.log(prms);
            vgca_sign_issued(json_prms, SignFileCallBack1);

        }

        function SignFileCallBack1(rv) {
            var ngay_ban_hanh = $('.ngay-ban-hanh-'+VbId).val();
            var van_ban_di_id = $('.van-ban-di-'+VbId).val();
            var received_msg = JSON.parse(rv);
            //console.log(received_msg);
            if (received_msg.Status == 0) {
                $.ajax({
                    url: APP_URL + '/quan_ly_van_ban/cap-so-van-ban/'+van_ban_di_id,
                    type:'POST',
                    dataType:'json',
                    data:{
                        ngay_ban_hanh:ngay_ban_hanh,
                        van_ban_di_id:van_ban_di_id,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },

                }).done(function (res){
                    toastr['success'](res.message, 'Thông báo hệ thống');
                    location.reload();
                }).fail(function (error) {
                    hideLoading();
                    location.reload();
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });

            } else {
                document.getElementById("_signature").value = received_msg.Message;
            }

                document.getElementById('kydientu_' + kyDienTu).style.display = "none";
                document.getElementById("_signature").value = received_msg.FileName + ":" + received_msg.FileServer + ":" + received_msg.DocumentNumber + ":" + received_msg.DocumentDate;
                document.getElementById("file1").value = received_msg.FileServer;
                document.getElementById("file2").value = received_msg.FileServer;


        }

        // them don vi nhan
        $('.them-noi-nhan').on('click', function () {
            let id = $(this).data('id');
            $('#modal-them-noi-nhan').find('input[name="van_ban_di_id"]').val(id);
        });


    </script>
@endsection