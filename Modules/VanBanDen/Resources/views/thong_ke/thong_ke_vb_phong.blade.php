@extends('admin::layouts.master')
@section('page_title', 'Thống kê văn bản ')
@section('content')
    <section class="content">
        <form action method="GET" action="{{ route('thongkevbphong') }}" class="form-export">
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

{{--                                        <input type="hidden" name="so_van_ban" value="{{ request('so_van_ban') }}">--}}

                                        <input type="hidden" name="type" value="">
                                        <input type="hidden" name="sovanbanden" value="">
                                        <button type="button" data-type="excel"
                                                class="btn btn-success waves-effect waves-light btn-sm btn-export-data"><i
                                                class="fa fa-file-excel-o"></i> Xuất Excel
                                        </button>
{{--                                        <button type="button" data-type="pdf"--}}
{{--                                                class="btn btn-warning waves-effect waves-light btn-sm btn-export-data"><i--}}
{{--                                                class="fa fa-file-pdf-o"></i> Xuất PDF--}}
{{--                                        </button>--}}
{{--                                        <button type="button" data-type="word"--}}
{{--                                                class="btn btn-info waves-effect waves-light btn-sm btn-export-data"><i--}}
{{--                                                class="fa  fa-file-word-o"></i> Xuất Word--}}
{{--                                        </button>--}}
                                        <button type="button" data-type="print"
                                                class="btn btn-primary waves-effect waves-light btn-sm print-data"><i
                                                class="fa fa-print "></i> In file
                                        </button>
                                </div>
                                <div class="col-md-12 collapse in" id="collapseExample">
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
                                </div>

                                <div class="col-md-12 ">
                                    <H4 style="text-align: center;font-weight: bold">BÁO CÁO THỐNG KÊ TỔNG HỢP SỐ LIỆU CHỈ ĐẠO VÀ GIẢI QUYẾT VĂN BẢN</H4><br>
                                    <h5 style="font-weight: bold">- Thời gian: {{Request::get('tu_ngay')}} @if(Request::get('tu_ngay') && Request::get('den_ngay') ) đến @endif  {{Request::get('den_ngay')}}<br><br>
                                        - Đơn vị kết xuất báo cáo: {{auth::user()->donVi->ten_don_vi}}
                                    </h5>
                                </div>
                                <div class="col-md-12" style="margin-top: 5px">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="vertical-align: middle" rowspan="2" width="5%">STT</th>
                                            <th class="text-center" style="vertical-align: middle" rowspan="2" width="">Tên cán bộ</th>
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
                                        @forelse ($nguoiDung as $key=>$donVidata)
                                            <tr>
                                                <td class="text-center" style="vertical-align: middle"> {{$key+1}}</td>
                                                <td class="text-left" style="vertical-align: middle;font-weight: bold">{{ $donVidata->ho_ten  }}</td>
                                                <td class="text-center so-luong-tong" style="vertical-align: middle;color: red;font-weight: bold">{{ $donVidata->vanBanDaGiaiQuyet['tong'] + $donVidata->vanBanChuaGiaiQuyet['tong'] }}
                                                    <input type="text" class="soVB hidden" value="{{ $donVidata->vanBanDaGiaiQuyet['tong'] + $donVidata->vanBanChuaGiaiQuyet['tong'] }}">
                                                </td>
{{--                                                <td class="text-center" style="vertical-align: middle">{{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_trong_han'] }}</td>--}}
{{--                                                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_qua_han'] }}</td>--}}
{{--                                                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanChuaGiaiQuyet['hoan_thanh_dung_han'] }}</td>--}}
{{--                                                <td style="vertical-align: middle;text-align: center">{{ $donVidata->vanBanChuaGiaiQuyet['hoan_thanh_qua_han'] }}</td>--}}
                                                <td class="text-center" style="vertical-align: middle"> <a href="{{route('chiTietDaGiaiQuyetTrongHanVanBanphong',$donVidata->id.'?tu_ngay='.Request::get('tu_ngay').'&den_ngay='.Request::get('den_ngay'))}}">
                                                        {{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_trong_han'] }}</a></td>
                                                <td style="vertical-align: middle;text-align: center">
                                                    <a href="{{route('chiTietDaGiaiQuyetQuaHanVanBanphong',$donVidata->id.'?tu_ngay='.Request::get('tu_ngay').'&den_ngay='.Request::get('den_ngay'))}}">
                                                        {{ $donVidata->vanBanDaGiaiQuyet['giai_quyet_qua_han'] }}</a>
                                                </td>
                                                <td style="vertical-align: middle;text-align: center"><a href="{{route('chiTietChuaGiaiQuyetTrongHanVanBanphong',$donVidata->id.'?tu_ngay='.Request::get('tu_ngay').'&den_ngay='.Request::get('den_ngay'))}}">
                                                        {{ $donVidata->vanBanChuaGiaiQuyet['hoan_thanh_dung_han'] }}
                                                    </a></td>
                                                <td style="vertical-align: middle;text-align: center">
                                                    <a href="{{route('chiTietChuaGiaiQuyetQuaHanVanBanphong',$donVidata->id.'?tu_ngay='.Request::get('tu_ngay').'&den_ngay='.Request::get('den_ngay'))}}">
                                                        {{ $donVidata->vanBanChuaGiaiQuyet['hoan_thanh_qua_han'] }}
                                                    </a></td>
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
        </form>

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
            var tu_ngay = $('input[name="tu_ngay"]').val();
            var den_ngay = $('input[name="den_ngay"]').val();
            $.ajax({
                beforeSend: showLoading(),
                url: APP_URL + '/thong-ke-van-ban-phong',
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    sovanbanden: sovanbanden,
                    tu_ngay: tu_ngay,
                    den_ngay: den_ngay


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







