@extends('admin::layouts.master')
@section('page_title', 'Lịch công tác')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Lịch họp cả phòng</h4>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">

                            <form action="{{ route('caPhong') }}" method="get" class="form-row">
{{--                                <div class="col-md-2">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <select name="lanh_dao_id" class="form-control select2"--}}
{{--                                                onchange="this.form.submit()">--}}
{{--                                            <option value="">-- Lãnh đạo --</option>--}}
{{--                                            @forelse($danhSachLanhDao as $lanhdao)--}}
{{--                                                <option--}}
{{--                                                    value="{{ $lanhdao->id }}" {{ !empty(Request::get('lanh_dao_id')) && Request::get('lanh_dao_id') == $lanhdao->id ? 'selected' :  null }}>{{ $lanhdao->ho_ten }}</option>--}}
{{--                                            @empty--}}
{{--                                            @endforelse--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-2 text-center">
                                    <div class="form-group">
                                        <a href="{{ route('caPhong','tuan='. date('W') ) }}"
                                           class="btn btn-primary" data-original-title="" title=""><i
                                                class="fa fa-calendar"></i> Tuần hiện tại</a>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center hidden-xs">
                                    <div class="form-group">
                                        <a href="{{ route('caPhong','tuan='.$tuanTruoc. '&year='.Request::get('year')) }}"
                                           class="btn btn-primary"
                                           data-original-title="" title=""><i
                                                class="fa fa-backward"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="tuan" class="form-control select2"
                                                onchange="this.form.submit()">
                                            @for($i = 1; $i <= $totalWeekOfYear; $i++)
                                                <option value="{{ $i < 10 ? '0'.$i : $i }}" {{ $i == $week ? 'selected' : '' }}>
                                                    Tuần {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <div class="form-group">
                                        <a href="{{ route('caPhong','tuan='.$tuanSau .'&year='.Request::get('year')) }}"
                                           class="btn btn-primary" data-original-title="" title=""><i
                                                class="fa fa-forward"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select name="year" class="form-control form-inline select2" onchange="this.form.submit()">
                                        @for($i = 2020; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                                Năm {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered dataTable data-row table-lich-cong-tac">
                                <thead>
                                <tr class="background:#ccc;">
                                    <th width="9%" class="text-center" style="vertical-align: middle;">Họ tên / Thứ</th>
                                    @foreach($ngayTuan as $key=>$ngayTrongTuan)
                                    <th width="9%" class="text-center" style="vertical-align: middle;"><b>{{ $ngayTrongTuan[0] }}
                                            <br>
                                          ({{ $ngayTrongTuan[1] }})</b>
                                    </th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>

                                    @forelse($danhSachLanhDao as $item)

                                        <tr>
                                        <td  class="text-left bg-table-gray">
                                            <b>{{ $item->ho_ten }}</b>
                                            <b></b>
                                        </td>
                                            @foreach($ngayTuan as $key=>$ngayTrongTuan)
                                            <td>
                                                @forelse($item->caPhong($item->id,Request::get('tuan'),Request::get('year')) as $lichCongTac)
                                                    @if ( $ngayTrongTuan[1] == date('d/m/Y', strtotime($lichCongTac->ngay)))

                                                        <b style="font-size: 18px">
                                                            - {{ date('H:i', strtotime($lichCongTac->gio)) }} :</b> <a
                                                            href="{{ route('van_ban_den_chi_tiet.show', $lichCongTac->object_id ) }}">{{ $lichCongTac->noi_dung ??  null  }}</a> <br>
                                                        <div class="text-center bg-table-gray mt-2 mb-2">------------------
                                                            <a href="{{route('giay_moi_don_vi.da_chi_dao','type=1&id='.$lichCongTac->object_id)}}"><i class="fa fa-edit"></i></a> <br>    </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @endforeach

                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="modal fade modal-edit-calendar" role="dialog" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content modal-data-push">

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
        $('.btn-edit-calendar').on('click', function () {
            let id = $(this).data('id');
            $.ajax({
                url: APP_URL + '/lich-cong-tac/edit/' + id,
                type: 'get'
            })
                .done(function (response) {
                    $('.modal-data-push').html(response.html);
                    $('.modal-edit-calendar').modal();
                    $('.time-picker-24h').timepicker({
                        showMeridian: !1,
                        showInputs: false
                    })
                })
                .fail(function (error) {
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });
        });
    </script>
@endsection
