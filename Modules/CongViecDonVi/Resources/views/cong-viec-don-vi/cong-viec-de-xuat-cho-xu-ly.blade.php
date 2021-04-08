@extends('admin::layouts.master')
@section('page_title', 'Công việc đề xuất chờ duyệt')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Công việc đề xuất chờ duyệt</h3>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered dataTable table-hover data-row">
                                <thead>
                                <tr role="row" class="text-center">
                                    <th width="4%" class="text-center">STT</th>
                                    <th width="13%" class="text-center">Người gửi</th>
                                    <th class="text-center">Nội dung công việc</th>
                                    <th width="10%" class="text-center">File</th>
                                    <th width="10%" class="text-center">Hạn xử lý</th>
                                    <th width="10%" class="text-center" class="text-center">Kết quả</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($congviecdexuat as $key=>$congViecDonVi)
                                    <form action="{{route('DuyetCongViecDeXuat')}}" method="post">
                                        @csrf
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-left">{{$congViecDonVi->chuyenvien->ho_ten ?? '' }}</td>
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
                                            <input type="text" class="hidden" name="id" value="{{$congViecDonVi->id}}">
                                            <button
                                                class="btn waves-effect btn-primary btn-choose-status btn-sm mb-2"
                                                name="submit_Duyet" onclick="" value="1" data-type="3">
                                                <i class="fa fa-send"></i> Duyệt
                                            </button><br>


                                            <button
                                                class="btn waves-effect btn-danger btn-choose-status btn-sm"
                                                name="submit_tralai" value="2" data-type="2"><i class="fa fa-refresh"></i> Trả lại
                                            </button>
                                        </td>

                                    </tr>
                                    </form>
                                @empty
                                    <td colspan="7"
                                        class="text-center">Không tìm
                                        thấy dữ liệu.
                                    </td>
                                @endforelse
                                </tbody>
                            </table>
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

    </script>
@endsection
