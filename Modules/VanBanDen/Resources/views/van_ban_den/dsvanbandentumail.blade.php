@extends('admin::layouts.master')
@section('page_title', 'Văn Bản Đến Từ Mail')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Văn bản đến từ mail</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <!-- <div class="col-md-2">
                                    <h4 style="color: #1f91f3">Hòm thư công </h4>
                                </div> -->
                                <div class="col-md-2">
                                    <a href="{{route('dsvanbandentumail','tinhtrang=1')}}" class="btn @if(Request::get('tinhtrang')!= 2 ) btn-primary @else btn-default @endif" data-original-title="" title="">Email chưa xem</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('dsvanbandentumail','tinhtrang=2')}}" class="btn  @if(Request::get('tinhtrang')==2) btn-primary @else btn-default @endif" data-original-title="" title="">Email đã xem</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=3')}}" class="btn  @if(Request::get('noiguimail')==3) btn-success @else btn-default @endif" data-original-title="" title="">Email trung ương</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=2')}}" class="btn @if(Request::get('noiguimail')==2) btn-success @else btn-default @endif" data-original-title="" title="">Email quận-huyện</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=1')}}" class="btn @if(Request::get('noiguimail')==1) btn-success @else btn-default @endif" data-original-title="" title="">Email sở ngành</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=4')}}" class="btn  @if(Request::get('noiguimail')==4) btn-success @else btn-default @endif" data-original-title="" title="">Email đơn vị khác</a>
                                </div>
                            </div>
                        </div>
                            <div class="card-box pd-0">
                                <div class="tab-content pd-0">
                                    <div class="tab-pane active" id="home">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped dataTable mb-0">
                                                    <thead>
                                                    <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                                        <th class="text-center" width="5%">STT</th>
                                                        <th class="text-center">Chủ Đề</th>
                                                        <th class="text-center" width="12%">Nơi Gửi</th>
                                                        <th class="text-center" width="10%">Thời Gian</th>
                                                        <th class="text-center" width="12%">Tình Trạng</th>
                                                        <th class="text-center" width="5%">Xoá</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse ($getEmail as $key=>$vbDen)
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td><a href="{{route('vanbandentumail','id='.$vbDen->id.'&xml='.$vbDen->mail_attachment.'&pdf='.$vbDen->mail_pdf.'&doc='.$vbDen->mail_doc.'&xls='.$vbDen->mail_xls)}}" title="Tạo mới văn bản đến">{{$vbDen->mail_subject}}</a></td>
                                                            <td>{{$vbDen->mail_from}}</td>
                                                            <td>{{ date('d-m-Y', strtotime($vbDen->mail_date)) }}</td>

                                                            <td class="text-center">
                                                                @if($vbDen->mail_active == 1)
                                                                    <button type="button" class="btn btn-primary btn-sm">Chưa xem</button>
                                                                @endif

                                                                @if($vbDen->mail_active == 2)
                                                                    <button type="button" class="btn btn-success btn-sm">Đã xem</button>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <form method="POST"
                                                                      action="{{route('delete-email',$vbDen->id)}}"
                                                                      accept-charset="UTF-8" style="display:inline">
                                                                    @csrf
                                                                    <button class="btn btn-color-red btn-icon btn-remove-item btn-light" role="button" title="Xóa">
                                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                                    </button>
                                                                </form>

                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <td colspan="7" class="text-center">Không tìm thấy dữ liệu.</td>
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 col-12">
                                                        Tổng số văn bản: <b>{{ $getEmail->total() }}</b>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        {!! $getEmail->appends(['noiguimail' => Request::get('noiguimail'), 'tinhtrang' => Request::get('tinhtrang')])->render() !!}
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script>
        $(function(){
            $('#noiguimail').on('change', function () {
                var url = $(this).val();
                if (url) {
                    window.location = url;
                }
                return false;
            });
        });
    </script>
@endsection
