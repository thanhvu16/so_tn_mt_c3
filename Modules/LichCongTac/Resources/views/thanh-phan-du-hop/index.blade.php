@extends('admin::layouts.master')
@section('page_title', 'Tham dự cuộc họp')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Tham dự cuộc họp</h4>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        {{--                        table table-striped table-bordered dataTable data-row table-lich-cong-tac--}}
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered table-customize-border table-hover">
                                <thead>
                                <tr>
                                    <th width="3%" class="text-center">STT</th>
                                    <th width="14%" class="text-center">Thời gian họp</th>
                                    <th width="20%" class="text-center">Nội dung</th>
                                    <th width="14%" class="text-center">Chủ trì</th>
                                    <th width="19%" class="text-center">Địa điểm</th>
                                    <th width="14%" class="text-center">Cán bộ đi họp</th>
                                    <th width="6%" class="text-center">Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($danhSachLichCongTac as $lichCongTac)
                                    <tr>
                                        <td class="text-center">{{ $order++ }}</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($lichCongTac->ngay)) .' - '. date("H:i", strtotime($lichCongTac->gio)) }}</td>
                                        <td>
                                            <a href="{{route('chitiethop',$lichCongTac->id)}}">{{ $lichCongTac->noi_dung }}</a>
                                        </td>
                                        <td>{{ $lichCongTac->lanhDao->ho_ten }}</td>
                                        <td>{{ $lichCongTac->dia_diem ?? '' }}</td>
                                        <td>
                                            @if (count($lichCongTac->listThanhPhanDuHop))
                                                @foreach($lichCongTac->listThanhPhanDuHop as $canBoDuHop)
                                                    <p style="font-size: 14px;">{{ $canBoDuHop->user->ho_ten ?? null }}</p>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @unlessrole('chuyên viên')
                                            <p>
                                                <button type="button" value="{{ $lichCongTac->id }}"
                                                        class="btn btn-primary btn-sm btn-giao-du-hop">Giao dự họp
                                                </button>
                                            </p>
                                            @endunlessrole
                                            @if ($lichCongTac->lichCaNhanDuHop->trang_thai == 1 && empty($lichCongTac->checkDaChuyenLichCaNhan))
                                                <form action="{{ route('lich-cong-tac.store') }}"
                                                      method="post">
                                                    @csrf
                                                    <input type="hidden" name="thanh_phan_du_hop_id" value="{{ $lichCongTac->lichCaNhanDuHop->id }}">
                                                    <p>
                                                        <button type="submit"
                                                                onclick="return confirm('Bạn có chắc muốn chuyển sang lịch công tác ' +
                                                                        'cá nhân');"
                                                                name="lich_cong_tac_id"
                                                                value="{{ $lichCongTac->id }}"
                                                                class="btn btn-primary btn-sm"><i
                                                                class="fa fa-send"></i> Chuyển lịch cá nhân
                                                        </button>
                                                    </p>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số: <b>{{ $danhSachLichCongTac->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {{ $danhSachLichCongTac->appends(['so_den'  => Request::get('so_den'), 'han_xu_ly'  => Request::get('han_xu_ly'), 'trich_yeu' => Request::get('trich_yeu')])->render() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="modal fade modal-show-can-bo-du-hop" role="dialog"
                             aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog" role="document">
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
        $('.btn-giao-du-hop').on('click', function () {
            let id = $(this).val();
            console.log(id);
            $.ajax({
                url: APP_URL + '/tham-du-cuoc-hop/' + id,
                type: 'get'
            })
                .done(function (response) {
                    $('.modal-data-push').html(response.html);
                    $('.modal-show-can-bo-du-hop').modal();
                })
                .fail(function (error) {
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });
        });
    </script>
@endsection
