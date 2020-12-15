
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
                                <div class="col-md-12">
{{--                                    @include('congviecdonvi::cong-viec-don-vi.log_chi_tiet_van_ban', ['congViecDonVi' => $chuyenNhanCongViecDonVi->congViecDonVi])--}}
                                    <div class="row row-bd-bt">
                                        <div class="col-md-12 form-group">
                                            <label class="col-form-label">Nội dung công việc:</label>
                                            {{ $chuyenNhanCongViecDonVi->congViecDonVi->noi_dung_cuoc_hop ?? '' }}
                                            <br>
                                            <span class="font-bold">Tệp tin:</span>
                                            @if (isset($chuyenNhanCongViecDonVi->congViecDonVi->congViecDonViFile))
                                                @foreach($chuyenNhanCongViecDonVi->congViecDonVi->congViecDonViFile as $key => $file)
                                                    <a href="{{ $file->getUrlFile() }}"
                                                       target="popup"
                                                       class="detail-file-name seen-new-window">[{{ $file->ten_file }}
                                                        ]</a>
                                                    @if (count($chuyenNhanCongViecDonVi->congViecDonVi->congViecDonViFile)-1 != $key)
                                                        &nbsp;|&nbsp;
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row row-bd-bt">
                                        <div class="col-md-12 form-group">
                                            <label class="col-form-label">Nội dung đầu việc đơn
                                                vị:</label> {{ $chuyenNhanCongViecDonVi->noi_dung }}
                                            <br>
                                        </div>
                                    </div>
                                    @include('congviecdonvi::cong-viec-don-vi.log_trinh_tu_chuyen_nhan_cong_viec', ['chuyenNhanCongViecDonVi' => $chuyenNhanCongViecDonVi])
                                    @include('congviecdonvi::cong-viec-don-vi.log_gia_han_cong_viec', ['chuyenNhanCongViecDonVi' => $chuyenNhanCongViecDonVi])

                                    @if (Request::get('ph'))
                                        @include('congviecdonvi::cong-viec-don-vi.log_don_vi_phoi_hop_giai_quyet', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->getPhoiHopDaGiaiQuyet(Request::get('ph')) ])
                                    @else
                                        @include('congviecdonvi::cong-viec-don-vi.log_don_vi_phoi_hop_giai_quyet', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->getPhoiHopDaGiaiQuyet(null) ])
                                    @endif

                                    @include('congviecdonvi::cong-viec-don-vi.log_chuyen_vien_phoi_hop_giai_quyet', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->chuyenVienPhoiHop() ])
                                    @include('congviecdonvi::cong-viec-don-vi.log_giai_quyet_cong_viec', ['danhSachPhoiHopGiaiQuyet' => $chuyenNhanCongViecDonVi->chuyenVienPhoiHop() ])

                                    <div class="row row-bd-bt">
                                        @if (Request::get('type') && Request::get('type') == 'cv_phoi_hop' && empty($chuyenNhanCongViecDonVi->chuyenVienphoiHopGiaiQuyet()))
                                            <div class="col-md-12 phoi-hop-giai-quyet">
                                                <form action="{{ route('cong-viec-don-vi-phoi-hop.store') }}" method="post"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="chuyen_nhan_cong_viec_don_vi_id"
                                                           value="{{ $chuyenNhanCongViecDonVi->id }}">
                                                    <input type="hidden" name="type" value="2">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-noi-dung">Nội dung </label>
                                                            <textarea name="noi_dung"
                                                                      placeholder="nhập nội dung kết quả"
                                                                      cols="5" rows="3"
                                                                      class="form-control noi-dung-cong-viec"
                                                                      aria-required="true" required=""></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="row">
                                                            <div class="increment">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="ten_file">Tên tệp</label>
                                                                        <input type="text" class="form-control pho-phong-file"
                                                                               name="txt_file[]" value=""
                                                                               placeholder="Nhập tên file...">
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label for="url-file">Chọn tệp tin</label>
                                                                        <div class="form-line input-group control-group">
                                                                            <input type="file" id="url-file" name="ten_file[]"
                                                                                   class="form-control">
                                                                            <div class="input-group-btn">
                                        <span class="btn btn-info"
                                              onclick="multiUploadFile('ten_file[]')"
                                              type="button">
                                            <i class="fa fa-plus"></i> thêm</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <button type="submit"
                                                                class="btn btn-primary waves-effect text-uppercase">
                                                            Gửi
                                                        </button>
                                                        <a href=""
                                                           title="hủy" class="btn btn-default go-back">Hủy</a>
                                                    </div>
                                                </form>
                                            </div>
                                        @elseif (Request::get('type') && Request::get('type') == 'phoi_hop' && empty($chuyenNhanCongViecDonVi->chuyenVienDonViPhoiHopGiaiQuyet()))
                                            <div class="col-md-12 phoi-hop-giai-quyet">
                                                <form action="{{ route('cong-viec-don-vi-phoi-hop.store') }}" method="post"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="chuyen_nhan_cong_viec_don_vi_id"
                                                           value="{{ $chuyenNhanCongViecDonVi->id }}">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-noi-dung">Nội dung </label>
                                                            <textarea name="noi_dung"
                                                                      placeholder="nhập nội dung kết quả"
                                                                      cols="5" rows="3"
                                                                      class="form-control noi-dung-cong-viec"
                                                                      aria-required="true" required=""></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="row">
                                                            <div class="increment">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="ten_file">Tên tệp</label>
                                                                        <input type="text" class="form-control pho-phong-file"
                                                                               name="txt_file[]" value=""
                                                                               placeholder="Nhập tên file...">
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label for="url-file">Chọn tệp tin</label>
                                                                        <div class="form-line input-group control-group">
                                                                            <input type="file" id="url-file" name="ten_file[]"
                                                                                   class="form-control">
                                                                            <div class="input-group-btn">
                                        <span class="btn btn-info"
                                              onclick="multiUploadFile('ten_file[]')"
                                              type="button">
                                            <i class="fa fa-plus"></i> thêm</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <button type="submit"
                                                                class="btn btn-primary waves-effect text-uppercase">
                                                            Gửi
                                                        </button>
                                                        <a href=""
                                                           title="hủy" class="btn btn-default go-back">Hủy</a>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            @if (!empty(Request::get('xuly')) && Request::get('xuly') == 'true')
                                                @if (count($chuyenNhanCongViecDonVi->giaiQuyetCongViec) == 0 || !empty($chuyenNhanCongViecDonVi->giaiQuyetCongViecTraLai()))
                                                    <div class="col-md-12 truc-tiep-giai-quyet">
                                                        <form action="{{ route('giai-quyet-cong-viec.store') }}"
                                                              method="post"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="chuyen_nhan_cong_viec_don_vi_id"
                                                                   value="{{ $chuyenNhanCongViecDonVi->id }}">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label class="form-noi-dung">Nhập kết quả <span
                                                                            class="color-red">(*)</span></label>
                                                                    <textarea name="noi_dung"
                                                                              placeholder="nhập kết quả.."
                                                                              cols="5" rows="3"
                                                                              class="form-control noi-dung-cong-viec"
                                                                              aria-required="true" required=""></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="row">
                                                                        <div class="increment">
                                                                            <div class="row">
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="ten_file">Tên tệp</label>
                                                                                    <input type="text"
                                                                                           class="form-control pho-phong-file"
                                                                                           name="txt_file[]" value=""
                                                                                           placeholder="Nhập tên file...">
                                                                                </div>
                                                                                <div class="form-group col-md-8">
                                                                                    <label for="url-file">Chọn tệp
                                                                                        tin</label>
                                                                                    <div
                                                                                        class="form-line input-group control-group">
                                                                                        <input type="file" id="url-file"
                                                                                               name="ten_file[]"
                                                                                               class="form-control">
                                                                                        <div class="input-group-btn">
                                                                            <span class="btn btn-info"
                                                                                  onclick="multiUploadFile('ten_file[]')"
                                                                                  type="button">
                                                                                <i class="fa fa-plus"></i> thêm</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="lanh-dao-duyet">Cán bộ
                                                                            duyệt</label>
                                                                        <select name="lanh_dao_duyet_id"
                                                                                class="form-control select2-search">
                                                                            @foreach($danhSachLanhDao as $LanhDao)
                                                                                <option
                                                                                    value="{{ $LanhDao->id }}">{{ $LanhDao->ho_ten }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-12">
                                                                <div class="row">
                                                                    <button type="submit"
                                                                            class="btn btn-primary waves-effect text-uppercase">
                                                                        Lưu
                                                                    </button>
                                                                    <a href=""
                                                                       title="hủy" class="btn btn-default go-back">Hủy</a>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
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
                $('input[name="status_action"]').on('click', function () {
                    let status = $(this).val();
                    if (status == 1) {
                        $('.form-du-thao').removeClass('hide');
                        $('.truc-tiep-giai-quyet').addClass('hide')
                    } else {
                        $('.truc-tiep-giai-quyet').removeClass('hide');
                        $('.form-du-thao').addClass('hide');
                    }
                });

                $('.chon-loai-van-ban').on('change', function () {
                    let ten = $(this).find('option:selected').attr('data-ten');
                    console.log(ten);
                    $('input[name="so_ky_hieu"]').val(ten);
                    $('.ket-qua').val(ten);
                });

                $('.chon-nguoi-ky').on('change', function () {
                    let chucVu = $(this).find('option:selected').data('chuc-vu');
                    if (chucVu != 0) {
                        $('input[name="chuc_vu"]').val(chucVu);
                    } else {
                        $('input[name="chuc_vu"]').val();
                    }
                })

            </script>
@endsection

