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
                <div class="col-md-12 row-bd-bt">
                    <div class="col-md-4">
                        <p>
                            <b>Số Ký hiệu: </b>{{ $vanBanDen->so_ky_hieu ?? null }}
                        </p>
                        <p>
                            <b>Loại văn bản:</b> {{ isset($vanBanDen->loaiVanBan) ? $vanBanDen->loaiVanBan->ten_loai_van_ban : null }}
                        </p>
                        <p>
                            <b>Số đến:</b>
                            <b class="color-red"> {{ $vanBanDen->so_den ?? null }}</b>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p>
                            <b>Ngày
                                ban hành:</b> {{ !empty($vanBanDen->ngay_ban_hanh) ? date('d/m/Y', strtotime($vanBanDen->ngay_ban_hanh)) : null }}
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
                            <b>Độ mật:</b> {{ isset($vanBanDen->doBaoMat) ? $vanBanDen->doBaoMat->ten_muc_do : null }}
                        </p>
                        <p>
                            <b>Độ khẩn:</b> {{ isset($vanBanDen->doKhan) ? $vanBanDen->doKhan->ten_muc_do : null }}
                        </p>
                    </div>
                    @if (!empty($loaiVanBanGiayMoi) && $vanBanDen->so_van_ban_id == $loaiVanBanGiayMoi->id)
                    <div class="col-md-12">
                        <label class="col-form-label">Nội dung họp:</label> {{ $vanBanDen->noi_dung_hop ?? ' V/v Hội nghị trực tuyến của Chính phủ ' }}
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
                <div class="col-md-12 mt-2">
                    <a class="btn btn-default go-back" data-original-title="" title="">Quay lại &gt;&gt;</a>
                </div>
            </div>
        </div>
    </section>
@endsection

