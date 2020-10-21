@extends('admin::layouts.master')
@section('page_title', 'Quản lý người dùng')
@section('content')
    <section class="content">
{{--        <div class="box">--}}
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="{{ Request::get('tab') == 'tab_1' || empty(Request::get('tab')) ? 'active' : null }}">
                        <a href="{{ route('nguoi-dung.index') }}">
                            <i class="fa fa-user"></i> Quản lý người dùng
                        </a>
                    </li>
                    <li class="{{ Request::get('tab') == 'tab_2' ? 'active' : null }}">
                        <a href="{{ route('nguoi-dung.create') }}">
                            <i class="fa fa-plus"></i> Thêm mới</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane {{ Request::get('tab') == 'tab_1' || empty(Request::get('tab')) ? 'active' : null }}" id="tab_1">
                        <div class="box-body">
                            <table class="table table-bordered table-striped table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tài khoản</th>
                                        <th class="text-center">Họ tên</th>
                                        <th class="text-center">Chức vụ</th>
                                        <th class="text-center">Đơn vị</th>
                                        <th class="text-center">Giới tính</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $order++ }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->ho_ten }}</td>
                                        <td>{{ $user->chuc_vu_id }}</td>
                                        <td>{{ $user->don_vi_id }}</td>
                                        <td>{{ $user->trang_thai == 1 ? 'Nam' : 'Nữ' }}</td>
                                        <td class="text-center">{!! getStatusLabel($user->trang_thai) !!}</td>
                                        <td class="text-center">
                                            <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm" href="{{ route('nguoi-dung.edit', $user->id) }}"
                                               role="button" title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('nguoi-dung.destroy', $user->id) }}" accept-charset="UTF-8" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item" role="button" title="Xóa">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    Tổng số Người dùng: <b>{{ $users->total() }}</b>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="pagination pagination-sm no-margin pull-right">
                                        {{ $users->render() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
    </section>
@endsection
