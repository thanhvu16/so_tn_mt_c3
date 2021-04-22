@extends('admin::layouts.master')
@section('page_title', 'Cấu hình email đơn vị')
@section('content')
    <section class="content">
    {{--        <div class="box">--}}
    <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#">
                        <i class="fa fa-plus"></i> Thông tin</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- /.tab-pane -->
                <div class="tab-pane active" id="tab_2">
                    <form role="form" action="{{ route('luu_cau_hinh_email_don_vi')}} " method="post">
                        @csrf
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nhập email</label>
                                    <input type="text" class="form-control" name="email"
                                           placeholder="Email" value="{{ old('email', isset($donVi) ? $donVi->email : null) }}" required>
                                </div>
                            </div>
                            @if (isset($donVi) && $donVi->password)
                                <div class="col-md-3">
                                    <label for="update-pass-email">&nbsp; <br><br>
                                    <input type="checkbox" name="update_password" value="1" id="update-pass-email">
                                    Cập nhật mật khẩu
                                    </label>
                                </div>
                                <div class="col-md-3 update-password hide">
                                    <div class="form-group">
                                        <label>Nhập mật khẩu</label>
                                        <input type="password" class="form-control" name="password"
                                               placeholder="Nhập mật khẩu">
                                    </div>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <input type="hidden" name="update_password" value="1">
                                    <div class="form-group">
                                        <label>Nhập mật khẩu</label>
                                        <input type="password" class="form-control" name="password"
                                               placeholder="Nhập mật khẩu">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Trạng thái</label>
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status_email" id="optionsRadios1" value="1"
                                                {{ isset($donVi) && $donVi->status_email == 1 ? 'checked' : null }} required>
                                                Hoạt động
                                            </label>  
                                            <label>
                                                <input type="radio" name="status_email" id="optionsRadios2" value="2"
                                                    {{ isset($donVi) && $donVi->status_email == 2 ? 'checked' : null }}>
                                                Khoá
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /.tab-content -->
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        $('input[name="update_password"]').on('click', function () {
            if($(this).prop("checked") == true){
                $('.update-password').removeClass('hide');
                $('input[name="password"]').prop('required', true);
            }
            else {
                $('.update-password').addClass('hide');
                $('input[name="password"]').prop('required', false);
            }
        })
    </script>
@endsection
