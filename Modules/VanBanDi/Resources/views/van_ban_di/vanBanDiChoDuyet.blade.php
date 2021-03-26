@extends('admin::layouts.master')
@section('page_title', 'Danh sách văn bản đi')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách văn bản đi đã tạo</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12 mt-1 ">




                    </div>



                    <div class="box-body">
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th width="2%" style="vertical-align: middle" class="text-center">STT</th>
                                <th width="26%" style="vertical-align: middle" class="text-center">Thông
                                    tin
                                </th>
                                <th width="38%" style="vertical-align: middle" class="text-center">Trích yếu
                                </th>
                                <th width="21%" style="vertical-align: middle" class="text-center">Nơi
                                    nhận
                                </th>
                                <th width="6%" style="vertical-align: middle" class="text-center">Tác vụ
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ds_vanBanDi as $key=>$vbDi)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>
                                        <p>- Số ký hiệu: {{$vbDi->so_ky_hieu}}</p>
                                        <p>- Ngày ban
                                            hành: {{ date('d-m-Y', strtotime($vbDi->ngay_ban_hanh)) }}</p>
                                        <p>- Loại văn bản: {{$vbDi->loaivanban->ten_loai_van_ban ?? ''}}</p>
                                        <p>- Số đi: <span
                                                class="font-bold color-red">Chưa cấp số</span></p>
                                    </td>
                                    <td style="text-align: justify"><a
                                            href="{{ route('Quytrinhxulyvanbandi',$vbDi->id) }}"
                                            title="{{$vbDi->trich_yeu}}">{{$vbDi->trich_yeu}}</a>
                                        <div class="text-right " style="pointer-events: auto">
                                            @forelse($vbDi->filechinh as $filedata)
                                                <a class="seen-new-window" target="popup"
                                                   href="{{$filedata->getUrlFile()}}">[File văn bản đi]</a>
                                            @empty
                                            @endforelse
                                       {{--         @forelse($vbDi->filetrinhky as $filedata)
                                                <a class="seen-new-window" target="popup"
                                                   href="{{$filedata->getUrlFile()}}">[File trình ký]</a>
                                            @empty
                                            @endforelse
                                            @forelse($vbDi->filephieutrinh as $filedata)
                                                &nbsp; |<a class="seen-new-window" target="popup"
                                                           href="{{$filedata->getUrlFile()}}"> [File phiếu
                                                    trình]</a>
                                            @empty
                                            @endforelse
                                            @forelse($vbDi->filehoso as $filedata)
                                                &nbsp; |<a href="{{$filedata->getUrlFile()}}"> [File hồ
                                                    sơ]</a>
                                            @empty
                                            @endforelse--}}
                                            {{--                                                        @if(Auth::user()->quyen_vanthu_cq == 1 || Auth::user()->quyen_vanthu_dv == 1)--}}
                                            {{--                                                            <a title="Cập nhật file" href="{{route('ds_file_di',$vbDi->id)}}"><span role="button">&emsp;<i class="fa  fa-search"></i></span></a>@endif--}}
                                        </div>
                                    </td>
                                    <td>
                                        {{--                                                    {{$vbDi->mailtrongtp}}--}}
                                        @forelse($vbDi->donvinhanvbdi as $key=>$item)
                                            <p>
                                                - {{$item->laytendonvinhan->ten_don_vi ?? ''}}
                                            </p>
                                        @empty
                                        @endforelse
                                        @forelse($vbDi->mailngoaitp as $key=>$item)
                                            <p>
                                                - {{$item->laytendonvingoai->ten_don_vi ?? ''}}
                                            </p>
                                        @empty
                                        @endforelse
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">
                                        <form method="Get" action="{{route('vanbandidelete',$vbDi->id)}}">
                                            @csrf
                                            <a href="{{route('van-ban-di.edit',$vbDi->id)}}"
                                               class="fa fa-edit" role="button"
                                               title="Sửa">
                                                <i class="fas fa-file-signature"></i>
                                            </a>
{{--                                            <br><br>--}}
{{--                                            <button--}}
{{--                                                class="btn btn-action btn-color-red btn-icon btn-ligh btn-remove-item"--}}
{{--                                                role="button"--}}
{{--                                                title="Xóa">--}}
{{--                                                <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>--}}
{{--                                            </button>--}}
{{--                                            <input type="text" class="hidden" value="{{$vbDi->id}}" name="id_vb">--}}
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="5" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6" style="margin-top: 5px">
                                Tổng số văn bản: <b>{{ $ds_vanBanDi->count() }}</b>
                            </div>
                            <div class="col-md-6 text-right">
{{--                                {!! $ds_vanBanDi->appends(['loaivanban_id' => Request::get('loaivanban_id'), 'sovanban_id' => Request::get('sovanban_id')--}}
{{--                                   ,'vb_sokyhieu' => Request::get('vb_sokyhieu'),--}}
{{--                                   'donvisoanthao_id' => Request::get('donvisoanthao_id'),'start_date' => Request::get('start_date'),--}}
{{--                                   'end_date' => Request::get('end_date'),'nguoiky_id' => Request::get('nguoiky_id'),'chuc_vu' => Request::get('chuc_vu'),--}}
{{--                                   'vb_trichyeu' => Request::get('vb_trichyeu'),'search' =>Request::get('search'), 'year' => Request::get('year')])->render() !!}--}}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->


                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('multiple_file_di') }}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title"><i
                                                class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="sokyhieu" class="col-form-label">Chọn tệp tin
                                                    <br>
                                                    <small><i>(Đặt tên file theo định dạng: tên viết tắt
                                                            loại văn bản + số đi + năm (vd:
                                                            QD-1-2020.pdf))</i></small>
                                                </label><br>
                                                <input type="file" multiple name="ten_file[]"
                                                       accept=".xlsx,.xls,.doc, .docx,.txt,.pdf"/>
                                                <input type="text" id="url-file" value="123" class="hidden"
                                                       name="txt_file[]">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <button class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Tải
                                                    lên
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </form>
                            </div>
                        </div>
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
    </script>
@endsection
