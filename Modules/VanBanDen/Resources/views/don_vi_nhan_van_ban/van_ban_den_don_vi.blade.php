@extends('admin::layouts.master')
@section('page_title', 'Thêm văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vào sổ văn Bản Đến</h3>
                    </div>
                    <form role="form" action="{{route('vaosovanbanhuyen')}}" method="post" enctype="multipart/form-data"
                          id="myform">
                        @csrf
                        <input type="hidden" value="{{ $type }}" name="type">
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Loại văn bản <span style="color: red">(*)</span></label>
                                    <select class="form-control select2" autofocus name="loai_van_ban" required>
                                        <option value="">-- Chọn loại văn bản --</option>
                                        @foreach($loaivanban as $loaivanbands)
                                            <option value="{{ $loaivanbands->id }}"{{ $van_ban_den->vanBanDen->loai_van_ban_id == $loaivanbands->id ? 'selected' : '' }}
                                            >{{ $loaivanbands->ten_loai_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Sổ văn bản <span style="color: red">(*)</span></label>
                                    <select class="form-control select2 check-so-den-vb" data-don-vi="{{auth::user()->don_vi_id}}" name="so_van_ban" required>
                                        <option value="">-- Chọn sổ văn bản --</option>
                                        @foreach($sovanban as $data)
                                            <option value="{{ $data->id }}" >{{ $data->ten_so_van_ban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Số đến văn bản</label>
                                    <input type="text" class="form-control " value="" readonly name="so_den" id="exampleInputEmail3"
                                           placeholder="Số đến" style="font-weight: 800;color: #F44336;cursor: not-allowed;" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Số ký hiệu <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->vanBanDen->so_ky_hieu}}" name="so_ky_hieu" id="exampleInputEmail4"
                                           placeholder="Số ký hiệu" required>
                                    <input type="text" class="hidden" value="{{$id}}" name="id_van_ban_di">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Ngày ban hành <span style="color: red">(*)</span></label>
                                    <input type="date" class="form-control" value="{{$van_ban_den->vanBanDen->ngay_ban_hanh}}" name="ngay_ban_hanh" id="exampleInputEmail5"
                                           placeholder="" required >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Cơ quan ban hành <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->vanBanDen->co_quan_ban_hanh}}" name="co_quan_ban_hanh" id="exampleInputEmail6"
                                           placeholder="Cơ quan ban hành" required >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Người ký <span style="color: red">(*)</span></label>
                                    <input type="text" class="form-control" value="{{$van_ban_den->vanBanDen->nguoi_ky}}" name="nguoi_ky" id="exampleInputEmail7"
                                           placeholder="Người ký" required>
                                </div>
                            </div>
                            <div class="col-md-3 hidden">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Lãnh đạo tham mưu <span style="color: red">(*)</span></label>
                                    <input type="text" name="id_don_vi_chu_tri" value="{{$id}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Hạn xử lý </label>
                                    <input type="date" class="form-control" value="{{$hangiaiquyet}}" name="han_xu_ly" placeholder="Hạn xử lý" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Trích yếu <span style="color: red">(*)</span></label>
                                    <textarea class="form-control" name="trich_yeu" rows="3" required>{{$van_ban_den->vanBanDen->trich_yeu}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 text-right {{isset($van_ban_den->vanBanDen) ? 'hidden': ''}}">
                                <a class="btn btn-primary " role="button" data-toggle="collapse"
                                   href="#collapseExample"
                                   aria-expanded="false" aria-controls="collapseExample"><i
                                        class="fa fa-plus"></i>
                                </a>
                                <b class="text-danger"> Hiển thị thêm nội dung</b>
                            </div>

                            <div class="col-md-12 collapse @if($van_ban_den->vanBanDen) @if($van_ban_den->vanBanDen->noi_dung != null)show @endif @else in @endif "
                                 id="collapseExample">
                                <div class="col-md-12 layout2 ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr style="border: 0.5px solid #3c8dbc">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="noi_dung" class="col-form-label">Nội dung</label>
                                            <textarea rows="3" class="form-control"
                                                      name="noi_dung[]">{{$van_ban_den->vanBanDen->noi_dung ?? ''}}</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="han_giai_quyet" class="col-form-label">Hạn giải quyết</label>
                                            <div id="">
                                                <input type="date" class="form-control"
                                                       name="han_giai_quyet[]" value="{{$van_ban_den->vanBanDen->han_giai_quyet ?? ''}}">
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="input-group-btn text-right " style="margin-top: 10px">
            <span class="btn btn-primary" onclick="noidungvanban('noi_dung[]')" type="button">
                        <i class="fa fa-plus"></i> thêm nội dung</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Độ khẩn</label>
                                    <select class="form-control select2"  name="do_khan">
                                        {{--                                        <option value="">-- Chọn độ khẩn --</option>--}}
                                        @foreach($dokhan as $dokhands)
                                            <option value="{{ $dokhands->id }}"  >{{ $dokhands->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Độ Mật</label>
                                    <select class="form-control select2"  name="do_mat">
                                        {{--                                        <option value="">-- Chọn độ mật--</option>--}}
                                        @foreach($domat as $domatds)
                                            <option value="{{ $domatds->id }}"  >{{ $domatds->ten_muc_do }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 mt-4">
                                <div class="form-group">
                                    <button
                                        class="btn btn-primary" type="submit"><i class="fa fa-plus-square-o mr-1"></i>
                                        <span>Thêm mới</span></button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3 " >
                                <span style="font-weight: bold">File văn bản :</span>
                                <span>
                                    @if($van_ban_den->vanBanDen->vanBanDenFile)
                                        @forelse($van_ban_den->vanBanDen->vanBanDenFile as $key=>$item)
                                            <a href="{{$item->getUrlFile()}}" target="popup" class="seen-new-window">[file văn bản]</a>
                                            {{--                                                    @if($item->duoi_file == 'pdf')<i--}}
                                            {{--                                                class="fa fa-file-pdf-o"--}}
                                            {{--                                                style="font-size:20px;color:red"></i>@elseif($item->duoi_file == 'docx' || $item->duoi_file == 'doc')--}}
                                            {{--                                                <i class="fa fa-file-word-o"--}}
                                            {{--                                                   style="font-size:20px;color:blue"></i> @elseif($item->duoi_file == 'xlsx' || $item->duoi_file == 'xls')--}}
                                            {{--                                                <i class="fa fa-file-excel-o"--}}
                                            {{--                                                   style="font-size:20px;color:green"></i> @endif--}}
                                            {{--                                                </a>@if(count($van_ban_den->vanBanDen->vanBanDenFile) == $key+1) @else &nbsp;|&nbsp; @endif--}}
                                            <input type="text" value="{{$item->id}}" class="hidden" name="id_file">
                                        @empty
                                        @endforelse
                                    @endif
                                </span>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
