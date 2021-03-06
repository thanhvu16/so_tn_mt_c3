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
                                <h4 class="header-title pt-2">Văn
                                    bản {{ empty(Request::get('chuyen_tiep')) ? 'chờ' : 'đang' }} xử lý</h4>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('van-ban-den-phoi-hop.store') }}" method="post"
                                      id="form-tham-muu">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_id" value="">
                                    <input type="hidden" name="van_ban_tra_lai" value="">
                                    @if (empty(Request::get('chuyen_tiep')))
                                        <button type="button"
                                                class="btn btn-sm mt-1 btn-submit btn-primary waves-effect waves-light pull-right btn-duyet-all disabled pull-right btn-sm mb-2"
                                                data-original-title=""
                                                title=""><i class="fa fa-check"></i> Duyệt
                                        </button>
                                    @endif
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
                                    <form action="@if(Request::get('type') == 1){{ empty(Request::get('chuyen_tiep')) ? route('giay-moi-den-phoi-hop.index') : route('giay-moi-den-phoi-hop.dang-xu-ly') }}@else {{ empty(Request::get('chuyen_tiep')) ? route('van-ban-den-phoi-hop.index') : route('van-ban-den-phoi-hop.dang-xu-ly') }} @endif" id="formsb">
                                        <b>Sắp xếp:</b>

                                        <select class="" name="sap_xep" form="formsb"   onchange="this.form.submit();">
                                            <option value="" {{ Request::get('sap_xep') == '' ? 'selected' : '' }}>-- Mặc định --</option>
                                            <option value="1" {{ Request::get('sap_xep') == 1 ? 'selected' : '' }}>-- Sắp xếp A-Z --</option>
                                            <option value="2" {{ Request::get('sap_xep') == 2 ? 'selected' : '' }}>-- Sắp xếp Z-A --</option>
                                        </select>
                                        <input type="hidden" name="chuyen_tiep" value="{{Request::get('chuyen_tiep')}}">
                                        <input type="hidden" name="type" form="formsb"  value="{{Request::get('type')}}">
                                    </form>

                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="45%" class="text-center">Trích yếu - Thông tin</th>
                                <th class="text-center" width="22%">Ý kiến</th>
                                <th width="23%" class="text-center">Chỉ đạo</th>
                                @hasanyrole ('phó trưởng phòng|phó tp đơn vị cấp 2|phó chánh văn phòng')
                                @if (empty(Request::get('chuyen_tiep')))
                                    <th class="text-center" width="7%">
                                        <input id="check-all" type="checkbox" name="check_all" value="">
                                    </th>
                                @endif
                                @endrole
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        @if($vanBanDen->hasChild)
                                            <p>
                                                <a href="{{ !empty(Request::get('chuyen_tiep')) ? route('van_ban_den_chi_tiet.show', $vanBanDen->id. '?status=1') : route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=phoi_hop&status=1') }}">{{ $vanBanDen->hasChild->trich_yeu }}</a>
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
                                                <a href="{{ !empty(Request::get('chuyen_tiep')) ? route('van_ban_den_chi_tiet.show', $vanBanDen->id) : route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=phoi_hop') }}">{{ $vanBanDen->trich_yeu }}</a>
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
                                        @include('dieuhanhvanbanden::van-ban-den.thong_tin')
                                    </td>
                                    <td>
                                        <div class="dau-viec-chi-tiet">
                                            @hasanyrole('trưởng phòng|tp đơn vị cấp 2|chánh văn phòng')
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
                                            @endrole
                                            @hasanyrole('trưởng phòng|phó trưởng phòng|tp đơn vị cấp 2|phó tp đơn vị cấp 2|chánh văn phòng|phó chánh văn phòng')
                                            <p>
                                                <select name="chuyen_vien_id[{{ $vanBanDen->id }}]"
                                                        id="chuyen-vien-{{ $vanBanDen->id }}"
                                                        class="form-control select2 chuyen-vien"
                                                        data-id="{{ $vanBanDen->id }}"
                                                        data-placeholder="Chọn chuyên viên thực hiện"
                                                        form="form-tham-muu">
                                                    <option value="">Chọn chuyên viên thực hiện</option>
                                                    @forelse($danhSachChuyenVien as $chuyenVien)
                                                        <option
                                                            value="{{ $chuyenVien->id }}" {{ !empty($vanBanDen->chuyenVien) && $vanBanDen->chuyenVien->can_bo_nhan_id == $chuyenVien->id ? 'selected' : null }}>{{ $chuyenVien->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
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
                                            </p>
                                            @endrole

                                            @if (auth::user()->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG]) && auth::user()->donVi->parent_id == 0 )
                                                <p>
                                                    <select
                                                        name="chuyen_vien_du_hop[{{ $vanBanDen->id }}][]"
                                                        id="chuyen-vien-du-hop{{ $vanBanDen->id }}"
                                                        class="form-control chuyen-vien-du-hop  select2"
                                                        data-id="{{ $vanBanDen->id }}"
                                                        data-placeholder="Thêm chuyên viên dự họp"
                                                        form="form-tham-muu" multiple="multiple">
                                                        @forelse($danhSachChuyenVien as $chuyenVien)
                                                            <option
                                                                value="{{ $chuyenVien->id }}" {{ !empty($vanBanDen->layNguoiDuHop($vanBanDen->id)) && in_array($chuyenVien->id, $vanBanDen->layNguoiDuHop($vanBanDen->id)) ? 'selected' : '' }}>{{ $chuyenVien->ho_ten }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </p>
                                                <p>Lãnh đạo dự họp:</p>

                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                       class="radio-col-cyan tp-du-hop"
                                                       value="1"
                                                       form="form-tham-muu" {{ $vanBanDen->checkLichCongTacDonViPhong($vanBanDen->id) == 1 || $vanBanDen->checkLichCongTacDonViPhong($vanBanDen->id) == 0 ? 'checked' : null  }}>
                                                <label for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"><i>Trưởng phòng dự họp</i></label><br>
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                       class="radio-col-cyan "
                                                       value="2"
                                                       form="form-tham-muu" {{ $vanBanDen->checkLichCongTacDonViPhong($vanBanDen->id) == 2 ? 'checked' : null  }}>
                                                <label for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phó phòng dự họp</i></label><br>

                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.4' }}"
                                                       class="radio-col-cyan "
                                                       value="3"
                                                       form="form-tham-muu" {{ $vanBanDen->checkLichCongTacDonViPhong($vanBanDen->id) == 3 ? 'checked' : null  }}>
                                                <label for="lanh-dao-du-hop-{{ $vanBanDen->id .'.4' }}"><i>Chuyên viên dự họp</i></label>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{--                                        @role ('trưởng phòng|tp đơn vị cấp 2')--}}
                                        {{--                                        <p>--}}
                                        {{--                                            {{ !empty($vanBanDen->truongPhong) ? $vanBanDen->truongPhong->noi_dung : null }}--}}
                                        {{--                                        </p>--}}
                                        {{--                                        @endrole--}}

                                        @role('trưởng phòng|tp đơn vị cấp 2|chánh văn phòng')
                                        <p>
                                            <textarea name="noi_dung_pho_phong[{{ $vanBanDen->id }}]"
                                                      form="form-tham-muu"
                                                      class="form-control {{ !empty($vanBanDen->phoPhong)  ? 'show' : 'hide' }}"
                                                      rows="3">{{ $vanBanDen->phoPhong->noi_dung ?? null  }}</textarea>
                                        </p>
                                        @endrole

                                        <p>
                                            <textarea
                                                name="noi_dung_chuyen_vien[{{ $vanBanDen->id }}]"
                                                form="form-tham-muu"
                                                class="form-control noi-dung-chuyen-vien {{ !empty($vanBanDen->chuyenVien) ? 'show' : 'hide' }}"
                                                rows="3">{{ !empty($vanBanDen->chuyenVien) ? $vanBanDen->chuyenVien->noi_dung : null }}</textarea>
                                        </p>
                                    </td>

                                    @role ('phó trưởng phòng|phó tp đơn vị cấp 2|phó chánh văn phòng')
                                    @if (empty(Request::get('chuyen_tiep')))
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
                                    @endrole
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
                                @if (empty(Request::get('chuyen_tiep')))
                                    <div class="col-md-6">
                                        <button type="button"
                                                class="btn  mt-2 btn-sm btn-submit btn-primary waves-effect waves-light pull-right btn-duyet-all disabled pull-right btn-sm mb-2"
                                                form="form-tham-muu"
                                                title=""><i class="fa fa-check"></i> Duyệt
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                {{ $danhSachVanBanDen->appends(['so_den'  => Request::get('so_den'), 'chuyen_tiep'  => Request::get('chuyen_tiep'), 'han_xu_ly'  => Request::get('han_xu_ly'),'type'  => Request::get('type'),
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
        let status = '{{ $trinhTuNhanVanBan }}';
        let vanBanDenDonViId = null;
        let ArrVanBanDenDonViId = [];
        let txtChuyenVien = null;

        $('.pho-phong').on('change', function () {
            let $this = $(this);
            let id = $this.val();
            let traLai = $(this).data('tra-lai');

            vanBanDenDonViId = $this.data('id');

            let textPhoPhong = $this.find("option:selected").text() + ' chỉ đạo';

            if (id) {
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
                })
                    .done(function (response) {
                        var html = '<option value="">chọn chuyên viên phối hợp</option>';
                        if (response.success) {

                            let selectAttributes = response.data.map((function (attribute) {
                                return `<option value="${attribute.id}" >${attribute.ho_ten}</option>`;
                            }));

                            $this.parents('.dau-viec-chi-tiet').find('.chuyen-vien-phoi-hop').html(selectAttributes);
                            $this.parents('.dau-viec-chi-tiet').find('.chuyen-vien-du-hop').html(selectAttributes);
                        } else {
                            $this.parents('.dau-viec-chi-tiet').find('.chuyen-vien-phoi-hop').html(html);
                            $this.parents('.dau-viec-chi-tiet').find('.chuyen-vien-du-hop').html(html);
                        }
                    })
                    .fail(function (error) {
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
