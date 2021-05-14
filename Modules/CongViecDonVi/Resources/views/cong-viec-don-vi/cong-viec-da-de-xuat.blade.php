@extends('admin::layouts.master')
@section('page_title', 'Công việc đã đề xuất')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Công việc đã đề xuất</h3>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dataTable table-hover data-row">
                                <thead>
                                <tr role="row" class="text-center">
                                    <th width="4%" class="text-center">STT</th>
                                    <th width="13%" class="text-center">Người nhận</th>
                                    <th class="text-center">Nội dung công việc</th>
                                    <th width="10%" class="text-center">File</th>
                                    <th width="10%" class="text-center">Hạn xử lý</th>
                                    <th width="10%" class="text-center" class="text-center">Kết quả</th>
                                    <th width="7%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($congviecdexuat as $key=>$congViecDonVi)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td>{{  $congViecDonVi->truongPhong->ho_ten  ?? '' }}</td>
                                        <td>{{ $congViecDonVi->noi_dung }}</td>
                                        <td class="text-center">
                                            @if($congViecDonVi->file)
                                                @forelse($congViecDonVi->file as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="popup" class="seen-new-window">
                                                        {{$item->ten_file}}.{{$item->duoi_file}}
                                                    </a>
                                                @empty
                                                @endforelse
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($congViecDonVi->han_xu_ly)) }}</td>
                                        <td class="text-center">
                                            @if($congViecDonVi->trang_thai == 1)<span class="label label-pill label-sm label-warning">Chờ duyệt</span>
                                            @elseif($congViecDonVi->trang_thai == 2)<span class="label label-pill label-sm label-success">Đã duyệt</span>
                                                @else<span class="label label-pill label-sm label-danger">Trả lại</span>@endif
                                        </td>
                                        <td class="text-center">
                                            @if($congViecDonVi->trang_thai == 1)
                                            <a style="cursor: pointer"
                                               class="fa fa-edit" role="button"
                                               title="Sửa" onclick="showModal({{$congViecDonVi->id}})">
                                                <i class="fas fa-file-signature"></i>
                                            </a> &emsp;
                                                <a href="{{route('xoaCongViecDeXuat',$congViecDonVi->id)}}" class="btn-remove-item"><i class="fa fa-trash" aria-hidden="true" style="color: red"></i></a>
                                            @else - @endif

                                        </td>

                                    </tr>
                                @empty
                                    <td colspan="7"
                                        class="text-center">Không tìm
                                        thấy dữ liệu.
                                    </td>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="modal fade" id="myModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('suaCongViecDeXuat') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title"><i
                                                        class="fa fa-cogs"></i> Sửa công việc</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="sokyhieu" class="">Nội dung
                                                        </label>
                                                        <textarea class="form-control noi-dung" name="noi_dung"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="sokyhieu" class="">Hạn xử lý
                                                        </label><br>
                                                        <input type="date" class="form-control han-xu-ly" name="han_xu_ly">
                                                        <input type="text" class="form-control id-cv hidden" value="" name="id">
                                                    </div>
                                                    <div class="form-group col-md-12 text-right" >
                                                        <button class="btn btn-primary"><i class="fa fa-check-circle-o"></i> Lưu lại</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6 col-12">
                                    <b>Tổng số công việc: {{$congviecdexuat->count()}}</b>
                                </div>
                                <div class="col-md-6 col-12">
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
    <script type="text/javascript">
        function showModal(id) {
            $.ajax({
                url: APP_URL + '/chiTietCongViecDeXuat',
                type: 'POST',
                beforeSend: showLoading(),
                dataType: 'json',
                data: {
                    id: id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
            }).done(function (res) {
                if (res.html) {
                    hideLoading();
                    $('.noi-dung').val(res.html.noi_dung);
                    $('.han-xu-ly').val(res.html.han_xu_ly);
                    $('.id-cv').val(id);
                    $("#myModal").modal('show');
                } else {

                }

            });
        }


    </script>
@endsection
