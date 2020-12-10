@if ($congViecDonVi->lichCongTac->vanBanDenDonVi)
    <div class="row row-bd-bt">
        <div class="col-md-12">
            <p><b>Thông tin giấy mời:</b></p>
        </div>
        <div class="col-md-4">
            <p>
                <b>Ký hiệu:</b>{{ $congViecDonVi->lichCongTac->vanBanDenDonVi->vanBanDen->vb_so_ky_hieu }}
            </p>
            <p>
                <b>Số đến:</b>
                <b
                    class="color-red"> {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->vb_so_den }}</b>
            </p>
        </div>
        <div class="col-md-4">
            <p>
                <b>Nơi gửi
                    đến: </b> {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->co_quan_ban_hanh_id ?? null }}
            </p>
        </div>
        <div class="col-md-4">
            <p>
                <b>Loại văn bản:</b>{{ $congViecDonVi->lichCongTac->vanBanDenDonVi->vanBanDen->loaiVanBan->ten_loai_van_ban }}
            </p>
        </div>
        <div class="col-md-12">
            <p>
                <b>Trích yếu: </b> {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->vanBanDen->vb_trich_yeu }}
            </p>
        </div>
        @if (isset($congViecDonVi->lichCongTac->vanBanDenDonVi->vanBanDen->vanBanDenFile))
            <div class="col-md-4">
                <p>
                    <b>File:</b>
                @foreach($congViecDonVi->lichCongTac->vanBanDenDonVi->vanBanDen->vanBanDenFile as  $file)
                    <div class="detail-file-name giai-quyet-file">
                        <a href="{{ $file->getUrlFile() }}"
                           target="popup"
                           class="detail-file-name seen-new-window">{{ $file->ten_file }}</a>
                    </div>
                    @endforeach
                    </p>
            </div>
        @endif
    </div>
    @if ($congViecDonVi->lichCongTac->vanBanDenDonVi->so_van_ban_id == SO_VB_GIAY_MOI)
        <div class="row row-bd-bt">
            <div class="col-md-12 form-group">
                <label class="col-form-label">Nội dung họp:</label> {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->noi_dung_hop ?? null }}
                <br>
                <i>
                    (Vào hồi {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->gio_hop_chinh }}
                    ngày {{ date('d/m/Y', strtotime($congViecDonVi->lichCongTac->vanBanDenDonVi->ngay_hop_chinh)) }}
                    , tại {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->dia_diem_chinh }})
                </i>
            </div>
        </div>
    @endif
    @if (isset($congViecDonVi->lichCongTac->vanBanDenDonVi->noi_dung))
        <div class="row row-bd-bt">
            <div class="col-md-12 form-group">
                <label class="col-form-label">Nội dung :</label> {{ $congViecDonVi->lichCongTac->vanBanDenDonVi->noi_dung ?? null }}
            </div>
        </div>
    @endif
@endif
