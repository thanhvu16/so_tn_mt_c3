@extends('administrator::layouts.master')
@section('page_title', 'Công việc đơn vị đang xử lý')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title pt-2">Công việc đang xử lý</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{{ route('cong-viec-don-vi.store') }}" method="post"
                                              id="form-tham-muu">
                                            @csrf
                                            <input type="hidden" name="van_ban_den_don_vi_id" value="">
                                            <input type="hidden" name="don_vi_phoi_hop"
                                                   value="{{ $typeDonViPhoiHop ?? null }}">
                                        </form>
                                    </div>
                                </div>
                                <!--datatable-->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr role="row" class="text-center">
                                            <th width="2%">STT</th>
                                            <th width="30%">Nội dung công việc</th>
                                            <th width="20%">ý kiến</th>
                                            <th width="22%">Chỉ đạo</th>
                                            <th width="21%">Trình tự xử lý</th>
                                            <th width="9%">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($chuyenNhanCongViecDonVi as $congViecDonVi)
                                            <tr class="tr-tham-muu">
                                                <td class="text-center">{{ $order++ }}</td>
                                                <td>
                                                    <p>
                                                        <a href="{{ route('cong-viec-don-vi.show', $congViecDonVi->id) }}">{{ $congViecDonVi->congViecDonVi->noi_dung_cuoc_hop }}</a>
                                                    </p>
                                                    <p>
                                                        <b>Nội dung chỉ đạo đơn vị:</b> {{ $congViecDonVi->noi_dung }}
                                                    </p>
                                                    @if (!empty($congViecDonVi->han_xu_ly))
                                                        <p>
                                                            - <b>Hạn xử
                                                                lý:
                                                                {{ date('d/m/Y', strtotime($congViecDonVi->han_xu_ly)) }}
                                                            </b>
                                                        </p>
                                                    @endif
                                                    @if (isset($congViecDonVi->congViecDonVi->congViecDonViFile))
                                                        @foreach($congViecDonVi->congViecDonVi->congViecDonViFile as $key => $file)
                                                            <a href="{{ $file->getUrlFile() }}"
                                                               target="popup"
                                                               class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                                ]</a>
                                                            @if (count($congViecDonVi->congViecDonVi->congViecDonViFile)-1 != $key)
                                                                &nbsp;|&nbsp;
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>

                                                    <div class="dau-viec-chi-tiet">
                                                        @if (Auth::user()->vai_tro == CAP_TRUONG)
                                                            <p>
                                                                <select name="pho_phong_id[{{ $congViecDonVi->id }}]"
                                                                        id="pho-phong-chu-tri-{{ $congViecDonVi->id }}"
                                                                        data-id="{{ $congViecDonVi->id }}"
                                                                        class="form-control select2-search pho-phong"
                                                                        placeholder="Chọn phó phòng chủ trì"
                                                                        data-id="{{ $congViecDonVi->id }}"
                                                                        form="form-tham-muu">
                                                                    <option value="">Chọn phó phòng chủ trì</option>
                                                                    @forelse($danhSachPhoPhong as $phoPhong)
                                                                        <option
                                                                            value="{{ $phoPhong->id }}" {{ !empty($congViecDonVi->checkCanBoNhan([$phoPhong->id])) && $congViecDonVi->checkCanBoNhan([$phoPhong->id])->can_bo_nhan_id == $phoPhong->id ? 'selected' : null }}>{{ $phoPhong->ho_ten }}</option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>
                                                            </p>
                                                        @endif
                                                        <p>
                                                            <select name="chuyen_vien_id[{{ $congViecDonVi->id }}]"
                                                                    id="chuyen-vien-{{ $congViecDonVi->id }}"
                                                                    class="form-control select2-search chuyen-vien"
                                                                    data-id="{{ $congViecDonVi->id }}"
                                                                    data-placeholder="Chọn chuyên viên thực hiện"
                                                                    form="form-tham-muu">
                                                                <option value="">Chọn chuyên viên thực hiện</option>
                                                                @forelse($danhSachChuyenVien as $chuyenVien)
                                                                    <option
                                                                        value="{{ $chuyenVien->id }}" {{ !empty($congViecDonVi->checkCanBoNhan([$chuyenVien->id])) && $congViecDonVi->checkCanBoNhan([$chuyenVien->id])->can_bo_nhan_id == $chuyenVien->id ? 'selected' : null }}>{{ $chuyenVien->ho_ten }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </p>
                                                        @if (!isset($typeDonViPhoiHop))
                                                            <p>
                                                                <select
                                                                    name="chuyen_vien_phoi_hop_id[{{ $congViecDonVi->id }}][]"
                                                                    id="chuyen-vien-phoi-hop{{ $congViecDonVi->id }}"
                                                                    class="form-control chuyen-vien-phoi-hop select2-search"
                                                                    data-id="{{ $congViecDonVi->id }}"
                                                                    data-placeholder="Chọn chuyên viên phối hợp"
                                                                    form="form-tham-muu" multiple>
                                                                    @forelse($danhSachChuyenVien as $chuyenVien)
                                                                        <option
                                                                            value="{{ $chuyenVien->id }}" {{ in_array($chuyenVien->id, $congViecDonVi->checkChuyenVienPhoiHop()->pluck('can_bo_nhan_id')->toArray()) ? 'selected' : '' }}>{{ $chuyenVien->ho_ten }}</option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>
                                                            </p>
                                                            <p>
                                                                <select
                                                                    name="lanh_dao_xem_de_biet[{{ $congViecDonVi->id }}][]"
                                                                    class="form-control select2-search lanh-dao-xem-de-biet"
                                                                    multiple
                                                                    form="form-tham-muu"
                                                                    data-placeholder="Chọn phó phòng xem để biết"
                                                                    style="width: 95% !important;">
                                                                    @forelse($danhSachPhoPhong as $phoPhongPhoiHop)
                                                                        <option
                                                                            value="{{ $phoPhongPhoiHop->id }}" {{ in_array($phoPhongPhoiHop->id, $congViecDonVi->checklanhdaoXemDeBiet()->pluck('can_bo_nhan_id')->toArray()) ? 'selected' : '' }}>{{ $phoPhongPhoiHop->ho_ten }}</option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>
                                                            </p>
                                                        @endif
                                                    </div>

                                                </td>
                                                <td>

                                                    @if (Auth::user()->vai_tro == CAP_TRUONG)
                                                        <p>
                                                            <textarea
                                                                name="noi_dung_pho_phong[{{ $congViecDonVi->id }}]"
                                                                form="form-tham-muu"
                                                                class="form-control {{ !empty($congViecDonVi->checkCanBoNhan($danhSachPhoPhong->pluck('id')->toArray())) ? 'show' : 'hide' }}"
                                                                rows="3">{{ !empty($congViecDonVi->checkCanBoNhan($danhSachPhoPhong->pluck('id')->toArray())) ? $congViecDonVi->checkCanBoNhan($danhSachPhoPhong->pluck('id')->toArray())->noi_dung_chuyen : '' }}</textarea>
                                                        </p>
                                                    @endif

                                                    <p>

                                                        <textarea
                                                            name="noi_dung_chuyen_vien[{{ $congViecDonVi->id }}]"
                                                            form="form-tham-muu"
                                                            class="form-control noi-dung-chuyen-vien {{ !empty($congViecDonVi->checkCanBoNhan($danhSachChuyenVien->pluck('id')->toArray())) ? 'show' : 'hide' }}"
                                                            rows="5">{{ !empty($congViecDonVi->checkCanBoNhan($danhSachChuyenVien->pluck('id')->toArray())) ? $congViecDonVi->checkCanBoNhan($danhSachChuyenVien->pluck('id')->toArray())->noi_dung_chuyen : '' }}</textarea>

                                                    </p>
                                                </td>
                                                <td>
                                                    @if (!empty($congViecDonVi->getTrinhTuXuLy()))
                                                        @foreach($congViecDonVi->getTrinhTuXuLy() as $key => $trinhTuXuLy)
                                                            <p>
                                                                {{ $key+1 }}
                                                                . {{ $trinhTuXuLy->canBoNhan->ho_ten ?? null }}
                                                            </p>
                                                            <hr class="border-dashed {{  count($congViecDonVi->getTrinhTuXuLy())-1 == $key ? 'hide' : 'show' }}">
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (!empty($congViecDonVi->checkUpdateChuyenNhanCongViec()))
                                                        <button
                                                            class="btn waves-effect btn-primary btn-sm btn-update"
                                                            data-id="{{ $congViecDonVi->id }}" title="Cập nhật">Cập nhật
                                                        </button>
                                                    @endif
                                                </td>

                                            </tr>
                                        @empty
                                            <td colspan="6"
                                                class="text-center">Không tìm
                                                thấy dữ liệu.
                                            </td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-md-6 col-12">
                                            Tổng số công việc: <b>{{ $chuyenNhanCongViecDonVi->total() }}</b>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            {{ $chuyenNhanCongViecDonVi->appends(['ngay_tao'  => Request::get('ngay_tao'), 'type' => Request::get('type')])->render() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        let vanBanDenDonViId = null;
        let ArrVanBanDenDonViId = [];
        let txtChuyenVien = null;

        $('.pho-phong').on('change', function () {
            let $this = $(this);
            let id = $this.val();
            vanBanDenDonViId = $this.data('id');

            let textPhoPhong = $this.find("option:selected").text() + ' chỉ đạo';

            if (id) {
                checkVanBanDenId(vanBanDenDonViId);
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển phó phòng ' + textPhoPhong);
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).addClass('hide');
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_phong[${vanBanDenDonViId}]"]`).text('');
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
                checkVanBanDenId(vanBanDenDonViId);
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
                    url: '../list-can-bo-phoi-hop/' + JSON.stringify(arrId),
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

            if (txtChuyenVien.length == 0) {

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
                    $('#form-tham-muu').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(allId));
                }
            } else {
                $(this).closest('.data-row').find(".sub-check").prop('checked', false);
                $('.btn-duyet-all').addClass('disabled');
                allId = [];
                $('#form-tham-muu').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(allId));
            }

        });


        $('.sub-check').on('click', function () {

            let id = $(this).val();

            if ($(this).is(':checked')) {

                if (allId.indexOf(id) === -1) {
                    allId.push(id);
                }

                $('#form-tham-muu').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(allId));
            } else {

                var index = allId.indexOf(id);

                if (index > -1) {
                    allId.splice(index, 1);
                }

                $('#form-tham-muu').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(allId));
            }

            if (allId.length != 0) {
                $('.btn-duyet-all').removeClass('disabled');
            } else {
                $('.btn-duyet-all').addClass('disabled');
            }
        });

        $('.btn-update').on('click', function () {
            let vanBanDenDonViId = $(this).data('id');
            checkVanBanDenId(vanBanDenDonViId);
            if (confirm('Xác nhận gửi?')) {
                $('#form-tham-muu').submit();
            }
        });

        function checkVanBanDenId(vanBanDenDonViId) {

            if (ArrVanBanDenDonViId.indexOf(vanBanDenDonViId) === -1) {
                ArrVanBanDenDonViId.push(vanBanDenDonViId);
            }

            $('#form-tham-muu').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(ArrVanBanDenDonViId));

            $('.btn-duyet-all').removeClass('disabled');
        }

        function removeVanBanDenDonViId(vanBanDenDonViId) {
            let index = ArrVanBanDenDonViId.indexOf(vanBanDenDonViId);

            if (index > -1) {
                ArrVanBanDenDonViId.splice(index, 1);
            }
            $('#form-tham-muu').find('input[name="van_ban_den_don_vi_id"]').val(JSON.stringify(ArrVanBanDenDonViId));
        }

        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            $('#modal-tra-lai').find('input[name="van_ban_den_don_vi_id"]').val(id);
        });

        $('.btn-submit').on('click', function () {
            let id = $('#form-tham-muu').find('input[name="van_ban_den_don_vi_id"]').val();
            if (id.length == 0) {
                toastr['warning']('Vui lòng chọn trước khi duyệt', 'Thông báo hệ thống');
            } else {
                $('#form-tham-muu').submit();
            }
        })

    </script>
@endsection

