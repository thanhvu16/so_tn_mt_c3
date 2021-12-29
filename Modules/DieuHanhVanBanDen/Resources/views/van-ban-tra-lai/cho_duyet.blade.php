@extends('admin::layouts.master')
@section('page_title', 'Danh sách văn bản trả lại')
@section('content')
    <section class="content" style="font-size: 14px">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách văn bản trả lại</h3>
                    </div>
                    <!-- /.box-header -->
                    @include('dieuhanhvanbanden::van-ban-den.fom_tra_lai', ['active' => \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB])
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
                        <div class="col-md-12 mb-2 mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    Tổng số loại văn bản: <b>{{ $danhSachVanBanDen->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    <form action=" @if(Request::get('type') == 1) {{route('giay_moi_tra_lai.cho_duyet')}} @else {{route('van_ban_tra_lai.cho_duyet')}} @endif" id="formsb">
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
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" class="text-center">STT</th>
                                <th width="26%" class="text-center">Thông tin</th>
                                <th width="44%" class="text-center">Trích yếu</th>
                                <th width="21%" class="text-center">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($danhSachVanBanDen as $key => $vanBanDen)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>
                                        @include('dieuhanhvanbanden::van-ban-den.info')
                                    </td>
                                    <td style="text-align: justify">

                                        @if($vanBanDen->hasChild)
                                            <p>
                                                <a href="{{ route('van_ban_den_chi_tiet.show', $vanBanDen->id) }}">{{ $vanBanDen->hasChild->trich_yeu ?? null }}</a>
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

                                        @if ($vanBanDen->vanBanTraLaiChoDuyet)
                                            <p class="color-red"><b>Lý
                                                    do trả
                                                    lại: </b><i>{{ $vanBanDen->vanBanTraLaiChoDuyet->noi_dung ?? '' }}</i>
                                            </p>
                                            <p>
                                                @if (isset($vanBanDen->vanBanTraLaiChoDuyet->vanBanTraLaiFile))
                                                    File:
                                                    @foreach($vanBanDen->vanBanTraLaiChoDuyet->vanBanTraLaiFile as $key => $file)
                                                        <a href="{{ $file->getUrlFile() }}"
                                                           target="popup"
                                                           class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                                        @if (count($vanBanDen->vanBanTraLaiChoDuyet->vanBanTraLaiFile)-1 != $key)
                                                            &nbsp;|&nbsp;
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </p>
                                            <p>
                                                (Cán bộ trả
                                                lại: {{ $vanBanDen->vanBanTraLaiChoDuyet->canBoChuyen->ho_ten  ?? '' }}
                                                - {{ $vanBanDen->vanBanTraLaiChoDuyet->canBoChuyen->donVi->ten_don_vi ?? null }}
                                                - {{ date('d/m/Y h:i:s', strtotime($vanBanDen->vanBanTraLaiChoDuyet->created_at)) }}
                                                )</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p><span class="label label-warning">Chờ duyệt</span></p>
                                        (Cán bộ duyệt: <i>Đ/c {{ $vanBanDen->vanBanTraLaiChoDuyet->canBoNhan->ho_ten ?? '' }}</i>)
                                    </td>
                                </tr>
                            @empty
                                <td colspan="4" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                            </div>
                            <div class="col-md-6 text-right">
                                {!! $danhSachVanBanDen->appends(['sap_xep' => Request::get('sap_xep'), 'type' => Request::get('type')])->render() !!}

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
        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            let traLai = $(this).data('tra-lai');

            $('#modal-tra-lai').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-tra-lai').find('input[name="type"]').val(traLai);
        });
    </script>
@endsection















