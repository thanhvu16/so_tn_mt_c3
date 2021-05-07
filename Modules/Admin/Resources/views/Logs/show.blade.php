<div class="modal-header" style="padding: 12px; background: #daf7f5;">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-original-title="" title="">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title text-bold" id="exampleModalLabel">#Chi tiết</h4>
</div>
<div class="modal-body">
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                @foreach($decode_content[0] as $data)
                    @foreach($data as $key => $content)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $content }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-default btn-sm border" data-dismiss="modal" data-original-title=""
                title=""><i class="fa fa-close"></i> Đóng lại
        </button>
    </div>
</div>
