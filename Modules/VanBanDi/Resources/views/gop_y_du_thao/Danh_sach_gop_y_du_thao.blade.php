@extends('admin::layouts.master')
@section('page_title', 'Danh sách góp ý')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <ul class="nav nav-tabs">
                            <li class="{{ Route::is('danhsachgopy')
                             ? 'active'  : '' }}">
                                <a href="{{route('danhsachgopy')}}">
                                    <i class="fa fa-list"></i> Danh sách góp ý
                                </a>
                            </li>
                            <li class="{{ Route::is('danhsachgopyxong')
                             ? 'active'  : '' }}">
                                <a href="{{route('danhsachgopyxong')}}">
                                    <i class="fa fa-thumbs-o-up"></i> Danh sách đã góp ý</a>
                            </li>
                        </ul>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" width="5%">STT</th>
                                <th class="text-center" width="10%"> Ngày dự thảo

                                </th>
                                <th class="text-center" width="10%">Ký hiệu

                                </th>
                                <th class="text-center" width="">Trích yếu

                                </th>
                                <th class="text-center" width="12%">File</th>
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
                                        <td>
                                            {{ !empty($data->thongtinduthao) && !empty($data->thongtinduthao->ngay_thang) ? date('d-m-Y', strtotime($data->thongtinduthao->ngay_thang)) : '' }}
                                        </td>
                                        <td>{{$data->thongtinduthao->so_ky_hieu ?? ''}}</td>
                                        <td style="text-align: justify"><a href=""
                                                                           title="{{$data->thongtinduthao->vb_trich_yeu ?? ''}}">{{$data->thongtinduthao->vb_trich_yeu ?? ''}}</a><br>
                                            <span
                                                style="font-style: italic">Người nhập : {{$data->thongtinduthao->nguoiDung->ho_ten ?? ''}}</span>

                                        </td>
                                        <td class="text-center">
                                            @if ($data->thongtinduthao)
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
                                            @endif
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
                                <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content" style="max-width: 500px">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fas fa-file-alt btn-color-blue"></i> Sửa ý kiến</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <form class="form-row" method="post"
                                                              action="{{route('sugopy',$data->id)}}"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group col-md-12">
                                                                <label for="sokyhieu" class="col-form-label">Ý
                                                                    kiến</label>
                                                                <textarea class="form-control" name="y_kien_sua"
                                                                          rows="3"></textarea>
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <label for="sokyhieu" class="col-form-label">Chọn tệp
                                                                    tin</label><br>
                                                                <input type="file" id="url-file" name="ten_file[]">
                                                                <input class="form-control hidden " value="123hihi"
                                                                       name="txt_file[]" type="text">
                                                            </div>
                                                            <div class="form-group col-md-4" style="margin-top: 26px">
                                                                <input type="text" id="url-file"
                                                                       value="{{$data->du_thao_vb_id}}"
                                                                       name="id_van_ban"
                                                                       class="hidden">
                                                                <input type="text" id="url-file"
                                                                       value="{{$data->id}}" name="id_can_bo"
                                                                       class="hidden">
                                                                <button class="btn btn-primary capnhatykien">Cập nhật
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
                            @endforelse
                            @forelse ($canbogopyngoai as $key=>$data)
                                <form action="{{  route('themgopyvbngoai',$data->id)}}"
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <tr>
                                        <td>{{$key+1+$key2}}</td>
                                        <td>{{ !empty($data->thongtinduthao) ?  date('d-m-Y', strtotime($data->thongtinduthao->ngay_thang) ) : '' }}</td>
                                        <td>{{$data->thongtinduthao->so_ky_hieu ?? ''}}</td>
                                        <td><a href=""
                                               title="{{$data->thongtinduthao->vb_trich_yeu ?? null }}">{{$data->thongtinduthao->vb_trich_yeu ?? ''}}</a><br>
                                            <span
                                                style="font-style: italic">Người nhập : {{$data->thongtinduthao->nguoiDung->ho_ten ?? ''}}</span>

                                        </td>
                                        <td>
                                            @if ($data->thongtinduthao)
                                                @forelse($data->thongtinduthao->Duthaofile as $key=>$item)
                                                    <a href="{{$item->getUrlFile()}}" target="_blank">
                                                        @if($item->stt == 1)
                                                            [file phiếu trình]
                                                        @elseif($item->stt == 2)
                                                            [file trình ký]
                                                        @elseif($item->stt == 3)
                                                            [file hồ sơ]
                                                        @endif
                                                    </a>

                                                @empty
                                                @endforelse
                                            @endif
                                        </td>
                                        <td>
                                            <div class="col-md-12">
{{--                                                @if($nguoinhan == null)--}}
{{--                                                @else--}}
{{--                                                    <div style="margin: 5px 0px">--}}
{{--                                                        <select name="can_bo_chuyen_xuong"--}}
{{--                                                                class="form-control dropdown-search" id="">--}}
{{--                                                            <option value="">--Chọn cán bộ góp ý----}}
{{--                                                            </option>--}}
{{--                                                            @forelse($nguoinhan as $data2)--}}
{{--                                                                <option value="{{$data2->id}}">{{$data2->ho_ten}}--}}
{{--                                                                </option>--}}
{{--                                                            @empty--}}
{{--                                                            @endforelse--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
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
                                @if($key2 == 0 && $key1 == 0)
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                @endif
                            @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
        @endsection
