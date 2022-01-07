@extends('admin::layouts.master')
@section('page_title', 'Danh sách văn bản đi chờ số')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách văn bản đi chờ số</h3>
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        @include('vanbandi::Du_thao_van_ban_di.error')
                        @include('vanbandi::Du_thao_van_ban_di.form_them_noi_nhan')
                        <div class="col-md-12 text-right">
                            <a class=" btn btn-primary" data-toggle="collapse"
                               href="#collapseExample"
                               aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i>
                                <span
                                    style="font-size: 14px">Tìm kiếm văn bản</span>
                            </a>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <form action="{{route('vanbandichoso')}}" id="search_vb" method="get">
                                    <div
                                        class="col-md-12 collapse {{ Request::get('search') == 1 || Request::get('year') ? 'in' : '' }}"
                                        id="collapseExample">
                                        <div class="row">

                                            <div class="form-group col-md-3">
                                                <label for="linhvuc_id" class="col-form-label">Đơn vị soạn thảo</label>
                                                <select class="form-control select2 show-tick select2-search"
                                                        name="donvisoanthao_id">
                                                    <option value="">Chọn đơn vị</option>
                                                    @foreach ($ds_DonVi as $donVi)
                                                        <option
                                                            value="{{ $donVi->id }}" {{Request::get('donvisoanthao_id') == $donVi->id ? 'selected' : ''}}
                                                        >{{ $donVi->ten_don_vi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Nhập từ ngày</label>
                                                <input type="date" name="start_date" class="form-control"
                                                       value="{{Request::get('start_date')}}"
                                                       autocomplete="off">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sokyhieu" class="col-form-label">Nhập đến ngày</label>
                                                <input type="date" name="end_date" id="vb_ngaybanhanh" class="form-control"
                                                       value="{{Request::get('end_date')}}"
                                                       autocomplete="off">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="co_quan_ban_hanh_id" class="col-form-label">Người ký</label>
                                                <select class="form-control show-tick select2-search" name="nguoiky_id">
                                                    <option value="">-- Chọn Người Ký --</option>
                                                    @foreach ($ds_nguoiKy as $nguoiKy)
                                                        <option
                                                            value="{{ $nguoiKy->id }}" {{Request::get('nguoiky_id') == $nguoiKy->id ? 'selected' : ''}}
                                                        >{{$nguoiKy->ho_ten}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                                <textarea rows="3" name="vb_trichyeu" class="form-control no-resize"
                                                          placeholder="Nhập nội dung trích yếu ..."
                                                >{{Request::get('vb_trichyeu')}}</textarea>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <button class="btn btn-primary" value="1" name="search"><i
                                                        class="fa  fa-search"></i> Tìm kiếm
                                                </button>
                                                @if(request('search'))
                                                    <a href="{{ route('vanbandichoso') }}">
                                                        <button type="button" class="btn btn-success">
                                                            <i class="fa fa-refresh"></i>
                                                        </button>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        Tổng số văn bản: <b>{{ $vanbandichoso->total() }}</b>
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th style="width: 2%; vertical-align: middle;" class="text-center">STT</th>
                                <th width="" class="text-center">Thông tin</th>
                                <th width="10%" class="text-center">Ngày tháng</th>
                                <th width="15%" class="text-center">Sổ văn bản</th>
                                <th width="6%" class="text-center">File</th>
                                <th width="20%" class="text-center">Nơi nhận</th>
                                <th width="5%" class="text-center">Duyệt
                                </th>
                                <th width="8%" class="text-center">Tác vụ
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            @forelse($vanbandichoso as $key=>$data)
                                <form method="post" id="choso-{{$data->id}}"
                                      action="{{route('Capsovanbandi',$data->id)}}">
                                    <tr>

                                            @csrf
                                        <td
                                            class="text-center" style="vertical-align: middle"> {{$key+1}}</td>
                                        <td style="vertical-align: middle">
                                            <a href="{{ route('Quytrinhxulyvanbandi',$data->id) }}">{{$data->trich_yeu ?? ''}}</a><br>
                                            <p>- Số ký hiệu: <span style="text-transform: uppercase ">{{$data->so_ky_hieu ?? ''}}</span></p>
                                            <p>- Loại văn
                                                bản: {{$data->loaivanban->ten_loai_van_ban ?? null }}</p>

                                            <p
                                                style="font-style: italic">- Người ký: {{$data->nguoidung2->ho_ten ?? ''}}</p>
                                            <p
                                                style="font-style: italic">- Đơn vị soạn thảo: {{$data->donViSoanThaoVB->ten_don_vi ?? ''}}</p>
                                            <p style="font-style: italic">- Ngày nhập: {{date('d/m/Y', strtotime($data->ngay_ban_hanh))}}
                                                           </p>



                                        </td>
                                            <td class="text-center" >
                                                <p>
                                                    <input type="date" name="ngay_ban_hanh" class="form-control ngay-ban-hanh-{{$data->id}}" value="{{$date}}">
                                                    <input type="text" value="{{$data->id}}"
                                                           class="hidden van-ban-di-{{$data->id}}" name="van_ban_di_id">
                                                </p>
                                            </td>
                                            <td>
                                                <select class="form-control show-tick select2 dropdown-search" name="sovanban_id"
                                                        >
{{--                                                    <option value="">-- Chọn sổ văn bản --</option>--}}
                                                    @foreach ($ds_soVanBan as $sovb)
                                                        <option value="{{$sovb->id}}"
                                                            {{ isset($vanbanduthao) && $vanbanduthao->so_van_ban_id == $sovb->id ? 'selected' : '' }}>{{$sovb->ten_so_van_ban}}</option>
                                                    @endforeach
                                                </select>

                                            </td>
                                            <td >
                                                <p>
                                                    @if (isset($data->filetrinhky))
                                                        @foreach($data->filetrinhky as $key => $filedata)
                                                            <a href="{{ $filedata->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[file_trinh_ky]</a>

                                                        @endforeach
                                                    @endif

                                                </p>
                                            </td>
                                        <td >
                                            {{--                                                        <div class="form-control" style="height: 100px;overflow: auto">--}}
                                            @forelse($data->donvinhanvbdi as $key=>$item)
                                                <p>
                                                    - {{$item->laytendonvinhan->ten_don_vi ?? ''}}
                                                </p>
                                            @empty
                                            @endforelse
                                            @forelse($data->mailngoaitp as $key=>$item)
                                                <p>
                                                    - {{$item->laytendonvingoai->ten_don_vi ?? ''}}
                                                </p>
                                            @empty
                                            @endforelse
                                        </td>
                                            <td class="text-center" >
                                                <button type="submit" form="choso-{{$data->id}}"
                                                        class="btn btn-primary btn-sm btn-remove-item-duyet"><i
                                                        class="fa  fa-check-square-o"></i></button>

                                            </td>
                                            <td class="text-center" >


                                                @hasanyrole('văn thư đơn vị|văn thư sở')
                                                <form method="Get" action="{{route('vanbandidelete',$data->id)}}">
                                                    @csrf
                                                    <a href="{{route('suavbdivacapso','id='.$data->id)}}"
                                                       class="fa fa-edit" role="button"
                                                       title="Sửa">
                                                        <i class="fas fa-file-signature"></i>
                                                    </a><br><br>
                                                    <button
                                                        class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                        role="button"
                                                        title="Xóa">
                                                        <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>
                                                    </button>
                                                    <input type="text" class="hidden" value="{{$data->id}}" name="id_vb">
                                                </form>

                                                @endrole
                                            </td>


                                    </tr>

                                </form>


                            @empty
                                <td colspan="9" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">

                            </div>
                            <div class="col-md-6 text-right">
                                {!! $vanbandichoso->appends(['loaivanban_id' => Request::get('loaivanban_id'),'nguoiky_id' => Request::get('nguoiky_id'),'vb_trichyeu' => Request::get('vb_trichyeu')
                        ,'donvisoanthao_id' => Request::get('donvisoanthao_id'),'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date')])->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

        $(document).ready(function() {
            // show the alert
            setTimeout(function() {
                $(".alert").alert('close');
            }, 3000);
        });


    </script>
@endsection
