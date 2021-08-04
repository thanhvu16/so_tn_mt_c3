@extends('admin::layouts.master')
@section('page_title', 'Văn bản đi chờ duyệt')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Văn bản đi trả lại</h3>
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        <table class="table table-striped table-bordered dataTable table-hover data-row">
                            <thead>
                            <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                <th style="width: 5%; vertical-align: middle;" class="text-center">STT</th>
                                <th width="10%" class="text-center" style="vertical-align: middle;">Số ký
                                    hiệu
                                </th>
                                <th width="" class="text-center" style="vertical-align: middle;">Trích
                                    yếu
                                </th>
                                <th width="16%" class="visible-lg text-center"
                                    style="vertical-align: middle;">Ý kiến
                                </th>
                                <th width="15%" class=" text-center"
                                    style="vertical-align: middle;">Ký số
                                </th>
                                {{--                                            <th width="10%" class="text-center" style="vertical-align: middle;">Tệp--}}
                                {{--                                                tin--}}
                                {{--                                            </th>--}}
                                <th width="17%" class="text-center" style="vertical-align: middle;">Ý kiến
                                    gửi đi
                                </th>
                                <th width="10%" class="text-center" style="vertical-align: middle;">Gửi</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($van_ban_di_tra_lai as $key=>$vanban)
                                    <form action="{{route('duyetvbditoken')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <tr class="duyet-gia-han">
                                        <td
                                            class="text-center" style="vertical-align: middle"> {{$key+1}}</td>
                                        <td>{{$vanban->vanbandi->so_ky_hieu ?? ''}}</td>
                                        <td><a
                                                href="">{{$vanban->vanbandi->trich_yeu ?? ''}}</a><br>
                                            - Người ký: {{$vanban->vanbandi->nguoidung2->ho_ten ?? ''}} <br>
                                            -Người nhập:{{$vanban->vanbandi->nguoitao->ho_ten ?? ''}} - {{date_format($vanban->vanbandi->created_at, 'd/m/Y H:i:s') ?? ''}}
                                            <div class="text-right " style="pointer-events: auto">
                                                @if (isset($vanban->vanbandi->filetrinhky))
                                                    @forelse($vanban->vanbandi->filetrinhky as $filedata)
                                                        <a href="{{$filedata->getUrlFile()}}" class="seen-new-window">[File trình ký]</a>
                                                    @empty
                                                    @endforelse
                                                @endif
                                                @if (isset($vanban->vanbandi->filephieutrinh))
                                                    @forelse($vanban->vanbandi->filephieutrinh as $filedata)
                                                        &nbsp; | <a href="{{$filedata->getUrlFile()}}" class="seen-new-window" target="popup">[File phiếu trình]</a>
                                                    @empty
                                                    @endforelse
                                                @endif
                                                @if (isset($vanban->vanbandi->filehoso))
                                                    @forelse($vanban->vanbandi->filehoso as $filedata)
                                                        &nbsp; | <a href="{{$filedata->getUrlFile()}}" >[File hồ sơ]</a>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </div>
                                        </td>

                                        <td> &emsp;<span style="font-weight: bold"> {{$vanban->y_kien_gop_y ?? ''}}</span>

                                        </td>
                                        <td class="text-center" >
                                            @if(auth::user()->donvi->cap_don_vi == 3 && (auth::user()->vai_tro==4 || auth::user()->vai_tro==3) )
                                            @else

                                                @forelse($vanban->vanbandi->filetrinhky as $filedata)
                                                    @if ($filedata->trang_thai ==2)
                                                        <button name="kydientu" id="kydientu_{{$vanban->vanbandi->id ?? ''}}"
                                                                type="button" class="btn btn-primary btn-sm mb-2 ky-token"
                                                                onclick="exc_sign_approved('{{$filedata->getUrlFile()}}',{{$vanban->vanbandi->id}},{{ $vanban->id }});"
                                                                value="{{$vanban->vanbandi->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp;Ký token</button>
                                                    @endif
                                                @empty
                                                @endforelse


                                                @forelse($vanban->vanbandi->filetrinhky as $filedata)
                                                    @if ($filedata->trang_thai ==2)
                                                        <br>

                                                        <button name="kydientu" type="button" id="_lanhdaoPheduyet"
                                                                class="btn btn-primary btn-sm mb-2"
                                                                onclick="exc_sign_sim('{{$filedata->duongdan}}','{{$vanban->vanbandi->id}}', '{{ $vanban->id }}');"
                                                                value="{{$vanban->vanbandi->id}}">&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp;Ký sim&nbsp;&nbsp;</button>

                                                        <p><input type="checkbox" name="kyruoi_{{$vanban->vanbandi->id}}"
                                                                  id="kyruoi_{{$vanban->vanbandi->id}}" value="1">
                                                            <label for="kyruoi_{{$vanban->vanbandi->id}}">
                                                                <b>&nbsp; Ký nháy</b>
                                                            </label></p>
                                                    @endif
                                                @empty
                                                @endforelse

                                            @endif

                                        </td>

                                        {{--                                                    <td class="text-center" style="vertical-align: middle;">--}}
                                        {{--                                                        @forelse($vanban->file as $filedata)--}}
                                        {{--                                                            <a href="{{$filedata->getUrlFile()}}">File Trình Ký</a>--}}
                                        {{--                                                        @empty--}}
                                        {{--                                                        @endforelse<br>--}}
                                        {{--                                                    </td>--}}
                                        <td>
                                            @if(auth::user()->id == $idnguoiky)
                                                <input type="text" class="hidden" value="1"
                                                       name="vb_cho_so">
                                                {{--                                                            <span style="font-weight: bold;color: red">&emsp;Chuyển văn thư cấp số</span>--}}
                                            @else
                                                <div class="col-md-12 form-group">
                                                    <select name="nguoi_nhan" id=""
                                                            class="form-control select2-search">
                                                        @forelse ($nguoinhan as $data)
                                                            <option value="{{ $data->id }}"
                                                            >{{ $data->ho_ten}}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>
                                                <input type="text" class="hidden" value="0"
                                                       name="vb_cho_so">
                                            @endif
                                            <input type="text" class="hidden" name="id_vb_cho_duyet"
                                                   value="{{$vanban->id}}">
                                            <input type="text" class="hidden" name="id_van_ban"
                                                   value="{{$vanban->van_ban_di_id}}">


                                            <div class="col-md-12 form-group">
                                                            <textarea class="form-control noi-dung"
                                                                      placeholder="nhập ý kiến tại đây" name="noi_dung"
                                                                      rows="3"
                                                                      required></textarea>

                                            </div>
                                            <div class="col-md-12 form-group">
                                                <input type="file" class="form-control" name="ten_file[]">
                                                <input type="text" value="123" class="form-control hidden" name="txt_file[]">

                                            </div>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle">
                                            <button
                                                class="btn waves-effect btn-primary btn-choose-status"
                                                name="submit_Duyet_lai" value="3" data-type="3"><i class="fa fa-send"></i> Duyệt
                                            </button>
                                            <br>
                                            <br>


                                        </td>

                                    </tr>
                                </form>
                            @empty
                                <td colspan="7" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script type="text/javascript">
        let urlKyRuoi = "{{ route('van_ban.ky_dt_qua_sim') }}";

        function callback(rv) {
            var obj = JSON.parse(rv);
            if (obj.Status == 0) {
                document.getElementById("file2").value = obj.FileServer;
            } else {
                document.getElementById("file2").value = obj.Message;
            }

            $('#LicenseDetailModal').modal('toggle');
        }

        function exc_sign_sim(url, maVB, vanBanId) {
            if (document.getElementById("kyruoi_" + maVB).checked == true) {
                kyruoi = 1;
            } else {
                kyruoi = 0;
            }

            var nguoi_nhan = $('.select-nguoi-nhan-'+vanBanId).val();
            var vb_cho_so = $('.vb-cho-so-'+vanBanId).val();
            var noi_dung = $('.noi-dung-'+vanBanId).val();
            var id_vb_cho_duyet = $('.id-vb-cho-duyet-'+vanBanId).val();
            var id_van_ban = $('.id-van-ban-'+vanBanId).val();
            var submit_Duyet = 1;
            console.log(noi_dung);

            $.ajax({
                url: APP_URL + '/ky-dien-tu-qua-sim',
                type: 'POST',
                dataType: 'json',
                data: {
                    nguoi_nhan:nguoi_nhan,
                    vb_cho_so:vb_cho_so,
                    noi_dung:noi_dung,
                    id_vb_cho_duyet:id_vb_cho_duyet,
                    id_van_ban:id_van_ban,
                    submit_Duyet:submit_Duyet,
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
                    location.reload();
                })
                .fail(function (error) {
                    hideLoading();
                    location.reload();
                    toastr['error'](error.message, 'Lỗi hệ thống, không ký được');
                });

        }


        let VbId = null;
        let kyDienTu = null;

        function exc_sign_approved(url,idkydt,vanBanId) {
            console.log(vanBanId);
            var prms = {};
            prms["FileUploadHandler"] = APP_URL + "/kydientu.php";
            prms["SessionId"] = "";
            prms["FileName"] = url;
            kyDienTu = idkydt;
            VbId = vanBanId;
            //console.log(prms);
            var json_prms = JSON.stringify(prms);
            //console.log(json_prms);
            vgca_sign_approved(json_prms, SignFileCallBack1);



        }

        function SignFileCallBack1(rv) {
            var nguoi_nhan1 = $('.select-nguoi-nhan-'+VbId).val();
            var vb_cho_so = $('.vb-cho-so-'+VbId).val();
            var noi_dung = $('.noi-dung-'+VbId).val();
            var id_vb_cho_duyet = $('.id-vb-cho-duyet-'+VbId).val();
            var id_van_ban = $('.id-van-ban-'+VbId).val();
            var submit_Duyet = 1;
            var received_msg = JSON.parse(rv);
            //console.log(received_msg);

            if (received_msg.Status == 0) {

                // console.log(13);
                $.ajax({
                    url: APP_URL + '/quan_ly_van_ban/duyet-vb-ky-token',
                    type:'POST',
                    dataType:'json',
                    data:{
                        nguoi_nhan:nguoi_nhan1,
                        vb_cho_so:vb_cho_so,
                        noi_dung:noi_dung,
                        id_vb_cho_duyet:id_vb_cho_duyet,
                        id_van_ban:id_van_ban,
                        submit_Duyet:submit_Duyet,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },

                }).done(function (res){
                    location.reload();
                }).fail(function (error) {
                    hideLoading();
                    location.reload();
                    //toastr['error'](error.message, 'Thông báo hệ thống');
                });
                document.getElementById('kydientu_' + kyDienTu).style.display = "none";
                document.getElementById("_signature").value = received_msg.FileName + ":" + received_msg.FileServer + ":" + received_msg.DocumentNumber + ":" + received_msg.DocumentDate;
                document.getElementById("file1").value = received_msg.FileServer;
                document.getElementById("file2").value = received_msg.FileServer;






            } else {
                console.log(12);
                document.getElementById("_signature").value = received_msg.Message;
            }
        }
    </script>
@endsection
