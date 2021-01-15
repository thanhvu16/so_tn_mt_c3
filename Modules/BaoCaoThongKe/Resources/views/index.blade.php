@extends('admin::layouts.master')
@section('page_title', 'Báo cáo thống kê văn bản')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <a href="{{ route('van-ban-den.index', 'year='.$year) }}">
                        <span class="info-box-icon bg-aqua">
                            <i class="fa fa-file-pdf-o"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text color-black">Văn bản đến</span>
                            <span class="info-box-number color-black">{{ $totalVanBanDen }}</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <a href="{{ route('giay-moi-den.index', 'year='.$year) }}">
                        <span class="info-box-icon bg-red"><i class="fa fa-send"></i></span>

                        <div class="info-box-content color-black">
                            <span class="info-box-text">Giấy mời đến</span>
                            <span class="info-box-number">{{ $totalGiayMoiDen }}</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <a href="{{ route('van-ban-di.index', 'year='.$year) }}">
                        <span class="info-box-icon bg-green"><i class="fa fa-file-text"></i></span>

                        <div class="info-box-content color-black">
                            <span class="info-box-text">Văn bản đi</span>
                            <span class="info-box-number">{{ $totalVanBanDi }}</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <a href="{{ route('giay-moi-di.index', 'year='.$year) }}">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-send-o"></i></span>

                        <div class="info-box-content color-black">
                            <span class="info-box-text">Giấy mời đi</span>
                            <span class="info-box-number">{{ $totalGiayMoiDi }}</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <div class="box box box-info">
            <div class="row">
                <div class="col-md-12">
                    @if (auth::user()->hasrole([CHU_TICH, PHO_CHUC_TICH]))
                        <h4 class="text-center text-uppercase">Biểu đồ thống kê văn bản
                            đến - đi của năm {{ $year }}</h4>
                    @else
                        <h4 class="text-center text-uppercase">Biểu đồ thống kê văn bản
                            đến - đi của {{ auth::user()->donVi->ten_don_vi }} năm {{ $year }}</h4>
                    @endif
                </div>
                <form action="{{ route('bao_cao_thong_ke.index') }}" method="get" class="form-row">

                    <div class="col-md-12">
                        <div class="col-sm-6 form-inline dt-bootstrap">
                            <div class="dataTables_length" id="example1_length">
                                <label>Năm
                                    <select name="year" class="form-control form-inline" onchange="this.form.submit()">
                                        @for($i = 2020; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
                <div style="width: 95%; margin: 15px auto;" class="chart-responsive">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        var ctx = document.getElementById("barChart").getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($dataLabel); ?>,
                datasets: [
                    {
                        label: 'Văn bản đến',
                        data: <?php echo json_encode($dataVanBanDen); ?>,
                        backgroundColor: "rgb(0,192,239)"
                    },
                    {
                        label: 'Văn bản đi',
                        data: <?php echo json_encode($dataVanBanDi); ?>,
                        backgroundColor: "rgb(0,166,90)"
                    }

                ]
            }
        });
    </script>
@endsection
