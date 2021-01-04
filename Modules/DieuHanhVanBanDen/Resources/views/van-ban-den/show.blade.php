@extends('admin::layouts.master')
@section('page_title', 'Chi tiết văn bản')
@section('content')
    <section class="content-header">
        <h1>
            Chi tiết văn bản
        </h1>
    </section>
    <section class="content">
        <div class="box box-detail box-primary">
            <div class="row box-body">
                @if($vanBanDen->hasChild)
                    <div class="col-md-12 row-bd-bt">
                        <div class="col-md-4">
                            <p>
                                <b>Số Ký hiệu: </b>{{ $vanBanDen->hasChild->so_ky_hieu ?? null }}
                            </p>
                            <p>
                                <b>Loại văn
                                    bản:</b> {{ isset($vanBanDen->hasChild->loaiVanBan) ? $vanBanDen->hasChild->loaiVanBan->ten_loai_van_ban : null }}
                            </p>
                            <p>
                                <b>Số đến:</b>
                                <b class="color-red"> {{ $vanBanDen->hasChild->so_den ?? null }}</b>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>Ngày
                                    ban
                                    hành:</b> {{ !empty($vanBanDen->hasChild->ngay_ban_hanh) ? date('d/m/Y', strtotime($vanBanDen->hasChild->ngay_ban_hanh)) : null }}
                            </p>
                            <p>
                                <b>Cơ quan ban hành: </b> {{ $vanBanDen->hasChild->co_quan_ban_hanh ?? null }}
                            </p>
                            <p>
                                <b>Người ký:</b> {{ $vanBanDen->hasChild->nguoi_ky ?? null }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>Cán bộ
                                    nhập: </b> {{ $vanBanDen->hasChild->nguoiDung->ho_ten ?? 'N/A' }}
                            </p>
                            <p>
                                <b>Hạn xử
                                    lý: </b> {{ !empty($vanBanDen->hasChild->han_xu_ly) ? date('d/m/Y', strtotime($vanBanDen->hasChild->han_xu_ly)) : null }}
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p>
                                <b>Trích yếu: </b> {{ $vanBanDen->hasChild->trich_yeu ?? null }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>File:</b>

                                @if (isset($vanBanDen->hasChild->vanBanDenFile))
                                    @foreach($vanBanDen->hasChild->vanBanDenFile as $key => $file)
                                        <a href="{{ $file->getUrlFile() }}"
                                           target="popup"
                                           class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                        @if (count($vanBanDen->hasChild->vanBanDenFile)-1 != $key)
                                            &nbsp;|&nbsp;
                                        @endif
                                    @endforeach
                                @endif

                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>Độ
                                    mật:</b> {{ isset($vanBanDen->hasChild->doBaoMat) ? $vanBanDen->hasChild->doBaoMat->ten_muc_do : null }}
                            </p>
                            <p>
                                <b>Độ
                                    khẩn:</b> {{ isset($vanBanDen->hasChild->doKhan) ? $vanBanDen->hasChild->doKhan->ten_muc_do : null }}
                            </p>
                        </div>
                        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->hasChild->so_van_ban_id == $loaiVanBanGiayMoi->id)
                            <div class="col-md-12">
                                <label class="col-form-label">Nội dung
                                    họp:</label> {{ $vanBanDen->hasChild->noi_dung_hop ?? ' V/v Hội nghị trực tuyến của Chính phủ ' }}
                                <br>
                                <i>
                                    (Vào hồi {{ $vanBanDen->hasChild->gio_hop }}
                                    ngày {{ date('d/m/Y', strtotime($vanBanDen->hasChild->ngay_hop)) }}
                                    , tại {{ $vanBanDen->hasChild->dia_diem }})
                                </i>
                            </div>
                        @endif
                        @if (isset($vanBanDen->hasChild->noi_dung))
                            <div class="col-md-12 mt-2">
                                <label class="col-form-label">Nội dung
                                    :</label> {{ $vanBanDen->hasChild->noi_dung ?? null }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="col-md-12 row-bd-bt">
                        <div class="col-md-4">
                            <p>
                                <b>Số Ký hiệu: </b>{{ $vanBanDen->so_ky_hieu ?? null }}
                            </p>
                            <p>
                                <b>Loại văn
                                    bản:</b> {{ isset($vanBanDen->loaiVanBan) ? $vanBanDen->loaiVanBan->ten_loai_van_ban : null }}
                            </p>
                            <p>
                                <b>Số đến:</b>
                                <b class="color-red"> {{ $vanBanDen->so_den ?? null }}</b>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>Ngày
                                    ban
                                    hành:</b> {{ !empty($vanBanDen->ngay_ban_hanh) ? date('d/m/Y', strtotime($vanBanDen->ngay_ban_hanh)) : null }}
                            </p>
                            <p>
                                <b>Cơ quan ban hành: </b> {{ $vanBanDen->co_quan_ban_hanh ?? null }}
                            </p>
                            <p>
                                <b>Người ký:</b> {{ $vanBanDen->nguoi_ky ?? null }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>Cán bộ
                                    nhập: </b> {{ $vanBanDen->nguoiDung->ho_ten ?? 'N/A' }}
                            </p>
                            <p>
                                <b>Hạn xử
                                    lý: </b> {{ !empty($vanBanDen->han_xu_ly) ? date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) : null }}
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p>
                                <b>Trích yếu: </b> {{ $vanBanDen->trich_yeu ?? null }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>File:</b>

                                @if (isset($vanBanDen->vanBanDenFile))
                                    @foreach($vanBanDen->vanBanDenFile as $key => $file)
                                        <a href="{{ $file->getUrlFile() }}"
                                           target="popup"
                                           class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                                        @if (count($vanBanDen->vanBanDenFile)-1 != $key)
                                            &nbsp;|&nbsp;
                                        @endif
                                    @endforeach
                                @endif

                            </p>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <b>Độ
                                    mật:</b> {{ isset($vanBanDen->doBaoMat) ? $vanBanDen->doBaoMat->ten_muc_do : null }}
                            </p>
                            <p>
                                <b>Độ khẩn:</b> {{ isset($vanBanDen->doKhan) ? $vanBanDen->doKhan->ten_muc_do : null }}
                            </p>
                        </div>
                        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->so_van_ban_id == $loaiVanBanGiayMoi->id)
                            <div class="col-md-12">
                                <label class="col-form-label">Nội dung
                                    họp:</label> {{ $vanBanDen->noi_dung_hop ?? ' V/v Hội nghị trực tuyến của Chính phủ ' }}
                                <br>
                                <i>
                                    (Vào hồi {{ $vanBanDen->gio_hop }}
                                    ngày {{ date('d/m/Y', strtotime($vanBanDen->ngay_hop)) }}
                                    , tại {{ $vanBanDen->dia_diem }})
                                </i>
                            </div>
                        @endif
                        @if (isset($vanBanDen->noi_dung))
                            <div class="col-md-12 mt-2">
                                <label class="col-form-label">Nội dung :</label> {{ $vanBanDen->noi_dung ?? null }}
                            </div>
                        @endif
                    </div>
                @endif
                @if ($vanBanDen->chuTich || $vanBanDen->PhoChuTich || $vanBanDen->truongPhong ||
                $vanBanDen->phoPhong || $vanBanDen->chuyenVien)
                <div class="col-md-12 row-bd-bt">
                    <div class="col-md-12">
                        <h5 class="text-bold">Sơ đồ chỉ đạo bản: </h5>
                    </div>
                    <ul class="progressbar">
                        @if ($vanBanDen->chuTich)
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >=1 ? 'complete active' : null }}">{!! $vanBanDen->chuTich->canBoNhan->ho_ten ."<br/> (Chủ tịch)" !!}
                                <br>
                                <i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->chuTich->created_at)) }}</i>
                            </li>
                        @endif
                        @if ($vanBanDen->PhoChuTich)
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >=2 ? 'complete active' : null }}">{!!  $vanBanDen->PhoChuTich->canBoNhan->ho_ten ."<br/> (Phó chủ tịch)"  !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->PhoChuTich->created_at)) }}</i>
                            </li>

                            @endif
                        @if (!empty($vanBanDen->truongPhong))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >=3 ? 'complete active' : null }}">{!! $vanBanDen->truongPhong->canBoNhan->ho_ten ."<br/> (Trưởng phòng)" !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->truongPhong->created_at)) }}</i>
                            </li>
                        @endif
                        @if ($vanBanDen->phoPhong)
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >=4 ? 'complete active' : null }}">{!! $vanBanDen->phoPhong->canBoNhan->ho_ten ."<br/> (Phó phòng)"  !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->phoPhong->created_at)) }}</i>
                            </li>
                        @endif
                        @if ($vanBanDen->chuyenVien)
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >=5 ? 'complete active' : null }}">{!! $vanBanDen->chuyenVien->canBoNhan->ho_ten ."<br/> (Chuyên viên)" !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->chuyenVien->created_at)) }}</i>
                            </li>
                        @endif
                    </ul>
                </div>
                @endif
                @include('dieuhanhvanbanden::van-ban-den.log_xu_ly_van_ban_den', ['xuLyVanBanDen' => $vanBanDen->xuLyVanBanDen ])
                @include('dieuhanhvanbanden::van-ban-den.log_tra_lai_van_ban', ['xuLyVanBanDen' => $vanBanDen->xuLyVanBanDenTraLai ])
                @include('dieuhanhvanbanden::van-ban-den.log_chuyen_don_vi_chu_tri', ['chuyenNhanVanBanDonViChuTri' => $vanBanDen->donViChuTri ])
                @include('dieuhanhvanbanden::van-ban-den.log_chuyen_don_vi_phoi_hop', ['chuyenNhanVanBanDonViPhoiHop' => $vanBanDen->donViPhoiHop ])
                @include('dieuhanhvanbanden::van-ban-den.log_gia_han_van_ban', ['giaHanVanBanDonVi' => $vanBanDen->giaHanVanBan ])
                @include('dieuhanhvanbanden::van-ban-den.log_chuyen_vien_phoi_hop', ['danhSachPhoiHopGiaiQuyet' => $vanBanDen->chuyenVienPhoiHop])
                @include('dieuhanhvanbanden::van-ban-den.log_don_vi_phoi_hop_giai_quyet', ['danhSachDonViPhoiHopGiaiQuyet' => $vanBanDen->donViPhoiHopGiaiquyet])
                @include('dieuhanhvanbanden::van-ban-den.log-hoan_thanh_cong_viec')
                @include('dieuhanhvanbanden::van-ban-den.log_du_thao_van_ban')
                @include('dieuhanhvanbanden::van-ban-den.log_van_ban_di')

            <!--giai quyet van ban-->
                @if ($vanBanDen->active != \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                    @hasanyrole('trưởng phòng|phó phòng|chuyên viên')
                    @include('dieuhanhvanbanden::van-ban-den._form_giai_quyet')
                @endif
                @endrole

                <div class="col-md-12 mt-3">
                    <a class="btn btn-default go-back" data-original-title="" title="">Quay lại &gt;&gt;</a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>

    <script type="text/javascript">
        $('input[name="status_action"]').on('click', function () {
            let status = $(this).val();
            if (status == 1) {
                $('.form-du-thao').removeClass('hide');
                $('.truc-tiep-giai-quyet').addClass('hide');
                $('.select2').select2();
            } else {
                $('.truc-tiep-giai-quyet').removeClass('hide');
                $('.form-du-thao').addClass('hide');
            }
        });
    </script>
@endsection

