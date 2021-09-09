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
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        <div class="col-md-12" style="margin-top: 20px">
                            <div class="row">
                                <form action="{{route('van-ban-den-don-vi.index')}}" method="get">
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
                                        <label>Tìm theo nơi gửi</label>
                                        <input type="text" class="form-control" value="{{Request::get('co_quan_ban_hanh')}}"
                                               name="co_quan_ban_hanh"
                                               placeholder="Nhập tên nơi gửi">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Tìm theo ngày</label>
                                        <input type="date" class="form-control" value="{{Request::get('date')}}"
                                               name="date">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Tìm theo số ký hiệu</label>
                                        <input type="text" class="form-control" value="{{Request::get('so_ky_hieu')}}"
                                               name="so_ky_hieu"
                                               placeholder="Nhập số ký hiệu..">
                                    </div>
                                    <div class="col-md-12 text-right">
                                        {{--                                    <label>&nbsp;</label><br>--}}
                                        <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                        @if (!empty(Request::get('trich_yeu')) || !empty(Request::get('so_den')) ||
                                                    !empty(Request::get('date')))
                                            <a href="{{ route('van-ban-den-don-vi.index') }}" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        @include('dieuhanhvanbanden::van-ban-den.fom_tra_lai', ['active' => $trinhTuNhanVanBan])
                        Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="45%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="22%" class="text-center">Ý kiến</th>
                                <th width="22%" class="text-center">Chỉ đạo</th>
                                @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::PHO_PHONG_NHAN_VB || $donVi->cap_xa == \Modules\Admin\Entities\DonVi::CAP_XA)
                                    <th class="text-center" width="7%">
                                        <input id="check-all" type="checkbox" name="check_all" value="">
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        @if($vanBanDen->hasChild)
                                            <p>
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?xuly=true') }}">
                                                    @if($vanBanDen->hasChild->ngay_nhan == date('Y-m-d'))<span style="color: #c000ff;font-weight: bold">{{ $vanBanDen->hasChild->trich_yeu ?? null }}</span> @else <span>{{ $vanBanDen->hasChild->trich_yeu ?? null }}</span> @endif
                                                </a>
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
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?xuly=true') }}">
                                                    @if($vanBanDen->ngay_nhan == date('Y-m-d'))<span style="color: #c000ff;font-weight: bold">{{ $vanBanDen->trich_yeu }}</span> @else <span>{{ $vanBanDen->trich_yeu }}</span> @endif
                                                    </a>
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
                                        @if (!empty($vanBanDen->tom_tat))
                                            <p>
                                                <a data-toggle="collapse" class="color-black" href="#tom-tat-van-ban-{{ $vanBanDen->id }}" role="button" aria-expanded="false" aria-controls="tom-tat-van-ban">
                                                    <i class="fa fa-book"></i> Tóm tăt văn bản
                                                </a>
                                            </p>
                                            <div class="collapse" id="tom-tat-van-ban-{{ $vanBanDen->id }}">
                                                <p>
                                                    {{ $vanBanDen->tom_tat ?? $vanBanDen->trich_yeu }}
                                                </p>
                                            </div>
                                        @endif
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
{{--                                        @if ((!empty($vanBanDen->parent_id) && $vanBanDen->type == \Modules\VanBanDen\Entities\VanBanDen::TYPE_VB_DON_VI) || $vanBanDen->type == \Modules\VanBanDen\Entities\VanBanDen::TYPE_VB_HUYEN)--}}
                                            <p>
                                                <a class="tra-lai-van-ban" data-toggle="modal"
                                                   data-target="#modal-tra-lai"
                                                   data-id="{{ $vanBanDen->id }}">
                                                    <span><i class="fa fa-reply"></i>Trả lại VB</span>
                                                </a>
                                            </p>
{{--                                        @endif--}}
                                        @include('dieuhanhvanbanden::van-ban-den.thong_tin')
                                    </td>

                                    <td>
                                        <div class="dau-viec-chi-tiet" style="width: 95%;">
                                            @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB)
                                                <p>
                                                    <select name="pho_phong_id[{{ $vanBanDen->id }}]"
                                                            id="pho-phong-chu-tri-{{ $vanBanDen->id }}"
                                                            data-id="{{ $vanBanDen->id }}"
                                                            class="form-control select2 pho-phong"
                                                            placeholder="Chọn phó phòng chủ trì"
                                                            data-id="{{ $vanBanDen->id }}"
                                                            data-tra-lai="{{ $vanBanDen->vanBanTraLai ? 1 : null }}"
                                                            form="form-tham-muu">
                                                        <option value="">Chọn phó phòng chủ trì</option>
                                                        @forelse($danhSachPhoPhong as $phoPhong)
                                                            <option
                                                                value="{{ $phoPhong->id }}" {{ !empty($vanBanDen->phoPhong) && $vanBanDen->phoPhong->can_bo_nhan_id == $phoPhong->id ? 'selected' : null }}>{{ $phoPhong->ho_ten }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </p>
                                            @endif
                                            @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB || $trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::PHO_PHONG_NHAN_VB)
                                                <p>
                                                    <select name="chuyen_vien_id[{{ $vanBanDen->id }}]"
                                                            id="chuyen-vien-{{ $vanBanDen->id }}"
                                                            class="form-control select2 chuyen-vien"
                                                            data-id="{{ $vanBanDen->id }}"
                                                            form="form-tham-muu">
                                                        <option value="">Chọn chuyên viên thực hiện</option>
                                                        @forelse($danhSachChuyenVien as $chuyenVien)
                                                            <option
                                                                value="{{ $chuyenVien->id }}" {{ !empty($vanBanDen->chuyenVien) && $vanBanDen->chuyenVien->can_bo_nhan_id == $chuyenVien->id ? 'selected' : null }}>{{ $chuyenVien->ho_ten }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </p>
                                            @endif
                                            <p>
                                                <select
                                                    name="chuyen_vien_phoi_hop_id[{{ $vanBanDen->id }}][]"
                                                    id="chuyen-vien-phoi-hop{{ $vanBanDen->id }}"
                                                    class="form-control chuyen-vien-phoi-hop select2"
                                                    data-id="{{ $vanBanDen->id }}"
                                                    data-placeholder="Chọn chuyên viên phối hợp"
                                                    form="form-tham-muu" multiple="multiple">
                                                    @forelse($danhSachChuyenVien as $chuyenVien)
                                                        @if (!empty($vanBanDen->chuyenVien) && $chuyenVien->id != $vanBanDen->chuyenVien->can_bo_nhan_id)
                                                            <option
                                                                value="{{ $chuyenVien->id }}" {{ !empty($vanBanDen->getChuyenVienPhoiHop) && in_array($chuyenVien->id, $vanBanDen->getChuyenVienPhoiHop) ? 'selected' : '' }}>{{ $chuyenVien->ho_ten }}</option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </select>
                                                <input type="checkbox" id="select-all-cv-ph-{{ $vanBanDen->id }}" data-id="{{ $vanBanDen->id }}" class="check-all-cv">
                                                <label for="select-all-cv-ph-{{ $vanBanDen->id }}" class="font-weight-normal">Chọn tất cả cv phối hợp</label>
                                            </p>
                                            <p>
                                                <select
                                                    name="lanh_dao_xem_de_biet[{{ $vanBanDen->id }}][]"
                                                    class="form-control select2 lanh-dao-xem-de-biet select2-hidden-accessible"
                                                    multiple="multiple"
                                                    form="form-tham-muu"
                                                    data-placeholder="Chọn phó phòng xem để biết">
                                                    @forelse($danhSachPhoPhong as $phoPhongPhoiHop)
                                                        <option
                                                            value="{{ $phoPhongPhoiHop->id }}" {{ in_array($phoPhongPhoiHop->id, $vanBanDen->lanhDaoXemDeBiet->pluck('lanh_dao_id')->toArray()) ? 'selected' : null }}>{{ $phoPhongPhoiHop->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB || $trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::PHO_PHONG_NHAN_VB)
                                                <span>Gia hạn xử lý</span>
                                                <div class="input-group date">
                                                    <input type="text" name="han_xu_ly[{{ $vanBanDen->id }}]"
                                                           value=""
                                                           class="form-control datepicker"
                                                           form="form-tham-muu" placeholder="dd/mm/yyyy">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </div>
                                                </div>
                                                <p>
                                                    <input
                                                        id="van-ban-can-tra-loi-{{ $vanBanDen->id }}"
                                                        type="checkbox"
                                                        name="van_ban_tra_loi[{{ $vanBanDen->id }}]"
                                                        value="1"
                                                        data-id="{{ $vanBanDen->id }}"
                                                        data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"
                                                        class="check-van-ban-can-tra-loi"
                                                        form="form-tham-muu" {{ $vanBanDen->van_ban_can_tra_loi == 1 ? 'checked' : null }}>
                                                    <label
                                                        for="van-ban-can-tra-loi-{{ $vanBanDen->id }}">
                                                        VB cần trả lời
                                                    </label>
                                                    <small><i>(có văn bản đi)</i></small>
                                                </p>
                                            @endif
                                            @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id && !empty($vanBanDen->lichCongTacDonVi))
                                                <p>Lãnh đạo dự họp:</p>
                                                @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB)
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                           class="radio-col-cyan tp-du-hop"
                                                           value="{{ $vanBanDen->lichCongTacDonVi->lanh_dao_id == auth::user()->id ? $vanBanDen->lichCongTacDonVi->lanh_dao_id : auth::user()->id }}"
                                                           form="form-tham-muu" {{ $vanBanDen->lichCongTacDonVi->lanh_dao_id == auth::user()->id ? 'checked' : null  }}>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                    ><i>Trưởng phòng dự họp</i></label><br>
                                                @endif
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                       class="radio-col-cyan pho-phong-du-hop"
                                                       value="{{ in_array($vanBanDen->lichCongTacDonVi->lanh_dao_id, $danhSachPhoPhong->pluck('id')->toArray()) ? $vanBanDen->lichCongTacDonVi->lanh_dao_id : null }}"
                                                       form="form-tham-muu" {{ in_array($vanBanDen->lichCongTacDonVi->lanh_dao_id, $danhSachPhoPhong->pluck('id')->toArray()) ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phó phòng dự
                                                        họp</i></label>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB)
                                            <p>
                                                {{ !empty($vanBanDen->truongPhong) ? $vanBanDen->truongPhong->noi_dung : null }}
                                            </p>
                                        @endif

                                        @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB)
                                            <p>

                                                    <textarea name="noi_dung_pho_phong[{{ $vanBanDen->id }}]"
                                                              form="form-tham-muu"
                                                              class="form-control {{ !empty($vanBanDen->phoPhong) ? 'show' : 'hide' }}"
                                                              rows="3">{{ $vanBanDen->phoPhong->noi_dung ?? null  }}</textarea>
                                            </p>
                                        @endif

                                        <p>
                                                <textarea
                                                    name="noi_dung_chuyen_vien[{{ $vanBanDen->id }}]"
                                                    form="form-tham-muu"
                                                    class="form-control noi-dung-chuyen-vien {{ !empty($vanBanDen->chuyenVien) ? 'show' : 'hide' }}"
                                                    rows="4">{{ !empty($vanBanDen->chuyenVien) ? $vanBanDen->chuyenVien->noi_dung : null }}</textarea>
                                        </p>
                                    </td>
                                    @if ($trinhTuNhanVanBan == \Modules\VanBanDen\Entities\VanBanDen::PHO_PHONG_NHAN_VB || $donVi->cap_xa == \Modules\Admin\Entities\DonVi::CAP_XA)
                                        <td class="text-center">
                                            <p>
                                                <span style="color: red;"> Chọn duyệt:</span><br>
                                                <input id="checkbox{{ $vanBanDen->id }}"
                                                       type="checkbox"
                                                       name="duyet[{{ $vanBanDen->id }}]"
                                                       value="{{ $vanBanDen->id }}"
                                                       class="duyet sub-check">
                                                <label for="checkbox{{ $vanBanDen->id }}"></label>
                                            </p>
                                        </td>
                                    @endif
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
        let status = '{{ $trinhTuNhanVanBan }}';
        let vanBanDenDonViId = null;
        let ArrVanBanDenDonViId = [];
        let txtChuyenVien = null;

        $('.pho-phong').on('change', function () {
            let $this = $(this);
            let id = $this.val();
            let traLai = $(this).data('tra-lai');

            vanBanDenDonViId = $this.data('id');
            $this.parents('.tr-tham-muu').find('.pho-phong-du-hop').val(id);
            let textPhoPhong = $this.find("option:selected").text() + ' chỉ đạo';

            if (id) {
                //truong phong nhan van ban
                if (status == 6) {
                    checkVanBanDenId(vanBanDenDonViId);
                }
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển phó phòng ' + textPhoPhong);
            } else {
                if (traLai != null) {
                    checkVanBanDenId(vanBanDenDonViId);
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).addClass('hide');
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).text('');
                } else {
                    removeVanBanDenDonViId(vanBanDenDonViId);
                    removeVanBanDenDonViId(vanBanDenDonViId);
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).addClass('hide');
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).text('');
                }
            }
        });

        $('.chuyen-vien').on('change', function () {
            let $this = $(this);
            let id = $this.val();

            let arrId = $this.find("option:selected").map(function () {
                return parseInt(this.value);
            }).get();

            let textChuyenVien = $this.find("option:selected").text() + ' giải quyết';

            vanBanDenDonViId = $this.data('id');

            if (id) {
                if (status == 6) {
                    checkVanBanDenId(vanBanDenDonViId);
                }

                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chuyen_vien[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển chuyên viên ' + textChuyenVien);
                txtChuyenVien = 'Chuyển chuyên viên ' + textChuyenVien;
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chuyen_vien[${vanBanDenDonViId}]"]`).text('');
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chuyen_vien[${vanBanDenDonViId}]"]`).addClass('hide');
            }

            if (arrId) {
                //lấy danh sach cán bộ phối hơp
                $.ajax({
                    url: APP_URL + '/list-can-bo-phoi-hop/' + JSON.stringify(arrId),
                    type: 'GET',
                    beforeSend: function() {
                        // setting a timeout
                        showLoading();
                    },
                })
                    .done(function (response) {
                        var html = '<option value="">chọn chuyên viên phối hợp</option>';
                        if (response.success) {

                            let selectAttributes = response.data.map((function (attribute) {
                                return `<option value="${attribute.id}" >${attribute.ho_ten}</option>`;
                            }));

                            $this.parents('.dau-viec-chi-tiet').find('.chuyen-vien-phoi-hop').html(selectAttributes);
                        } else {
                            $this.parents('.dau-viec-chi-tiet').find('.chuyen-vien-phoi-hop').html(html);
                        }
                        hideLoading();
                    })
                    .fail(function (error) {
                        hideLoading();
                        toastr['error'](error.message, 'Thông báo hệ thống');
                    });
            }
        });

        $('.chuyen-vien-phoi-hop').on('change', function () {

            let ChuyenVien = $(this).parents('.tr-tham-muu').find('.noi-dung-chuyen-vien').text();

            if (txtChuyenVien == null || txtChuyenVien.length == 0) {

                txtChuyenVien = ChuyenVien;
            }

            let arrId = $(this).find("option:selected").map(function () {
                return parseInt(this.value);
            }).get();

            let textChuyenVienPhoiHop = $(this).find("option:selected").map(function () {
                return this.text;
            }).get();

            if (arrId.length > 0) {
                let txtChiDao = txtChuyenVien + ', ' + textChuyenVienPhoiHop.join(', ') + ' phối hợp.';

                $(this).parents('.tr-tham-muu').find('.noi-dung-chuyen-vien').text(' ');
                $(this).parents('.tr-tham-muu').find('.noi-dung-chuyen-vien').text(txtChiDao);
            } else {
                $(this).parents('.tr-tham-muu').find('.noi-dung-chuyen-vien').text(txtChuyenVien);
            }


        })

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

    </script>
@endsection
