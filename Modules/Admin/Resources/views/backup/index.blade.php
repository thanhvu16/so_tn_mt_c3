@extends('admin::layouts.master')
@section('page_title', 'Sao lưu dữ liệu')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title">Danh sách sao lưu dữ liệu</h3>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('backup.create') }}" method="post">
                                    @csrf
                                <button class="btn btn-primary btn-sm pull-right" type="submit"><i class="fa fa-plus"></i> Tạo mới sao lưu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">File</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Ngày sao lưu</th>
                                <th class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($backups as $backup)
                                <tr>
                                    <td>{{ $backup['file_name'] }}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{ Spatie\Backup\Helpers\Format::humanReadableSize($backup['file_size']) }}</td>
                                    <td class="text-center">
                                        {{ date('d/m/Y H:i:s', $backup['last_modified']) }}
                                    </td>
                                    <td class="text-center">
                                            <a href="{{ route('backup.download', $backup['file_name']) }}" class="btn-action btn btn-color-blue btn-icon btn-light btn-sm">
                                                <i class="fa fa-download"></i> Download
                                            </a>

                                        <form method="POST" action="{{ route('backup.destroy', $backup['file_name']) }}" accept-charset="UTF-8" style="display:inline">
                                             @csrf
                                            <button class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item" role="button" title="Xóa">
                                                <i class="fa fa-trash" aria-hidden="true"></i> Xoá
                                            </button>
                                        </form>

                                    </td>

                                </tr>
                            @empty
                                <td class="text-center" colspan="6" style="vertical-align: middle">Không có dữ liệu !
                                </td>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                Tổng số: <b>{{ count($backups) }}</b>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection
