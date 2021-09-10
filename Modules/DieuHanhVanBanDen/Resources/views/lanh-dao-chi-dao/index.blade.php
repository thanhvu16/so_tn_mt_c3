@extends('admin::layouts.master')
@section('page_title', 'Lãnh đạo chỉ đạo')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-2">
                                <h3 class="box-title mt-2">Văn bản chờ chỉ đạo</h3>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" onclick="showModal()"><i
                                        class="fa  fa-search"></i><span
                                        style="font-size: 14px"> Tìm kiếm văn bản </span></button>
                            </div>
                            <div class="col-md-6">

                            </div>
                        </div>

                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <div class="modal fade" id="myModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content ">
                                        @csrf
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title"><i
                                                    class="fa fa-search"></i> Tìm kiếm nâng cao</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <form action="" method="get">
                                                    @include('dieuhanhvanbanden::form_tim_kiem')
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;" >
                        Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr role="row">
                                <th width="2%" class="text-center">STT</th>
                                <th width="" class="text-center">Trích yếu - Thông tin</th>
                                <th width="20%" class="text-center">Tóm tắt văn bản</th>
                                <th width="20%" class="text-center">Chỉ đạo</th>
                                <th width="8%" class="text-center">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <form action="{{route('lanh-dao-chi-dao.store')}}" method="post" id="form-tham-muu">
                                    @csrf
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        <p>
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->vanBanDen->id) }}">{{ $vanBanDen->vanBanDen->trich_yeu }}</a>
                                            <br>
                                            @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                <i>
                                                    (Vào hồi {{ date( "H:i", strtotime($vanBanDen->vanBanDen->gio_hop)) }}
                                                    ngày {{ date('d/m/Y', strtotime($vanBanDen->vanBanDen->ngay_hop)) }}
                                                    , tại {{ $vanBanDen->vanBanDen->dia_diem }})
                                                </i>
                                            @endif
                                        </p>
                                        @if (!empty($vanBanDen->vanBanDen->noi_dung))
                                            <p>
                                                <b>Nội dung:</b> <i>{{ $vanBanDen->vanBanDen->noi_dung }}</i>
                                            </p>
                                        @endif
                                        <p class="text-initial">- Nơi gửi
                                            đến: {{ $vanBanDen->vanBanDen->co_quan_ban_hanh ?? null }}</p>
                                        <p class="text-initial">- Số đến: <span class="color-red text-bold">{{ $vanBanDen->vanBanDen->so_den ?? null }}</span></p>
                                        <p class="text-initial">- Ngày
                                            nhập: {{  !empty($vanBanDen->vanBanDen->created_at) ? date('d/m/Y', strtotime($vanBanDen->vanBanDen->created_at)) : null }}</p>
                                        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                            @if (!empty($vanBanDen->vanBanDen->lichCongTac->lanhDao))
                                                <p class="text-initial">
                                                    <b>- Lãnh đạo dự họp:</b><i>{{ $vanBanDen->vanBanDen->lichCongTac->lanhDao->ho_ten ?? null }}</i>
                                                </p>
                                            @endif
                                        @endif
                                        @if ($vanBanDen->vanBanDen->nguoiDung)
                                            <p class="text-initial">- Cán bộ nhập: {{ $vanBanDen->vanBanDen->nguoiDung->ho_ten  }}</p>
                                        @endif
                                        @if(!empty($vanBanDen->vanBanDen->han_xu_ly))
                                            <p class="text-initial">
                                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->vanBanDen->han_xu_ly)) }}
                                                </b>
                                            </p>
                                        @endif

                                        @if (isset($vanBanDen->vanBanDen->vanBanDenFile))
                                            @foreach($vanBanDen->vanBanDen->vanBanDenFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                @if (count($vanBanDen->vanBanDen->vanBanDenFile)-1 != $key)
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <p>
                                            {{ $vanBanDen->vanBanDen->tom_tat ?? $vanBanDen->vanBanDen->trich_yeu }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <textarea name="y_kien" class="form-control"
                                                      form="form-tham-muu"
                                                      placeholder="nhập ý kiến..."
                                                      rows="9">Đồng ý</textarea>
                                            <input type="hidden" value="{{$vanBanDen->id}}" name="id_lanh_dao">
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            class="btn waves-effect btn-primary btn-choose-status btn-sm mb-2 "
                                            name="submit_Duyet" onclick="" value="1" data-type="3">
                                            <i class="fa fa-paper-plane-o"></i> Duyệt
                                        </button>
                                    </td>
                                </tr>
                                </form>
                            @empty
                                <td colspan="6" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">

                            </div>
                            <div class="col-md-6 text-right">
                                {!! $danhSachVanBanDen->appends(['trich_yeu' => Request::get('trich_yeu'), 'so_den' => Request::get('so_den'), 'date' => Request::get('date')])->render() !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/xu_ly_van_ban_den/js/index.js') }}"></script>
    <script>
        function showModal() {
            $("#myModal").modal('show');
        }
        $('body').on('keyup', 'input[name="so_den_start"]', function () {
            let val = $(this).val();
            $('input[name="so_den_end"]').val(val);
        });

        $('body').on('change', 'input[name="ngay_den_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_den_end"]').val(val);
        });

        $('body').on('keyup', 'input[name="ngay_den_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_den_end"]').val(val);
        });

        $('body').on('change', 'input[name="ngay_ban_hanh_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_ban_hanh_end"]').val(val);
        });

        $('body').on('keyup', 'input[name="ngay_ban_hanh_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_ban_hanh_end"]').val(val);
        });

        $('body').on('change', 'input[name="ngay_hop_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_hop_end"]').val(val);
        });

        $('body').on('keyup', 'input[name="ngay_hop_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_hop_end"]').val(val);
        });

    </script>
@endsection
