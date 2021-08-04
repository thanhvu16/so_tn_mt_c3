@extends('admin::layouts.master')
@section('page_title', 'Danh sách văn bản chờ vào sổ')
@section('content')
    <section class="content" style="font-size: 14px">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách văn bản trong đơn vị</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12 mt-1 mb-1" >
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">

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
                                        <a href="@if($vbDen->vanbandi->loai_van_ban_id == 1000){{route('thongtinvbsonhan',$vbDen->id)}}@else{{route('vaoSoVanBanDonViGuiSo',$vbDen->id)}} @endif" title="{{$vbDen->vanbandi->trich_yeu}}">{{$vbDen->vanbandi->trich_yeu}}</a><br>
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
                                        {{$vbDen->donViGuiDen($vbDen->vanbandi->phong_phat_hanh)->ten_don_vi ?? ''}}
                                    </td>
                                    <td>@if($vbDen->trang_thai == 1 || $vbDen->trang_thai == 2)<span class="label label-warning">Chưa vào sổ</span>@else <span class="label label-success">Đã vào sổ</span></td>@endif



                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>
                            @endforelse


                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12 mt-2" >
                                    Tổng số văn bản: <b>{{ $donvinhan->count() }}</b>
{{--<!--                                    {!! $donvinhan->appends(['so_van_ban_id' => Request::get('so_van_ban_id'),'loai_van_ban_id' => Request::get('loai_van_ban_id'), 'vb_so_den' => Request::get('vb_so_den')--}}
{{--                           ,'vb_so_ky_hieu' => Request::get('vb_so_ky_hieu'),--}}
{{--                           'end_date' => Request::get('end_date'),'start_date' => Request::get('start_date'),--}}
{{--                           'cap_ban_hanh_id' => Request::get('cap_ban_hanh_id'),'co_quan_ban_hanh_id' => Request::get('co_quan_ban_hanh_id'),'nguoi_ky_id' => Request::get('nguoi_ky_id'),--}}
{{--                           'vb_trich_yeu' => Request::get('vb_trich_yeu'),'search' =>Request::get('search') ])->render() !!}-->--}}
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

        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            let traLai = $(this).data('tra-lai');

            $('#modal-tra-lai').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-tra-lai').find('input[name="type"]').val(traLai);
        });

    </script>
@endsection















