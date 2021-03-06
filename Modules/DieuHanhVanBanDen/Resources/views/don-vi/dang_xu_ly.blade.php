@extends('admin::layouts.master')
@section('page_title', 'Văn bản đang xử lý')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-3">
                                <h4 class="header-title pt-2">Văn bản {{ Request::get('qua_han') ? 'quá hạn' : '' }} đang xử lý</h4>
                            </div>
                            <div class="col-md-4 text-left">
                                <button type="button" onclick="showModal()"
                                        class="btn btn-sm mt-1 btn-primary waves-effect waves-light  btn-sm mb-2"
                                        data-original-title=""
                                        title=""><i class="fa fa-search"></i> Tìm kiếm
                                </button>
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
                                                <div class="col-md-12">
                                                    <form action="@if(Request::get('type') == 1){{route('giay-moi-den-don-vi.dang_xu_ly')}}@else{{route('van-ban-den-don-vi.dang_xu_ly')}}@endif" method="get">
                                                        <div class="col-md-3 form-group">
                                                            <label>Tìm theo trích yếu</label>
                                                            <input type="text" class="form-control" value="{{Request::get('trich_yeu')}}"
                                                                   name="trich_yeu"
                                                                   placeholder="Nhập trích yếu">
                                                            <input type="text" class="form-control hidden" value="{{Request::get('type')}}"
                                                                   name="type"
                                                                   placeholder="Nhập trích yếu">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label>Tìm theo số đến</label>
                                                            <input type="text" class="form-control" value="{{Request::get('so_den')}}"
                                                                   name="so_den"
                                                                   placeholder="Nhập số đến">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label>Tìm theo số ký hiệu</label>
                                                            <input type="text" class="form-control" value="{{Request::get('so_ky_hieu')}}"
                                                                   name="so_ky_hieu"
                                                                   placeholder="Nhập số ký hiệu..">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label>Tìm theo ngày</label>
                                                            <div class="input-group date">
                                                                <input type="text" class="form-control datepicker" value="{{Request::get('date')}}"
                                                                       name="date" placeholder="dd/mm/yyyy">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar-o"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>&nbsp;</label><br>
                                                            <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                                            @if (!empty(Request::get('trich_yeu')) || !empty(Request::get('so_den')) ||
                                                                        !empty(Request::get('date')))
                                                                <a href="{{ route('van-ban-den-don-vi.dang_xu_ly') }}" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                                            @endif
                                                        </div>
                                                    </form>


                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                </div>
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
                                    <form action="@if(Request::get('type') == 1) {{route('giay-moi-den-don-vi.dang_xu_ly')}} @else {{route('van-ban-den-don-vi.dang_xu_ly')}} @endif" id="formsb">
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
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td class="{{ Request::get('qua_han') ? 'color-red' : null }}">
                                        @include('dieuhanhvanbanden::van-ban-den.info')
                                    </td>
                                    <td>
                                        @if ($vanBanDen->hasChild)
                                            <p>
                                                @if (!empty(Request::get('qua_han')))
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?xuly=true') }}">{{ $vanBanDen->hasChild->trich_yeu }}</a>
                                                @else
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id) }}">{{ $vanBanDen->hasChild->trich_yeu }}</a>
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
                                                @if (!empty(Request::get('qua_han')))
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?xuly=true') }}">{{ $vanBanDen->trich_yeu }}</a>
                                                @else
                                                    <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id) }}">{{ $vanBanDen->trich_yeu }}</a>
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
                                </tr>
                            @empty
                                <td colspan="5" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="clearfix">
                            <div class="row">
                                <div class="col-md-6" style="margin-top: 5px">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                {!! $danhSachVanBanDen->appends(['trich_yeu' => Request::get('trich_yeu'),'so_ky_hieu' => Request::get('so_ky_hieu'),'type' => Request::get('type'),'sap_xep' => Request::get('sap_xep'),
 'so_den' => Request::get('so_den'), 'date' => Request::get('date'), 'qua_han' => Request::get('qua_han')])->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        function showModal() {
            $("#myModal").modal('show');
        }

    </script>
@endsection
