@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-3">Dự thảo văn bản </h4>
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="far fa-plus-square"></i> Danh sách
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">

                            </div>
                            <div class="col-md-12" style=" width: 100%;overflow-x: auto;">

                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th width="10">STT</th>
                                            <th width="75"> Ngày dự thảo

                                            </th>
                                            <th width="45">Ký hiệu

                                            </th>
                                            <th width="400">Trích yếu

                                            </th>
                                            <th width="50">file</th>
                                            <th width="260">Ý kiến</th>
                                            <th width="10">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($canbogopy as $key=>$data)
                                            <form action="{{  route('gopy',$data->id)}}"
                                                  method="post" enctype="multipart/form-data">
                                                @csrf
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{dateFormat('d/m/Y',$data->thongtinduthao->ngay_thang ?? '')}}</td>
                                                    <td>{{$data->thongtinduthao->so_ky_hieu ?? ''}}</td>
                                                    <td><a href=""
                                                           title="{{$data->thongtinduthao->vb_trich_yeu}}">{{$data->thongtinduthao->vb_trich_yeu}}</a><br>
                                                        <span
                                                            style="font-style: italic">Người nhập : {{$data->thongtinduthao->nguoiDung->ho_ten ?? ''}}</span>

                                                    </td>
                                                    <td>
                                                        @forelse($data->thongtinduthao->Duthaofile as $key=>$item)
                                                            <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                [File dự thảo {{$key+1}}]
                                                            </a>

                                                        @empty
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        <div class="col-md-12  @if($data->trang_thai == 2) @else hidden @endif">
                                                            <div class="">
                                                                {{$data->y_kien}}
                                                                @forelse($data->gopyFilecanbophongngoai as $key=>$item)
                                                                    <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                        [xem file góp ý]
                                                                @empty
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($data->trang_thai == 2)
                                                        <button onclick="showModal()" data-sua-can-bo="{{$data->id}}" class="btn btn-danger layid">Sửa</button>
                                                        @else
                                                        -
                                                            @endif
                                                    </td>
                                                </tr>
                                            </form>
                                            <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content" style="max-width: 500px">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fas fa-file-alt btn-color-blue"></i> Sửa ý kiến</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <form class="form-row"  method="post" action="{{route('sugopy',$data->id)}}" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="form-group col-md-12">
                                                                            <label for="sokyhieu"  class="col-form-label">Ý kiến</label>
                                                                            <textarea class="form-control" name="y_kien_sua" rows="3"></textarea>
                                                                        </div>
                                                                        <div class="form-group col-md-8">
                                                                            <label for="sokyhieu" class="col-form-label">Chọn tệp tin</label><br>
                                                                            <input type="file" id="url-file" name="ten_file[]">
                                                                            <input class="form-control hidden " value="123hihi"
                                                                                   name="txt_file[]" type="text">
                                                                        </div>
                                                                        <div class="form-group col-md-4" style="margin-top: 26px">
                                                                            <input type="text" id="url-file"
                                                                                   value="{{$data->du_thao_vb_id}}" name="id_van_ban"
                                                                                   class="hidden">
                                                                            <input type="text" id="url-file"
                                                                                   value="" name="id_can_bo"
                                                                                   class="hidden">
                                                                            <button class="btn btn-primary capnhatykien">Cập nhật</button>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                        @empty
                                            <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>

                                    </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
        $('.bttuonsua').on('click', function () {
            let idcanbo = $(this).data('sua-can-bo');
            $('input[name="id_can_bo"]').val(idcanbo);
            console.log(idcanbo);

        })
    </script>
@endsection
