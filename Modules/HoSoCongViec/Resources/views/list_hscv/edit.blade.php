@extends('admin::layouts.master')
@section('page_title', 'Hồ sơ công việc')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sửa hồ sơ</h3>
                    </div>
                    <div class="box-body">
                        <form method="post"
                              action="{{ isset($hoso) ? route('ho-so-cong-viec.update', $hoso->id) : route('ho-so-cong-viec.store') }}"
                              enctype="multipart/form-data"
                              autocomplete="off"
                              class="form-horizontal">
                            @csrf
                            @if (isset($hoso))
                                @method('PUT')
                            @endif
                            <div class=" col-md-6 " style="border: 1px #bce8f1 solid;">
                                <div class="col-md-12">
                                    <label for="sokyhieu" class="col-form-label">Tên hồ sơ</label>
                                    <input class="form-control "
                                           value="{{isset($hoso) ? $hoso->ten_ho_so : ''}}" name="ten_ho_so"
                                           type="text">
                                </div>
                                <div class="col-md-12">
                                    <label for="url-file" class="col-form-label">Mô tả</label>
                                    <div class="col-md-12 form-line input-group control-group">
                                                    <textarea  rows="3" class="form-control"
                                                               name="mo_ta">{{isset($hoso) ? $hoso->mo_ta : ''}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px">
                                    <div class="row">
                                        <div class="col-md-4" style="margin-top: 5px">
                                            <label for="url-file">Chọn loại văn bản</label>
                                        </div>
                                        <div class="col-md-3" style="margin-top: 5px">
                                            <div class="custom-control custom-radio ">
                                                <input type="radio" value="1"
                                                       {{isset($hoso) && $hoso->trang_thai == 1 ? 'checked' : ''}} id="customRadio1"
                                                       checked
                                                       name="trang_thai"
                                                       class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio1">Hiển
                                                    thị</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 5px">
                                            <div class="custom-control custom-radio ">
                                                <input type="radio" id="customRadio2"
                                                       {{isset($hoso) && $hoso->trang_thai == 0 ? 'checked' : ''}} value="0"
                                                       name="trang_thai"
                                                       class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio2">không
                                                    hiển thị</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <button type="submit"
                                                    class="btn btn-primary">{{ isset($hoso) ? 'Cập nhật' : 'Lưu lại' }}
                                            </button>
                                        </div>
                                    </div>
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
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
