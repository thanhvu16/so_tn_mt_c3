@extends('admin::layouts.master')
@section('page_title', 'Tạo văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tạo văn bản đến</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row" @if($data_trung) style="padding-top: 5px;padding-bottom: 5px;" @endif>


                                @if($data_trung)
                                    <div class="col-md-4 blink_me">
                                        <h4 class="header-title mb-3 color-red ">Văn bản đã tồn tại trên hệ thống!!!
                                        </h4>
                                    </div>
                                    <div class="col-md-3" style="margin-top: -8px;">
                                        <a href="{{route('dsvanbandentumail')}}" class="btn btn-danger" >Quay lại hòm thư công</a>
                                    </div>
                                @endif
                            </div>
                            <form class="form-row"
                                  action="{{route('luuvanbantumail')}}"
                                  method="post" enctype="multipart/form-data" id="formCreateDoc">
                                @csrf

                                <div class="form-group col-md-3" id="loaivanban">
                                    <label for="loai_van_ban_id" class="col-form-label">Loại văn bản <span style="color: red">*</span></label>
                                    <select class="form-control" name="loai_van_ban"  id="loai_van_ban" autofocus required>
                                        <option value="">-- Chọn loại văn bản --</option>
                                        @foreach ($ds_loaiVanBan as $loaiVanBan)
                                            <option value="{{ $loaiVanBan->id }}"
                                                {{ isset($loaivb_email) && $loaivb_email == $loaiVanBan->id ? 'selected="selected"' : '' }}>{{ $loaiVanBan->ten_loai_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="sokyhieu" class="col-form-label">Sổ văn bản <span style="color: red">*</span></label>
                                    <select class="form-control  select-so-van-ban check-so-den-vb"
                                            data-don-vi="{{auth::user()->id}}" name="so_van_ban" required id="so_van_ban_id">
                                        <option value="">-- Chọn sổ văn bản --</option>
                                        @foreach ($ds_soVanBan as $soVanBan)
                                            <option
                                                value="{{ $soVanBan->id }}">{{ $soVanBan->ten_so_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="vb_so_den" class="col-form-label">Số đến văn bản<span style="color: red">*</span></label>
                                    <input type="text" name="so_den" class="form-control soden" value=""
                                           id="vb_so_den" readonly
                                           style="font-weight: 800;color: #F44336;cursor: not-allowed;"
                                           placeholder="Số đến văn bản">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="sokyhieu" class="col-form-label">Số ký hiệu <span style="color: red">*</span></label>
                                    <input type="text" name="so_ky_hieu"
                                           value="{{empty($data_xml) ? '': $data_xml->STRKYHIEU}}" required class="form-control file_insert"
                                           id="sokyhieu"
                                           placeholder="Số ký hiệu">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="vb_ngay_ban_hanh" class="col-form-label">Ngày ban hành <span style="color: red">*</span></label>
                                    <input class="form-control" required  id="ngay_ban_hanh"  value="{{empty($data_xml) ? '': $data_xml->STRNGAYKY}}"  name="ngay_ban_hanh" type="date">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="co_quan_ban_hanh_id" class="col-form-label">Cơ quan ban hành <span style="color: red">*</span></label>
                                    <input type="text" required name="co_quan_ban_hanh" value="{{empty($data_xml) ? '': $data_xml->STRNOIGUI}}" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="sokyhieu" class="col-form-label">Người ký <span style="color: red">*</span></label>
                                    <input type="text" required value="{{empty($data_xml) ? '': $data_xml->STRNGUOIKY}}" name="nguoi_ky"
                                           class="form-control">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cap_ban_hanh_id" class="col-form-label">Lãnh đạo tham mưu văn bản <span style="color: red">*</span></label>
                                    <select class="form-control dropdown-search" id="lanh_dao_tham_muu" name="lanh_dao_tham_muu" required>
                                        @foreach ($nguoi_dung as $nd)
                                            <option value="{{ $nd->id }}">{{ $nd->ho_ten}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="sokyhieu" class="col-form-label ">Trích yếu <span style="color: red">*</span></label>
                                    <textarea rows="3" class="form-control" required placeholder="nội dung" name="trich_yeu"
                                              type="text">{{empty($data_xml) ? $email->mail_subject:$data_xml->STRTRICHYEU}}</textarea>
                                </div>
                                @if(!empty($data_xml->STRNGAYHOP) >'2020-01-01' && $loaivb_email == 100)
                                    <div class="col-md-3" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label for="">Giờ họp <span style="color: red">*</span></label>
                                            <input type="time" required class="form-control" value="{{isset($data_xml->STRTHOIGIANHOP)? $data_xml->STRTHOIGIANHOP : ''}}" name="gio_hop_chinh">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label for="">Ngày họp <span style="color: red">*</span></label>
                                            <input type="date" required class="form-control " value="{{isset($data_xml->STRNGAYHOP)? $data_xml->STRNGAYHOP : ''}}"
                                                   name="ngay_hop_chinh" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label for="">Địa điểm <span style="color: red">*</span></label>
                                            <input type="text" required class="form-control" value="{{isset($data_xml->STRDIADIEM)? $data_xml->STRDIADIEM : ''}}"
                                                   name="dia_diem_chinh" placeholder="Địa điểm">
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-right" style="margin-top: 40px">
                                        <a class="btn btn-success btn-xs" role="button"  data-toggle="collapse"
                                           href="#collapseExample"
                                           aria-expanded="false" aria-controls="collapseExample"><i
                                                class="fa fa-plus"></i>
                                        </a>
                                        <b class="text-danger"> Hiển thị thêm nội dung</b>
                                    </div>

                                    <div class="col-md-12 collapse in" id="collapseExample">
                                        <div class="col-md-12  gmoi layout3 ">
                                            <div class="row" style="margin-top:-15px;margin-left: 0px;">
                                                <hr style="border: 0.5px solid #3c8dbc">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px">
                                                    <label for="detail-job">Nội dung họp <span style="color: red">*</span></label>
                                                    <textarea name="noi_dung_hop_con[]" placeholder="nhập nội dung công việc" rows="3"
                                                              class="form-control no-resize noi-dung-chi-dao"
                                                              aria-required="true">{{ old('noi_dung_hop', isset($vanban) ? $vanban->noi_dung_hop : '') }}</textarea>
                                                </div>
                                                <div class="col-md-4" style="margin-top: 10px">
                                                    <div class="form-group">
                                                        <label for="">Giờ họp</label>
                                                        <input type="time" class="form-control"  value="{{ isset($vanban) ? $vanban->gio_hop_con : '' }}" name="gio_hop_con[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="margin-top: 10px">
                                                    <div class="form-group">
                                                        <label for="">Ngày họp</label>
                                                        <input type="date" class="form-control"
                                                               value="{{ isset($vanban) ? $vanban->ngay_hop_con : '' }}"
                                                               name="ngay_hop_con[]" placeholder="Nhập ngày họp">
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="margin-top: 10px">
                                                    <div class="form-group">
                                                        <label for="">Địa điểm</label>
                                                        <input type="text" value="{{ isset($vanban) ? $vanban->dia_diem_con : '' }}"
                                                               placeholder="Nhập địa điểm" class="form-control" name="dia_diem_con[]">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="input-group-btn text-right {{ isset($vanban) ? 'hidden' : '' }}">
                                            <a class="btn btn-primary btn-xs" role="button"
                                               aria-expanded="false" ><span class="btn btn-primary" onclick="themgiaymoi('noi_dung_hop_con[]')" type="button">
                                                <i class="fa fa-plus"></i> thêm nội dung</span>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-12 gmoi hidden">
                                        <div class="row">
                                            <div class="col-md-3" style="margin-top: 10px">
                                                <div class="form-group">
                                                    <label for="">Giờ họp <span class="color-red">*</span></label>
                                                    <input type="time" class="form-control" value="{{isset($data_xml->STRTHOIGIANHOP)? $data_xml->STRTHOIGIANHOP : ''}}" name="gio_hop_chinh">
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="margin-top: 10px">
                                                <div class="form-group">
                                                    <label for="">Ngày họp <span class="color-red">*</span></label>
                                                    <input type="date" class="form-control layngayhop" value="{{isset($data_xml->STRNGAYHOP)? $data_xml->STRNGAYHOP : ''}}"
                                                           name="ngay_hop_chinh" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="margin-top: 10px">
                                                <div class="form-group">
                                                    <label for="">Địa điểm <span class="color-red">*</span></label>
                                                    <input type="text" class="form-control" value="{{isset($data_xml->STRDIADIEM)? $data_xml->STRDIADIEM : ''}}"
                                                           name="dia_diem_chinh" placeholder="Địa điểm">
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-right" style="margin-top: 40px">
                                                <a class="btn btn-success btn-xs" role="button"  data-toggle="collapse"
                                                   href="#collapseExample"
                                                   aria-expanded="false" aria-controls="collapseExample"><i
                                                        class="fa fa-plus"></i>
                                                </a>
                                                <b class="text-danger"> Hiển thị thêm nội dung</b>
                                            </div>

                                            <div class="col-md-12 collapse in" id="collapseExample">
                                                <div class="col-md-12  gmoi layout3 ">
                                                    <div class="row" style="margin-top:-15px;margin-left: 0px;">
                                                        <hr style="border: 0.5px solid #3c8dbc">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px">
                                                            <label for="detail-job">Nội dung họp</label>
                                                            <textarea name="noi_dung_hop_con[]" placeholder="nhập nội dung công việc" rows="3"
                                                                      class="form-control no-resize noi-dung-chi-dao"
                                                                      aria-required="true">{{ old('noi_dung_hop', isset($vanban) ? $vanban->noi_dung_hop : '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-4" style="margin-top: 10px">
                                                            <div class="form-group">
                                                                <label for="">Giờ họp</label>
                                                                <input type="time" class="form-control"  value="{{ isset($vanban) ? $vanban->gio_hop_con : '' }}" name="gio_hop_con[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="margin-top: 10px">
                                                            <div class="form-group">
                                                                <label for="">Ngày họp</label>
                                                                <input type="date" class="form-control"
                                                                       value="{{ isset($vanban) ? $vanban->ngay_hop_con : '' }}"
                                                                       name="ngay_hop_con[]" placeholder="Nhập ngày họp">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="margin-top: 10px">
                                                            <div class="form-group">
                                                                <label for="">Địa điểm</label>
                                                                <input type="text" value="{{ isset($vanban) ? $vanban->dia_diem_con : '' }}"
                                                                       placeholder="Nhập địa điểm" class="form-control" name="dia_diem_con[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group-btn text-right">
                                                    <a class="btn btn-primary btn-xs" role="button"
                                                       aria-expanded="false" ><span class="btn btn-primary" onclick="themgiaymoi('noi_dung_hop_con[]')" type="button">
                                                        <i class="fa fa-plus"></i> thêm nội dung</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right gmoian">
                                        <a class="btn btn-success btn-xs" role="button" data-toggle="collapse"
                                           href="#collapseExample1"
                                           aria-expanded="false" aria-controls="collapseExample1"><i
                                                class="fa fa-plus"></i>
                                        </a>
                                        <b class="text-danger"> Hiển thị thêm nội dung</b>
                                    </div>
                                @endif
                                <div class="col-md-12 collapse "
                                     id="collapseExample1">
                                    <div class="col-md-12 layout2 ">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr style="border: 0.5px solid #3c8dbc">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Nội dung</label>
                                                <textarea rows="3" class="form-control"
                                                          name="noi_dung[]"></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="vb_ngay_ban_hanh" class="col-form-label">Hạn giải quyết</label>
                                                <div id="">
                                                    <input type="date" class="form-control"
                                                           value="" name="han_giai_quyet[]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group-btn text-right" style="margin-top: 10px">
                                        <a class="btn btn-primary btn-xs" role="button"
                                           aria-expanded="false" ><span class="btn btn-primary" onclick="noidungvanban('noi_dung[]')" type="button">
                                                <i class="fa fa-plus"></i> thêm nội dung</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="do_khan_cap_id" class="col-form-label">Độ khẩn</label>
                                    <select class="form-control dropdown-search" id="do_khan_cap_id" name="do_khan" required>
                                        @foreach ($ds_doKhanCap as $doKhanCap)
                                            <option
                                                value="{{ $doKhanCap->id }}">{{ $doKhanCap->ten_muc_do}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="do_mat_id" class="col-form-label">Độ mật</label>
                                    <select class="form-control dropdown-search" name="do_mat" id="do_mat_id" required>
                                        @foreach ($ds_mucBaoMat as $doBaoMat)
                                            <option value="{{ $doBaoMat->id }}">{{ $doBaoMat->ten_muc_do}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <div id="">
                                        <label for="vb_han_xu_ly" class="col-form-label">Hạn xử lý</label>
                                        <input class="form-control" value="{{isset($hangiaiquyet)?$hangiaiquyet:''}}"
                                               name="han_xu_ly" id="han_xu_ly" type="date">
                                        <input type="hidden" class="form-control" id="don_vi_id" name="don_vi_id" value="{{auth::user()->donvi_id}}">
                                    </div>
                                </div>
                                <div class=" col-md-3 mt-4" >
                                    <input type="hidden" name="id_vanban_tumail" value="{{$id}}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-plus-square-o mr-1"></i><span>Thêm mới</span>
                                    </button>
                                </div>





                                <div class="clearfix"></div>

                                <div class="form-group col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="sokyhieu" class="col-form-label">File văn bản:</label>
                                            &nbsp;&nbsp;
                                            <a href="{{$url_pdf}}" target="popup" class="seen-new-window">[File_pdf]</a>
                                            <input type="hidden" name="file_pdf[]" value="{{$url_pdf}}">

                                            @if($url_doc)
                                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                                <a href="{{$url_doc}}" target="popup" class="seen-new-window">[File_docx]</a>
                                                <input type="hidden" name="file_pdf[]" value="{{$url_doc}}">
                                            @endif
                                            @if($url_xls)
                                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                                <a href="{{$url_xls}}" target="popup" class="seen-new-window">[File_xls]</a>
                                                <input type="hidden" name="file_pdf[]" value="{{$url_xls}}">
                                            @endif
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-12"></div>

                                <div id="ajax_loading" style="display: none"></div>
                                <div id="moda-search" class="modal fade" role="dialog"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script type="text/javascript">
    </script>

@endsection

@section('style')
<style>
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
@endsection
