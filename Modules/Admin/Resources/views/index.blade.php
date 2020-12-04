@extends('admin::layouts.master')
@section('page_title', 'Dashboard')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                {{--                        @if (Auth::user()->quyen_tham_muu)--}}
                {{--                            @include('admin::dashboard.van_ban_den')--}}
                {{--                        @endif--}}

                {{--                        @if(Auth::user()->quyen_han != ADMIN  && (Auth::user()->quyen_vanthu_cq == 0 && Auth::user()->quyen_vanthu_dv == 0))--}}
                {{--                            @include('admin::dashboard.chi-dao-dieu-hanh.index')--}}
                {{--                        @endif--}}

                {{--                        @if (Auth::user()->quyen_han != ADMIN && Auth::user()->quyen_vanthu_cq == 0 && Auth::user()->quyen_vanthu_cq == 0)--}}

                {{--                        @endif--}}

                {{--                        @if ((in_array(Auth::user()->quyen_vanthu_cq, QUYEN_VAN_THU) || in_array(Auth::user()->quyen_vanthu_dv, QUYEN_VAN_THU)))--}}
                @include('admin::dashboard.nhap_van_ban_den')
                @include('admin::dashboard.nhap_van_ban_di')
                @include('admin::dashboard.du_thao_van_ban')
                {{--                        @endif--}}


                {{--                        @if(Auth::user()->quyen_han != ADMIN  && (Auth::user()->quyen_vanthu_cq == 0 && Auth::user()->quyen_vanthu_dv == 0) && in_array(Auth::user()->donVi->cap_don_vi, [DON_VI_CAP_3]))--}}
                {{--                            @include('admin::dashboard.cong-viec-don-vi.index')--}}
                {{--                        @endif--}}
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        // google.charts.setOnLoadCallback(drawChart);
        // google.charts.setOnLoadCallback(drawChartVanChoPhanLoai);
        google.charts.setOnLoadCallback(drawChartDuThaoVanBan);
        google.charts.setOnLoadCallback(drawChartNhapVanBanDen);
        google.charts.setOnLoadCallback(drawChartNhapVanBanDi);
        // google.charts.setOnLoadCallback(drawChartCongViecDonVi);
        // google.charts.setOnLoadCallback(drawChartVanBanDi);

        {{--let dataPiceChiDao = <?php echo json_encode($chiDaoDieuHanhPiceCharts, JSON_NUMERIC_CHECK); ?>--}}

        {{--function drawChart() {--}}
        {{--    let data = google.visualization.arrayToDataTable(dataPiceChiDao);--}}
        {{--    let options = {--}}
        {{--        'title': '',--}}
        {{--        titleTextStyle: {--}}
        {{--            bold: true,--}}
        {{--            fontSize: 14,--}}
        {{--        },--}}
        {{--        legend: {position: 'none'},--}}
        {{--        colors: <?php echo json_encode($vanThuVanBanDiCoLors); ?>--}}
        {{--    };--}}

        {{--    if (document.getElementById('piechart') != undefined) {--}}
        {{--        let chart = new google.visualization.PieChart(document.getElementById('piechart'));--}}
        {{--        chart.draw(data, options);--}}
        {{--    }--}}
        {{--};--}}

        {{--function drawChartVanChoPhanLoai() {--}}

        {{--    let data = google.visualization.arrayToDataTable(<?php echo json_encode($phanLoaiVanBanPiceCharts,--}}
        {{--        JSON_NUMERIC_CHECK); ?>);--}}

        {{--    // Optional; add a title and set the width and height of the chart--}}
        {{--    let options = {--}}
        {{--        'title': '',--}}
        {{--        titleTextStyle: {--}}
        {{--            bold: true,--}}
        {{--            fontSize: 14,--}}
        {{--        },--}}
        {{--        legend: {position: 'none'},--}}
        {{--        colors: <?php echo json_encode($phanLoaiVanBanCoLors); ?>--}}
        {{--    };--}}

        {{--    if (document.getElementById('pie-chart-phan-loai-van-ban') != undefined) {--}}
        {{--        let chart = new google.visualization.PieChart(document.getElementById('pie-chart-phan-loai-van-ban'));--}}
        {{--        chart.draw(data, options);--}}
        {{--    }--}}
        {{--};--}}

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
