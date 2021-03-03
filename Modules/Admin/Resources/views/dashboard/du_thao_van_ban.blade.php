<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold" >
                <div class="col-md-7">
                    <i class="fa fa-hourglass-3"></i>
                    <span>&ensp;Dự thảo văn bản đi</span>
                </div>
                <div class="col-md-5 text-center panel-bieu-do">
                    <span class="text-center">Biểu đồ</span>
                </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
                <div class="col-md-7 pl-1">
                    <a class="text-title-item" href="{{ route('du-thao-van-ban.index') }}">
                        <p>Nhập mới dự thảo</p>
                    </a>
                    <a class="text-title-item" href="{{ route('Danhsachduthao') }}">
                        <p>D/s cá nhân dự thảo
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $danhSachDuThao }}</button>
                        </p>
                    </a>
                    <a class="text-title-item" href="{{ route('danhsachgopy') }}">
                        <p>D/s dự thảo chờ góp ý
                            <button
                                class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$gopy}}</button>
                        </p>
                    </a>
                        <a class="text-title-item" href="{{ route('danh_sach_vb_di_cho_duyet') }}">
                            <p>D/s văn bản đi chờ duyệt
                                <button
                                    class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$vanbandichoduyet}}</button>
                            </p>
                        </a>

{{--                    <a class="text-title-item" href="">--}}
{{--                        <p>D/s văn bản đi <button--}}
{{--                                class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">3</button>--}}
{{--                        </p>--}}
{{--                    </a>--}}

                    <a class="text-title-item" href="{{ route('vb_di_tra_lai') }}">
                        <p>D/s văn bản trả lại <button
                                class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$van_ban_di_tra_lai}}</button>
                        </p>
                    </a>
                </div>
                <div class="col-md-5 ">
                    <div id="pie-chart-du-thao-van-ban">

                    </div>
                </div>
        </div>
    </div>
</div>
@hasanyrole('trưởng phòng|phó phòng|phó chánh văn phòng|chánh văn phòng|tp đơn vị cấp 2|phó tp đơn vị cấp 2|chuyên viên')
    <div class="clearfix"></div>
@endrole
