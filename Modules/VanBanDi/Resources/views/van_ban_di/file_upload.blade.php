@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-3">Văn bản đi</h4>
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="far fa-plus-square"></i> File
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">

                            <div class="col-md-12" style="padding-top: 10px">
                                <form method="post" action="{{route('upload_motfile_di')}}"
                                      enctype="multipart/form-data"
                                      autocomplete="off"
                                      class="form-horizontal">
                                    @csrf
                                    <div class=" col-md-6 offset-md-3" style="border: 1px #bce8f1 solid;">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">Thêm mới/Cập nhật</div>
                                            <div class="panel-body">

                                                <div class="col-md-12">
                                                    <label for="sokyhieu" class="col-form-label">Tên tệp tin</label>
                                                    <input class="form-control " name="txt_file[]" type="text">
                                                    <input class="form-control hidden" name="vb_di_id" value="{{$id}}"
                                                           type="text">
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="url-file" class="col-form-label">Chọn tệp</label>
                                                    <div class="form-line input-group control-group">
                                                        <input type="file" id="url-file" name="ten_file[]"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                    <div class="col-md-12" style="margin-top: 10px">
                                                        <label for="url-file" class="col-form-label">Chọn loại tệp
                                                            tin</label>
                                                        <div class="row">
                                                            <div class="col-md-4">

                                                                <div class="custom-control custom-radio ">
                                                                    <input type="radio" value="2" id="customRadio1" checked name="loai_file"
                                                                           class="custom-control-input">
                                                                    <label class="custom-control-label" for="customRadio1">File
                                                                        trình ký</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="custom-control custom-radio ">
                                                                    <input type="radio" id="customRadio2" value="1" name="loai_file"
                                                                           class="custom-control-input">
                                                                    <label class="custom-control-label" for="customRadio2">File
                                                                        phiếu trình</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="custom-control custom-radio ">
                                                                    <input type="radio" id="customRadio3" value="3" name="loai_file"
                                                                           class="custom-control-input">
                                                                    <label class="custom-control-label" for="customRadio3">File hồ sơ</label>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>


                                                <div class="col-md-12 text-center mt-4">
                                                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12" style="margin-top: 50px">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th width="10" class="text-center">STT</th>
                                            <th width="250" class="text-center"> Tên tệp tin
                                            </th>
                                            <th width="70" class="text-center">Ngày nhập
                                            </th>
                                            <th width="50" class="text-center">Tải xuống</th>
                                            <th width="50" class="text-center">Tác vụ
                                            </th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($file as $key=>$data)
                                            <tr>
                                                <td class="text-center">{{$key+1}}</td>
                                                <td><a href="{{$data->getUrlFile()}}">{{$data->tenfile}}</a></td>
                                                <td class="text-center">{{date_format($data->ngaytao, 'd-m-Y H:i:s')}}</td>
                                                <td class="text-center"><a href="{{$data->getUrlFile()}}"><i
                                                            class="fas fa-download"></i></a></td>
                                                <td class="text-center"><a
                                                        href="{{route('delete_motfile_di',$data->id)}}"
                                                        style="color: red">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div><br></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>

@endsection
