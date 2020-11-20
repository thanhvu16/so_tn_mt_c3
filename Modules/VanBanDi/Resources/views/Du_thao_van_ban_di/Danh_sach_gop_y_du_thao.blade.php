@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="{{route('danhsachgopy')}}" aria-expanded="false" class="nav-link {{ Route::is('danhsachgopy')
                             ? 'active'  : '' }} ">
                            <i class="far fa-plus-square"></i> Danh sách góp ý
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('danhsachgopyxong')}}" aria-expanded="false" class="nav-link {{ Route::is('danhsachgopyxong')
                             ? 'active'  : '' }}">
                            <i class="far fa-plus-square"></i> Danh sách đã góp ý
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">

                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center" width="5%">STT</th>
                                            <th class="text-center" width="10%"> Ngày dự thảo

                                            </th>
                                            <th class="text-center" width="10%">Ký hiệu

                                            </th>
                                            <th class="text-center" width="30%">Trích yếu

                                            </th>
                                            <th class="text-center" width="10%">File</th>
                                            <th class="text-center" width="30%">Ý kiến</th>
                                            <th class="text-center" width="5%">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($canbogopy as $key=>$data)
                                            <form action="{{  route('gopy',$data->id)}}"
                                                  method="post" enctype="multipart/form-data">
                                                @csrf
                                                <tr>
                                                    <td> {{$key+1}}</td>
                                                    <td>{{dateFormat('d/m/Y',$data->thongtinduthao->ngay_thang ?? '')}}</td>
                                                    <td>{{$data->thongtinduthao->so_ky_hieu ?? ''}}</td>
                                                    <td style="text-align: justify"><a href=""
                                                           title="{{$data->thongtinduthao->vb_trich_yeu ?? ''}}">{{$data->thongtinduthao->vb_trich_yeu ?? ''}}</a><br>
                                                        <span
                                                            style="font-style: italic">Người nhập : {{$data->thongtinduthao->nguoiDung->ho_ten ?? ''}}</span>

                                                    </td>
                                                    <td class="text-center">
                                                        @forelse($data->thongtinduthao->Duthaofile as $key=>$item)
                                                            <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                @if($item->stt == 1)
                                                                    [file phiếu trình]
                                                                @elseif($item->stt == 2)
                                                                    [file trình ký]
                                                                @elseif($item->stt == 3)
                                                                    [file hồ sơ]
                                                                @endif
                                                            </a><br>

                                                        @empty
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        <div class="col-md-12">
                                                                <textarea rows="2"
                                                                          class="form-control @if($data->trang_thai == 2) hidden @else  @endif"
                                                                          required
                                                                          placeholder="Nhập ý kiến góp ý ..."
                                                                          name="y_kien"
                                                                          type="text"></textarea>
                                                        </div>
                                                        <div
                                                            class="col-md-12 @if($data->trang_thai == 2) hidden @else  @endif "
                                                            style="margin-top: 5px">
                                                            <input class="form-control hidden " value="123hihi"
                                                                   name="txt_file[]" type="text">
                                                            <input type="file" id="url-file" name="ten_file[]">
                                                            <input type="text" id="url-file"
                                                                   value="{{$data->du_thao_vb_id}}" name="id_van_ban"
                                                                   class="hidden">
                                                            <input type="text" id="url-file"
                                                                   value="{{$data->id}}" name="id_can_bo"
                                                                   class="hidden">
                                                        </div>
                                                        <div
                                                            class="col-md-12  @if($data->trang_thai == 2) @else hidden @endif">
                                                            <div class="">
                                                                {{$data->y_kien}}
                                                                @forelse($data->gopyFilecanbophong as $key=>$item)
                                                                    <a href="{{$item->getUrlFile()}}" target="_blank">
                                                                        [xem file góp ý]
                                                                @empty
                                                                @endforelse

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($data->trang_thai == 2)
{{--                                                            <a href="" class="btn btn-danger btn-xs" role="button"--}}
{{--                                                               aria-expanded="false"><span class="btn btn-danger">--}}
{{--                                        <i class="fa fa-plus"></i> Sửa</span>--}}
{{--                                                            </a>--}}
                                                            <button onclick="showModal()" class="btn btn-danger">Sửa</button>
                                                        @else
                                                            <button class="btn btn-success">Duyệt</button> @endif


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
                                                                                   value="{{$data->id}}" name="id_can_bo"
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
                                        @endforelse
                                        @forelse ($canbogopyngoai as $key=>$data)
                                            <form action="{{  route('themgopyvbngoai',$data->id)}}"
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
                                                        <div class="col-md-12">
{{--                                                            @if($nguoinhan == null)--}}
{{--                                                            @else--}}
{{--                                                                <div style="margin: 5px 0px">--}}
{{--                                                                    <select name="can_bo_chuyen_xuong"--}}
{{--                                                                            class="form-control dropdown-search" id="">--}}
{{--                                                                        <option value="">--Chọn cán bộ góp ý----}}
{{--                                                                        </option>--}}
{{--                                                                        @forelse($nguoinhan as $data2)--}}
{{--                                                                            <option value="{{$data2->id}}">{{$data2->ho_ten}}--}}
{{--                                                                            </option>--}}
{{--                                                                        @empty--}}
{{--                                                                        @endforelse--}}
{{--                                                                    </select>--}}
{{--                                                                </div>--}}
{{--                                                            @endif--}}
                                                            <textarea rows="2"
                                                                      class="form-control"
                                                                      required
                                                                      placeholder="Nhập ý kiến góp ý ..."
                                                                      name="y_kien"
                                                                      type="text"></textarea>
                                                        </div>
                                                        <div
                                                            class="col-md-12"
                                                            style="margin-top: 5px">
                                                            <input class="form-control hidden " value="123hihi"
                                                                   name="txt_file[]" type="text">
                                                            <input type="file" id="url-file" name="ten_file[]">
                                                            <input type="text" id="url-file"
                                                                   value="{{$data->du_thao_vb_id}}" name="id_van_ban"
                                                                   class="hidden">
                                                            <input type="text" id="url-file"
                                                                   value="{{$data->id}}" name="id_can_bo"
                                                                   class="hidden">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-success">Duyệt</button>
                                                    </td>
                                                </tr>
                                            </form>
                                            <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content" style="max-width: 500px">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true">×
                                                            </button>
                                                            <h4 class="modal-title" id="myModalLabel"><i
                                                                    class="fas fa-file-alt btn-color-blue"></i> Sửa ý
                                                                kiến</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <form class="form-row" method="post"
                                                                          action="{{route('sugopy',$data->id)}}"
                                                                          enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="form-group col-md-12">
                                                                            <label for="sokyhieu"
                                                                                   class="col-form-label">Ý kiến</label>
                                                                            <textarea class="form-control"
                                                                                      name="y_kien_sua"
                                                                                      rows="3"></textarea>
                                                                        </div>
                                                                        <div class="form-group col-md-8">
                                                                            <label for="sokyhieu"
                                                                                   class="col-form-label">Chọn tệp
                                                                                tin</label><br>
                                                                            <input type="file" id="url-file"
                                                                                   name="ten_file[]">
                                                                            <input class="form-control hidden "
                                                                                   value="123hihi"
                                                                                   name="txt_file[]" type="text">
                                                                        </div>
                                                                        <div class="form-group col-md-4"
                                                                             style="margin-top: 26px">
                                                                            <input type="text" id="url-file"
                                                                                   value="{{$data->du_thao_vb_id}}"
                                                                                   name="id_van_ban"
                                                                                   class="hidden">
                                                                            <input type="text" id="url-file"
                                                                                   value="{{$data->id}}"
                                                                                   name="id_can_bo"
                                                                                   class="hidden">
                                                                            <button
                                                                                class="btn btn-primary capnhatykien">Cập
                                                                                nhật
                                                                            </button>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có góp ý ngoài đơn vị nào !!</td>
                                            </tr>
                                        @endforelse
                                        </tbody>

                                    </table>
                                </div>
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
@endsection
@section('script')
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
<style type="text/css">
    .modal {
        text-align: center;
        padding: 0!important;
    }

    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: top;
    }
</style>
