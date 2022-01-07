@extends('admin::layouts.master')
@section('page_title', 'Văn bản chờ phân loại')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-2">
                                <h3 class="box-title mt-2">@if(Request::get('type') == 1)Giấy mời chờ phân loại @else Văn bản chờ phân loại @endif</h3>
                            </div>
                            <div class="col-md-4 text-left">
                                <button type="button" onclick="showModal()"
                                        class="btn btn-sm mt-1 btn-primary waves-effect waves-light  btn-sm mb-2"
                                        data-original-title=""
                                        title=""><i class="fa fa-search"></i> Tìm kiếm
                                </button>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('phan-loai-van-ban.store') }}" method="post" id="form-tham-muu">
                                    @csrf
                                    <input type="hidden" name="van_ban_den_id" value="">
                                    <input type="hidden" name="van_ban_tra_lai" value="">
                                    <button type="button"
                                            class="btn btn-sm mt-1 btn-submit btn-primary waves-effect waves-light pull-right btn-duyet-all disabled btn-sm mb-2"
                                            data-original-title=""
                                            title=""><i class="fa fa-check"></i> Duyệt
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body " style=" width: 100%;overflow-x: auto;">
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
                                                <form action="@if(Request::get('type') == 1){{route('phan_loai_giay_moi')}}@else{{route('phan-loai-van-ban.index')}}@endif" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label for="so-den" class="col-form-label">Tìm theo số đến</label>
                                                            <input type="number" class="form-control" placeholder="Nhập số đến"
                                                                   name="so_den" value="{{ Request::get('so_den') ?? null }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="han-xu-ly" class="col-form-label">Tìm theo ngày nhập</label>
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar-o"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right datepicker" placeholder="dd/mm/yyyy"
                                                                       name="ngay_den" value="{{ Request::get('ngay_den') ?? null }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="trich-yeu">Tìm theo trích yếu</label>
                                                            <input type="text" name="trich_yeu" class="form-control" value="{{ Request::get('trich_yeu') ?? null }}" placeholder="nhập nội dung...">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="search" class="col-form-label">&nbsp;</label><br>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fa fa-search"></i> Tìm kiếm
                                                            </button>
                                                            @if(request('ngay_den') || request('trich_yeu') || request('so_den'))
                                                                <a href="{{ route('phan-loai-van-ban.index') }}">
                                                                    <button type="button" class="btn btn-success">
                                                                        <i class="fa fa-refresh"></i>
                                                                    </button>
                                                                </a>
                                                            @endif
                                                        </div>
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
                        <br>
                        Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr role="row">
                                <th width="2%" class="text-center">STT</th>
                                <th width="22%" class="text-center">Trích yếu - Thông tin</th>
                                <th width="20%" class="text-center">Tóm tắt văn bản</th>
                                <th width="15%" class="text-center">Ý kiến</th>
                                <th width="20%" class="text-center">Chỉ đạo</th>
                                <th width="15%" class="text-center">Dự họp</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($danhSachVanBanDen as $key => $vanBanDen)
                                <tr class="tr-tham-muu">
                                    <input type="hidden" name="don_vi_du_hop[{{ $vanBanDen->id }}]" value=""
                                           class="check-don-vi-du-hop" form="form-tham-muu">
                                    <td class="text-center">{{ $order++ }}</td>
                                    <td>
                                        <p>
                                            <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id) }}">
                                                @if($vanBanDen->ngay_nhan == date('Y-m-d'))<span style="color: #c000ff;font-weight: bold">{{ $vanBanDen->trich_yeu }}</span> @else <span>{{ $vanBanDen->trich_yeu }}</span> @endif</a>
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
                                        @if ($vanBanDen->vanBanTraLai)
                                            <p class="color-red"><b>Lý
                                                    do trả
                                                    lại: </b><i>{{ $vanBanDen->vanBanTraLai->noi_dung ?? '' }}</i>
                                            </p>
                                            <p>
                                                (Cán bộ trả
                                                lại: {{ $vanBanDen->vanBanTraLai->canBoChuyen->ho_ten  ?? '' }}
                                                - {{ $vanBanDen->vanBanTraLai->canBoChuyen->donVi->ten_don_vi ?? null }}
                                                - {{ date('d/m/Y h:i:s', strtotime($vanBanDen->vanBanTraLai->created_at)) }})</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dau-viec-chi-tiet" style="width: 95%;">
{{--                                            <p>--}}
{{--                                                <select name="chu_tich_id[{{ $vanBanDen->id }}]"--}}
{{--                                                        id="lanh-dao-chu-tri-{{ $vanBanDen->id }}"--}}
{{--                                                        data-id="{{ $vanBanDen->id }}"--}}
{{--                                                        class="form-control select2 chu-tich"--}}
{{--                                                        placeholder="Chọn giám đốc chủ trì"--}}
{{--                                                        data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"--}}
{{--                                                        form="form-tham-muu">--}}
{{--                                                    <option value="">Chọn giám đốc chủ trì</option>--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $chuTich->id ?? null }}">{{ $chuTich->ho_ten ?? null }}</option>--}}
{{--                                                </select>--}}
{{--                                            </p>--}}
{{--                                            <p>--}}
{{--                                                <select--}}
{{--                                                    name="pho_chu_tich_id[{{ $vanBanDen->id }}]"--}}
{{--                                                    id="pho-chu-tich-{{ $vanBanDen->id }}"--}}
{{--                                                    class="form-control pho-chu-tich select2"--}}
{{--                                                    data-id="{{ $vanBanDen->id }}"--}}
{{--                                                    placeholder="Chọn phó giám đốc"--}}
{{--                                                    form="form-tham-muu"--}}
{{--                                                    data-tra-lai="{{ !empty($vanBanDen->vanBanTraLai) ? 1 : null }}"--}}
{{--                                                >--}}
{{--                                                    <option value="">Chọn phó giám đốc chủ trì--}}
{{--                                                    </option>--}}
{{--                                                    @forelse($danhSachPhoChuTich as $phoChuTich)--}}
{{--                                                        <option--}}
{{--                                                            value="{{ $phoChuTich->id }}">{{ $phoChuTich->ho_ten }}</option>--}}
{{--                                                    @empty--}}
{{--                                                    @endforelse--}}
{{--                                                </select>--}}
{{--                                            </p>--}}

                                            <p>
                                                <select name="giam_doc_id[{{ $vanBanDen->id }}]"
                                                        class="form-control select2 "
                                                        placeholder="Chọn giám đốc chủ trì"
                                                        form="form-tham-muu">
                                                    <option value="">Chọn giám đốc chủ trì</option>
                                                    <option
                                                        value="{{ $chuTich->id ?? null }}">{{ $chuTich->ho_ten ?? null }}</option>
                                                </select>
                                            </p>
                                            <p>
                                                <select
                                                    name="lanh_dao_chi_dao[{{ $vanBanDen->id }}][]"
                                                    class="form-control lanh-dao-xem-de-biet select2 select2-hidden-accessible"
                                                    multiple="multiple"
                                                    form="form-tham-muu"
                                                    data-placeholder="Chọn các phó giám đốc"
                                                >
                                                    <option value="">Chọn phó giám đốc
                                                    </option>
                                                    @forelse($danhSachPhoChuTich as $phoChuTich)
                                                        <option
                                                            value="{{ $phoChuTich->id }}">{{ $phoChuTich->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </p>
{{--                                            <p>--}}
{{--                                                <select--}}
{{--                                                    name="lanh_dao_xem_de_biet[{{ $vanBanDen->id }}][]"--}}
{{--                                                    class="form-control lanh-dao-xem-de-biet select2 select2-hidden-accessible"--}}
{{--                                                    multiple="multiple"--}}
{{--                                                    form="form-tham-muu"--}}
{{--                                                    data-placeholder="Chọn lãnh đạo chỉ đạo, giám sát"--}}
{{--                                                >--}}
{{--                                                    <option value="">Chọn lãnh đạo chỉ đạo, giám sát--}}
{{--                                                    </option>--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $chuTich->id ?? null }}">{{ $chuTich->ho_ten ?? null }}</option>--}}
{{--                                                    @forelse($danhSachPhoChuTich as $phoChuTich)--}}
{{--                                                        <option--}}
{{--                                                            value="{{ $phoChuTich->id }}">{{ $phoChuTich->ho_ten }}</option>--}}
{{--                                                    @empty--}}
{{--                                                    @endforelse--}}
{{--                                                </select>--}}
{{--                                            </p>--}}
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
                                                            value="{{ $donVi->id }}">{{ $donVi->ten_don_vi }}</option>
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
                                                    @if (!empty($vanBanDen->vanBanTraLai))
                                                        @forelse($danhSachDonVi as $donVi)
                                                            <option
                                                                value="{{ $donVi->id }}">{{ $donVi->ten_don_vi }}</option>
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </select>
                                            </p>
                                            <p>
                                                <input type="checkbox" id="select-all-cv-ph-{{ $vanBanDen->id }}" data-idvb="{{ $vanBanDen->id }}" class="check-all-cv1">
                                                <label for="select-all-cv-ph-{{ $vanBanDen->id }}" class="font-weight-normal">Chọn tất cả đơn vị phối hợp</label>
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <div class="icheckbox_flat-green checked" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" class="flat-red" name="van_ban_quan_trong[{{ $vanBanDen->id }}]" form="form-tham-muu" value="1" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                               &ensp;Văn bản quan trọng
                                            </label>

                                        </div>
                                    </td>
                                    <td>
                                        <p>
                                            <textarea name="noi_dung_chu_tich[{{ $vanBanDen->id }}]"
                                                      form="form-tham-muu"
                                                      class="form-control noi-dung-chu-tich hide"
                                                      rows="5"></textarea>
                                        </p>
                                        <p>
                                            <textarea
                                                name="noi_dung_pho_chu_tich[{{ $vanBanDen->id }}]"
                                                form="form-tham-muu" class="form-control hide"
                                                rows="3"></textarea>
                                        </p>
                                        <p>
                                            <textarea name="don_vi_chu_tri[{{ $vanBanDen->id }}]"
                                                      class="form-control hide"
                                                      form="form-tham-muu"
                                                      rows="3"></textarea>
                                        </p>
                                        <p>
                                            <textarea name="don_vi_phoi_hop[{{ $vanBanDen->id }}]"
                                                      class="form-control hide"
                                                      form="form-tham-muu"
                                                      rows="3"></textarea>
                                        </p>
                                    </td>
                                    <td>
                                        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                                            <div class="radio-info form-check-inline">
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"
                                                       class="radio-col-cyan chu-tich-du-hop" value=""
                                                       form="form-tham-muu" data-id="{{ $vanBanDen->id }}">
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.1' }}"><i>GD</i></label>
                                            </div>
                                            <div class=" radio-info form-check-inline">
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"
                                                       class="radio-col-cyan pho-ct-du-hop" value=""
                                                       form="form-tham-muu" data-id="{{ $vanBanDen->id }}">
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.2' }}"><i>PGD</i></label>
                                            </div>
                                            <div class=" radio-info form-check-inline">
                                                <input type="radio"
                                                       name="lanh_dao_du_hop_id[{{ $vanBanDen->id }}]"
                                                       id="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"
                                                       class="radio-col-cyan don-vi-du-hop" value=""
                                                       form="form-tham-muu" data-id="{{ $vanBanDen->id }}">
                                                <label
                                                    for="lanh-dao-du-hop-{{ $vanBanDen->id .'.3' }}"><i>Phòng dự họp</i></label>
                                            </div>
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
                                {!! $danhSachVanBanDen->appends(['so_den'  => Request::get('so_den'), 'ngay_den'  => Request::get('ngay_den'), 'trich_yeu' => Request::get('trich_yeu')])->render() !!}
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
    <script src="{{ asset('modules/xu_ly_van_ban_den/js/index.js') }}"></script>
    <script>
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
        function showModal() {
            $("#myModal").modal('show');
        }

    </script>
@endsection
