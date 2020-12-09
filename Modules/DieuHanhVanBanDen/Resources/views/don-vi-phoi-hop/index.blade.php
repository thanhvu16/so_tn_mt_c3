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
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="27%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="25%" class="text-center">Tóm tắt VB</th>
                                <th class="text-center">Ý kiến</th>
                                <th width="20%" class="text-center">Chỉ đạo</th>
                                @role ('phó phòng')
                                @if (empty(Request::get('chuyen_tiep')))
                                    <th class="text-center" width="7%">
                                        <input id="check-all" type="checkbox" name="check_all" value="">
                                    </th>
                                @endif
                                @endrole
                            </tr>
                            </thead>
                            <tbody class="text-justify">
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        @if($vanBanDen->hasChild())
                                            <p>
                                                <a href="{{ !empty(Request::get('chuyen_tiep')) ? route('van_ban_den_chi_tiet.show', $vanBanDen->id) : route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=phoi_hop') }}">{{ $vanBanDen->hasChild()->trich_yeu }}</a>
                                                <br>
                                                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->hasChild()->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                    <i>
                                                        (Vào hồi {{ $vanBanDen->hasChild()->gio_hop }}
                                                        ngày {{ date('d/m/Y', strtotime($vanBanDen->hasChild()->ngay_hop)) }}
                                                        , tại {{ $vanBanDen->hasChild()->dia_diem }})
                                                    </i>
                                                @endif
                                            </p>
                                        @else
                                            <p>
                                                <a href="{{ !empty(Request::get('chuyen_tiep')) ? route('van_ban_den_chi_tiet.show', $vanBanDen->id) : route('van_ban_den_chi_tiet.show', $vanBanDen->id.'?type=phoi_hop') }}">{{ $vanBanDen->trich_yeu }}</a>
                                                <br>
                                                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                    <i>
                                                        (Vào hồi {{ $vanBanDen->gio_hop }}
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
                                    </td>
                                    <td>
                                        <div class="dau-viec-chi-tiet">
                                            @role ('trưởng phòng')
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
                                                            value="{{ $phoPhong->id }}" {{ !empty($vanBanDen->donViPhoiHopVanBan($phoPhong->id)) ? 'selected' : null }}>{{ $phoPhong->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            @endrole
                                            @hasanyrole('trưởng phòng|phó phòng')
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
                                                            value="{{ $chuyenVien->id }}" {{ !empty($vanBanDen->donViPhoiHopVanBan($chuyenVien->id)) ? 'selected' : null }}>{{ $chuyenVien->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            @endrole
                                        </div>
                                    </td>
                                    <td>
                                        @role ('trưởng phòng')
                                        <p>
                                            {{ !empty($vanBanDen->donViPhoiHopVanBan(Auth::user()->id, auth::user()->donvi_id)) ? $vanBanDen->donViPhoiHopVanBan(auth::user()->id, auth::user()->donvi_id)->noi_dung : null }}
                                        </p>
                                        @endrole

                                        @role('trưởng phòng')
                                        <p>
                                            <textarea name="noi_dung_pho_phong[{{ $vanBanDen->id }}]"
                                                      form="form-tham-muu"
                                                      class="form-control {{ !empty($vanBanDen->checkChuyenVienPhoiHopThucHien($danhSachPhoPhong->pluck('id')->toArray(), null))  ? 'show' : 'hide' }}"
                                                      rows="3">{{ $vanBanDen->checkChuyenVienPhoiHopThucHien($danhSachPhoPhong->pluck('id')->toArray(), null)->noi_dung ?? null  }}</textarea>
                                        </p>
                                        @endrole

                                        <p>
                                            <textarea
                                                name="noi_dung_chuyen_vien[{{ $vanBanDen->id }}]"
                                                form="form-tham-muu"
                                                class="form-control noi-dung-chuyen-vien {{ !empty($vanBanDen->checkChuyenVienPhoiHopThucHien($danhSachChuyenVien->pluck('id')->toArray(), Auth::user()->donvi_id)) ? 'show' : 'hide' }}"
                                                rows="3">{{ !empty($vanBanDen->checkChuyenVienPhoiHopThucHien($danhSachChuyenVien->pluck('id')->toArray(), Auth::user()->donvi_id)) ? $vanBanDen->checkChuyenVienPhoiHopThucHien($danhSachChuyenVien->pluck('id')->toArray(), auth::user()->donvi_id)->noi_dung : null }}</textarea>
                                        </p>
                                    </td>
                                    @role ('phó phòng')
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
                                    Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
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
                            <div class="col-md-6 text-right">
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

            let textPhoPhong = $this.find("option:selected").text() + ' chỉ đạo';

            if (id) {
                if (status == 3) {
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
                if (status == 3) {
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
                        } else {
                            $this.parents('.dau-viec-chi-tiet').find('.chuyen-vien-phoi-hop').html(html);
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
