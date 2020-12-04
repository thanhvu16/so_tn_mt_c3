<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold">
                <div class="col-md-7">
                    <i class="fa  fa-send-o"></i>
                    <span>&ensp;Xử lý văn bản đi</span>
                </div>
                <div class="col-md-5 text-center panel-bieu-do">
                    <span class="text-center">Biểu đồ</span>
                </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
                <div class="col-md-7 pl-1">
                    <a class="text-title-item" href="{{ route('van-ban-di.create') }}">
                        <p>Nhập mới văn bản đi</p>
                    </a>

                    <a class="text-title-item" href="{{ route('giay-moi-di.create') }}">
                        <p>Nhập mới giấy mời</p>
                    </a>
                    <a class="text-title-item"  href="{{ route('giay-moi-di.index') }}">
                        <p>Danh sách giấy mời đi <button
                                class="btn br-10 btn-danger btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$ds_giaymoidi}}</button>
                        </p>
                    </a>
                    <a class="text-title-item" href="{{ route('van-ban-di.index') }}">
                        <p> Danh sách văn bản đi <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$ds_vanBanDi}}</button>
                        </p>
                    </a>
                    <a class="text-title-item"  href="{{ route('vanbandichoso') }}" >
                        <p>Danh sách chờ số
                            <button
                                class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$vanbandichoso}}</button>
                        </p>
                    </a>
                </div>
                <div class="col-md-5 ">
                    <div id="pie-chart-van-thu-nhap-van-ban-di">

                    </div>
                </div>
        </div>
    </div>
</div>
