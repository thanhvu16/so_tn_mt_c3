@extends('admin::layouts.master')
@section('page_title', 'Quản lý quyền hạn')
@section('content')

    <section class="content">
    {{--        <div class="box">--}}
    <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ Request::get('tab') == 'tab_1' || empty(Request::get('tab')) ? 'active' : null }}">
                    <a href="{{ route('vai-tro.index') }}">
                        <i class="fa fa-list"></i> Quản lý quyền hạn
                    </a>
                </li>
                <li class="{{ Request::get('tab') == 'tab_2' ? 'active' : null }}">
                    <a href="{{ route('vai-tro.create') }}">
                        <i class="fa fa-plus"></i> Thêm mới</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <form action="{{ route('vai-tro.index') }}" method="get">
                                <div class="col-md-3 form-group">
                                    <label>Tìm theo quyền </label>
                                    <input type="text" class="form-control" value="{{Request::get('name')}}"
                                           name="name"
                                           placeholder="nhập tên quyền">
                                </div>
                                <div class="col-md-3" style="margin-top: 22px">
                                    <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                    @if (!empty(Request::get('name')))
                                        <a href="{{ route('vai-tro.index') }}" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                    @endif
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Quyền hạn</th>
                                <th class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($roles as $key => $role)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{ $key+1 }}</td>
                                    <td class="text-center" style="vertical-align: middle">{{ ucfirst($role->name) }}</td>
                                    <td class="text-center">
                                        <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm" href="{{ route('vai-tro.edit', $role->id) }}"
                                           role="button" title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
{{--                                        @if ($role->name != strtolower('admin'))--}}
{{--                                            <form method="POST" action="{{ route('vai-tro.destroy', $role->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item" role="button" title="Xóa">--}}
{{--                                                    <i class="fa fa-trash" aria-hidden="true"></i>--}}
{{--                                                </button>--}}
{{--                                            </form>--}}
{{--                                        @endif--}}
                                    </td>

                                </tr>
                            @empty
                                <td class="text-center" colspan="3" style="vertical-align: middle">Không có dữ liệu !
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                Tổng số quyền: <b>{{ $roles->total() }}</b>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="pagination pagination-sm no-margin pull-right">
                                    {{ $roles->render() }}
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
