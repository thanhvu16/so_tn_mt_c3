@extends('admin::layouts.master')
@section('page_title', 'Văn bản chờ xử lý')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title pt-2">Văn bản chờ xử lý</h4>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('van-ban-den-don-vi.store') }}" method="post"
                                      id="form-tham-muu">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_id" value="">
                                    <input type="hidden" name="van_ban_tra_lai" value="">

                                    <button type="button"
                                            class="btn btn-sm mt-1 btn-submit btn-primary waves-effect waves-light pull-right btn-duyet-all disabled pull-right btn-sm mb-2"
                                            data-original-title=""
                                            title=""><i class="fa fa-check"></i> Duyệt
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="@if(Request::get('type') == 1){{route('giayMoiLanhDaoXuLy')}}@else{{route('van-ban-lanh-dao-xu-ly.index')}}@endif" method="get">
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
{{--                                <div class="col-md-3 form-group">--}}
{{--                                    <label>Tìm theo ngày</label>--}}
{{--                                    <div class="input-group date">--}}
{{--                                        <div class="input-group-addon">--}}
{{--                                            <i class="fa fa-calendar-o"></i>--}}
{{--                                        </div>--}}
{{--                                        <input type="text" class="form-control datepicker" value="{{Request::get('date')}}"--}}
{{--                                               name="date" placeholder="dd/mm/yyyy">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-3">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                    @if (!empty(Request::get('trich_yeu')) || !empty(Request::get('so_den')) ||
                                                !empty(Request::get('date')))
                                        <a href="{{ route('phan-loai-van-ban.da_phan_loai') }}" class="btn btn-success"><i
                                                class="fa fa-refresh"></i></a>
                                    @endif
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        @include('dieuhanhvanbanden::van-ban-den.fom_tra_lai', ['active' => $active])
                        Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="45%" class="text-center">Trích yếu - Thông tin</th>
                                <th class="text-center" width="21%">Ý kiến</th>
                                <th width="20%" class="text-center">Chỉ đạo</th>
                                {{--                                @if (auth::user()->hasRole(PHO_CHU_TICH))--}}
                                <th class="text-center" width="7%">
                                    <input id="check-all" type="checkbox" name="check_all" value="">
                                </th>
                                {{--                                @endif--}}
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <input type="hidden" name="don_vi_du_hop[{{ $vanBanDen->id }}]"
                                           value="{{ $vanBanDen->lichCongTacDonVi ? 1 : null }}"
                                           class="check-don-vi-du-hop" form="form-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        @if($vanBanDen->hasChild)
                                            <p>
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?xuly=true') }}">{{ $vanBanDen->hasChild->trich_yeu ?? null }}</a>
                                                <br>
                                                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->hasChild->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                    <i>
                                                        (Vào
                                                        hồi {{ date( "H:i", strtotime($vanBanDen->hasChild->gio_hop)) }}
                                                        ngày {{ date('d/m/Y', strtotime($vanBanDen->hasChild->ngay_hop)) }}
                                                        , tại {{ $vanBanDen->hasChild->dia_diem }})
                                                    </i>
                                                @endif
                                            </p>
                                        @else
                                            <p>
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?xuly=true') }}">{{ $vanBanDen->trich_yeu }}</a>
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
                                        <p>
                                            <a data-toggle="collapse" class="color-black"
                                               href="#tom-tat-van-ban-{{ $vanBanDen->id }}" role="button"
                                               aria-expanded="false" aria-controls="tom-tat-van-ban">
                                                <i class="fa fa-book"></i> Tóm tăt văn bản
                                            </a>
                                        </p>
                                        <div class="collapse" id="tom-tat-van-ban-{{ $vanBanDen->id }}">
                                            <p>
                                                {{ $vanBanDen->tom_tat ?? $vanBanDen->trich_yeu }}
                                            </p>
                                        </div>
                                        @if ($vanBanDen->vanBanTraLai)
                                            <p class="color-red"><b>Lý
                                                    do trả
                                                    lại: </b><i>{{ $vanBanDen->vanBanTraLai->noi_dung ?? '' }}</i>
                                            </p>
                                            <p>
                                                (Cán bộ trả
                                                lại: {{ $vanBanDen->vanBanTraLai->canBoChuyen->ho_ten  ?? '' }}
                                                - {{ $vanBanDen->vanBanTraLai->canBoChuyen->donVi->ten_don_vi ?? null }}
                                                - {{ date('d/m/Y h:i:s', strtotime($vanBanDen->vanBanTraLai->created_at)) }}
                                                )</p>
                                        @endif

                                        @if ((!empty($vanBanDen->parent_id) && $vanBanDen->type == \Modules\VanBanDen\Entities\VanBanDen::TYPE_VB_DON_VI) || $vanBanDen->type == \Modules\VanBanDen\Entities\VanBanDen::TYPE_VB_HUYEN)
                                            <p>
                                                <a class="tra-lai-van-ban" data-toggle="modal"
                                                   data-target="#modal-tra-lai"
                                                   data-id="{{ $vanBanDen->id }}">
                                                    <span><i class="fa fa-reply"></i>Trả lại VB</span>
                                                </a>
                                            </p>
                                        @else
                                            @if (auth::user()->hasRole(PHO_CHU_TICH))
                                                <p>
                                                    <a class="tra-lai-van-ban" data-toggle="modal"
                                                       data-target="#modal-tra-lai"
                                                       data-id="{{ $vanBanDen->id }}">
                                                        <span><i class="fa fa-reply"></i>Trả lại VB</span>
                                                    </a>
                                                </p>
                                            @endif
                                        @endif

                                        @include('dieuhanhvanbanden::van-ban-den.thong_tin')
                                    </td>
                                    <td>
                                        <div class="dau-viec-chi-tiet" style="width: 95%;">
                                            @if (auth::user()->hasRole(CHU_TICH))
                                                <p>
                                                    <select
                                                        name="pho_chu_tich_id[{{ $vanBanDen->id }}]"
                                                        id="pho-chu-tich-{{ $vanBanDen->id }}"
                                                        class="form-control pho-chu-tich select2"
                                                        data-id="{{ $vanBanDen->id }}"
                                                        data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"
                                                        data-type="{{ isset($donVi) ? $donVi->type : 1 }}"
                                                        placeholder="Chọn phó giám đốc"
                                                        form="form-tham-muu"
                                                    >
                                                        <option value="">
                                                            Chọn {{ isset($donVi) && $donVi->type == 2 ? 'phó chi cục trưởng ' : 'phó giám đốc ' }}
                                                            chủ trì
                                                        </option>
                                                        @forelse($danhSachPhoChuTich as $phoChuTich)
                                                            <option
                                                                value="{{ $phoChuTich->id }}" {{ !empty($vanBanDen->phoChuTich) && $vanBanDen->phoChuTich->can_bo_nhan_id == $phoChuTich->id ? 'selected' : null  }}>{{ $phoChuTich->ho_ten }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </p>
                                                <p>
                                                    <select
                                                        name="lanh_dao_xem_de_biet[{{ $vanBanDen->id }}][]"
                                                        class="form-control lanh-dao-xem-de-biet select2 select2-hidden-accessible"
                                                        multiple="multiple"
                                                        form="form-tham-muu"
                                                        data-placeholder="Chọn lãnh đạo chỉ đạo, giám sát"
                                                    >
                                                        <option value="">Chọn lãnh đạo chỉ đạo, giám sát
                                                        </option>
                                                        <option
                                                            value="{{ $chuTich->id ?? null }}" {{ in_array($chuTich->id, $vanBanDen->lanhDaoXemDeBiet->pluck('lanh_dao_id')->toArray()) ? 'selected' : '' }}>{{ $chuTich->ho_ten ?? null }}</option>
                                                        @forelse($danhSachPhoChuTich as $phoChuTich)
                                                            <option
                                                                value="{{ $phoChuTich->id }}" {{ in_array($phoChuTich->id, $vanBanDen->lanhDaoXemDeBiet->pluck('lanh_dao_id')->toArray()) ? 'selected' : '' }}>{{ $phoChuTich->ho_ten }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </p>
                                            @endif
                                            <p>
                                                <select name="don_vi_chu_tri_id[{{ $vanBanDen->id }}]"
                                                        id="don-vi-chu-tri-{{ $vanBanDen->id }}"
                                                        class="form-control don-vi-chu-tri dropdown-search select2"
                                                        data-id="{{ $vanBanDen->id }}"
                                                        data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"
                                                        form="form-tham-muu">
                                                    <option value="">Chọn đơn vị chủ trì</option>
                                                    @forelse($danhSachDonVi as $donVi)
                                                        <option
                                                            value="{{ $donVi->id }}" {{ !empty($vanBanDen->donViCapXaChuTri) && $vanBanDen->donViCapXaChuTri->don_vi_id == $donVi->id ? 'selected' : null }}>{{ $donVi->ten_don_vi }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            <p>
                                                <select
                                                    name="don_vi_phoi_hop_id[{{ $vanBanDen->id }}][]"
                                                    id="don-vi-phoi-hop-{{ $vanBanDen->id }}"
                                                    class="form-control select2 don-vi-phoi-hop select2-hidden-accessible"
                                                    multiple
                                                    data-placeholder=" Chọn đơn vị phối hợp"
                                                    data-id="{{ $vanBanDen->id }}"
                                                    data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"
                                                    form="form-tham-muu">
                                                    @forelse($danhSachDonVi as $donVi)
                                                        @if (!empty($vanBanDen->donViCapXaChuTri) && $vanBanDen->donViCapXaChuTri->don_vi_id != $donVi->id)
                                                            <option
                                                                value="{{ $donVi->id }}" {{ !empty($vanBanDen->DonViCapXaPhoiHop) && in_array($donVi->id, $vanBanDen->DonViCapXaPhoiHop->pluck('don_vi_id')->toArray()) ? 'selected' : null }}>{{ $donVi->ten_don_vi }}</option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                                <p>
                                                    <input type="checkbox" id="select-all-cv-ph-{{ $vanBanDen->id }}" data-idvb="{{ $vanBanDen->id }}" class="check-all-cv1">
                                                    <label for="select-all-cv-ph-{{ $vanBanDen->id }}" class="font-weight-normal">Chọn tất cả cv phối hợp</label>
                                                </p>
                                            {{--@if($vanBanDen->checkQuyenGiaHan)--}}
                                            <p>
                                                <span>Gia hạn xử lý</span>
                                                <input type="date" name="han_xu_ly[{{ $vanBanDen->id }}]"
                                                       value=""
                                                       class="form-control" form="form-tham-muu">
                                            </p>
                                            {{--@endif--}}
                                            <input id="van-ban-quan-trong{{ $vanBanDen->id }}" type="checkbox"
                                                   name="van_ban_quan_trong[{{ $vanBanDen->id }}]" value="1"
                                                   form="form-tham-muu">
                                            <label for="van-ban-quan-trong{{ $vanBanDen->id }}"
                                                   class="color-red font-weight-normal">
                                                VB Quan trọng
                                            </label>
                                            @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id && !empty($vanBanDen->lichCongTacDonVi))
                                                <p>Lãnh đạo dự họp:</p>
                                                @if (auth::user()->hasRole(CHU_TICH))
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                           class="radio-col-cyan chu-tich-du-hop"
                                                           value="{{ $chuTich->id ?? null }}"
                                                           form="form-tham-muu" {{ !empty($vanBanDen->lichCongTacChuTich) ? 'checked' : null  }}>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                    ><i>GD</i></label>
                                                @endif
                                                &nbsp;
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                       class="radio-col-cyan pho-ct-du-hop"
                                                       value="{{ $vanBanDen->phoChuTich->can_bo_nhan_id ?? null }}"
                                                       form="form-tham-muu" {{ !empty($vanBanDen->lichCongTacPhoChuTich) ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                ><i>PGD</i></label>
                                                &nbsp;
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                       class="radio-col-cyan don-vi-du-hop"
                                                       value="{{ !empty($vanBanDen->donViCapXaChuTri->don_vi_du_hop) ? $vanBanDen->donViCapXaChuTri->don_vi_id : null }}"
                                                       form="form-tham-muu" {{ $vanBanDen->donViCapXaChuTri ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phòng dự
                                                        họp</i></label>
                                            @endif
                                            @if ($vanBanDen->type == \Modules\VanBanDen\Entities\VanBanDen::TYPE_VB_DON_VI && !empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                <p>Lãnh đạo dự họp:</p>
                                                @if (auth::user()->hasRole(CHU_TICH))
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                           class="radio-col-cyan chu-tich-du-hop"
                                                           value="{{ $chuTich->id ?? null }}"
                                                           form="form-tham-muu" {{ !empty($vanBanDen->lichCongTacChuTich) ? 'checked' : null  }}>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                    ><i>GD</i></label>
                                                    &nbsp;
                                                @endif
                                                @if ($vanBanDen->lichCongTacPhoChuTich || auth::user()->hasRole(CHU_TICH))
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                           class="radio-col-cyan pho-ct-du-hop"
                                                           value="{{ $vanBanDen->phoChuTich->can_bo_nhan_id ?? null }}"
                                                           form="form-tham-muu" {{ !empty($vanBanDen->lichCongTacPhoChuTich) ? 'checked' : null  }}>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                    ><i>PGD</i></label>
                                                    &nbsp;
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                           class="radio-col-cyan don-vi-du-hop"
                                                           value="{{ !empty($vanBanDen->donViCapXaChuTri->don_vi_du_hop) ? $vanBanDen->donViCapXaChuTri->don_vi_id : null }}"
                                                           form="form-tham-muu" {{ $vanBanDen->lichCongTacDonVi == true ? 'checked' : null  }}>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phòng dự
                                                            họp</i></label>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($vanBanDen->lanhDaoChiDao)
                                            @foreach($vanBanDen->lanhDaoDaChiDao as $data)
                                                <span style="color: red;font-weight: bold">{{$data->lanhDao->chucVu->ten_chuc_vu ?? ''}} {{$data->lanhDao->ho_ten ?? ''}} chỉ đạo: {{$data->y_kien}}</span> <br>
                                            @endforeach
                                        @endif

                                    @if (auth::user()->hasRole(CHU_TICH))
                                            <p>
                                                <textarea
                                                    name="noi_dung_pho_chu_tich[{{ $vanBanDen->id }}]"
                                                    form="form-tham-muu"
                                                    class="form-control {{ !empty($vanBanDen->phoChuTich) ? 'show' : 'hide' }}"
                                                    rows="3">{{ $vanBanDen->phoChuTich->noi_dung ?? '' }}</textarea>
                                            </p>
                                        @endif
                                        <p>
                                        <textarea name="don_vi_chu_tri[{{ $vanBanDen->id }}]"
                                                  class="form-control {{ !empty($vanBanDen->donViCapXaChuTri) ? 'show' : 'hide' }}"
                                                  form="form-tham-muu"
                                                  rows="3">{{ $vanBanDen->donViCapXaChuTri->noi_dung ?? null }}</textarea>
                                        </p>
                                        <p>
                                            <textarea name="don_vi_phoi_hop[{{ $vanBanDen->id }}]"
                                                      class="form-control {{ count($vanBanDen->DonViCapXaPhoiHop) > 0 ? 'show' : 'hide' }}"
                                                      form="form-tham-muu"
                                                      rows="4">@if (!empty($vanBanDen->DonViCapXaPhoiHop))Chuyển đơn vị
                                                phối hợp: @foreach($vanBanDen->DonViCapXaPhoiHop as $donViPhoiHop)
                                                    {{ $donViPhoiHop->donVi->ten_don_vi }} @endforeach
                                                @endif
                                            </textarea>
                                        </p>
                                    </td>
                                    {{--                                    @if (auth::user()->hasRole(PHO_CHU_TICH))--}}
                                    <td class="text-center">
                                        <label style="color: red; font-weight: 500 !important;"
                                               for="checkbox{{ $vanBanDen->id }}"> Chọn duyệt:</label><br>
                                        <input id="checkbox{{ $vanBanDen->id }}" type="checkbox"
                                               name="duyet[{{ $vanBanDen->id }}]" value="{{ $vanBanDen->id }}"
                                               class="duyet sub-check">
                                    </td>
                                    {{--                                    @endif--}}
                                </tr>
                            @empty
                                <td colspan="6" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="clearfix">
                            <div class="row">
                                <div class="col-md-6" style="margin-top: 5px">

                                </div>
                                <div class="col-md-6">
                                    <button type="button"
                                            class="btn  mt-2 btn-sm btn-submit btn-primary waves-effect waves-light pull-right btn-duyet-all disabled pull-right btn-sm mb-2"
                                            form="form-tham-muu"
                                            title=""><i class="fa fa-check"></i> Duyệt
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                {!! $danhSachVanBanDen->render() !!}
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
        let status = '{{ $active }}';
        let vanBanDenDonViId = null;
        let ArrVanBanDenDonViId = [];
        let txtChuTich = null;

        $('.check-all-cv1').on('click', function () {
            let id = $(this).data('idvb');
            console.log(id);
            let chuyenVienPhoiHopId = `#don-vi-phoi-hop-${id}`;
            if($(this).is(':checked') ){
                console.log(1);
                $(this).closest('.dau-viec-chi-tiet').find(chuyenVienPhoiHopId + "> option").prop("selected","selected");
                $(this).closest('.dau-viec-chi-tiet').find(chuyenVienPhoiHopId).trigger("change");

            }else{
                $(this).closest('.dau-viec-chi-tiet').find(chuyenVienPhoiHopId + "> option").prop('selected', '');
                $(this).closest('.dau-viec-chi-tiet').find(chuyenVienPhoiHopId).trigger("change");
            }
        });

        $('.pho-chu-tich').on('change', function () {
            let $this = $(this);
            let id = $this.val();
            let statusTraLai = $(this).data('tra-lai');
            let type = $(this).data('type');

            let textPhoChuTich = $this.find("option:selected").text() + ' chỉ đạo';
            vanBanDenDonViId = $this.data('id');

            let ct = $this.parents('.tr-tham-muu').find('.chu-tich option:selected').text();
            if (ct.length > 0) {
                if (type == 2) {
                    txtChuTich = 'Kính báo cáo chi cục trưởng ' + ct + ' xem xét';
                } else {
                    txtChuTich = 'Kính báo cáo giám đốc ' + ct + ' xem xét';
                }
            }

            if (statusTraLai) {
                $('#form-tham-muu').find('input[name="van_ban_tra_lai"]').val(statusTraLai);
            }

            if (id) {
                $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val(id);
                let txtChiDao = txtChuTich + ', giao PGD ' + textPhoChuTich;
                if (type == 2) {
                    txtChiDao = txtChuTich + ', giao PCCT ' + textPhoChuTich;
                }
                if (status == 2) {
                    if (type == 2) {
                        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển phó chi cục trưởng ' + textPhoChuTich);
                    } else {
                        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển phó giám đốc ' + textPhoChuTich);
                    }

                } else {
                    $this.parents('.tr-tham-muu').find('.noi-dung-chu-tich').text(txtChiDao);
                    if (type == 2) {
                        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển phó chi cục trưởng ' + textPhoChuTich);
                    } else {
                        $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính chuyển phó giám đốc ' + textPhoChuTich);
                    }
                }

                checkVanBanDenId(vanBanDenDonViId);

            } else {
                $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val(' ');
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).text('');
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).addClass('hide');
                removeVanBanDenDonViId(vanBanDenDonViId);
            }

            lanhDaoXemDeBiet($this, 'PCT');
        });


        $('body').on('change', '.don-vi-chu-tri', function () {
            let $this = $(this);
            let arrId = $this.find("option:selected").map(function () {
                return parseInt(this.value);
            }).get();

            let id = $(this).val();

            vanBanDenDonViId = $this.data('id');

            let donViChuTri = $(this).find("option:selected").map(function () {
                return this.text;
            }).get();

            let statusTraLai = $(this).data('tra-lai');
            if (statusTraLai) {
                $('#form-tham-muu').find('input[name="van_ban_tra_lai"]').val(statusTraLai);
            }

            if (donViChuTri.length > 0 && id.length > 0) {
                $this.parents('.tr-tham-muu').find('.don-vi-du-hop').val(id);
                checkVanBanDenId(vanBanDenDonViId);
                $(this).parents('.data-row').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị chủ trì: ' + donViChuTri.toString());
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $(this).parents('.data-row').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).addClass('hide');
            }

            if (arrId) {
                //lấy danh sach cán bộ phối hơp
                $.ajax({
                    url: APP_URL + '/list-don-vi-phoi-hop/' + JSON.stringify(arrId),
                    type: 'GET',
                    beforeSend: function() {
                        // setting a timeout
                        showLoading();
                    },
                })
                    .done(function (response) {
                        hideLoading();
                        var html = '<option value="">chọn đơn vị phối hợp</option>';
                        if (response.success) {

                            let selectAttributes = response.data.map((function (attribute) {
                                return `<option value="${attribute.id}" >${attribute.ten_don_vi}</option>`;
                            }));

                            $this.parents('.dau-viec-chi-tiet').find('.don-vi-phoi-hop').html(selectAttributes);
                            $this.parents('.data-row').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).text(' ').addClass('hide');
                        } else {
                            $this.parents('.dau-viec-chi-tiet').find('.don-vi-phoi-hop').html(html);
                        }
                    })
                    .fail(function (error) {
                        hideLoading();
                        toastr['error'](error.message, 'Thông báo hệ thống');
                    });
            }

        });

        $('body').on('change', '.don-vi-phoi-hop', function () {

            let donViPhoiHop = $(this).find("option:selected").map(function () {
                return this.text;
            }).get();

            vanBanDenDonViId = $(this).data('id');

            let statusTraLai = $(this).data('tra-lai');
            if (statusTraLai) {
                $('#form-tham-muu').find('input[name="van_ban_tra_lai"]').val(statusTraLai);
            }

            if (donViPhoiHop.length > 0) {

                checkVanBanDenId(vanBanDenDonViId);

                $(this).parents('.data-row').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị phối hợp: ' + donViPhoiHop.join(', '));
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $(this).parents('.data-row').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).addClass('hide');
            }


        });


        // check all
        let allId = [];

        $(document).on('change', 'input[name=check_all]', function () {

            if ($(this).is(':checked', true)) {
                $(this).closest('.data-row').find(".sub-check").prop('checked', true);

                $(this).closest('.data-row').find('.sub-check:checked').each(function () {
                    allId.push($(this).val());
                });

                if (allId.length != 0) {
                    $('.btn-duyet-all').removeClass('disabled');
                    $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(allId));
                }
            } else {
                $(this).closest('.data-row').find(".sub-check").prop('checked', false);
                $('.btn-duyet-all').addClass('disabled');
                allId = [];
                $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(allId));
            }

        });


        $('.sub-check').on('click', function () {

            let id = $(this).val();

            if ($(this).is(':checked')) {

                if (allId.indexOf(id) === -1) {
                    allId.push(id);
                }

                $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(allId));
            } else {

                var index = allId.indexOf(id);

                if (index > -1) {
                    allId.splice(index, 1);
                }

                $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(allId));
            }

            if (allId.length != 0) {
                $('.btn-duyet-all').removeClass('disabled');
            } else {
                $('.btn-duyet-all').addClass('disabled');
            }
        });

        function checkVanBanDenId(vanBanDenDonViId) {

            if (ArrVanBanDenDonViId.indexOf(vanBanDenDonViId) === -1) {
                ArrVanBanDenDonViId.push(vanBanDenDonViId);
            }

            $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(ArrVanBanDenDonViId));

            $('.btn-duyet-all').removeClass('disabled');
        }

        function removeVanBanDenDonViId(vanBanDenDonViId) {
            let index = ArrVanBanDenDonViId.indexOf(vanBanDenDonViId);

            if (index > -1) {
                ArrVanBanDenDonViId.splice(index, 1);
            }
            $('#form-tham-muu').find('input[name="van_ban_den_id"]').val(JSON.stringify(ArrVanBanDenDonViId));
        }

        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            let traLai = $(this).data('tra-lai');

            $('#modal-tra-lai').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-tra-lai').find('input[name="type"]').val(traLai);
        });

        $('.btn-submit').on('click', function () {
            let id = $('#form-tham-muu').find('input[name="van_ban_den_id"]').val();
            if (id.length == 0) {
                toastr['warning']('Vui lòng chọn trước khi duyệt', 'Thông báo hệ thống');
            } else {
                $('#form-tham-muu').submit();
            }
        })

        $('.don-vi-du-hop').on('click', function () {
            $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val(1);
        });

        $('.pho-ct-du-hop').on('click', function () {
            $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
        });

        $('.chu-tich-du-hop').on('click', function () {
            $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
        });

    </script>
@endsection
