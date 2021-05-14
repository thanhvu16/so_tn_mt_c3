@extends('admin::layouts.master')
@section('page_title', 'Đơn Vị ngoài hệ thống')
@section('content')
    <section class="content">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ Request::get('tab') == 'tab_1' || empty(Request::get('tab')) ? 'active' : null }}">
                    <a href="{{ route('danhsachdonvi') }}">
                        <i class="fa fa-tasks"></i> Danh sách đơn vị ngoài hệ thống
                    </a>
                </li>
                <li>
                    <button type="button" class="btn btn-success update-all waves-effect waves-light" data-status="1">
                        <span>Cập nhật cho phép gửi qua email</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="btn btn-warning update-all waves-effect waves-light" data-status="2">
                        <span>Huỷ cho phép gửi qua email</span>
                    </button>
                </li>
            </ul>
            <form action="{{ route('email-don-vi-ngoai-he-thong.update_all') }}" method="post" class="form-update-status">
                @csrf
                <input type="hidden" name="accepted" value="">
            </form>
            <div class="tab-content">
                <div
                    class="tab-pane {{ Request::get('tab') == 'tab_1' || empty(Request::get('tab')) ? 'active' : null }}"
                    id="tab_1">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- /.box-header -->
                            <div class="col-md-3">
                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-1"
                                        data-toggle="collapse"
                                        href="#collapseExample"
                                        aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fa fa-plus"></i>
                                    THÊM MỚI
                                </button>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="collapse " id="collapseExample">
                                        <div class="row">
                                            @include('admin::don-vi-ngoai-he-thong._form')
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <form method="GET" action="{{ route('email-don-vi-ngoai-he-thong.index') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="date">Tìm theo tên đơn vị</label><br>
                                            <input type="text" style="width:100%" name="ten_don_vi" class="form-control"
                                                   value="{{ request('ten_don_vi') }}"
                                                   placeholder="Tìm theo tên đơn vị...">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date">Tìm theo tên email</label><br>
                                            <input type="text" style="width:100%" name="email"
                                                   class="form-control" value="{{ request('email') }}"
                                                   placeholder="Tìm theo tên email...">
                                        </div>
                                        <div class="col-md-3">
                                            <label> Tìm theo mã định danh</label>
                                            <input type="text" style="width:100%" name="ma_dinh_danh"
                                                   class="form-control" value="{{ request('ma_dinh_danh') }}"
                                                   placeholder="Tìm theo tên email...">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Cho phép gửi email</label>
                                            <select class="form-control " name="accepted">
                                                <option value="">Chọn</option>
                                                <option
                                                    value="1" {{ Request::get('accepted') == 1 ? 'selected' : null }}>
                                                    Hoạt động
                                                </option>
                                                <option
                                                    value="2" {{ Request::get('accepted') == 2 ? 'selected' : null }}>
                                                    Không hoạt động
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="search">&nbsp</label><br>
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fa fa-search"></i> Tìm kiếm
                                            </button>
                                            @if (!empty(Request::get('ten_don_vi')) || !empty(Request::get('email')) ||
                                            !empty(Request::get('ma_dinh_danh')) || !empty(Request::get('accepted')))
                                                <a href="{{ route('email-don-vi-ngoai-he-thong.index') }}" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="box-body table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="5%" class="text-center">STT</th>
                                        <th class="text-center">Tên đơn vị</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Mã định danh</th>
                                        <th class="text-center">Cho phép gửi email</th>
                                        <th class="text-center">Ngày cập nhập</th>
                                        <th class="text-center">Tác Vụ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($danhSachEmails as $key => $email)
                                        <tr>
                                            <td class="text-center">
                                                {{$key+1}}
                                            </td>
                                            <td>
                                                {{$email->ten_don_vi}}
                                            </td>
                                            <td>
                                                {{ $email->email }}
                                            </td>
                                            <td class="text-center">
                                                {{$email->ma_dinh_danh}}
                                            </td>
                                            <td class="text-center">
                                                {!! $email->checkGuiMail() !!}
                                            </td>
                                            <td class="text-center">
                                                {{ !empty($email->updated_at) ? date('d/m/Y H:i:s', strtotime($email->updated_at)) : null }}
                                            </td>
                                            <td class="text-center">
                                                <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm"
                                                   href="{{ route('email-don-vi-ngoai-he-thong.edit', $email->id) }}"
                                                   role="button"
                                                   title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('email-don-vi-ngoai-he-thong.destroy',$email->id) }}"
                                                      accept-charset="UTF-8" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                        role="button" title="Xóa">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>

                                                </form>

                                            </td>

                                        </tr>
                                    @empty
                                        <td class="text-center" colspan="6" style="vertical-align: middle">Không có dữ
                                            liệu !
                                        </td>
                                    @endforelse

                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-6" style="margin-top: 5px">
                                        Tổng số đơn vị: <b>{{ $danhSachEmails->total() }}</b>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        {!! $danhSachEmails->appends(['ten_don_vi' => Request::get('ten_don_vi'),'ma_hanh_chinh' => Request::get('ma_hanh_chinh'),
                                           'ten_viet_tat' => Request::get('ten_viet_tat'),'search' =>Request::get('search') ])->render() !!}
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        $('.update-all').on('click', function () {
            let status = $(this).data('status');
            $('.form-update-status').find('input[name="accepted"]').val(status);
            if (confirm('Xác nhận gửi')) {
                $('.form-update-status').submit();
            }
        });
    </script>
@endsection
