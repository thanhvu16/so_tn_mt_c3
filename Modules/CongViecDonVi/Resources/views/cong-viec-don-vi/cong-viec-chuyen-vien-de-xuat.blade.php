@extends('admin::layouts.master')
@section('page_title', 'Công việc đề xuất')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Đề xuất công việc</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{route('luuCongViecDeXuat')}}" method="post" enctype="multipart/form-data" class="form-row">
                            @csrf
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="pho-phong-chu-tri" class="col-form-label">Chọn trưởng phòng xem xét <span class="color-red">*</span></label>
                                        <select name="truong_phong"
                                                class="form-control select2" required>
                                                @forelse($nguoinhan as $chuyenVien)
                                                    <option
                                                        value="{{ $chuyenVien->id }}">{{ $chuyenVien->ho_ten }}</option>
                                                @empty
                                                @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-3 ">
                                        <label for="han_xu_ly" class="col-form-label">Hạn xử lý <span class="color-red">*</span></label>
                                        <div id="">
                                            <input class="form-control" required="" id="han_xu_ly" value="" type="date" name="han_xu_ly">
                                        </div>
                                    </div>
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

                                </div>
                            </div>
                            <div class="col-md-12 mt-2 text-right">
                                <div class="row">
                                        <button type="submit" class="btn btn-primary waves-effect text-uppercase">
                                            <i class="fa fa-paper-plane-o"></i> Gửi
                                        </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

