@extends('admin::layouts.master')
@section('page_title', 'Thống kê văn bản Sở')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <a class="btn btn-default waves-effect waves-light btn-sm" role="button"  data-toggle="collapse"
                                       href="#collapseExample"
                                       aria-expanded="false" aria-controls="collapseExample"><i
                                            class="fa fa-search"></i> Lọc dữ liệu văn bản
                                    </a>

                                </div>
                                <div class="col-md-8 text-right">
                                    <form action method="GET" action="{{ route('thongkevbso') }}" class="form-export">

                                        <input type="hidden" name="type" value="">
                                        <input type="hidden" name="sovanbanden" value="">
                                        <button type="button" data-type="excel"
                                                class="btn btn-success waves-effect waves-light btn-sm btn-export-data"><i
                                                class="fa fa-file-excel-o"></i> Xuất Excel
                                        </button>
                                        <button type="button" data-type="print"
                                                class="btn btn-primary waves-effect waves-light btn-sm print-data"><i
                                                class="fa fa-print "></i> In file
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-12 collapse in" id="collapseExample">
                                    <form action method="GET" action="{{ route('thongkevbso') }}" >
                                    <div class="col-md-5 form-group mt-2">
                                        <label>Tìm từ ngày</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar-o"></i>
                                            </div>
                                            <input type="text" class="form-control datepicker" value="{{Request::get('tu_ngay')}}"
                                                   name="tu_ngay" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                    <div class="col-md-5 form-group mt-2">
                                        <label>Tìm đến ngày</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar-o"></i>
                                            </div>
                                            <input type="text" class="form-control datepicker" value="{{Request::get('den_ngay')}}"
                                                   name="den_ngay" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="margin-top: 30px">
                                        <button type="submit" name="search" class="btn btn-primary"><i
                                                class="fa fa-search"></i> Tìm Kiếm</button>
                                    </div>
                                    </form>
                                </div>
                                <div class="col-md-12 ">
                                    <H4 style="text-align: center;font-weight: bold">BÁO CÁO THỐNG KÊ TỔNG HỢP SỐ LIỆU CHỈ ĐẠO VÀ GIẢI QUYẾT VĂN BẢN</H4><br>
                                    <h5 style="font-weight: bold">- Thời gian: Từ 01/01/2021 đến 31/12/2021<br><br>
                                        - Đơn vị kết xuất báo cáo: Sở Tài nguyên và Môi trường
                                    </h5>
                                </div>
                                <div class="col-md-12" style="margin-top: 5px">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="vertical-align: middle" rowspan="2" width="5%">STT</th>
                                            <th class="text-center" style="vertical-align: middle" rowspan="2" width="">Đơn vị</th>
                                            <th class="text-center" style="vertical-align: middle" rowspan="2" width="10%">Tổng số văn bản</th>
                                            <th class="text-center" style="vertical-align: middle" colspan="2" width="20%">Số văn bản đã giải quyết</th>
                                            <th class="text-center" style="vertical-align: middle" colspan="2" width="20%">Số văn bản chưa giải quyết</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="vertical-align: middle">Trong hạn</th>
                                            <th class="text-center" style="vertical-align: middle">Quá hạn</th>
                                            <th class="text-center" style="vertical-align: middle">Trong hạn</th>
                                            <th class="text-center" style="vertical-align: middle">Quá Hạn </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($danhSachDonVi as $key=>$donVidata)
                                            <tr>
                                                <td class="text-center" style="vertical-align: middle"> {{$key+1}}</td>
                                                <td class="text-left" style="vertical-align: middle;font-weight: bold">{{ $donVidata->ten_don_vi  }}</td>
                                                <td class="text-center so-luong-tong" style="vertical-align: middle;color: red;font-weight: bold">
                                                    <a href="{{route('chiTietTongVanBanSo',$donVidata->id)}}">{{ $donVidata->vanBanDaGiaiQuyet['tong'] }}</a>
                                                    <input type="text" class="soVB hidden" value="{{ $donVidata->vanBanDaGiaiQuyet['tong'] }}">
                                                </td>
                                                <td class="text-center" style="vertical-align: middle">{{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_trong_han'] }}</td>
                                                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_qua_han'] }}</td>
                                                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanDaGiaiQuyet['chua_giai_quyet_giai_quyet_trong_han'] }}</td>
                                                <td>{{ $donVidata->vanBanDaGiaiQuyet['chua_giai_quyet_giai_quyet_qua_han'] }}</td>
                                            </tr>
                                        @empty
                                            <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        <tr>
                                            <td style="font-weight: bold" class="text-center">*</td>
                                            <td style="font-weight: bold">Tổng số</td>
                                            <td class="text-center"><span class="tongSo text-center" style="color: red;font-weight: bold"></span>

                                            </td>
                                            <td class="" id="body1"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
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
        // $(document).ready(function() {
        //     var sum = $("#body1").val();
        //
        //         console.log(sum)
        // });
        $(document).ready(function () {
            var sum = 0;
            $('.soVB').each(function(){
                sum += parseInt($(this).val());  // Or this.innerHTML, this.innerText
            });

            $('.tongSo').append(sum);

            $('input[name="sovanbanden"]').val(sum);
        });
        $('.btn-export-data').on('click', function () {
            let type = $(this).data('type');
            $('input[name="type"]').val(type);
            $('.form-export').submit();
            hideLoading();
        });

        $('.print-data').on('click', function () {
            let $this = $(this);
            let type = $(this).data('type');
            var sovanbanden = $('input[name="sovanbanden"]').val();
            $.ajax({
                beforeSend: showLoading(),
                url: APP_URL + '/thong-ke-van-ban-so',
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    sovanbanden: sovanbanden,


                }
            })
                .done(function (response) {
                    hideLoading();
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(response.html);
                    w.document.close();
                    w.window.print();
                    // $this.printPage(response.html);
                })
                .fail(function (error) {
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });

        })

        // $('.print-data').printPage();

    </script>
@endsection







