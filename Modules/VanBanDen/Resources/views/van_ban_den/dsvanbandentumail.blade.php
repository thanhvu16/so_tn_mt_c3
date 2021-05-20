@extends('admin::layouts.master')
@section('page_title', 'Văn Bản Đến Từ Mail')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Văn bản đến từ mail</h3>
                        @can(\App\Common\AllPermission::capNhatHomThuCongHomThuCong())
                            <a href="{{ route('lay-van-ban-tu-email.index') }}" class="pull-right"><i class="fa fa-plus"></i> Cập nhật hòm thư công</a>
                        @endcan
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <!-- <div class="col-md-2">
                                <h4 style="color: #1f91f3">Hòm thư công </h4>
                            </div> -->
                            <div class="col-md-2">
                                <a href="{{route('dsvanbandentumail','tinhtrang=1')}}"
                                   class="btn @if(Request::get('tinhtrang')!= 2 ) btn-primary @else btn-default @endif"
                                   data-original-title="" title="">Email chưa xem</a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{route('dsvanbandentumail','tinhtrang=2')}}"
                                   class="btn  @if(Request::get('tinhtrang')==2) btn-primary @else btn-default @endif"
                                   data-original-title="" title="">Email đã xem</a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=3')}}"
                                   class="btn  @if(Request::get('noiguimail')==3) btn-success @else btn-default @endif"
                                   data-original-title="" title="">Email trung ương</a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=2')}}"
                                   class="btn @if(Request::get('noiguimail')==2) btn-success @else btn-default @endif"
                                   data-original-title="" title="">Email quận-huyện</a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=1')}}"
                                   class="btn @if(Request::get('noiguimail')==1) btn-success @else btn-default @endif"
                                   data-original-title="" title="">Email sở ngành</a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{route('dsvanbandentumail','tinhtrang='.Request::get('tinhtrang').'&noiguimail=4')}}"
                                   class="btn  @if(Request::get('noiguimail')==4) btn-success @else btn-default @endif"
                                   data-original-title="" title="">Email đơn vị khác</a>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="row">
                                    <form action="{{route('dsvanbandentumail')}}" method="get">
                                        <div class="col-md-3 form-group">
                                            <label>Tìm theo chủ đề </label>
                                            <input type="text" class="form-control" value="{{Request::get('mail_subject')}}"
                                                   name="mail_subject"
                                                   placeholder="Nhập chủ đề">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Tìm theo ngày</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-o"></i>
                                                </div>
                                                <input type="text" class="form-control datepicker" value="{{Request::get('mail_date')}}"
                                                       name="mail_date" placeholder="dd/mm/yyyy">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>&nbsp;</label><br>
                                            <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                            @if (!empty(Request::get('mail_subject')) ||
                                                        !empty(Request::get('mail_date')))
                                                <a href="{{ route('dsvanbandentumail') }}" class="btn btn-success"><i
                                                        class="fa fa-refresh"></i></a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12">
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
                                                <td>
                                                    <a href="{{route('vanbandentumail','id='.$vbDen->id.'&xml='.$vbDen->mail_attachment.'&pdf='.$vbDen->mail_pdf.'&doc='.$vbDen->mail_doc.'&xls='.$vbDen->mail_xls)}}"
                                                       title="Tạo mới văn bản đến">{{$vbDen->mail_subject}}</a></td>
                                                <td>{{$vbDen->mail_from}}</td>
                                                <td>{{ !empty($vbDen->mail_date) ? date('d/m/Y H:i:s', strtotime($vbDen->mail_date)) : null }}</td>

                                                <td class="text-center">
                                                    @if($vbDen->mail_active == 1)
                                                        <a href="{{route('vanbandentumail','id='.$vbDen->id.'&xml='.$vbDen->mail_attachment.'&pdf='.$vbDen->mail_pdf.'&doc='.$vbDen->mail_doc.'&xls='.$vbDen->mail_xls)}}" class="color-white btn btn-primary btn-sm">Chưa xem</a>
                                                    @endif

                                                    @if($vbDen->mail_active == 2)
                                                        <button type="button" class="btn btn-success btn-sm">Đã xem
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                          action="{{route('delete-email',$vbDen->id)}}"
                                                          accept-charset="UTF-8" style="display:inline">
                                                        @csrf
                                                        <button
                                                            class="btn btn-color-red btn-icon btn-remove-item btn-light"
                                                            role="button" title="Xóa">
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
                                    <div class="row mt-2">
                                        <div class="col-md-6 col-12">
                                            Tổng số văn bản: <b>{{ $getEmail->total() }}</b>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="pull-right">
                                                {!! $getEmail->appends(['noiguimail' => Request::get('noiguimail'), 'tinhtrang' => Request::get('tinhtrang'),
                                                'mail_subject' => Request::get('mail_subject'), 'mail_date' => Request::get('mail_date')])->render() !!}
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
        $(function () {
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
