@extends('admin::layouts.master')
@section('page_title', 'Sở TNMT')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="row">
                        <form action="{{route('tim-kiem-van-ban-den-full.index')}}" method="get">
                        <div class="col-md-8">
                            <label for="tu_khoa" class="col-form-label">Tìm kiếm văn bản đến </label>
                            <input type="text" value="{{Request::get('tu_khoa')}}"
                                   id="tu_khoa" name="tu_khoa" class="form-control"
                                   placeholder="Nhập từ khóa...">
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="tu_so" class="col-form-label">Tìm kiếm văn bản đi </label>
                            <input type="text" value="{{Request::get('tu_so')}}"
                                   id="tu_so" name="tu_so" class="form-control"
                                   placeholder="Nhập từ khóa...">
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                @unlessrole('văn thư sở|văn thư đơn vị')
                    @include('admin::dashboard.ho_so_cong_viec')
                    @include('admin::dashboard.xu_ly_giay_moi_den')
                @endunlessrole

                @hasanyrole('văn thư sở|văn thư đơn vị')
                    @include('admin::dashboard.nhap_van_ban_den')
                    @include('admin::dashboard.nhap_van_ban_di')
                @endrole
                @unlessrole('giám đốc / chi cục trưởng|phó giám đốc / phó chi cục trưởng|văn thư sở|văn thư đơn vị')
                @include('admin::dashboard.cong_viec_phong_ban')
                @endunlessrole

                @unlessrole('văn thư sở|văn thư đơn vị')
                    @include('admin::dashboard.du_thao_van_ban')
                @endunlessrole


            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChartHoSoCoViec);
        google.charts.setOnLoadCallback(drawChartGiayMoi);
        google.charts.setOnLoadCallback(drawChartDuThaoVanBan);
        google.charts.setOnLoadCallback(drawChartNhapVanBanDen);
        google.charts.setOnLoadCallback(drawChartNhapVanBanDi);
        google.charts.setOnLoadCallback(drawChartCongViecPhongBan);

        if ($.browser.webkit) {
            $("input").attr('autocomplete','off');
        }
        function drawChartHoSoCoViec() {
            let data = google.visualization.arrayToDataTable(<?php echo json_encode($hoSoCongViecPiceCharts,
                JSON_NUMERIC_CHECK); ?>);

            // Optional; add a title and set the width and height of the chart
            let options = {
                'title': '',
                titleTextStyle: {
                    bold: true,
                    fontSize: 14,
                },
                legend: {position: 'none'},
                colors: <?php echo json_encode($hoSoCongViecCoLors); ?>
            };

            if (document.getElementById('pie-chart-ho-so-cong-viec') != undefined) {
                let chart = new google.visualization.PieChart(document.getElementById('pie-chart-ho-so-cong-viec'));
                chart.draw(data, options);
            }
        };
        function drawChartGiayMoi() {
            let data = google.visualization.arrayToDataTable(<?php echo json_encode($giayMoiPiceCharts,
                JSON_NUMERIC_CHECK); ?>);

            // Optional; add a title and set the width and height of the chart
            let options = {
                'title': '',
                titleTextStyle: {
                    bold: true,
                    fontSize: 14,
                },
                legend: {position: 'none'},
                colors: <?php echo json_encode($giayMoiCoLors); ?>
            };

            if (document.getElementById('pie-chart-giay-moi') != undefined) {
                let chart = new google.visualization.PieChart(document.getElementById('pie-chart-giay-moi'));
                chart.draw(data, options);
            }
        };

        function drawChartCongViecPhongBan() {
            let data = google.visualization.arrayToDataTable(<?php echo json_encode($congViecPhongBanPiceCharts,
                JSON_NUMERIC_CHECK); ?>);

            // Optional; add a title and set the width and height of the chart
            let options = {
                'title': '',
                titleTextStyle: {
                    bold: true,
                    fontSize: 14,
                },
                legend: {position: 'none'},
                colors: <?php echo json_encode($congViecPhongBanCoLors); ?>
            };

            if (document.getElementById('pie-chart-cong-viec-phong-ban') != undefined) {
                let chart = new google.visualization.PieChart(document.getElementById('pie-chart-cong-viec-phong-ban'));
                chart.draw(data, options);
            }
        };


        function drawChartDuThaoVanBan() {

            let data = google.visualization.arrayToDataTable(<?php echo json_encode($duThaoPiceCharts,
                JSON_NUMERIC_CHECK); ?>);

            // Optional; add a title and set the width and height of the chart
            let options = {
                'title': '',
                titleTextStyle: {
                    bold: true,
                    fontSize: 14,
                },
                legend: {position: 'none'},
                colors: <?php echo json_encode($duThaoCoLors); ?>
            };

            if (document.getElementById('pie-chart-du-thao-van-ban') != undefined) {
                let chart = new google.visualization.PieChart(document.getElementById('pie-chart-du-thao-van-ban'));
                chart.draw(data, options);
            }
        };

        function drawChartNhapVanBanDen() {

            let data = google.visualization.arrayToDataTable(<?php echo json_encode($vanThuVanBanDenPiceCharts,
                JSON_NUMERIC_CHECK); ?>);

            // Optional; add a title and set the width and height of the chart
            let options = {
                'title': '',
                titleTextStyle: {
                    bold: true,
                    fontSize: 14,
                },
                legend: {position: 'none'},
                colors: <?php echo json_encode($vanThuVanBanDenCoLors); ?>
            };

            if (document.getElementById('pie-chart-van-thu-nhap-van-ban-den') != undefined) {
                let chart = new google.visualization.PieChart(document.getElementById('pie-chart-van-thu-nhap-van-ban-den'));
                chart.draw(data, options);
            }
        }

        function drawChartNhapVanBanDi() {

            let data = google.visualization.arrayToDataTable(<?php echo json_encode($vanThuVanBanDiPiceCharts,
                JSON_NUMERIC_CHECK); ?>);

            // Optional; add a title and set the width and height of the chart
            let options = {
                'title': '',
                titleTextStyle: {
                    bold: true,
                    fontSize: 14,
                },
                legend: {position: 'none'},
                colors: <?php echo json_encode($vanThuVanBanDiCoLors); ?>
            };

            if (document.getElementById('pie-chart-van-thu-nhap-van-ban-di') != undefined) {
                let chart = new google.visualization.PieChart(document.getElementById('pie-chart-van-thu-nhap-van-ban-di'));
                chart.draw(data, options);
            }
        }


    </script>
@endsection
