@extends('admin::layouts.master')
@section('page_title', 'Nhật ký truy cập')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ url('theme/plugins/datatable/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nhật ký hoạt động</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="table-log"
                               class="table table-bordered table-striped table-hover"
                               data-ordering-index="0">
                            <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th class="text-center">Người dùng</th>
                                <th width="" class="text-center">Hành động</th>
                                <th class="text-center">Chi tiết</th>
                                <th class="text-center">Thời gian</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if (count($newLogCollections) > 0)
                                @forelse($newLogCollections as $key=>$data)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-left">{{ $data['user'] ?? ''}}</td>
                                        <td class="text-left">{{ $data['action'] }}</td>
                                        <td class="text-center">
                                            <a class="btn-action btn btn-color-blue btn-sm btn-view-detail"
                                               title="xem chi tiết"
                                               data-id="{{ $key+1 }}" data-content="{{ $data['content'] }}"><i
                                                    class="fa fa-eye"></i> xem chi tiết
                                            </a>
                                        </td>
                                        <td class="text-center">{{ date('d/m/Y H:i:s', strtotime($data['date'])) ?? '' }}</td>
                                    </tr>
                                @empty
                                    <td class="text-center" colspan="5">Không có dữ liệu !
                                    </td>
                                @endforelse
                            @else
                                <td class="text-center" colspan="5">Không có dữ liệu !
                                </td>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- chi tiet -->
                    <div class="col-md-10">
                        <div class="modal fade modal-view-datail" role="dialog" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content modal-data-push">

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
    <script type="text/javascript" src="{{ url('theme/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('theme/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        $('.btn-view-detail').on('click', function () {
            let id = $(this).data('id');
            let content = $(this).data('content');
            console.log(content);
            $.ajax({
                url: APP_URL + '/nhat-ky-truy-cap/' + id,
                type: 'GET',
                data: {
                    content: content
                },
                beforeSend: function () {
                    showLoading();
                }
            })
                .done(function (response) {
                    $('.modal-data-push').html(response.html);
                    $('.modal-view-datail').modal();
                    hideLoading();
                })
                .fail(function (error) {
                    toastr['error'](error.message, 'Thông báo hệ thống');
                    hideLoading();
                });
        });

        $(document).ready(function () {
            $('.table-container tr').on('click', function () {
                $('#' + $(this).data('display')).toggle();
            });
            $('#table-log').DataTable({
                "order": [$('#table-log').data('orderingIndex'), 'asc'],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                }
            });
            $('#delete-log, #clean-log, #delete-all-log').click(function () {
                return confirm('Are you sure?');
            });
        });
    </script>
@endsection
