<div class="modal-dialog modal-lg" >
    <!-- Modal content-->
    <div class="modal-content" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">DANH SÁCH NGHI TRÙNG</h4>
        </div>
        <div class="modal-body" >
            <div class="card">
                <div class="body table-responsive" style="max-height: 500px;overflow: auto">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable" >
                        <thead>
                        <tr>
                            <th class="text-center" width="50">Stt</th>
                            <th class="text-center" width="400">Nội dung</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key=>$vanBanDen)
                            <tr>
                                <td scope="row" class="text-center" style="vertical-align: middle;">{{$key+1}}</td>
                                <td style="word-wrap: break-word">
                                    <p title="{{$vanBanDen->so_ky_hieu}}">
                                        <b>({{$vanBanDen->so_ky_hieu}})</b> {{$vanBanDen->trich_yeu}}
                                    </p>
                                    <p> Số đến: <span  style="color: red">{{$vanBanDen->so_den ?? ''}}</span></p>
                                    <p> Cơ quan ban hành: {{$vanBanDen->co_quan_ban_hanh ?? ''}}</p>
                                    <p> Ngày ban
                                        hành: {{!empty((int)$vanBanDen->ngay_ban_hanh) ? date('d/m/Y',strtotime($vanBanDen->ngay_ban_hanh)) : ''}}</p>
                                    <p> Người ký: {{$vanBanDen->nguoi_ky ?? ''}}</p>
                                    <p> Loại văn bản: {{$vanBanDen->loaivanban->ten_loai_van_ban ?? ''}}</p>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-primary" form="myform" type="submit">Tiếp tục thêm</button>
                    <!-- pagination -->
                    <div id="modal-pagination">

                    </div>
                </div>
            </div>
        </div>
{{--                <div class="modal-footer" style="text-align: center; ">--}}
{{--                    <button type="button" class="btn btn-lg btn-danger remove-multi-file" id="comfirmCreateDoc">Tiếp tục tạo mới</button>--}}
{{--                </div>--}}
    </div>
</div>

{{--<div class="modal fade show" id="myModal" tabindex="-1" role="dialog"--}}
{{--     aria-labelledby="exampleModalLabel">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">×</span></button>--}}
{{--                <h4 class="modal-title"><i--}}
{{--                        class="fa fa-folder-open-o"></i> Tải nhiều tệp tin</h4>--}}
{{--            </div>--}}
{{--            <form class="form-row" method="post"--}}
{{--                  action="{{route('multiple_file')}}"--}}
{{--                  enctype="multipart/form-data">--}}
{{--                @csrf--}}
{{--                <div class="form-group col-md-12">--}}
{{--                    <label for="sokyhieu" class="">Chọn tệp--}}
{{--                        tin</label><br>--}}
{{--                    <input type="file" multiple name="ten_file[]"--}}
{{--                           accept=".xlsx,.xls,image/*,.doc, .docx,.txt,.pdf"/>--}}
{{--                    <input type="text" id="url-file" value="123"--}}
{{--                           class="hidden" name="txt_file[]">--}}
{{--                </div>--}}
{{--                <div class="form-group col-md-4" >--}}
{{--                    <button class="btn btn-primary">Tải lên</button>--}}
{{--                </div>--}}

{{--            </form>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>--}}
{{--                <button type="button" class="btn btn-outline">Save changes</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <!-- /.modal-content -->--}}
{{--    </div>--}}
{{--    <!-- /.modal-dialog -->--}}
{{--</div>--}}
