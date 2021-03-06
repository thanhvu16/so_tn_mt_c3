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
                        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->hasChild->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                            <div class="col-md-12 mb-2">
                                <label class="col-form-label">Nội dung
                                    họp:</label> {{ $vanBanDen->hasChild->noi_dung_hop ?? ' V/v Hội nghị trực tuyến của Chính phủ ' }}
                                <br>
                                <i>
                                    (Vào hồi {{ date( "H:i", strtotime($vanBanDen->hasChild->gio_hop)) }}
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
                                <b>Ngày
                                    nhập: </b> {{ date('d/m/Y H:i:s', strtotime($vanBanDen->created_at))  }}
                            </p>
                            <p>
                                <b>Hạn văn bản: </b> {{ !empty($vanBanDen->han_xu_ly) ? date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) : null }}
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
                        </div><div class="col-md-4">
                            <p>
                                <b>Độ khẩn:</b> {{ isset($vanBanDen->doKhan) ? $vanBanDen->doKhan->ten_muc_do : null }}
                            </p>
                        </div>
                        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                            <div class="col-md-12 mb-2">
                                <label class="col-form-label">Nội dung
                                    họp:</label> {{ $vanBanDen->noi_dung_hop ?? ' V/v Hội nghị trực tuyến của Chính phủ ' }}
                                <br>
                                <i>
                                    (Vào hồi {{ date( "H:i", strtotime($vanBanDen->gio_hop)) }}
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
                        <h5 class="text-bold">Sơ đồ chỉ đạo văn bản: </h5>
                    </div>
                    <ul class="progressbar">
                        @if (!empty($vanBanDen->chuTich->can_bo_nhan_id))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >= \Modules\VanBanDen\Entities\VanBanDen::CHU_TICH_NHAN_VB ? 'complete active' : null }}">{!! $vanBanDen->chuTich->canBoNhan->ho_ten ."<br/> (". $vanBanDen->chuTich->canBoNhan->chucVu->ten_chuc_vu.")" !!}
                                <br>
                                <i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->chuTich->created_at)) }}</i>
                            </li>
                        @endif
                        @if (!empty($vanBanDen->PhoChuTich->can_bo_nhan_id))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >= \Modules\VanBanDen\Entities\VanBanDen::PHO_CHU_TICH_NHAN_VB ? 'complete active' : null }}">{!!  $vanBanDen->PhoChuTich->canBoNhan->ho_ten ."<br/> (".$vanBanDen->PhoChuTich->canBoNhan->chucVu->ten_chuc_vu.")"  !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->PhoChuTich->created_at)) }}</i>
                            </li>
                        @endif
                        @if (!empty($vanBanDen->chuTichXa->can_bo_nhan_id))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >= \Modules\VanBanDen\Entities\VanBanDen::PHO_CHU_TICH_NHAN_VB ? 'complete active' : null }}">{!!  $vanBanDen->chuTichXa->canBoNhan->ho_ten ."<br/> (".$vanBanDen->chuTichXa->canBoNhan->chucVu->ten_chuc_vu.")"  !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->chuTichXa->created_at)) }}</i>
                            </li>
                        @endif
                        @if (!empty($vanBanDen->phoChuTichXa->can_bo_nhan_id))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >= \Modules\VanBanDen\Entities\VanBanDen::PHO_CHU_TICH_NHAN_VB ? 'complete active' : null }}">{!!  $vanBanDen->phoChuTichXa->canBoNhan->ho_ten ."<br/> (".$vanBanDen->phoChuTichXa->canBoNhan->chucVu->ten_chuc_vu.")"  !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->phoChuTichXa->created_at)) }}</i>
                            </li>
                        @endif
                        @if (!empty($vanBanDen->truongPhong->can_bo_nhan_id))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >= \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB ? 'complete active' : null }}">{!! !empty($vanBanDen->chuTichXa) ? $vanBanDen->truongPhong->canBoNhan->ho_ten. "<br/> (".$vanBanDen->truongPhong->canBoNhan->chucVu->ten_chuc_vu.")": $vanBanDen->truongPhong->canBoNhan->ho_ten ."<br/> (".$vanBanDen->truongPhong->canBoNhan->chucVu->ten_chuc_vu.")" !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->truongPhong->created_at)) }}</i>
                            </li>
                        @endif
                        @if (!empty($vanBanDen->phoPhong->can_bo_nhan_id))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >= \Modules\VanBanDen\Entities\VanBanDen::PHO_PHONG_NHAN_VB ? 'complete active' : null }}">{!! !empty($vanBanDen->chuTichXa) ? $vanBanDen->phoPhong->canBoNhan->ho_ten ."<br/> (".$vanBanDen->phoPhong->canBoNhan->chucVu->ten_chuc_vu.")" : $vanBanDen->phoPhong->canBoNhan->ho_ten ."<br/> (".$vanBanDen->phoPhong->canBoNhan->chucVu->ten_chuc_vu.")"  !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->phoPhong->created_at)) }}</i>
                            </li>
                        @endif
                        @if (!empty($vanBanDen->chuyenVien->can_bo_nhan_id))
                            <li class="{{ $vanBanDen->trinh_tu_nhan_van_ban >= \Modules\VanBanDen\Entities\VanBanDen::CHUYEN_VIEN_NHAN_VB ? 'complete active' : null }}">{!! $vanBanDen->chuyenVien->canBoNhan->ho_ten ."<br/> (Chuyên viên)" !!}
                                <br><i>{{ date('d/m/Y H:i:s', strtotime($vanBanDen->chuyenVien->created_at)) }}</i>
                            </li>
                        @endif
                    </ul>
                </div>
                @endif
                @include('dieuhanhvanbanden::van-ban-den.log_xu_ly_van_ban_den', ['xuLyVanBanDen' => $vanBanDen->xuLyVanBanDen ])
                @include('dieuhanhvanbanden::van-ban-den.log_tra_lai_van_ban', ['xuLyVanBanDen' => $vanBanDen->xuLyVanBanDenTraLai ])
                @include('dieuhanhvanbanden::van-ban-den.log_lanh_dao_chi_dao', ['lanhDaoChiDao' => $vanBanDen->lanhDaoDaChiDao ])
                @include('dieuhanhvanbanden::van-ban-den.can_bo_du_hop', ['canBoDuHop' => $vanBanDen->lanhDaoDuHop($vanBanDen->id),'loaiVanBanGiayMoi'=>$loaiVanBanGiayMoi,'vanBanDen'=>$vanBanDen ])
                @include('dieuhanhvanbanden::van-ban-den.log_chuyen_don_vi_chu_tri', ['chuyenNhanVanBanDonViChuTri' => $vanBanDen->donViChuTri ])
                @include('dieuhanhvanbanden::van-ban-den.log_chuyen_don_vi_phoi_hop', ['chuyenNhanVanBanDonViPhoiHop' => $vanBanDen->donViPhoiHop ])
                @include('dieuhanhvanbanden::van-ban-den.log_gia_han_van_ban', ['giaHanVanBanDonVi' => $vanBanDen->giaHanVanBan ])
                @include('dieuhanhvanbanden::van-ban-den.log_chuyen_vien_phoi_hop', ['danhSachPhoiHopGiaiQuyet' => $vanBanDen->chuyenVienPhoiHop])
                @include('dieuhanhvanbanden::van-ban-den.log_don_vi_phoi_hop_giai_quyet', ['danhSachDonViPhoiHopGiaiQuyet' => $vanBanDen->donViPhoiHopGiaiquyet])
                @include('dieuhanhvanbanden::van-ban-den.log-hoan_thanh_cong_viec')
                @include('dieuhanhvanbanden::van-ban-den.log_du_thao_van_ban')
                @include('dieuhanhvanbanden::van-ban-den.log_van_ban_di')
                @include('dieuhanhvanbanden::van-ban-den.log_luu_vet')
                @include('dieuhanhvanbanden::van-ban-den.log_da_xem', ['vanBanDaXem' => $vanBanDen->vanBanDaXem ])

            <!--giai quyet van ban-->
                @if ($vanBanDen->trinh_tu_nhan_van_ban != \Modules\VanBanDen\Entities\VanBanDen::HOAN_THANH_VAN_BAN)
                    @include('dieuhanhvanbanden::van-ban-den._form_giai_quyet')
                @endif

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

        $('.btn-remove-file').on('click', function () {
            let id = $(this).data('id');

            if (confirm('Bạn muốn xóa dữ liệu này?')) {
                $.ajax({
                    url: APP_URL + '/remove-file/' + id,
                    type: 'POST',
                })
                    .done(function (response) {
                        if (response.success) {
                            toastr['success'](response.message, 'Thông báo hệ thống');
                            $('.file-phoi-hop-'+id).remove();
                        }
                    })
                    .fail(function (error) {
                        toastr['error'](error.message, 'Thông báo hệ thống');
                    });
            } else{
                return false;
            }
        });

    </script>
@endsection

