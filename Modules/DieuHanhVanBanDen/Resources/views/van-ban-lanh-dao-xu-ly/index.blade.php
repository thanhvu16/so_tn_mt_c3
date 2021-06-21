@extends('admin::layouts.master')
@section('page_title', 'Văn bản chờ xử lý')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-2">
                                <h4 class="header-title pt-2">Văn bản chờ xử lý</h4>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" onclick="showModal()"><i
                                        class="fa  fa-search"></i><span
                                        style="font-size: 14px"> Tìm kiếm văn bản </span></button>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('van-ban-lanh-dao-xu-ly.store') }}" method="post"
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
                                                    <form action="@if(Request::get('type') == 1){{route('giayMoiLanhDaoXuLy')}}@else{{route('van-ban-lanh-dao-xu-ly.index')}}@endif" method="get">
{{--                                                        <div class="col-md-3 form-group">--}}
{{--                                                            <label>Tìm theo trích yếu</label>--}}
{{--                                                            <input type="text" class="form-control" value="{{Request::get('trich_yeu')}}"--}}
{{--                                                                   name="trich_yeu"--}}
{{--                                                                   placeholder="Nhập trích yếu">--}}
{{--                                                            --}}
{{--                                                        </div>--}}
                                                        <input type="text" class="form-control hidden" value="{{Request::get('type')}}"
                                                               name="type"
                                                               placeholder="">
{{--                                                        <div class="col-md-3 form-group">--}}
{{--                                                            <label>Tìm theo số đến</label>--}}
{{--                                                            <input type="text" class="form-control" value="{{Request::get('so_den')}}"--}}
{{--                                                                   name="so_den"--}}
{{--                                                                   placeholder="Nhập số đến">--}}
{{--                                                        </div>--}}
{{--                                                        --}}{{--                                <div class="col-md-3 form-group">--}}
                                                        {{--                                    <label>Tìm theo ngày</label>--}}
                                                        {{--                                    <div class="input-group date">--}}
                                                        {{--                                        <div class="input-group-addon">--}}
                                                        {{--                                            <i class="fa fa-calendar-o"></i>--}}
                                                        {{--                                        </div>--}}
                                                        {{--                                        <input type="text" class="form-control datepicker" value="{{Request::get('date')}}"--}}
                                                        {{--                                               name="date" placeholder="dd/mm/yyyy">--}}
                                                        {{--                                    </div>--}}
                                                        {{--                                </div>--}}
{{--                                                        <div class="col-md-3">--}}
{{--                                                            <label>&nbsp;</label><br>--}}
{{--                                                            <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>--}}
{{--                                                            @if (!empty(Request::get('trich_yeu')) || !empty(Request::get('so_den')) ||--}}
{{--                                                                        !empty(Request::get('date')))--}}
{{--                                                                <a href="{{ route('phan-loai-van-ban.da_phan_loai') }}" class="btn btn-success"><i--}}
{{--                                                                        class="fa fa-refresh"></i></a>--}}
{{--                                                            @endif--}}
{{--                                                        </div>--}}

                                                        @include('dieuhanhvanbanden::form_tim_kiem')

                                                    </form>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        @include('dieuhanhvanbanden::van-ban-den.fom_tra_lai')
                        <table class="table table-striped table-bordered table-hover data-row">
                            <thead>
                            <tr role="row" class="text-center">
                                <th width="2%" class="text-center">STT</th>
                                <th width="42%" class="text-center">Trích yếu - Thông tin</th>
{{--                                <th width="20%" class="text-center">Tóm tắt VB</th>--}}
                                <th class="text-center" width="24%">Ý kiến</th>
                                <th width="22%" class="text-center">Chỉ đạo</th>
                                <th class="text-center" width="7%">
                                    <input id="check-all" type="checkbox" name="check_all" value="">
                                </th>
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
                                            <a href="{{ route('van_ban_den_chi_tiet.show',  $vanBanDen->id.'?xuly=true') }}">{{ $vanBanDen->trich_yeu }}</a>
                                            <br>
                                            @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                <i>
                                                    (Vào hồi {{ date( "H:i", strtotime($vanBanDen->gio_hop)) }}
                                                    ngày {{ date('d/m/Y', strtotime($vanBanDen->ngay_hop)) }}
                                                    , tại {{ $vanBanDen->dia_diem }})
                                                </i>
                                            @endif
                                        </p>
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
                                        @if (!empty($checkThamMuuSo))
                                            <p>
                                                <a class="tra-lai-van-ban" data-toggle="modal" data-target="#modal-tra-lai"
                                                   data-id="{{ $vanBanDen->id }}">
                                                    <span><i class="fa fa-reply"></i>Trả lại VB</span>
                                                </a>
                                            </p>
                                        @endif

                                        @include('dieuhanhvanbanden::van-ban-den.thong_tin')
                                    </td>
{{--                                    <td>--}}
{{--                                        <p>--}}
{{--                                            {{ $vanBanDen->tom_tat ?? $vanBanDen->trich_yeu }}--}}
{{--                                        </p>--}}
{{--                                    </td>--}}
                                    <td>
                                        <div class="dau-viec-chi-tiet" style="width: 95%;">
                                            <p>
                                                <select
                                                    name="pho_chu_tich_id[{{ $vanBanDen->id }}]"
                                                    id="pho-chu-tich-{{ $vanBanDen->id }}"
                                                    class="form-control pho-chu-tich select2"
                                                    data-id="{{ $vanBanDen->id }}"
                                                    data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"
                                                    placeholder="Chọn phó giám đốc"
                                                    form="form-tham-muu"
                                                >
                                                    <option value="">Chọn phó giám đốc chủ trì
                                                    </option>
                                                    @forelse($danhSachPhoChuTich as $phoChuTich)
                                                        <option
                                                            value="{{ $phoChuTich->id }}" {{ !empty($vanBanDen->PhoChuTich) && $vanBanDen->PhoChuTich->can_bo_nhan_id == $phoChuTich->id ? 'selected' : null  }}>{{ $phoChuTich->ho_ten }}</option>
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
                                                    data-placeholder="Chọn lãnh đạo chỉ đạo, giám sát"
                                                >
                                                    <option value=""> Chọn lãnh đạo chỉ đạo, giám sát
                                                    </option>
                                                    @forelse($danhSachPhoChuTich as $phoChuTich)
                                                        @if (isset($vanBanDen->PhoChuTich) && $vanBanDen->PhoChuTich->can_bo_nhan_id != $phoChuTich->id)
                                                        <option
                                                            value="{{ $phoChuTich->id }}" {{ in_array($phoChuTich->id, $vanBanDen->lanhDaoXemDeBiet->pluck('lanh_dao_id')->toArray()) ? 'selected' : '' }}>{{ $phoChuTich->ho_ten }}</option>
                                                        @else
                                                            <option
                                                                value="{{ $phoChuTich->id }}" {{ in_array($phoChuTich->id, $vanBanDen->lanhDaoXemDeBiet->pluck('lanh_dao_id')->toArray()) ? 'selected' : '' }}>{{ $phoChuTich->ho_ten }}</option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
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
                                                    data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"
                                                    form="form-tham-muu">
                                                    @forelse($danhSachDonVi as $donVi)
                                                        @if (!empty($vanBanDen->checkDonViChuTri) && $vanBanDen->checkDonViChuTri->don_vi_id != $donVi->id)
                                                            <option
                                                                value="{{ $donVi->id }}" {{ !empty($vanBanDen->checkDonViPhoiHop) && in_array($donVi->id, $vanBanDen->checkDonViPhoiHop->pluck('don_vi_id')->toArray()) ? 'selected' : null }}>{{ $donVi->ten_don_vi }}</option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>

                                            <div class="input-group date">
                                                <input type="text" name="han_xu_ly[{{ $vanBanDen->id }}]"
                                                       value=""
                                                       class="form-control datepicker"
                                                       form="form-tham-muu" placeholder="dd/mm/yyyy">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-o"></i>
                                                </div>
                                            </div>

                                            <input id="van-ban-quan-trong{{ $vanBanDen->id }}" type="checkbox"
                                                   name="van_ban_quan_trong[{{ $vanBanDen->id }}]" value="1"
                                                   form="form-tham-muu">
                                            <label for="van-ban-quan-trong{{ $vanBanDen->id }}"
                                                   class="color-red font-weight-normal">
                                                VB Quan trọng
                                            </label>
                                            @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                                <p>Lãnh đạo dự họp:</p>
                                                @if (empty($checkThamMuuSo))
                                                    <input type="radio"
                                                           name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                           id="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                           class="radio-col-cyan chu-tich-du-hop"
                                                           value="{{ $chuTich->id ?? null }}"
                                                           form="form-tham-muu" checked>
                                                    <label
                                                        for="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                    ><i>GD</i></label>
                                                @else
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
                                                       value="{{ $vanBanDen->PhoChuTich->can_bo_nhan_id ?? null }}"
                                                       form="form-tham-muu" {{ !empty($vanBanDen->lichCongTacPhoChuTich) ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                ><i>PGD</i></label>
                                                &nbsp;
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                       class="radio-col-cyan don-vi-du-hop"
                                                       value="{{ !empty($vanBanDen->lichCongTacDonVi->don_vi_du_hop) ? $vanBanDen->checkDonViChuTri->don_vi_id : null }}"
                                                       form="form-tham-muu" {{ $vanBanDen->lichCongTacDonVi ? 'checked' : null  }}>
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phòng dự
                                                        họp</i></label>
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        <p>
                                            <textarea
                                                name="noi_dung_pho_chu_tich[{{ $vanBanDen->id }}]"
                                                form="form-tham-muu"
                                                class="form-control {{ !empty($vanBanDen->PhoChuTich) ? 'show' : 'hide' }}"
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
                                                      rows="4">@if (!empty($vanBanDen->checkDonViPhoiHop))Chuyển đơn vị phối hợp: @foreach($vanBanDen->checkDonViPhoiHop as $donViPhoiHop)
                                                    {{ $donViPhoiHop->donVi->ten_don_vi }} @endforeach
                                                @endif
                                            </textarea>
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <label style="color: red; font-weight: 500 !important;"
                                               for="checkbox{{ $vanBanDen->id }}"> Chọn duyệt:</label><br>
                                        <input id="checkbox{{ $vanBanDen->id }}" type="checkbox"
                                               name="duyet[{{ $vanBanDen->id }}]" value="{{ $vanBanDen->id }}"
                                               class="duyet sub-check">

                                    </td>
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

        function showModal() {
            $("#myModal").modal('show');
        }

        $('body').on('keyup', 'input[name="so_den_start"]', function () {
            let val = $(this).val();
            $('input[name="so_den_end"]').val(val);
        });

        $('body').on('change', 'input[name="ngay_den_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_den_end"]').val(val);
        });

        $('body').on('keyup', 'input[name="ngay_den_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_den_end"]').val(val);
        });

        $('body').on('change', 'input[name="ngay_ban_hanh_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_ban_hanh_end"]').val(val);
        });

        $('body').on('keyup', 'input[name="ngay_ban_hanh_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_ban_hanh_end"]').val(val);
        });

        $('body').on('change', 'input[name="ngay_hop_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_hop_end"]').val(val);
        });

        $('body').on('keyup', 'input[name="ngay_hop_start"]', function () {
            let val = $(this).val();
            $('input[name="ngay_hop_end"]').val(val);
        });




        $('.pho-chu-tich').on('change', function () {
            let $this = $(this);
            let id = $this.val();
            let statusTraLai = $(this).data('tra-lai');

            let textPhoChuTich = $this.find("option:selected").text() + ' chủ trì';
            vanBanDenDonViId = $this.data('id');

            let ct = $this.parents('.tr-tham-muu').find('.chu-tich option:selected').text();
            if (ct.length > 0) {
                txtChuTich = 'Kính báo cáo giám đốc ' + ct + ' xem xét';
            }

            if (statusTraLai) {
                $('#form-tham-muu').find('input[name="van_ban_tra_lai"]').val(statusTraLai);
            }

            if (id) {
                $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val(id);
                //checkedDuHop($this, '.pho-ct-du-hop');
                let txtChiDao = txtChuTich + ', giao PGD ' + textPhoChuTich;
                if (status == 2) {
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Chuyển phó giám đốc ' + textPhoChuTich);

                } else {
                    $this.parents('.tr-tham-muu').find('.noi-dung-chu-tich').text(txtChiDao);
                    $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).removeClass('hide').text('Kính chuyển phó giám đốc ' + textPhoChuTich);
                }

            } else {
                //removeDuHop($this, '.pho-ct-du-hop');
                $this.parents('.tr-tham-muu').find('.pho-ct-du-hop').val();
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).text('');
                $this.parents('.tr-tham-muu').find(`textarea[name="noi_dung_pho_chu_tich[${vanBanDenDonViId}]"]`).addClass('hide');
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
                    beforeSend: function () {
                        showLoading();
                    }
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

        // check du hop
        function checkedDuHop($this, $className) {
            $this.parents('.tr-tham-muu').find($className).prop('checked', true);
            if ($className === '.don-vi-du-hop') {
                $this.parents('.tr-tham-muu').find('.check-don-vi-du-hop').val(1);
            } else {
                $this.parents('.tr-tham-muu').find('.check-don-vi-du-hop').val("");
            }
        }

        function removeDuHop($this, $className) {
            $this.parents('.tr-tham-muu').find($className).prop('checked', false);
        }

    </script>
@endsection
