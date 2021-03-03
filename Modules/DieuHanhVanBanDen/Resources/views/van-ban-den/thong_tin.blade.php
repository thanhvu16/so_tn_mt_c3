@if (!empty($type) && $type == 'gia_han')
    @if($vanBanDen->hasChild())
        @if (!empty($vanBanDen->hasChild()->noi_dung))
            <p>
                <b>Nội dung:</b> <i>{{ $vanBanDen->hasChild()->noi_dung }}</i>
            </p>
        @endif
        <p class="text-initial">- Nơi gửi
            đến: {{ $vanBanDen->hasChild()->co_quan_ban_hanh ?? null }}</p>
        <p class="text-initial">- Số đến: <span
                class="color-red text-bold">{{ $vanBanDen->hasChild()->so_den ?? null }}</span></p>
        <p class="text-initial">- Ngày
            nhập: {{  !empty($vanBanDen->hasChild()->created_at) ? date('d/m/Y', strtotime($vanBanDen->hasChild()->created_at)) : null }}</p>
        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->hasChild()->loai_van_ban_id == $loaiVanBanGiayMoi->id)
            @if (!empty($vanBanDen->hasChild()->lichCongTac->lanhDao))
                <p class="text-initial">
                    <b>- Lãnh đạo dự họp:</b><i>{{ $vanBanDen->hasChild()->lichCongTac->lanhDao->ho_ten ?? null }}</i>
                </p>
            @endif
        @endif
        @if ($vanBanDen->hasChild()->nguoiDung)
            <p class="text-initial">- Cán bộ nhập: {{ $vanBanDen->hasChild()->nguoiDung->ho_ten  }}</p>
        @endif
        @if(!empty($vanBanDen->hasChild()->han_xu_ly))
            <p class="text-initial">
                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->hasChild()->han_xu_ly)) }}
                </b>
            </p>
        @endif

        @if (isset($vanBanDen->hasChild()->vanBanDenFile))
            @foreach($vanBanDen->hasChild()->vanBanDenFile as $key => $file)
                <a href="{{ $file->getUrlFile() }}"
                   target="popup"
                   class="detail-file-name seen-new-window">[{{ $file->ten_file }}]</a>
                @if (count($vanBanDen->hasChild()->vanBanDenFile)-1 != $key)
                    &nbsp;|&nbsp;
                @endif
            @endforeach
        @endif
    @else
        @if (!empty($vanBanDen->noi_dung))
            <p>
                <b>Nội dung:</b> <i>{{ $vanBanDen->noi_dung }}</i>
            </p>
        @endif
        <p class="text-initial">- Nơi gửi
            đến: {{ $vanBanDen->co_quan_ban_hanh ?? null }}</p>
        <p class="text-initial">- Số đến: <span class="color-red text-bold">{{ $vanBanDen->so_den ?? null }}</span></p>
        <p class="text-initial">- Ngày
            nhập: {{  !empty($vanBanDen->created_at) ? date('d/m/Y', strtotime($vanBanDen->created_at)) : null }}</p>
        @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
            @if (!empty($vanBanDen->lichCongTac->lanhDao))
                <p class="text-initial">
                    <b>- Lãnh đạo dự họp:</b><i>{{ $vanBanDen->lichCongTac->lanhDao->ho_ten ?? null }}</i>
                </p>
            @endif
        @endif
        @if ($vanBanDen->nguoiDung)
            <p class="text-initial">- Cán bộ nhập: {{ $vanBanDen->nguoiDung->ho_ten  }}</p>
        @endif
        @if(!empty($vanBanDen->han_xu_ly))
            <p class="text-initial">
                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) }}
                </b>
            </p>
        @endif

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
    @endif
@else
    @if($vanBanDen->hasChild)
        @if (!empty($vanBanDen->hasChild->noi_dung))
            <p>
                <b>Nội dung:</b> <i>{{ $vanBanDen->hasChild->noi_dung }}</i>
            </p>
        @endif
        <p class="text-initial">- Nơi gửi
            đến: {{ $vanBanDen->hasChild->co_quan_ban_hanh ?? null }}</p>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <p class="text-initial">- Số đến: <span
                        class="color-red text-bold">{{ $vanBanDen->hasChild->so_den ?? null }}</span></p>
                <p class="text-initial"> - Ngày ban
                    hành: {{  !empty($vanBanDen->hasChild->ngay_ban_hanh) ? date('d/m/Y', strtotime($vanBanDen->hasChild->ngay_ban_hanh)) : null }}</p>
                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->hasChild->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                    @if (!empty($vanBanDen->hasChild->lichCongTac->lanhDao))
                        <p class="text-initial">
                            <b>- Lãnh đạo dự
                                họp:</b><i>{{ $vanBanDen->hasChild->lichCongTac->lanhDao->ho_ten ?? null }}</i>
                        </p>
                    @endif
                @endif
                @if ($vanBanDen->hasChild->nguoiDung)
                    <p class="text-initial">- Cán bộ nhập: {{ $vanBanDen->hasChild->nguoiDung->ho_ten  }}</p>
                @endif
            </div>
            <div class="col-md-6 col-sm-12">
                <p> - Tệp tin:
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
            @if(!empty($vanBanDen->hasChild->han_xu_ly))
                <!--gia han cua lanh dao-->
                    @if (auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]) && empty(auth::user()->donVi->cap_xa))
                        @if (!empty($vanBanDen->giaHanXuLy->han_xu_ly))
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->giaHanXuLy->han_xu_ly)) }}
                                </b>
                            </p>
                        @else
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->hasChild->han_xu_ly)) }}
                                </b>
                            </p>
                        @endif
                    @else
                        @if (!empty($vanBanDen->giaHanXuLy->han_xu_ly_moi))
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->giaHanXuLy->han_xu_ly_moi)) }}
                                </b>
                            </p>
                        @else
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->hasChild->han_xu_ly)) }}
                                </b>
                            </p>
                        @endif
                    @endif
                @endif
                <p class="text-initial">- Ngày
                    nhập: {{  !empty($vanBanDen->hasChild->created_at) ? date('d/m/Y', strtotime($vanBanDen->hasChild->created_at)) : null }}</p>
            </div>
        </div>
    @else
        @if (!empty($vanBanDen->noi_dung))
            <p>
                <b>Nội dung:</b> <i>{{ $vanBanDen->noi_dung }}</i>
            </p>
        @endif
        <p class="text-initial">- Nơi gửi
            đến: {{ $vanBanDen->co_quan_ban_hanh ?? null }}</p>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <p class="text-initial">- Số đến: <span
                        class="color-red text-bold">{{ $vanBanDen->so_den ?? null }}</span></p>
                <p class="text-initial"> - Ngày ban
                    hành: {{  !empty($vanBanDen->ngay_ban_hanh) ? date('d/m/Y', strtotime($vanBanDen->ngay_ban_hanh)) : null }}</p>
                @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->loai_van_ban_id == $loaiVanBanGiayMoi->id)
                    @if (!empty($vanBanDen->lichCongTac->lanhDao))
                        <p class="text-initial">
                            <b>- Lãnh đạo dự
                                họp:</b><i>{{ $vanBanDen->lichCongTac->lanhDao->ho_ten ?? null }}</i>
                        </p>
                    @endif
                @endif
                @if ($vanBanDen->nguoiDung)
                    <p class="text-initial">- Cán bộ nhập: {{ $vanBanDen->nguoiDung->ho_ten  }}</p>
                @endif
            </div>
            <div class="col-md-6 col-sm-12">
                <p> - Tệp tin:
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
            @if(!empty($vanBanDen->han_xu_ly))
                <!--gia han cua lanh dao-->
                    @if (auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]) && empty(auth::user()->donVi->cap_xa))
                        @if (!empty($vanBanDen->giaHanXuLy->han_xu_ly))
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->giaHanXuLy->han_xu_ly)) }}
                                </b>
                            </p>
                        @else
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) }}
                                </b>
                            </p>
                        @endif
                    @else
                        @if (!empty($vanBanDen->han_xu_ly_moi))
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->giaHanXuLy->han_xu_ly_moi)) }}
                                </b>
                            </p>
                        @else
                            <p class="text-initial">
                                - <b>Hạn xử lý: {{ date('d/m/Y', strtotime($vanBanDen->han_xu_ly)) }}
                                </b>
                            </p>
                        @endif
                    @endif
                @endif
                <p class="text-initial">- Ngày
                    nhập: {{  !empty($vanBanDen->created_at) ? date('d/m/Y', strtotime($vanBanDen->created_at)) : null }}</p>
            </div>
        </div>
    @endif
@endif
