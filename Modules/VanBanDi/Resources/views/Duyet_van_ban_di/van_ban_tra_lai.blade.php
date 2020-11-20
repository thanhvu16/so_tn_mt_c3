@extends('administrator::layouts.master')
@section('page_title', 'Văn bản đi chờ duyệt')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-3">Văn bản đi trả lại</h4>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th style="width: 5%; vertical-align: middle;" class="text-center">STT</th>
                                            <th width="10%" class="text-center" style="vertical-align: middle;">Số ký
                                                hiệu
                                            </th>
                                            <th width="30%" class="text-center" style="vertical-align: middle;">Trích
                                                yếu
                                            </th>
                                            <th width="15%" class="visible-lg text-center"
                                                style="vertical-align: middle;">Ý kiến
                                            </th>
{{--                                            <th width="10%" class="text-center" style="vertical-align: middle;">Tệp--}}
{{--                                                tin--}}
{{--                                            </th>--}}
                                            <th width="30%" class="text-center" style="vertical-align: middle;">Ý kiến
                                                gửi đi
                                            </th>
                                            <th width="10%" class="text-center" style="vertical-align: middle;">Gửi</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($van_ban_di_tra_lai as $key=>$vanban)
                                            <form action="{{route('duyet-vbdi')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <tr class="duyet-gia-han">
                                                    <td
                                                        class="text-center"> {{$key+1}}</td>
                                                    <td>{{$vanban->vanbandi->vb_sokyhieu ?? ''}}</td>
                                                    <td><a
                                                            href="">{{$vanban->vanbandi->vb_trichyeu ?? ''}}</a><br>
                                                        -Người nhập:{{$vanban->vanbandi->nguoitao->ho_ten}} </td>
                                                    <td> &emsp;<span style="font-weight: bold"> {{$vanban->y_kien_gop_y ?? ''}}</span>
                                                        <br>
                                                        <span
                                                            style="font-style: italic">- Người ký: {{$vanban->vanbandi->nguoidung2->ho_ten ?? ''}} </span>
                                                        <br>
                                                        <span
                                                            style="font-style: italic">  - {{$vanban->vanbandi->vb_ngaybanhanh ?? ''}}</span>
                                                    </td>
{{--                                                    <td class="text-center" style="vertical-align: middle;">--}}
{{--                                                        @forelse($vanban->file as $filedata)--}}
{{--                                                            <a href="{{$filedata->getUrlFile()}}">File Trình Ký</a>--}}
{{--                                                        @empty--}}
{{--                                                        @endforelse<br>--}}
{{--                                                    </td>--}}
                                                    <td>
                                                        @if($userAuth->id == $idnguoiky)
                                                            <input type="text" class="hidden" value="1"
                                                                   name="vb_cho_so">
                                                            {{--                                                            <span style="font-weight: bold;color: red">&emsp;Chuyển văn thư cấp số</span>--}}
                                                        @else
                                                            <div class="col-md-12 form-group">
                                                                <select name="nguoi_nhan" id=""
                                                                        class="form-control select2-search">
                                                                    @forelse ($nguoinhan as $data)
                                                                        <option value="{{ $data->id }}"
                                                                        >{{ $data->ho_ten}}</option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                            <input type="text" class="hidden" value="0"
                                                                   name="vb_cho_so">
                                                        @endif
                                                        <input type="text" class="hidden" name="id_vb_cho_duyet"
                                                               value="{{$vanban->id}}">
                                                        <input type="text" class="hidden" name="id_van_ban"
                                                               value="{{$vanban->van_ban_di_id}}">


                                                        <div class="col-md-12 form-group">
                                                            <textarea class="form-control noi-dung"
                                                                      placeholder="nhập ý kiến tại đây" name="noi_dung"
                                                                      rows="3"
                                                                      required></textarea>

                                                        </div>
                                                            <div class="col-md-12 form-group">
                                                                <input type="file" class="form-control" name="ten_file[]">
                                                                <input type="text" value="123" class="form-control hidden" name="txt_file[]">

                                                            </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button
                                                            class="btn waves-effect btn-primary btn-choose-status"
                                                            name="submit_Duyet_lai" value="3" data-type="3">Duyệt
                                                        </button>
                                                        <br>
                                                        <br>


                                                    </td>

                                                </tr>
                                            </form>
                                        @empty
                                            <td colspan="7" class="text-center">Không tìm
                                                thấy dữ liệu.
                                            </td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row col-md-12 mb-1">
                                        {{--                                        <div class="float-left">--}}
                                        {{--                                            Tổng số văn bản: <b></b>--}}
                                        {{--                                        </div>--}}
                                        <br>
                                    </div><!--col-->
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
    </script>
@endsection
