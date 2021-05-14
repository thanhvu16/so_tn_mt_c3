
@extends('admin::layouts.master')
@section('page_title', 'Danh sách')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách hồ sơ công việc</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12 text-right " style="margin-bottom: 5px">
                            <a class="btn btn-primary " role="button"
                               href="{{route('ds_tim_kiem_van_ban_hs',$id)}}"><i class="fa fa-plus"></i>
                            </a>
                            <b class="text-danger"> Thêm văn bản vào hồ sơ </b>
                        </div>
                        <table class="table table-bordered table-striped dataTable mb-0" >
                            <thead>
                            <tr>
                                <th width="2%" class="text-center">STT</th>
                                <th width="25%" class="text-center">Thông tin
                                </th>
                                <th width="" class="text-center">Trích yếu</th>
                                <th width="13%" class="text-center">Cơ quan ban hành</th>
                                <th width="4%" class="text-center">Tác vụ
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            @if ($ds_vb_hoso && count($ds_vb_hoso) > 0)
                                @foreach($ds_vb_hoso as $key=>$data)
                                    <tr>
                                        @if($data->loai_van_ban == 2)
                                            <td class="text-center"
                                                style="vertical-align: middle;">{{$key+1}}</td>
                                            <td>
                                                <p>- Văn bản đi số: <span class="color-red">{{ $data->vanbandi->so_di }}</span></p>
                                                <p>- Số ký hiệu: {{$data->vanbandi->so_ky_hieu}}</p>
                                                <p>- loại văn bản: {{$data->vanbandi->loaivanban->ten_loai_van_ban ?? ''}}</p>
                                                <p>
                                                    - ngày nhập: {{dateformat($data->vanbandi->ngay_ban_hanh) ?? ''}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="{{route('Quytrinhxulyvanbandi',$data->vanbandi->id)}}" class="color-black">{{$data->vanbandi->trich_yeu ?? ''}}</a>
                                                <br>
                                                <div class="col-md-12 text-right">
                                                    @forelse($data->vanbandi->filetrinhky as $file)
                                                        <a href="{{$file->getUrlFile()}}">[File văn bản]
                                                            <br></a>
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td>{{$data->vanbandi->nguoitao->donvi->ten_don_vi ?? ''}}</td>
                                            <td>
                                                <a href="{{route('delete_tailieuhs',$data->id)}}"
                                                   class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                   role="button" title="Xóa">
                                                    <i class="fa fa-trash " style="color: red"></i></a>
                                            </td>
                                        @else
                                            @if (!empty($data->vanBanDen))
                                                <td class="text-center"
                                                    style="vertical-align: middle;">{{$key+1}}</td>
                                                <td>
                                                    <p>- Văn bản đến số: <span class="color-red">{{ $data->vanBanDen->so_den ?? null }}</span></p>
                                                    <p>- Số ký hiệu: {{$data->vanBanDen->so_ky_hieu }}</p>
                                                    <p>- loại văn bản: {{$data->vanBanDen->loaiVanBan->ten_loai_van_ban ?? ''}}</p>
                                                    <p>- Ngày nhập: {{dateformat($data->vanBanDen->ngay_ban_hanh) ?? ''}}</p>
                                                </td>
                                                <td style="text-align: justify">
                                                    <a href="" class="color-black">{{$data->vanBanDen->trich_yeu ?? ''}}</a>
                                                    <div class="col-md-12 text-right " style="pointer-events: auto">
                                                        <p>
                                                            @if($data->vanBanDen)

                                                            @forelse($data->vanBanDen->vanBanDenFilehs as $file)
                                                                <a href="{{$file->getUrlFile()}}">[File văn bản] <br></a>
                                                            @empty
                                                            @endforelse
                                                            @endif
                                                        </p>
                                                    </div>
                                                </td>

                                                <td>{{$data->vanBanDen->co_quan_ban_hanh ?? ''}}</td>
                                                <td>
                                                    <a href="{{route('delete_tailieuhs',$data->id)}}"
                                                       class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                       role="button" title="Xóa">
                                                        <i class="fa fa-trash " style="color: red"></i></a>
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                            @endif
                            </tbody>
                        </table>
                        {{ $ds_vb_hoso->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
