@extends('admin::layouts.master')
@section('page_title', 'Danh sách')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tìm kiếm - Thêm văn bản vào hồ sơ</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{route('ds_tim_kiem_van_ban_hs',$id)}}" method="get">
                            <div class="row">
                                <div class="form-group col-md-12" >
                                    <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                    <textarea rows="3" class="form-control" placeholder="nội dung"
                                              name="vb_trich_yeu"
                                              type="text">{{ old('vb_trich_yeu') }}</textarea>
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                    <input type="text" name="vb_so_ky_hieu"
                                           value="{{ old('vb_so_ky_hieu') }}" class="form-control "
                                           id="sokyhieu"
                                           placeholder="Số ký hiệu">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for="noi_gui_den" class="col-form-label">Nơi gửi đến</label>
                                    <input type="text" name="noi_gui_den"
                                           value="{{ old('noi_gui_den') }}" class="form-control file_insert"
                                           id="noi_gui_den"
                                           placeholder="Nơi gửi đến">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for="noi_gui_den" class="col-form-label">Loại văn bản tìm
                                        kiếm</label>
                                    <div class="row">
                                        <div class="col-md-6 custom-control custom-radio " style="margin-left: 20px">
                                            <input type="radio" value="2" id="customRadio1" checked
                                                   name="loai_van_ban"
                                                   class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio1">Văn bản
                                                đi</label>
                                        </div>
                                        <div class=" col-md-6custom-control custom-radio " style="margin-left: 30px">
                                            <input type="radio" id="customRadio2" value="1"
                                                   name="loai_van_ban"
                                                   class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio2">Văn bản
                                                đến</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group col-md-3 mt-4" >
                                    <button type="button" class="btn btn-primary lay_van_ban"
                                            onclick="showModal();" name="search">Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12">
                            <br><div class="row">
                                <a class="btn btn-default" href="javascript: history.back(1)" id="backLink" data-original-title="" title="">Quay lại &gt;&gt;</a>
                            </div>

                        </div>
                        <div class=""></div>
                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('luu_vao_detail') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title"><i
                                                    class="fa fa-folder-open-o"></i> Chọn văn bản tải vào thư mục</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <table class="table table-bordered table-striped dataTable mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th width="5%" class="text-center">Chọn</th>
                                                            <th width="95%" class="text-center">Trích yếu</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="data-append">


                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="form-group col-md-12 text-right" >
                                                    <input type="text" name="id_ho_so" value="{{$id}}" class="hidden">
                                                    <button class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Lưu lại</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </form>
                                </div>
                            </div>
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
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
