@extends('admin::layouts.master')
@section('page_title', 'Công việc phòng ban')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tạo công việc</h3>
                    </div>
                    <div class="box-body">
                    <form action="{{route('tao-cong-viec-don-vi.store')}}" method="post" enctype="multipart/form-data" class="form-row">
                        @csrf
                        <input type="hidden" name="lich_cong_tac_id" value="{{ Request::get('lich_cong_tac_id') ?? '' }}">
                            <div class="col-md-12">
                                <label class="form-tham-muu h5 bold">Nội dung công việc<span
                                        class="color-red"> *</span></label>
                                <textarea name="noi_dung" placeholder="nhập nội dung công việc" cols="30"
                                          rows="3"
                                          class="form-control" aria-required="true"
                                          required=""></textarea>
                            </div>
                            <div class="dau-viec col-md-12 row-bd-bt">
                                <div class="dau-viec-chi-tiet pb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="h5 bold">Nội dung giao đơn vị <span
                                                    class="color-red">*</span></label>
                                            <textarea name="noi_dung_dau_viec[]" id="" rows="2"
                                                      class="form-control"
                                                      placeholder="Nhập nội dung của đơn vị" required></textarea>
                                        </div>
                                            <div class="col-md-3">
                                                <label for="don-vi-chu-tri" class="h5 bold">Đơn vị chủ trì <span
                                                        class="color-red">*</span></label>
                                                <select name="don_vi_chu_tri" id="don-vi-chu-tri"
                                                        class="form-control don-vi-chu-tri multiple-select select2-search"
                                                        required data-placeholder="Chọn đơn vị chủ trì"
                                                        onchange="selectDonViAppend()">
                                                    @foreach($donViChuTri as $donViChutri)
                                                        <option
                                                            value="{{ $donViChutri->id }}">{{ $donViChutri->ten_don_vi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        <div class="col-md-3">
                                            <label for="don-vi-phoi-hop" class="h5 bold">Đơn vị phối hợp</label>
                                            <select name="don_vi_phoi_hop[]" id="don-vi-phoi-hop"
                                                    class="form-control select2"
                                                    multiple
                                                    data-placeholder="Chọn đơn vi phối hợp">
                                                @foreach($danhSachDonViChutri as $donViChutri)
                                                    <option
                                                        value="{{ $donViChutri->id }}">{{ $donViChutri->ten_don_vi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                            <div class="col-md-3 mt-2">
                                                <label for="pho-phong-chu-tri" class="col-form-label">Chọn phó phòng chủ trì</label>
                                                <select name="pho_phong_id"
                                                        id="pho-phong-chu-tri"
                                                        class="form-control select2-search pho-phong"
                                                        placeholder="Chọn phó phòng chủ trì">
                                                    <option value="">Chọn phó phòng chủ trì</option>
                                                    @forelse($danhSachPhoPhong as $phoPhong)
                                                        <option
                                                            value="{{ $phoPhong->id }}">{{ $phoPhong->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>

                                            <div class="col-md-3 mt-2">
                                                <label for="pho-phong-chu-tri" class="col-form-label">Chọn chuyên viên thực hiện</label>
                                                <select name="chuyen_vien_id"
                                                        id="chuyen-vien"
                                                        class="form-control select2"
                                                        data-placeholder="Chọn chuyên viên thực hiện"
                                                >
                                                    <option value="">Chọn chuyên viên thực hiện</option>
                                                    @forelse($danhSachChuyenVien as $chuyenVien)
                                                        <option
                                                            value="{{ $chuyenVien->id }}">{{ $chuyenVien->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>

                                            <div class="col-md-3 mt-2">
                                                <label for="pho-phong-chu-tri" class="col-form-label">Chọn chuyên viên phối hợp</label>
                                                <select
                                                    name="chuyen_vien_phoi_hop_id[]"
                                                    id="chuyen-vien-phoi-hop"
                                                    class="form-control select2"
                                                    data-placeholder="Chọn chuyên viên phối hợp"
                                                    multiple>
                                                    @forelse($danhSachChuyenVien as $chuyenVien)
                                                        <option
                                                            value="{{ $chuyenVien->id }}">{{ $chuyenVien->ho_ten }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>

                                        <div class="col-md-3 mt-2">
                                            <label for="han_xu_ly" class="col-form-label">Hạn xử lý <span class="color-red">*</span></label>
                                            <div id="">
                                                <input class="form-control" required="" id="han_xu_ly" value="" type="date" name="han_xu_ly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-6">
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
                                                               class="form-control" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"/>
                                                        <div class="input-group-btn">
                                                                    <span class="btn btn-primary" onclick="multiUploadFile('ten_file[]')"
                                                                          type="button">
                                                                        <i class="fa fa-plus"></i> thêm</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                            <button type="submit" class="btn btn-primary waves-effect text-uppercase">
                                               <i class="fa fa-paper-plane-o"></i> Thêm mới
                                            </button>
{{--                                            <a title="hủy"--}}
{{--                                               class="btn btn-default go-back">Hủy</a>--}}
                                    </div>
{{--                                        <div class="col-md-6 text-right">--}}
{{--                                            <label for="">&nbsp;</label><br>--}}
{{--                                            <span class="btn btn-sm btn-success add-row">  + </span>--}}
{{--                                            <span> Nhấn Vào (+) để thêm nội dung </span>--}}
{{--                                        </div>--}}
                                </div>
                            </div>
                        <div class="col-md-12 mt-2 text-center">
                            <div class="row">

                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')

    <script type="text/javascript">
        //add more job
        var stt = 0;
        var lanhDaoChuTri = '#lanh-dao-chu-tri';
        var lanhDaoChuTriClass = '.lanh-dao-chu-tri';

        var lanhDaoPhoiHop = '#lanh-dao-phoi-hop';
        var lanhDaoPhoiHopClass = '.lanh-dao-phoi-hop';
        var lanhDaoPhoiHopName = 'select[name="lanh_dao_phoi_hop[]"]';

        var donViChuTri = '#don-vi-chu-tri';
        var donViChuTriClass = '.don-vi-chu-tri';

        var donViPhoiHop = '#don-vi-phoi-hop';
        var donViPhoiHopClass = '.don-vi-phoi-hop';


        var keyItem = [];

        $('.add-row').on('click', function () {
            stt++;
            donViChuTri = `#don-vi-chu-tri-${stt}`;
            donViChuTriClass = `.don-vi-chu-tri-${stt}`;

            donViPhoiHop = `#don-vi-phoi-hop-${stt}`;
            donViPhoiHopClass = `.don-vi-phoi-hop-${stt}`;


            if (keyItem.indexOf(stt) === -1) {
                keyItem.push(stt);
            }

            $.ajax({
                url: APP_URL + '/data-don-vi-chu-tri',
                type: 'GET',
                beforeSend: showLoading(),
            })
                .done(function (response) {
                    hideLoading();
                    let danhSachDonViChutri = response.danhSachDonViChutri;

                    let dataDonVi = danhSachDonViChutri.map(function (donVi) {
                        return `<option value="${donVi.ma_id}">${donVi.ten_don_vi}</option>`;
                    });


                    let data = `<div class="dau-viec-chi-tiet pb-3">
                        <div class="row">
                            <div class="col-md-6">
                                    <label class="h5 bold">Nội dung giao đơn vị <span
                                            class="color-red">*</span></label>
                                    <textarea name="noi_dung_dau_viec[]" rows="2"
                                              class="form-control"
                                              placeholder="Nhập nội dung của đơn vị"></textarea>
                            </div>
                            <div class="col-md-3">
                                <label for="don-vi-chu-tri-${stt}" class="h5 bold">Đơn vị chủ trì <span
                                            class="color-red">*</span></label>
                                    <select name="don_vi_chu_tri_${stt}" id="don-vi-chu-tri-${stt}"
                                            class="form-control multiple-select don-vi-chu-tri-${stt}"
                                            required data-placeholder="Chọn đơn vị chủ trì" onchange="selectDonViAppend()">
                                            <option value="">Chọn đơn vị chủ trì</option>
                                            ${dataDonVi}
                                    </select>
                            </div>
                            <div class="col-md-3">
                                <label for="don-vi-phoi-hop-${stt}" class="h5 bold">Đơn vị phối hợp <span
                                            class="color-red">*</span></label>
                                    <select name="don_vi_phoi_hop_${stt}[]" id="don-vi-phoi-hop-${stt}"
                                            class="form-control multiple-select don-vi-phoi-hop-${stt}"
                                            multiple
                                            required data-placeholder="Chọn đơn vi phối hợp">
                                    </select>
                            </div>
                            <div class="col-md-3">
                                <label for="han_xu_ly_${stt}" class="col-form-label">Hạn xử lý <span class="color-red">*</span></label>
                                <div id="">
                                    <input class="form-control" required="" id="han_xu_ly_${stt}" value="" type="date" name="han_xu_ly_${stt}">
                                </div>
                            </div>
                            </div>
                        </div>`;

                    $('.dau-viec').append(data);
                    // $(lanhDaoPhoiHopName).select2();
                    $(donViChuTriClass).select2();
                    $(donViPhoiHopClass).select2();
                    // $(lanhDaoPhoiHopClass).select2();
                    // $('.dropdown-search').select2();
                })
                .fail(function (error) {
                    console.log(error);
                    toastr['error'](error.message, 'Thông báo hệ thống');
                });

        });

        function selectDonViAppend() {
            let $this = $(donViChuTri);
            let arrId = $this.find("option:selected").map(function () {
                return parseInt(this.value);
            }).get();

            if (arrId) {
                //lấy danh sach cán bộ phối hơp
                $.ajax({
                    url: APP_URL + '/get-don-vi-phoi-hop/' + JSON.stringify(arrId),
                    type: 'GET',
                })
                    .done(function (response) {
                        var html = '<option value="">chọn đơn vị phối hợp</option>';
                        if (response.success) {

                            let selectAttributes = response.data.map((function (attribute) {
                                return `<option value="${attribute.ma_id}" >${attribute.ten_don_vi}</option>`;
                            }));

                            $this.parents('.dau-viec-chi-tiet').find(donViPhoiHop).html(selectAttributes);
                        } else {
                            $this.parents('.dau-viec-chi-tiet').find(donViPhoiHop).html(html);
                        }
                    })
                    .fail(function (error) {
                        toastr['error'](error.message, 'Thông báo hệ thống');
                    });
            }

        }
    </script>
@endsection
