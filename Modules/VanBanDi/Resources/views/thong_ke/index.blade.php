@extends('admin::layouts.master')
@section('page_title', 'In sổ văn bản đi')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">In sổ văn bản đi</h3>
                    </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <form action="{{route('in-so-van-ban-di.index')}}" method="get">

                                        <select class="form-control select2-search" name="so_van_ban"
                                                onchange="this.form.submit()">
                                            <option  value="">-- Chọn sổ muốn in --</option>
                                            @foreach($sovanban as $loaiso)
                                            <option {{Request:: get('so_van_ban')== $loaiso->id ? 'selected': ''}} value="{{$loaiso->id}}">{{$loaiso->ten_so_van_ban}}</option>
                                            @endforeach

                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-8 text-right">
                                    <form action method="GET" action="{{ route('in-so-van-ban-di.index') }}" class="form-export">
                                        <input type="hidden" name="so_van_ban" value="{{ request('so_van_ban') }}">

                                        <input type="hidden" name="type" value="">
                                        <button type="button" data-type="excel"
                                                class="btn btn-success waves-effect waves-light btn-sm btn-export-data"><i
                                                class="fa fa-file-excel-o"></i> Xuất Excel
                                        </button>
                                        <button type="button" data-type="pdf"
                                                class="btn btn-warning waves-effect waves-light btn-sm btn-export-data"><i
                                                class="fa fa-file-pdf-o"></i> Xuất PDF
                                        </button>
                                        <button type="button" data-type="word"
                                                class="btn btn-info waves-effect waves-light btn-sm btn-export-data"><i
                                                class="fa  fa-file-word-o"></i> Xuất Word
                                        </button>
{{--                                        <button type="button" data-type="print"--}}
{{--                                                class="btn btn-primary waves-effect waves-light btn-sm print-data"><i--}}
{{--                                                class="fa fa-print "></i> In file--}}
{{--                                        </button>--}}
                                    </form>
                                </div>
                                <div class="col-md-12 table-responsive" style="margin-top: 5px">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center" width="5%">STT</th>
                                            <th class="text-center" width="10%">Ngày ban hành
                                            </th>
                                            <th class="text-center" width="10%">Số ký hiệu
                                            </th>
                                            <th class="text-center" width="10%">Loại văn bản</th>

                                            <th class="text-center" width="">Trích yếu
                                            </th>
                                            <th class="text-center" width="20%">Nơi nhận</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($ds_vanBanDi as $key=>$vbDi)
                                            <tr>

                                                <td class="text-center" style="vertical-align: middle"> {{$key+1}}</td>
                                                <td class="text-center" style="vertical-align: middle">{{ date('d-m-Y', strtotime($vbDi->ngay_ban_hanh))}}</td>
                                                <td class="text-center" style="vertical-align: middle">{{$vbDi->so_ky_hieu}}</td>
                                                <td style="vertical-align: middle;text-align: center">{{$vbDi->loaivanban->ten_loai_van_ban ?? ''}}</td>
                                                <td ><a
                                                        href="{{route('Quytrinhxulyvanbandi',$vbDi->id)}}"
                                                        title="{{$vbDi->trich_yeu}}">{{$vbDi->trich_yeu}}</a></td>
                                                <td class="text-left" style="vertical-align: middle">
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
                                            </tr>
                                        @empty
                                            <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
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
        $('.btn-export-data').on('click', function () {
            let type = $(this).data('type');
            $('input[name="type"]').val(type);
            $('.form-export').submit();
            hideLoading();
        });

        $('.print-data').on('click', function () {
            let $this = $(this);
            let so_van_ban = $('input[name="so_van_ban"]').val();
            let type = $(this).data('type');
            console.log(so_van_ban , type);
            $.ajax({
                url: APP_URL + 'in-so-van-ban-di',
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    so_van_ban: so_van_ban,

                }
            })
                .done(function (response) {
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







