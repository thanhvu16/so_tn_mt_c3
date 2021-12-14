@extends('admin::layouts.master')
@section('page_title', 'In sổ văn bản đi')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="col-md-3">
                            <h3 class="box-title">In sổ văn bản đi</h3>

                        </div>
                        <div class="col-md-6 text-right">
                            <a class=" btn btn-primary" data-toggle="collapse"
                               href="#collapseExample"
                               aria-expanded="false" aria-controls="collapseExample"> <i class="fa  fa-search"></i>
                                <span
                                    style="font-size: 14px"> Lọc sổ văn bản</span>
                            </a>
                        </div>
                        <div class="col-md-3 text-right">
                            <form action method="GET" action="{{ route('in-so-van-ban-di.index') }}" class="form-export">
                                <input type="hidden" name="so_van_ban" value="{{ request('so_van_ban') }}">
                                <input type="hidden" name="tu_ngay" value="{{ request('tu_ngay') }}">
                                <input type="hidden" name="den_ngay" value="{{ request('den_ngay') }}">
                                <input type="hidden" name="tu_so" value="{{ request('tu_so') }}">
                                <input type="hidden" name="den_so" value="{{ request('den_so') }}">

                                <input type="hidden" name="type" value="">
                                <button type="button" data-type="excel"
                                        class="btn btn-success waves-effect waves-light btn-sm btn-export-data"><i
                                        class="fa fa-file-excel-o"></i> Xuất Excel
                                </button>
{{--                                <button type="button" data-type="pdf"--}}
{{--                                        class="btn btn-warning waves-effect waves-light btn-sm btn-export-data"><i--}}
{{--                                        class="fa fa-file-pdf-o"></i> Xuất PDF--}}
{{--                                </button>--}}
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
                    </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h3>SỔ ĐĂNG KÝ VĂN BẢN ĐI</h3>
                                </div>
                                <div class="col-md-12 text-center">
                                    <b>Năm: {{date('Y')}}</b>
                                </div>
                                @if(Request::get('den_ngay') || Request::get('tu_ngay') )
                                <div class="col-md-12 text-center">
                                    <b>Từ ngày: {{Request::get('tu_ngay')}} &emsp;&emsp;&emsp; Đến ngày: {{Request::get('den_ngay')}}</b>
                                </div>
                                @endif
                                @if(Request::get('tu_so') || Request::get('den_so') )
                                <div class="col-md-12 text-center">
                                    <b>Từ số: {{Request::get('tu_so')}}  &emsp;&emsp;&emsp; Đến số: {{Request::get('den_so')}}</b>
                                </div>
                                @endif
                                <form action="{{route('in-so-van-ban-di.index')}}" method="get">
                                <div class="col-md-12 collapse" id="collapseExample">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="sokyhieu" class="col-form-label">chọn sổ văn bản </label>
                                            <select class="form-control select2" name="so_van_ban">
                                                <option  value="">-- Chọn sổ muốn in --</option>
                                                @foreach($sovanban as $loaiso)
                                                    <option {{Request:: get('so_van_ban')== $loaiso->id ? 'selected': ''}} value="{{$loaiso->id}}">{{$loaiso->ten_so_van_ban}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tu_ngay" class="col-form-label">Từ ngày </label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control han-xu-ly datepicker"
                                                       name="tu_ngay" value="{{Request::get('tu_ngay')}}" placeholder="Nhập ngày...">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="den_ngay" class="col-form-label">Đến ngày</label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control han-xu-ly datepicker ngay-nhan"
                                                       name="den_ngay" value="{{Request::get('den_ngay')}}" placeholder="Nhập ngày...">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tu_so" class="col-form-label">Từ số </label>
                                            <input type="number" value="{{Request::get('tu_so')}}"
                                                   id="tu_so" name="tu_so" class="form-control"
                                                   placeholder="Nhập số đi...">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="den_so" class="col-form-label">Đến số</label>
                                            <input type="number" value="{{Request::get('den_so')}}"
                                                   id="den_so" name="den_so" class="form-control"
                                                   placeholder="Nhập số đi..">
                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>

                                </form>

                                <div class="col-md-12 table-responsive mt-2" style="margin-top: 5px">
                                    Tổng số văn bản: <b>{{ $ds_vanBanDi->total() }}</b>
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="vertical-align: middle" width="3%">STT</th>
                                            <th class="text-center" style="vertical-align: middle" width="8%">Số ký hiệu</th>
                                            <th class="text-center" style="vertical-align: middle" width="8%">Ngày ban hành</th>
                                            <th class="text-center" style="vertical-align: middle" width="">Loại văn bản và Trích yếu</th>
                                            <th class="text-center" style="vertical-align: middle" width="10%">Người ký</th>
                                            <th class="text-center" style="vertical-align: middle" width="10%">Nơi nhận</th>
                                            <th class="text-center" style="vertical-align: middle" width="">Người nhận bản lưu</th>
                                            <th class="text-center" style="vertical-align: middle" width="">Số bản</th>
                                            <th class="text-center" style="vertical-align: middle" width="">Ngày chuyển</th>
                                            <th class="text-center" style="vertical-align: middle" width="">Ký nhận</th>
                                            <th class="text-center" style="vertical-align: middle" width="">Ghi chú</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($ds_vanBanDi as $key=>$vbDi)
                                            <tr>

                                                <td class="text-center" style="vertical-align: middle"> {{$key+1}}</td>
                                                <td class="text-center" style="vertical-align: middle">{{$vbDi->so_ky_hieu}}</td>
                                                <td class="text-center" style="vertical-align: middle">{{ date('d-m-Y', strtotime($vbDi->ngay_ban_hanh))}}</td>
                                                <td ><a href="{{route('Quytrinhxulyvanbandi',$vbDi->id)}}" title="{{$vbDi->trich_yeu}}"><u>({{$vbDi->loaivanban->ten_loai_van_ban ?? ''}})</u> - {{$vbDi->trich_yeu}}</a></td>
                                                <td style="vertical-align: middle;text-align: center">{{$vbDi->nguoidung2->ho_ten ?? ''}}</td>
                                                <td class="text-left" style="vertical-align: middle" >
                                                    <div style="max-height:120px;  overflow:auto">
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
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @empty
                                            <td colspan="12" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-6" style="margin-top: 5px">
                                            Tổng số văn bản: <b>{{ $ds_vanBanDi->total() }}</b>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            {!! $ds_vanBanDi->appends(['tu_ngay' => Request::get('tu_ngay'), 'den_ngay' => Request::get('den_ngay')
                                               ,'so_van_ban' => Request::get('so_van_ban')])->render() !!}
                                        </div>
                                    </div>
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
            let tu_ngay = $('input[name="tu_ngay"]').val();
            let den_ngay = $('input[name="den_ngay"]').val();
            let tu_so = $('input[name="tu_so"]').val();
            let den_so = $('input[name="den_so"]').val();
            let type = $(this).data('type');
            console.log(so_van_ban , type);
            $.ajax({
                url: APP_URL + 'in-so-van-ban-di',
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    so_van_ban: so_van_ban,
                    tu_ngay: tu_ngay,
                    den_ngay: den_ngay,
                    tu_so: tu_so,
                    den_so: den_so,

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







