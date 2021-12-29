@extends('admin::layouts.master')
@section('page_title', 'Văn bản đã xử lý')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Văn bản đã xử lý</h4>
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
                                    <form action="@if(Request::get('type') == 1) {{ route('giay-moi-den-phoi-hop.da-xu-ly') }} @else {{ route('van-ban-den-phoi-hop.da-xu-ly') }}@endif" id="formsb">
                                        <b>Sắp xếp:</b>

                                        <select class="" name="sap_xep" form="formsb"   onchange="this.form.submit();">
                                            <option value="" {{ Request::get('sap_xep') == '' ? 'selected' : '' }}>-- Mặc định --</option>
                                            <option value="1" {{ Request::get('sap_xep') == 1 ? 'selected' : '' }}>-- Sắp xếp A-Z --</option>
                                            <option value="2" {{ Request::get('sap_xep') == 2 ? 'selected' : '' }}>-- Sắp xếp Z-A --</option>
                                        </select>
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
                                <th width="14%" class="text-center">Kết quả</th>
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
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id. '?status=1') }}">{{ $vanBanDen->hasChild->trich_yeu }}</a>
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
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id) }}">{{ $vanBanDen->trich_yeu }}</a>
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
                                    <td>
                                        <p><b>Nội
                                            dung:</b> {{ $vanBanDen->donViPhoiHopGiaiQuyetByUserId->noi_dung ?? null }}</p>
                                        @if (isset($vanBanDen->donViPhoiHopGiaiQuyetByUserId->phoiHopGiaiQuyetFile))
                                            @foreach($vanBanDen->donViPhoiHopGiaiQuyetByUserId->phoiHopGiaiQuyetFile as $key => $file)
                                                <a href="{{ $file->getUrlFile() }}"
                                                   target="popup"
                                                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                    ]</a>
                                                @if (count($vanBanDen->donViPhoiHopGiaiQuyetByUserId->phoiHopGiaiQuyetFile)-1 != $key)
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <td colspan="5" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">

                            </div>
                            <div class="col-md-6 text-right">
                                {!! $danhSachVanBanDen->appends(['trich_yeu' => Request::get('trich_yeu'), 'so_den' => Request::get('so_den'), 'type' => Request::get('type'),
                            'sap_xep' => Request::get('sap_xep')])->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
