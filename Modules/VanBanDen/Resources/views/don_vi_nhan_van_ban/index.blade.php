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
                    @include('dieuhanhvanbanden::van-ban-den.fom_tra_lai', ['active' => \Modules\VanBanDen\Entities\VanBanDen::TRUONG_PHONG_NHAN_VB])
                    <!-- /.box-header -->
                    <div class="col-md-12 mt-1 mb-1" >
{{--                        <form action="{{route('don-vi-nhan-van-ban-den.index')}}" method="get">--}}
{{--                                <div class="col-md-offset-9">--}}

{{--                                    <select class="form-control show-tick select2-search"--}}
{{--                                            name="don_vi_van_ban"  onchange="this.form.submit()" id="">--}}
{{--                                        <option value="3" {{Request::get('don_vi_van_ban') == 3 ? 'selected' : ''}}>Văn bản đã vào sổ</option>--}}
{{--                                        <option value="2" {{Request::get('don_vi_van_ban') == 2 ? 'selected' : ''}}>Văn bản chưa vào sổ</option>--}}
{{--                                        <option  value="" {{Request::get('don_vi_van_ban') == '' ? 'selected' : ''}}>Tất cả văn bản</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                        </form>--}}
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
                                        <p> {{ isset($vbDen->vanbandi) && $vbDen->vanbandi->so_ky_hieu ?? null }}</p>
                                    </td>
                                    <td style="text-align: justify">
                                        <a href="@if(isset($vbDen->vanbandi) && $vbDen->vanbandi->loai_van_ban_id == 1000){{route('thongtinvb',$vbDen->id)}}@else{{route('don-vi-nhan-van-ban-den.edit',$vbDen->id)}} @endif" title="{{isset($vbDen->vanbandi) && $vbDen->vanbandi->trich_yeu}}">{{isset($vbDen->vanbandi) && $vbDen->vanbandi->trich_yeu}}</a><br>
                                    </td>
                                    <td>
                                        @if (isset($vbDen->vanbandi))
                                            <div class="text-center " style="pointer-events: auto">
                                                @forelse($vbDen->vanbandi->filechinh as $filedata)
                                                    <a class="seen-new-window" target="popup" href="{{$filedata->getUrlFile()}}">[File trình ký]</a><br>
                                                @empty
                                                @endforelse
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center" style="vertical-align: middle">
                                        {{$vbDen->donvigui->ten_don_vi ?? ''}}
                                    </td>
                                    <td>@if($vbDen->trang_thai == 1 || $vbDen->trang_thai == 2)<span class="label label-warning">Chưa vào sổ</span>@else <span class="label label-success">Đã vào sổ</span></td>@endif



                                </tr>
                            @empty
                            @endforelse
                            @forelse ($vanbanhuyenxuongdonvi as $key=>$vbDen2)
                                <tr>
                                    <td class="text-center">{{$donvinhancount + $key +1}} </td>
                                    <td class="text-center">{{$vbDen2->vanBanDen->loaiVanBan->ten_loai_van_ban ?? ''}}</td>
                                    <td>
                                        <p> {{$vbDen2->vanBanDen->so_ky_hieu ?? ''}}</p>
                                    </td>
                                    <td style="text-align: justify">
                                        <a href="@if($vbDen2->vanBanDen->so_van_ban_id == 100){{route('thongtinvbhuyen',$vbDen2->id)}}@else{{route('chi_tiet_van_ban_den_don_vi',$vbDen2->id)}}@endif" title="{{$vbDen2->vanBanDen->trich_yeu}}">{{$vbDen2->vanBanDen->trich_yeu}}</a><br>

                                        @if($vbDen2->vanBanDen->noi_dung != null)<span style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vbDen2->vanBanDen->noi_dung ?? ''}}</span>@if($vbDen2->vanBanDen->noi_dung != null)
                                            <br>@endif
                                                     Hạn giải quyết: {{ !empty($vbDen2->vanBanDen->han_giai_quyet) ? date('d/m/Y', strtotime($vbDen2->vanBanDen->han_giai_quyet)) : '' }} -
                                        <span
                                            style="font-style: italic">Người nhập : {{$vbDen2->vanBanDen->nguoiDung->ho_ten ?? ''}}</span>


                                        <p class="mt-2">
                                            <input id="van-ban-don-vi-{{ $vbDen2->id }}" type="checkbox"
                                                   name="van_ban-don_vi" value="1" checked>
                                            <label for="van-ban-don-vi-{{ $vbDen2->id }}"
                                                   class="color-red font-weight-normal">
                                                Văn bản đơn vị chủ trì
                                            </label>
                                        </p>
                                            <a class="tra-lai-van-ban" data-toggle="modal" data-target="#modal-tra-lai"
                                               data-id="{{ $vbDen2->van_ban_den_id }}">
                                                <span><i class="fa fa-reply"></i>Trả lại VB</span>
                                            </a>
                                    </td>
                                    <td>
                                        <div class="text-center " style="pointer-events: auto">
                                            @forelse($vbDen2->vanBanDen->vanBanDenFilehs as $filedata)
                                                <a class="seen-new-window" target="popup" href="{{$filedata->getUrlFile()}}">[File]</a><br>
                                            @empty
                                            @endforelse
                                        </div>
                                    </td>

                                    <td>
                                        <span>{{ $vbDen2->canBoChuyen ? $vbDen2->canBoChuyen->donVi->ten_don_vi : null }}</span>
                                    </td>
                                    <td>@if($vbDen2->vao_so_van_ban == null)<span class="label label-warning">Chưa vào sổ</span>@else <span class="label label-success">Đã vào sổ</span></td>@endif
                                </tr>
                            @empty
                            @endforelse

                            <!--Don vi phoi hop-->
                            @forelse ($vanBanHuyenChuyenDonViPhoiHop as $key=>$vbDen2)
                                <tr>
                                    <td class="text-center">{{$countphoihop +  $key + 1 }}</td>
                                    <td class="text-center">{{$vbDen2->vanBanDen->loaiVanBan->ten_loai_van_ban ?? ''}}</td>
                                    <td>
                                        <p> {{$vbDen2->vanBanDen->so_ky_hieu ?? ''}}</p>
                                    </td>
                                    <td style="text-align: justify">
                                        <a href="{{route('chi_tiet_van_ban_den_don_vi',$vbDen2->id.'?type=phoi_hop')}}" title="{{$vbDen2->vanBanDen->trich_yeu ?? ''}}">{{$vbDen2->vanBanDen->trich_yeu ?? ''}}</a>
                                        <br>
                                        @if($vbDen2->vanBanDen->noi_dung != null)<span style="font-weight: bold;">Nội dung:</span>@endif
                                        <span
                                            style="font-style: italic">{{$vbDen2->vanBanDen->noi_dung ?? ''}}</span>@if($vbDen2->vanBanDen->noi_dung != null)
                                            <br>@endif
                                        Hạn giải quyết: {{ !empty($vbDen2->vanBanDen->han_giai_quyet) ? date('d/m/Y', strtotime($vbDen2->vanBanDen->han_giai_quyet)) : '' }} -
                                        <span
                                            style="font-style: italic">Người nhập : {{$vbDen2->vanBanDen->nguoiDung->ho_ten ?? ''}}</span>
                                        <p class="mt-2">
                                            <input id="van-ban-don-vi-{{ $vbDen2->id }}" type="checkbox"
                                                   name="van_ban-don_vi" value="1" checked>
                                            <label for="van-ban-don-vi-{{ $vbDen2->id }}"
                                                   class="color-red font-weight-normal">
                                                văn bản đơn vị phối hợp
                                            </label>
                                        </p>
                                    </td>
                                    <td>
                                        <div class="text-center " style="pointer-events: auto">
                                            @if($vbDen2->vanBanDen)
                                            @forelse($vbDen2->vanBanDen->vanBanDenFilehs as $filedata)
                                                <a class="seen-new-window" target="popup" href="{{$filedata->getUrlFile()}}">[File]</a><br>
                                            @empty
                                            @endforelse
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <span>{{ $vbDen2->canBoChuyen ? $vbDen2->canBoChuyen->donVi->ten_don_vi : null }}</span>
                                    </td>
                                    <td>@if($vbDen2->vao_so_van_ban == null)<span class="label label-warning">Chưa vào sổ</span>@else <span class="label label-success">Đã vào sổ</span></td>@endif
                                </tr>
                            @empty
                            @endforelse

                            @if($tong < 0) <tr><td colspan="7">Không có dữ liệu</td></tr>@endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số văn bản: <b>{{ $tong }}</b>
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

        // tra lai van ban
        $('.tra-lai-van-ban').on('click', function () {
            let id = $(this).data('id');
            let traLai = $(this).data('tra-lai');

            $('#modal-tra-lai').find('input[name="van_ban_den_id"]').val(id);
            $('#modal-tra-lai').find('input[name="type"]').val(traLai);
        });

    </script>
@endsection















