@extends('admin::layouts.master')
@section('page_title', 'Sổ Văn Bản')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách sổ văn bản</h3>
                    </div>
                    <div class="col-md-3 form-group mt-4">
                        <button type="button" class="btn btn-sm btn-info waves-effect waves-light mb-1"
                                data-toggle="collapse"
                                href="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample">
                            THÊM SỔ VĂN BẢN</button>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="collapse " id="collapseExample">
                                <div class="row">
                                    @include('admin::So_van_ban.index')
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="col-md-12" style="margin-top: 20px">
                        <div class="row">
                            @include('vanbandi::Du_thao_van_ban_di.error')
                            <form action="{{route('danhsachsovanban')}}" method="get">
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên sổ văn bản</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_don_vi')}}"
                                           name="ten_don_vi"
                                           placeholder="Tên đơn vị">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo tên viết tắt</label>
                                    <input type="text" class="form-control" value="{{Request::get('ten_viet_tat')}}"
                                           name="ten_viet_tat"
                                           placeholder="Tên viết tắt">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="exampleInputEmail1">Tìm theo loại sổ</label>
                                    <select name="loai_so" class="form-control lay-so">
                                        <option value="">Chọn loại sổ</option>
                                        <option value="1" {{ Request::get('loai_so') == 1 ? 'selected' : '' }}>Sổ đến
                                        </option>
                                        <option value="2" {{ Request::get('loai_so') == 2 ? 'selected' : '' }}>Sổ đi
                                        </option>
                                        <option value="3" {{ Request::get('loai_so') == 3 ? 'selected' : '' }}>Sổ dùng
                                            chung
                                        </option>
                                        <option value="4" {{ Request::get('loai_so') == 4 ? 'selected' : '' }}>Sổ
                                            riêng
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3" style="margin-top: 20px">
                                    <button type="submit" name="search" class="btn btn-primary">Tìm Kiếm</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th width="" class="text-center">Tên sổ đơn vị</th>
                                <th width="10%" class="text-center">Tên viết tắt</th>
                                <th width="10%" class="text-center">Mô tả</th>
                                <th width="20%" class="text-center">Loại sổ</th>
                                <th width="10%" class="text-center">Đơn vị riêng</th>
                                <th width="10%" class="text-center">Tác Vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ds_sovanban as $key=>$sovanban)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle">{{$key+1}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$sovanban->ten_so_van_ban}}</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$sovanban->ten_viet_tat}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$sovanban->mo_ta}}</td>
                                    <td class="text-center" style="vertical-align: middle">@if($sovanban->loai_so == 3)
                                            Sổ dùng chung @elseif($sovanban->loai_so ==2) Sổ
                                            đi @elseif($sovanban->loai_so == 1) Sổ đến @else Sổ riêng @endif</td>
                                    <td class="text-center"
                                        style="vertical-align: middle">{{$sovanban->donvi->ten_don_vi ?? ''}}</td>
                                    <td class="text-center">

                                        @can('sửa sổ văn bản')
                                            <a class="btn-action btn btn-color-blue btn-icon btn-light btn-sm"
                                               href="{{route('so-van-ban.edit',$sovanban->id)}}" role="button"
                                               title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('xoá sổ văn bản')
                                            <form method="POST" action="{{route('xoasovanban',$sovanban->id)}}">
                                                @csrf
                                                <button
                                                    class="btn btn-action btn-color-red btn-icon btn-ligh btn-sm btn-remove-item"
                                                    role="button"
                                                    title="Xóa">
                                                    <i class="fa fa-trash" aria-hidden="true" style="color: red"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </td>

                                </tr>
                            @empty
                                <td class="text-center" colspan="7" style="vertical-align: middle">Không có dữ liệu !
                                </td>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6" style="margin-top: 5px">
                                    Tổng số sổ văn bản: <b>{{ $ds_sovanban->total() }}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    {!! $ds_sovanban->appends(['ten_don_vi' => Request::get('ten_don_vi'),'loai_so' => Request::get('loai_so'),
                                       'ten_viet_tat' => Request::get('ten_viet_tat'),'search' =>Request::get('search') ])->render() !!}
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
