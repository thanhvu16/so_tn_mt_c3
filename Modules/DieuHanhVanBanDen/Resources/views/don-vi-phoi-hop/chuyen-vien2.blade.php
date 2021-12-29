@extends('admin::layouts.master')
@if (empty(Request::get('chuyen_tiep')))
    @section('page_title', 'Văn bản chờ xử lý')
@else
    @section('page_title', 'Văn bản đang xử lý')
@endif
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Văn bản {{ empty(Request::get('chuyen_tiep')) ? 'chờ' : 'đang' }} xử lý</h4>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('van-ban-den-don-vi.store') }}" method="post"
                                      id="form-tham-muu">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_id" value="">
                                    <input type="hidden" name="van_ban_tra_lai" value="">
                                    <input type="hidden" name="type" value="update">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        <div class="col-md-12 mb-2 mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    <form action="@if(Request::get('type') == 1) {{ empty(Request::get('chuyen_tiep')) ? route('giay-moi-den-phoi-hop.index') : route('giay-moi-den-phoi-hop.dang-xu-ly') }} @else {{ empty(Request::get('chuyen_tiep')) ? route('van-ban-den-phoi-hop.index') : route('van-ban-den-phoi-hop.dang-xu-ly') }}@endif" id="formsb">
                                        <b>Sắp xếp:</b>

                                        <select class="" name="sap_xep" form="formsb"   onchange="this.form.submit();">
                                            <option value="" {{ Request::get('sap_xep') == '' ? 'selected' : '' }}>-- Mặc định --</option>
                                            <option value="1" {{ Request::get('sap_xep') == 1 ? 'selected' : '' }}>-- Sắp xếp A-Z --</option>
                                            <option value="2" {{ Request::get('sap_xep') == 2 ? 'selected' : '' }}>-- Sắp xếp Z-A --</option>
                                        </select>
                                        <input type="hidden" name="chuyen_tiep" value="{{Request::get('chuyen_tiep')}}">
                                        <input type="hidden" name="type" value="{{Request::get('type')}}">
                                    </form>

                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="25%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="20%" class="text-center">Tóm tắt VB</th>
                                <th width="14%" class="text-center">Trình tự xử lý</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        @if($vanBanDen->hasChild)
                                            <p>
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=phoi_hop&status=1') }}">{{ $vanBanDen->hasChild->trich_yeu }}</a>
                                                <br>
                                                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->hasChild->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                    <i>
                                                        (Vào hồi {{ date( "H:i", strtotime($vanBanDen->hasChild->gio_hop)) }}
                                                        ngày {{ date('d/m/Y', strtotime($vanBanDen->hasChild->ngay_hop)) }}
                                                        , tại {{ $vanBanDen->hasChild->dia_diem }})
                                                    </i>
                                                @endif
                                            </p>
                                        @else
                                            <p>
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=phoi_hop') }}">{{ $vanBanDen->trich_yeu }}</a>
                                                <br>
                                                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                    <i>
                                                        (Vào hồi {{ date( "H:i", strtotime($vanBanDen->gio_hop)) }}
                                                        ngày {{ date('d/m/Y', strtotime($vanBanDen->ngay_hop)) }}
                                                        , tại {{ $vanBanDen->dia_diem }})
                                                    </i>
                                                @endif
                                            </p>
                                        @endif
                                        @include('dieuhanhvanbanden::van-ban-den.info')
                                    </td>
                                    <td>
                                        <p>
                                            {{ $vanBanDen->tom_tat ?? $vanBanDen->trich_yeu }}
                                        </p>
                                    </td>
                                    <td>
                                        @if($vanBanDen->xuLyVanBanDen)
                                            @foreach($vanBanDen->xuLyVanBanDen as $key => $chuyenVienXuLy)
                                                <p>
                                                    {{ $key+1 }}. {{$chuyenVienXuLy->canBoNhan->ho_ten ?? null }}
                                                </p>
                                                <hr class="border-dashed {{ count($vanBanDen->donViChuTri) == 0 && count($vanBanDen->xuLyVanBanDen  )-1 == $key ? 'hide' : 'show' }}">
                                            @endforeach
                                        @endif
                                        @if($vanBanDen->donViChuTri)
                                            @foreach($vanBanDen->donViChuTri as $key => $chuyenNhanVanBanDonVi)
                                                <p>
                                                    {{ count($vanBanDen->xuLyVanBanDen) > 0 ? count($vanBanDen->xuLyVanBanDen)+($key+1) : $key+1 }}
                                                    . {{$chuyenNhanVanBanDonVi->canBoNhan->ho_ten ?? null }}
                                                </p>
                                                <hr class="border-dashed {{ count($vanBanDen->donViChuTri)-1 == $key ? 'hide' : 'show' }}">
                                            @endforeach
                                        @endif

                                        @if (!empty($vanBanDen->giaHanVanBanLanhDaoDuyet(1)))
                                            <p>
                                                <i>(<b>Gia hạn thêm chờ
                                                        duyệt:</b> {{ date('d/m/Y', strtotime($vanBanDen->giaHanVanBanLanhDaoDuyet(1)->thoi_han_de_xuat)) }}
                                                    )</i>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <td colspan="4" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">

                            </div>
                            <div class="col-md-6 text-right">
                                {{ $danhSachVanBanDen->appends(['so_den'  => Request::get('so_den'),'type'  => Request::get('type'), 'han_xu_ly'  => Request::get('han_xu_ly'),
 'sap_xep'  => Request::get('sap_xep'), 'trich_yeu' => Request::get('trich_yeu')])->render() }}
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
        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            let traLai = $(this).data('tra-lai');
            $('#modal-tra-lai').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-tra-lai').find('input[name="type"]').val(traLai);
        });

        $('.btn-gia-han').on('click', function () {
            let id = $(this).data('id');
            let hanCu = $(this).data('han');
            let hanFormat = moment(hanCu, "YYYY-MM-DD").format("DD/MM/YYYY");

            $('#modal-de_xuat_gia_han').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-de_xuat_gia_han').find('input[name="thoi_han_cu"]').val(hanFormat);
        });

    </script>
@endsection
