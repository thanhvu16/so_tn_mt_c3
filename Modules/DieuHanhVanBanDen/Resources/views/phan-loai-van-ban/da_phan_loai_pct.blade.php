@extends('admin::layouts.master')
@section('page_title', 'Văn bản đã chỉ đạo')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title mt-2">Văn bản đã chỉ đạo</h3>
                            </div>
                            <div class="col-md-6">
                                <form action="{{  route('van-ban-lanh-dao.save_don_vi_chu_tri') }}" method="post"
                                      id="form-tham-muu">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_id" value="">
                                    <input type="hidden" name="type" value="update">
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
                                        <a href="{{ route('phan-loai-van-ban.da_phan_loai') }}" class="btn btn-success"><i class="fa fa-refresh"></i></a>
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
                                <th width="45%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="22%" class="text-center">Ý kiến</th>
                                <th width="24%" class="text-center">Chỉ đạo</th>
                                <th width="8%" class="text-center">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-data">
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
                                        @include('dieuhanhvanbanden::van-ban-den.thong_tin')
                                    </td>
                                    <td>
                                        <div class="dau-viec-chi-tiet" style="width: 95%;">
                                            <p>
                                                <select name="don_vi_chu_tri_id[{{ $vanBanDen->id }}]"
                                                        id="don-vi-chu-tri-{{ $vanBanDen->id }}"
                                                        class="form-control don-vi-chu-tri dropdown-search select2"
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
                                                        @if(!empty($vanBanDen->checkDonViChuTri) && $vanBanDen->checkDonViChuTri->don_vi_id != $donVi->id)
                                                        <option
                                                            value="{{ $donVi->id }}" {{ !empty($vanBanDen->checkDonViPhoiHop) && in_array($donVi->id, $vanBanDen->checkDonViPhoiHop->pluck('don_vi_id')->toArray()) ? 'selected' : null }}>{{ $donVi->ten_don_vi }}</option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
                                            @if ($active)
{{--                                                @if($vanBanDen->checkQuyenGiaHan(auth::user()->id))--}}
                                                    <p>
                                                        <span>Gia hạn xử lý</span>
                                                        <input type="date" name="han_xu_ly[{{ $vanBanDen->id }}]"
                                                               value="{{ $vanBanDen->checkDonViChuTri->han_xu_ly_moi ?? null }}"
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
                                                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id && empty($vanBanDen->lichCongTacChuTich))
                                                    <p>Lãnh đạo dự họp:</p>
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                           class="radio-col-cyan pho-ct-du-hop"
                                                           value="{{ $vanBanDen->PhoChuTich->can_bo_nhan_id ?? null }}"
                                                           form="form-tham-muu" {{ $vanBanDen->lichCongTacPhoChuTich ? 'checked' : null  }}>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                    ><i>PGD</i></label>
                                                    &nbsp;
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                           class="radio-col-cyan don-vi-du-hop"
                                                           value="{{ !empty($vanBanDen->checkDonViChuTri) ? $vanBanDen->checkDonViChuTri->don_vi_id : null }}"
                                                           form="form-tham-muu" {{ $vanBanDen->lichCongTacDonVi ? 'checked' : null  }}>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phòng dự
                                                            họp</i></label>
                                                @endif
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        <p>
                                            <textarea name="don_vi_chu_tri[{{ $vanBanDen->id }}]"
                                                      class="form-control {{ !empty($vanBanDen->checkDonViChuTri) ? 'show' : 'hide' }}"
                                                      form="form-tham-muu"
                                                      rows="4">{{ $vanBanDen->checkDonViChuTri->noi_dung ?? null }}</textarea>
                                        </p>
                                        <p>
                                            <textarea name="don_vi_phoi_hop[{{ $vanBanDen->id }}]"
                                                      class="form-control {{ count($vanBanDen->checkDonViPhoiHop) > 0 ? 'show' : 'hide' }}"
                                                      form="form-tham-muu"
                                                      rows="4">@if (!empty($vanBanDen->checkDonViPhoiHop))Chuyển đơn vị phối hợp: @foreach($vanBanDen->checkDonViPhoiHop as $donViPhoiHop)
                                                    {{ $donViPhoiHop->donVi->ten_don_vi }} @endforeach
                                                @endif
                                            </textarea>
                                        </p>
                                    </td>
                                    <td>
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
                $this.parents('.tr-data').find('.don-vi-du-hop').val(id);
                checkVanBanDenId(vanBanDenDonViId);
                $(this).parents('.tr-data').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị chủ trì: ' + donViChuTri.toString());
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $(this).parents('.tr-data').find(`textarea[name="don_vi_chu_tri[${vanBanDenDonViId}]"]`).addClass('hide');
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
                            $this.parents('.tr-data').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).text(' ').addClass('hide');
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

                $(this).parents('.tr-data').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển đơn vị phối hợp: ' + donViPhoiHop.join(', '));
            } else {
                removeVanBanDenDonViId(vanBanDenDonViId);
                $(this).parents('.tr-data').find(`textarea[name="don_vi_phoi_hop[${vanBanDenDonViId}]"]`).addClass('hide');
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
            $('#modal-tra-lai').find('input[name="van_ban_den_id"]').val(id);
        });

        $('.btn-submit').on('click', function () {
            let id = $('#form-tham-muu').find('input[name="van_ban_den_id"]').val();
            if (id.length == 0) {
                toastr['warning']('Vui lòng chọn trước khi duyệt', 'Thông báo hệ thống');
            } else {
                $('#form-tham-muu').submit();
            }
        })

        $('.btn-update').on('click', function () {
            let vanBanDenDonViId = $(this).data('id');
            checkVanBanDenId(vanBanDenDonViId);
            if (confirm('Xác nhận gửi?')) {
                $('#form-tham-muu').submit();
            }
        });

        $('.don-vi-du-hop').on('click', function () {
            $(this).parents('.tr-data').find('.check-don-vi-du-hop').val(1);
        });

        $('.pho-ct-du-hop').on('click', function () {
            $(this).parents('.tr-data').find('.check-don-vi-du-hop').val("");
        });

        $('.chu-tich-du-hop').on('click', function () {
            $(this).parents('.tr-data').find('.check-don-vi-du-hop').val("");
        });

    </script>
@endsection
