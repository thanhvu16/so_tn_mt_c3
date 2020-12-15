<div class="col-md-6 col-sm-12">
    <div class="panel panel-info">
        <div class="panel-heading col-md-12 pl-1" style="background:#3c8dbc;color:white;font-weight: bold">
                <div class="col-md-7">
                    <i class="fa fa-envelope-o"></i>
                    <span>&ensp;Văn bản đến</span>
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
                    @role('văn thư huyện')
                    <a class="text-title-item" href="{{route('dsvanbandentumail')}}">
                        <p>Hòm thư công
                            <button
                            class="btn br-10 btn-primary btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $homThuCong }}</button>
                        </p>
                    </a>
                    @endrole
                    @role('văn thư đơn vị')
                    <a class="text-title-item" href="{{ route('don-vi-nhan-van-ban-den.index') }}">
                        <p>Văn bản chờ vào sổ
                            <button
                                class="btn br-10 btn-warning btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $vanBanDenDonViChoVaoSo }}</button>
                        </p>
                    </a>
                    @endrole

                    <a class="text-title-item" href="{{ route('van-ban-den.index') }}">
                        <p>Danh sách văn bản đến
                            <button
                                class="btn br-10 btn-pinterest btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $danhSachVanBanDen }}</button>
                        </p>
                    </a>
                    <a class="text-title-item" href="{{ route('giay-moi-den.index') }}">
                        <p>Danh sách giấy mời đến
                            <button
                                class="btn br-10 btn-success btn-circle waves-effect waves-light btn-sm pull-right count-item">{{ $giayMoiDen }}</button>
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