@extends('admin::layouts.master')
@if(Request::get('status'))
    @section('page_title', 'Văn bản đã xử lý')
@else
    @section('page_title', 'Văn bản chờ xử lý')
@endif
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Văn bản {{ Request::get('status') ? 'đã' : 'chờ' }} xử lý</h4>
                            </div>
                        </div>
                    </div>
                    <div class="box-body " style=" width: 100%;overflow-x: auto;">
                        <form action="{{ route('van-ban-den-hoan-thanh.index') }}" method="get">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="so-den" class="col-form-label">Tìm theo số đến</label>
                                    <input type="text" class="form-control" placeholder="Nhập số đến"
                                           name="so_den" value="{{ Request::get('so_den') ?? null }}">
                                    <input type="text" class="form-control hidden" value="{{Request::get('type')}}"
                                           name="type"
                                           placeholder="Nhập trích yếu">
                                </div>
                                <div class="col-md-3">
                                    <label for="han-xu-ly" class="col-form-label">Tìm theo hạn xử lý</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right datepicker" placeholder="DD/MM/YYYY"
                                               name="han_xu_ly" value="{{ Request::get('han_xu_ly') ?? null }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="trich-yeu">Tìm theo trích yếu</label>
                                    <input type="text" name="trich_yeu" class="form-control" value="{{ Request::get('trich_yeu') ?? null }}" placeholder="nhập nội dung...">
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="col-form-label">&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Tìm kiếm
                                    </button>
                                    @if(request('han_xu_ly') || request('trich_yeu') || request('so_den'))
                                        <a href="{{ route('van-ban-den-hoan-thanh.index') }}">
                                            <button type="button" class="btn btn-success">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="col-md-12 mb-2 mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    <form action="@if(Request::get('type') == 1) {{ empty(Request::get('status')) ? route('giay_moi_den_chuyen_vien.index') : route('giay_moi_den_chuyen_vien.da_xu_ly') }} @else {{ empty(Request::get('status')) ? route('van_ban_den_chuyen_vien.index') : route('van_ban_den_chuyen_vien.da_xu_ly') }}@endif" id="formsb">
                                        <b>Sắp xếp:</b>

                                        <select class="" name="sap_xep" form="formsb"   onchange="this.form.submit();">
                                            <option value="" {{ Request::get('sap_xep') == '' ? 'selected' : '' }}>-- Mặc định --</option>
                                            <option value="1" {{ Request::get('sap_xep') == 1 ? 'selected' : '' }}>-- Sắp xếp A-Z --</option>
                                            <option value="2" {{ Request::get('sap_xep') == 2 ? 'selected' : '' }}>-- Sắp xếp Z-A --</option>
                                        </select>
                                        <input type="hidden" name="status" value="{{Request::get('status')}}">
                                        <input type="hidden" name="type" value="{{Request::get('type')}}">

                                    </form>

                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                                <tr role="row" class="text-center">
                                    <th width="2%" class="text-center">STT</th>
                                    <th width="22%" class="text-center">Thông tin</th>
                                    <th width="25%" class="text-center">Trích yếu - nội dung</th>
                                    <th width="15%" class="text-center">Trình tự xử lý</th>
                                    @if (Request::get('status'))
                                        <th width="14%" class="text-center">Kết quả</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        @include('dieuhanhvanbanden::van-ban-den.info')
                                    </td>
                                    <td>
                                        @if($vanBanDen->hasChild)
                                            <p>
                                                @if (Request::get('status'))
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=cv_phoi_hop&edit=true') }}">{{ $vanBanDen->hasChild->trich_yeu }}</a>
                                                @else
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=cv_phoi_hop') }}">{{ $vanBanDen->hasChild->trich_yeu }}</a>
                                                @endif
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
                                                @if (Request::get('status'))
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=cv_phoi_hop&edit=true') }}">{{ $vanBanDen->trich_yeu }}</a>
                                                @else
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=cv_phoi_hop') }}">{{ $vanBanDen->trich_yeu }}</a>
                                                @endif
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
                                    </td>
                                    @if (Request::get('status'))
                                        <td>
                                            <p><b>Nội dung:</b> {{ $vanBanDen->phoiHopGiaiQuyetByUserId->noi_dung ?? null }}</p>
                                            @if (isset($vanBanDen->phoiHopGiaiQuyetByUserId->phoiHopGiaiQuyetFile))
                                                @foreach($vanBanDen->phoiHopGiaiQuyetByUserId->phoiHopGiaiQuyetFile as $key => $file)
                                                    <a href="{{ $file->getUrlFile() }}"
                                                       target="popup"
                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                    @if (count($vanBanDen->phoiHopGiaiQuyetByUserId->phoiHopGiaiQuyetFile)-1 != $key)
                                                        &nbsp;|&nbsp;
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <td colspan="{{ Request::get('status') ? 5 : 4 }}" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">

                            </div>
                            <div class="col-md-6 text-right">
                                {!! $danhSachVanBanDen->appends(['status' => Request::get('status'), 'so_den' => Request::get('so_den'), 'sap_xep' => Request::get('sap_xep'), 'date' => Request::get('date'), 'chuyen_tiep' => 1])->render() !!}

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
