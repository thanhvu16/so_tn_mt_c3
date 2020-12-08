    @extends('admin::layouts.master')
@section('page_title', 'Hồ sơ công việc')
@section('content')
    <section class="content">
    {{--        <div class="box">--}}
    <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ Request::get('tab') == 'tab_1' || empty(Request::get('tab')) ? 'active' : null }}">
                    <a href="{{ route('ho-so-cong-viec.index') }}">
                        <i class="fa fa-user"></i> Danh sách hồ sơ
                    </a>
                </li>
                    <li class="{{ Request::get('tab') == 'tab_2' ? 'active' : null }}">
                        <a href="{{ route('ho-so-cong-viec.create') }}">
                            <i class="fa fa-plus"></i> Thêm mới</a>
                    </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane {{ Request::get('tab') == 'tab_1' || empty(Request::get('tab')) ? 'active' : null }}" id="tab_1">
                    <div class="box-body">
                        @forelse($ds_hoso as $key=>$data)
                            <div class="col-md-2   imghover" style="margin-bottom: 20px">
                                <a href="{{route('ds_van_ban_hs',$data->id)}}"><span><i
                                            class="fa  fa-folder-open"
                                            style="font-size: 60px ;color: #eed522"></i></span></a><br>
                                <a href="@if($data->nguoi_tao == auth::user()->id){{route('ho-so-cong-viec.edit',$data->id)}}@else{{route('ds_van_ban_hs',$data->id)}}@endif">{{$data->ten_ho_so}}</a>
                            </div>
                        @empty
                            <div class="col-md-12 mt-2" >
                                <p>Không có hồ sơ nào !</p>
                            </div>
                        @endforelse
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">

                    </div>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </section>
@endsection

