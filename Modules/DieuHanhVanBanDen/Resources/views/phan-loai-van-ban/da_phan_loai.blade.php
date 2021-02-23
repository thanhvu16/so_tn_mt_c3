@extends('admin::layouts.master')
@if (auth::user()->hasRole('tham mưu'))
    @section('page_title', 'Văn bản đã phân loại')
@else
    @section('page_title', 'Văn bản đã chỉ đạo')
@endif
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title mt-2">Văn bản
                                    đã {{ auth::user()->hasRole(['tham mưu', 'chánh văn phòng']) ? 'phân loại' : 'chỉ đạo' }}</h3>
                            </div>
                            <div class="col-md-6">
                                <form
                                    action="{{ !empty($active) ? route('van-ban-lanh-dao-xu-ly.store') : route('phan-loai-van-ban.store') }}"
                                    method="post" id="form-tham-muu">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_id" value="">
                                    <input type="hidden" name="type" value="update">
                                    <input type="hidden" name="active" value="{{ $active }}">
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            <form action="{{route('phan-loai-van-ban.da_phan_loai')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label>Tìm theo trích yếu</label>
                                    <input type="text" class="form-control" value="{{Request::get('trich_yeu')}}"
                                           name="trich_yeu"
                                           placeholder="Nhập trích yếu">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Tìm theo số đến</label>
                                    <input type="text" class="form-control" value="{{Request::get('so_den')}}"
                                           name="so_den"
                                           placeholder="Nhập số đến">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Tìm theo ngày</label>
                                    <input type="date" class="form-control" value="{{Request::get('date')}}"
                                           name="date">
                                </div>
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
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr role="row">
                                <th width="2%" class="text-center">STT</th>
                                <th width="22%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="20%" class="text-center">Tóm tắt văn bản</th>
                                <th class="15%">Ý kiến</th>
                                <th width="20%" class="text-center">Chỉ đạo</th>
                                <th width="15%" class="text-center">Tác vụ</th>
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
                                        @include('dieuhanhvanbanden::van-ban-den.info')
                                    </td>
                                    <td>
                                        <p>
                                            <textarea name="tom_tat[{{ $vanBanDen->id }}]" class="form-control"
                                                      form="form-tham-muu"
                                                      placeholder="nhập tóm tắt văn bản..."
                                                      rows="9">{{ $vanBanDen->tom_tat ?? $vanBanDen->trich_yeu }}</textarea>
                                        </p>
                                    </td>
                                    <td>
                                        <div class="dau-viec-chi-tiet" style="width: 95%;">
                                            @if (empty($active))
                                                <p>
                                                    <select name="chu_tich_id[{{ $vanBanDen->id }}]"
                                                            id="lanh-dao-chu-tri-{{ $vanBanDen->id }}"
                                                            data-id="{{ $vanBanDen->id }}"
                                                            class="form-control select2 chu-tich"
                                                            placeholder="Chọn giám đốc chủ trì"
                                                            data-id="{{ $vanBanDen->id }}"
                                                            form="form-tham-muu">
                                                        <option value="">Chọn giám đốc chủ trì</option>
                                                        <option
                                                            value="{{ $chuTich->id ?? null }}" {{ in_array($chuTich->id, $vanBanDen->arr_can_bo_nhan) ? 'selected' : null  }}>{{ $chuTich->ho_ten ?? null }}</option>
                                                    </select>
                                                </p>
                                            @endif
                                            <p>
                                                <select
                                                    name="pho_chu_tich_id[{{ $vanBanDen->id }}]"
                                                    id="pho-chu-tich-{{ $vanBanDen->id }}"
                                                    class="form-control pho-chu-tich select2"
                                                    data-id="{{ $vanBanDen->id }}"
                                                    placeholder="Chọn phó giám đốc"
                                                    form="form-tham-muu"
                                                >
                                                    <option value="">Chọn phó giám đốc chủ trì
                                                    </option>
                                                    @forelse($danhSachPhoChuTich as $phoChuTich)
                                                        <option
                                                            value="{{ $phoChuTich->id }}" {{ in_array($phoChuTich->id, $vanBanDen->arr_can_bo_nhan) ? 'selected' : null  }}>{{ $phoChuTich->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            <p>
                                                <select
                                                    name="lanh_dao_xem_de_biet[{{ $vanBanDen->id }}][]"
                                                    class="form-control lanh-dao-xem-de-biet select2"
                                                    multiple="multiple"
                                                    form="form-tham-muu"
                                                    data-placeholder="Chọn lãnh đạo xem để biết"
                                                >
                                                    <option value="">Chọn lãnh đạo xem để
                                                        biết
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
                                            <p>
                                                <select name="don_vi_chu_tri_id[{{ $vanBanDen->id }}]"
                                                        id="don-vi-chu-tri-{{ $vanBanDen->id }}"
                                                        class="form-control don-vi-chu-tri dropdown-search select2"
                                                        data-placeholder="Chọn đơn vị chủ trì"
                                                        data-id="{{ $vanBanDen->id }}"
                                                        form="form-tham-muu">
                                                    <option value="">Chọn đơn vị chủ trì</option>
                                                    @forelse($danhSachDonVi as $donVi)
                                                        <option
                                                            value="{{ $donVi->id }}" {{ !empty($vanBanDen->checkDonViChuTri) && $vanBanDen->checkDonViChuTri->don_vi_id == $donVi->id ? 'selected' : null }}>{{ $donVi->ten_don_vi }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            <p>
                                                <select
                                                    name="don_vi_phoi_hop_id[{{ $vanBanDen->id }}][]"
                                                    id="don-vi-phoi-hop-{{ $vanBanDen->id }}"
                                                    class="form-control select2 don-vi-phoi-hop"
                                                    multiple
                                                    data-placeholder=" Chọn đơn vị phối hợp"
                                                    data-id="{{ $vanBanDen->id }}"
                                                    form="form-tham-muu">
                                                    @forelse($danhSachDonVi as $donVi)
                                                        <option
                                                            value="{{ $donVi->id }}" {{ !empty($vanBanDen->checkDonViPhoiHop) && in_array($donVi->id, $vanBanDen->checkDonViPhoiHop->pluck('don_vi_id')->toArray()) ? 'selected' : null }}>{{ $donVi->ten_don_vi }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            <!-- chu tich -->
                                            @if ($active)
{{--                                                @if($vanBanDen->checkQuyenGiaHan)--}}
                                                    <p>
                                                        <input type="date" name="han_xu_ly[{{ $vanBanDen->id }}]"
                                                               value="{{ $vanBanDen->PhoChuTich->han_xu_ly ?? null }}"
                                                               class="form-control change-han-xu-ly"
                                                               form="form-tham-muu" data-id="{{ $vanBanDen->id }}">
                                                    </p>
{{--                                                @endif--}}

                                                <input id="van-ban-quan-trong{{ $vanBanDen->id }}" type="checkbox"
                                                       name="van_ban_quan_trong[{{ $vanBanDen->id }}]" value="1"
                                                       form="form-tham-muu" data-id="{{ $vanBanDen->id }}"
                                                       {{ $vanBanDen->vanBanQuanTrong ? 'checked' : null }} class="check-van-ban-quan-trong">
                                                <label for="van-ban-quan-trong{{ $vanBanDen->id }}"
                                                       class="color-red font-weight-normal">
                                                    VB Quan trọng
                                                </label>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if (empty($active))
                                            <p>
                                            <textarea name="noi_dung_chu_tich[{{ $vanBanDen->id }}]"
                                                      form="form-tham-muu"
                                                      class="form-control noi-dung-chu-tich {{ !empty($vanBanDen->chuTich) ? 'show' : 'hide' }}"
                                                      rows="5">{{ $vanBanDen->chuTich->noi_dung ?? '' }}</textarea>
                                            </p>
                                        @endif
                                        <p>
                                            <textarea
                                                name="noi_dung_pho_chu_tich[{{ $vanBanDen->id }}]"
                                                form="form-tham-muu"
                                                class="form-control {{ !empty($vanBanDen->PhoChuTich->noi_dung) ? 'show' : 'hide' }}"
                                                rows="3">{{ $vanBanDen->PhoChuTich->noi_dung ?? '' }}</textarea>
                                        </p>
                                        <p>
                                            <textarea name="don_vi_chu_tri[{{ $vanBanDen->id }}]"
                                                      class="form-control {{ !empty($vanBanDen->checkDonViChuTri) ? 'show' : 'hide' }}"
                                                      form="form-tham-muu"
                                                      rows="3">{{ $vanBanDen->checkDonViChuTri->noi_dung ?? null }}</textarea>
                                        </p>
                                        <p>
                                            <textarea name="don_vi_phoi_hop[{{ $vanBanDen->id }}]"
                                                      class="form-control {{ count($vanBanDen->checkDonViPhoiHop) > 0 ? 'show' : 'hide' }}"
                                                      form="form-tham-muu"
                                                      rows="5">@if (!empty($vanBanDen->checkDonViPhoiHop))Chuyển đơn vị phối hợp: @foreach($vanBanDen->DonViCapXaPhoiHop as $donViPhoiHop)
                                                    {{ $donViPhoiHop->donVi->ten_don_vi }} @endforeach
                                                @endif
                                            </textarea>
                                        </p>
                                    </td>
                                    <td>
                                        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                            <p>Lãnh đạo dự họp:</p>
                                            <div class="radio-info form-check-inline">
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                       class="radio-col-cyan chu-tich-du-hop"
                                                       value="{{ $chuTich->id ?? null }}"
                                                       form="form-tham-muu" {{ $vanBanDen->lichCongTacChuTich ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                ><i>GD</i></label>
                                            </div>
                                            <div class="radio-info form-check-inline">
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                       class="radio-col-cyan pho-ct-du-hop"
                                                       value="{{ $vanBanDen->PhoChuTich->can_bo_nhan_id ?? null }}"
                                                       form="form-tham-muu" {{ !empty($vanBanDen->lichCongTacPhoChuTich) && !empty($vanBanDen->PhoChuTich) && $vanBanDen->lichCongTacPhoChuTich->lanh_dao_id == $vanBanDen->PhoChuTich->can_bo_nhan_id  ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                ><i>PGD</i></label>
                                            </div>
                                            <div class=" radio-info form-check-inline">
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                       class="radio-col-cyan don-vi-du-hop"
                                                       value="{{ !empty($vanBanDen->checkDonViChuTri) ? $vanBanDen->checkDonViChuTri->don_vi_id : null }}"
                                                       form="form-tham-muu" {{ $vanBanDen->lichCongTacDonVi ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phòng dự họp</i></label>
                                            </div>
                                        @endif
                                        @if (isset($vanBanDen->checkLuuVetVanBanDen) && $vanBanDen->checkLuuVetVanBanDen->can_bo_chuyen_id == auth::user()->id)
                                            <button
                                                class="btn mt-1 waves-effect btn-sm btn-primary btn-update"
                                                data-id="{{ $vanBanDen->id }}">Cập nhật
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <td colspan="6" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                                Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
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
    <script type="text/javascript">
        let vanBanDenDonViId = null;
        let ArrVanBanDenDonViId = [];
        let txtChuTich = null;

        $('.chu-tich').on('change', function () {
            let $this = $(this);
            let id = $this.val();
            vanBanDenDonViId = $this.data('id');

            let textChuTich = $this.find("option:selected").text() + ' xem xét';
            let txtChiDao = textChuTich;
            let textPhoChuTich = $this.parents('.tr-tham-muu').find('.pho-chu-tich option:selected').text() + ' chỉ đạo';

            if (textPhoChuTich) {
                txtChiDao = textChuTich + ',giao PGD ' + textPhoChuTich;
            }
            if (id) {
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính báo cáo giám đốc ' + txtChiDao);
                checkVanBanDenId(vanBanDenDonViId);
                txtChuTich = 'Kính báo cáo giám đốc ' + textChuTich;
                $this.parents('.tr-tham-muu').find('.chu-tich-du-hop').val(id);
                checkedDuHop($this, '.chu-tich-du-hop');
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chu_tich[${vanBanDenDonViId}]"]`).addClass('hide');
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_chu_tich[${vanBanDenDonViId}]"]`).text('');
                $this.parents('.tr-tham-muu').find('.chu-tich-du-hop').val();
                removeDuHop($this, '.chu-tich-du-hop');
            }
        });

        $('.pho-chu-tich').on('change', function () {
            let $this = $(this);
            let id = $this.val();
            let textPhoChuTich = $this.find("option:selected").text() + ' chỉ đạo';
            vanBanDenDonViId = $this.data('id');
            let checkChuTich = $this.parents('.tr-tham-muu').find('.chu-tich option:selected').val();
            let textChuTich = $this.parents('.tr-tham-muu').find('.chu-tich option:selected').text() + ' xem xét';

            if (id) {
                let txtChiDao = 'Kính báo cáo giám đốc ' + textChuTich + ', giao PGD ' + textPhoChuTich;

                // check empty chu tich
                if (checkChuTich && checkChuTich.length > 0) {
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính chuyển phó giám đốc ' + textPhoChuTich);
                } else {
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính báo cáo phó chủ tịch ' + textPhoChuTich);
                }

                checkVanBanDenId(vanBanDenDonViId);
                $this.parents('.tr-tham-muu').find('.noi-dung-chu-tich').text(txtChiDao);
                $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val(id);
                checkedDuHop($this, '.pho-ct-du-hop');
            } else {
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).text('');
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).addClass('hide');
                removeVanBanDenDonViId(vanBanDenDonViId);
                $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val();
                $this.parents('.tr-tham-muu').find('.noi-dung-chu-tich').text('Kính báo cáo giám đốc ' + textChuTich);
                removeDuHop($this, '.pho-ct-du-hop');
            }
        });

        $('.check-van-ban-quan-trong').on('click', function () {
            vanBanDenDonViId = $(this).data('id');
            checkVanBanDenId(vanBanDenDonViId);
        });

        $('.change-han-xu-ly').on('change', function () {
            vanBanDenDonViId = $(this).data('id');
            checkVanBanDenId(vanBanDenDonViId);
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

            if (donViChuTri.length > 0 && id.length > 0) {
                checkVanBanDenId(vanBanDenDonViId);
                $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị chủ trì: ' + donViChuTri.toString());
                $this.parents('.tr-tham-muu').find('.don-vi-du-hop').val(id);
                checkedDuHop($this, '.don-vi-du-hop');
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).addClass('hide');
            }

            if (arrId) {
                //lấy danh sach cán bộ phối hơp
                $.ajax({
                    url: APP_URL + '/list-don-vi-phoi-hop/' + JSON.stringify(arrId),
                    type: 'GET',
                })
                    .done(function (response) {
                        var html = '<option value="">chọn đơn vị phối hợp</option>';
                        if (response.success) {

                            let selectAttributes = response.data.map((function (attribute) {
                                return `<option value="${attribute.id}" >${attribute.ten_don_vi}</option>`;
                            }));

                            $this.parents('.dau-viec-chi-tiet').find('.don-vi-phoi-hop').html(selectAttributes);
                            $this.parents('.tr-tham-muu').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).text(' ').addClass('hide');
                        } else {
                            $this.parents('.dau-viec-chi-tiet').find('.don-vi-phoi-hop').html(html);
                        }
                    })
                    .fail(function (error) {
                        toastr['error'](error.message, 'Thông báo hệ thống');
                    });
            }

        });

        $('body').on('change', '.don-vi-phoi-hop', function () {

            let donViPhoiHop = $(this).find("option:selected").map(function () {
                return this.text;
            }).get();

            vanBanDenDonViId = $(this).data('id');

            if (donViPhoiHop.length > 0) {

                checkVanBanDenId(vanBanDenDonViId);

                $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị phối hợp: ' + donViPhoiHop.join(', '));
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $(this).parents('.tr-tham-muu').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).addClass('hide');
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

        $('.btn-submit').on('click', function () {
            let id = $('#form-tham-muu').find('input[name="van_ban_den_id"]').val();
            if (id.length == 0) {
                toastr['warning']('Vui lòng chọn trước khi duyệt', 'Thông báo hệ thống');
            } else {
                $('#form-tham-muu').submit();
            }
        });

        $('.btn-update').on('click', function () {
            let vanBanDenDonViId = $(this).data('id');
            checkVanBanDenId(vanBanDenDonViId);
            if (confirm('Xác nhận gửi?')) {
                $('#form-tham-muu').submit();
            }
        });

        $('.don-vi-du-hop').on('click', function () {
            $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val(1);
        });

        $('.pho-ct-du-hop').on('click', function () {
            $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
        });

        $('.chu-tich-du-hop').on('click', function () {
            $(this).parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
        });

        // check du hop
        function checkedDuHop($this, $className) {
            $this.parents('.tr-tham-muu').find($className).prop('checked', true);
        }

        function removeDuHop($this, $className) {
            $this.parents('.tr-tham-muu').find($className).prop('checked', false);
        }

    </script>
@endsection
