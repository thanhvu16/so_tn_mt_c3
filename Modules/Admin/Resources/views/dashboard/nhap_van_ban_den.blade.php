<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold">
                <div class="col-md-7">
                    <i class="fa fa-envelope-o"></i>
                    <span>&ensp;Xử lý văn bản đến</span>
                </div>
                <div class="col-md-5 text-center panel-bieu-do">
                    <span class="text-center">Biểu đồ</span>
                </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">
                <div class="col-md-7 pl-1">
                    <a class="text-title-item" href="{{ route('van-ban-den.create') }}">
                        <p>Nhập mới văn bản</p>
                    </a>

                    <a class="text-title-item" href="{{ route('giay-moi-den.create') }}">
                        <p>Nhập mới giấy mời</p>
                    </a>
                    <a class="text-title-item" href="{{route('dsvanbandentumail')}}">
                        <p>Hòm thư công
                            <button
                            class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$homthucong}}</button>
                        </p>
                    </a>
                    <a class="text-title-item" href="{{ route('van-ban-den.index') }}">
                        <p>Danh sách văn bản đến
                            <button
                                class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$ds_vanBanDen}}</button>
                        </p>
                    </a>
                    <a class="text-title-item" href="{{ route('giay-moi-den.index') }}">
                        <p>Danh sách giấy mời đến
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{$ds_giaymoiden}}</button>
                        </p>
                    </a>
                </div>
                <div class="col-md-5 ">
                    <div id="pie-chart-van-thu-nhap-van-ban-den">

                    </div>
                </div>
        </div>
    </div>
</div>
