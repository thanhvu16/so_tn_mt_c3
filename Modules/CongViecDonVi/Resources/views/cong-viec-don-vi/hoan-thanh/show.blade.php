@extends('admin::layouts.master')
@section('page_title', 'Chi tiết công việc')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chi tiết công việc</h3>
                    </div>
                    <div class="box-body">
                        @include('congviecdonvi::cong-viec-don-vi.log_chi_tiet_van_ban', ['congViecDonVi' => $congViecDonVi])
                        <div class="row row-bd-bt">
                            <div class="col-md-12 form-group">
                                <label class="col-form-label">Nội dung công việc:</label>
                                {{ $congViecDonVi->noi_dung_cuoc_hop ?? '' }}
                                <br>
                                <span class="font-bold">Tệp tin:</span>
                                @if (isset($congViecDonVi->congViecDonViFile))
                                    @foreach($congViecDonVi->congViecDonViFile as $key => $file)
                                        <a href="{{ $file->getUrlFile() }}"
                                           target="popup"
                                           class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                            ]</a>
                                        @if (count($congViecDonVi->congViecDonViFile)-1 != $key)
                                            &nbsp;|&nbsp;
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if (!empty($congViecDonVi->chuyenNhanCongViecDonVi))
                            @foreach($congViecDonVi->chuyenNhanCongViecDonVi as $chuyenNhanCongViecDonVi)
                                <div class="row row-bd-bt mt-2">
                                    <div class="col-md-12 form-group">
                                        <label class="col-form-label">Nội dung đầu việc đơn
                                            vị:</label> {{ $chuyenNhanCongViecDonVi->noi_dung }}
                                        <br>
                                    </div>
                                </div>
                                <div class="row">
                                    @include('congviecdonvi::cong-viec-don-vi.log_trinh_tu_chuyen_nhan_cong_viec', ['chuyenNhanCongViecDonVi' => $chuyenNhanCongViecDonVi])
                                    @include('congviecdonvi::cong-viec-don-vi.log_gia_han_cong_viec', ['chuyenNhanCongViecDonVi' => $chuyenNhanCongViecDonVi])
                                    @if (Request::get('ph'))
                                        @include('congviecdonvi::cong-viec-don-vi.log_don_vi_phoi_hop_giai_quyet', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->getPhoiHopDaGiaiQuyet(Request::get('ph')) ])
                                    @else
                                        @include('congviecdonvi::cong-viec-don-vi.log_don_vi_phoi_hop_giai_quyet', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->getPhoiHopDaGiaiQuyet(null) ])
                                    @endif

                                    @include('congviecdonvi::cong-viec-don-vi.log_chuyen_vien_phoi_hop_giai_quyet', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->chuyenVienPhoiHop() ])

                                    @include('congviecdonvi::cong-viec-don-vi.log_giai_quyet_cong_viec', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->chuyenVienPhoiHop() ])
                                    <div class="col-md-12">
                                        <hr class="border-dashed show">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


