@extends('admin::layouts.master')
@section('page_title', 'Danh sách văn bản chờ vào sổ')
@section('content')
    <section class="content" style="font-size: 14px">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách văn bản chờ vào sổ</h3>

                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12 mt-1 mb-1" >
                        <form action="{{route('don-vi-nhan-van-ban-den.index')}}" method="get">
                                <div class="col-md-offset-9">

                                    <select class="form-control show-tick select2-search"
                                            name="don_vi_van_ban"  onchange="this.form.submit()" id="">
                                        <option value="3" {{Request::get('don_vi_van_ban') == 3 ? 'selected' : ''}}>Văn bản đã vào sổ</option>
                                        <option value="2" {{Request::get('don_vi_van_ban') == 2 ? 'selected' : ''}}>Văn bản chưa vào sổ</option>
                                        <option  value="" {{Request::get('don_vi_van_ban') == '' ? 'selected' : ''}}>Tất cả văn bản</option>
                                    </select>
                                </div>
                        </form>
                    </div>
                    <div class="box-body" >

                        <table class="table table-bordered table-striped dataTable mb-0 ">
                            <thead>
                            <tr>
                                <th width="2%" class="text-center">STT</th>
                                <th width="10%" class="text-center">Loại văn bản</th>
                                <th width="8%" class="text-center">số ký hiệu</th>
                                <th width="" class="text-center">Trích yếu</th>
                                <th width="10%" class="text-center">File</th>
                                <th width="15%" class="text-center">Đơn vị gửi đến</th>
                                <th width="7%" class="text-center">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($donvinhan as $key=>$vbDen)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{$vbDen->vanbandi->loaiVanBanid->ten_loai_van_ban ?? ''}}</td>
                                    <td>
                                        <p> {{$vbDen->vanbandi->so_ky_hieu}}</p>
                                    </td>
                                    <td style="text-align: justify">
                                        <a href="{{route('don-vi-nhan-van-ban-den.edit',$vbDen->id)}}" title="{{$vbDen->vanbandi->trich_yeu}}">{{$vbDen->vanbandi->trich_yeu}}</a><br>

                                    </td>
                                    <td>
                                        <div class="text-center " style="pointer-events: auto">
                                            @forelse($vbDen->vanbandi->filechinh as $filedata)
                                                <a class="seen-new-window" target="popup" href="{{$filedata->getUrlFile()}}">[File trình ký]</a><br>
                                            @empty
                                            @endforelse
                                        </div>
                                    </td>

                                    <td class="text-center" style="vertical-align: middle">
                                        {{$vbDen->donvigui->ten_don_vi ?? ''}}
                                    </td>
                                    <td>@if($vbDen->trang_thai ==1)<span class="label label-warning">Chưa vào sổ</span>@else <span class="label label-success">Đã vào sổ</span></td>@endif



                                </tr>



                            @empty
                                <td colspan="5" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số văn bản: <b>{{ $donvinhan->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $donvinhan->appends(['so_van_ban_id' => Request::get('so_van_ban_id'),'loai_van_ban_id' => Request::get('loai_van_ban_id'), 'vb_so_den' => Request::get('vb_so_den')
                           ,'vb_so_ky_hieu' => Request::get('vb_so_ky_hieu'),
                           'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),
                           'cap_ban_hanh_id' => Request::get('cap_ban_hanh_id'),'co_quan_ban_hanh_id' => Request::get('co_quan_ban_hanh_id'),'nguoi_ky_id' => Request::get('nguoi_ky_id'),
                           'vb_trich_yeu' => Request::get('vb_trich_yeu'),'search' =>Request::get('search') ])->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection















