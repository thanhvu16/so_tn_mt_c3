@extends('administrator::layouts.master')

@section('page_title', 'Quản lý văn bản')

@section('content')
{{--                    <div class="body p-b-0">--}}
{{--                        <!-- include tab-header form -->--}}
{{--                        @include('quanlyvanban::tabs.header_vb')--}}

{{--                        <ul class="nav nav-tabs tab-nav-right" role="tablist">--}}

{{--                            <li role="presentation"--}}
{{--                                class="text-uppercase {{ route::is('van_ban_den.create') || route::is('van_ban_den.create') || route::is('van_ban_den.thong_ke') ? 'active' : '' }}">--}}
{{--                                <a href="{{ route('van_ban_den.create','type=1') }}" aria-expanded="true">Thêm Văn Bản</a>--}}
{{--                            </li>--}}
{{--                            <li role="presentation"--}}
{{--                                class="text-uppercase {{  Route::is('donvi') ? 'active' : '' }}">--}}
{{--                                <a href="{{ route('donvi') }}" aria-expanded="true">TKVB Chi Tiết</a>--}}
{{--                            </li>--}}
{{--                            <li role="presentation" class="text-uppercase {{ route::is('TKVB_So_Lieu') ? 'active' : '' }}">--}}
{{--                                <a href="{{ route('TKVB_So_Lieu') }}" aria-expanded="true">TKVB Số Liệu</a>--}}
{{--                            </li>--}}
{{--                            @if ($userAuth->quyen_vanthu_dv==1 || $userAuth->quyen_vanthu_cq==1 )--}}
{{--                            <li role="presentation" class="text-uppercase {{ route::is('insvb') ? 'active' : '' }}">--}}
{{--                                <a href="{{ route('insvb') }}" aria-expanded="true">In Sổ Văn Bản</a>--}}
{{--                            </li>--}}
{{--                            @endif--}}
{{--                        </ul>--}}
{{--                        <div class="tab-content">--}}
{{--                            <div role="tabpanel" class="tab-pane fade active in" id="div_1">--}}
{{--                                 them moi--}}
{{--                                @if ($userAuth->quyen_vanthu_dv==1 || $userAuth->quyen_vanthu_cq==1 )--}}
{{--                                <div class="header">--}}
{{--                                    <a class="btn bg-indigo waves-effect" role="button" data-toggle="collapse" href="#collapseExample"--}}
{{--                                       aria-expanded="false" aria-controls="collapseExample">--}}
{{--                                        {{ isset($vanban) ? 'CẬP NHẬT' : 'THÊM VĂN BẢN' }}--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                                @endif--}}
{{--                                <br>--}}
{{--                                @if ($userAuth->quyen_vanthu_dv==1 || $userAuth->quyen_vanthu_cq==1 )--}}
{{--                                <div class="collapse in" id="collapseExample">--}}
{{--                                    @include('quanlyvanban::van_ban_den._form')--}}
{{--                                </div>--}}
{{--                                @endif--}}
{{--                                <div role="tabpanel" class="tab-pane fade in" id="div_1">--}}
{{--                                    <fieldset class="feildset-form">--}}
{{--                                        <legend>Danh sách</legend>--}}
{{--                                          search here--}}
{{--                                        <div class="body">--}}
{{--                                            <div class="table-responsive">--}}
{{--                                                <div  class="dataTables_wrapper form-inline dt-bootstrap">--}}

{{--                                                        @csrf--}}
{{--                                                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">--}}
{{--                                                            <colgroup width="100%">--}}
{{--                                                                <col style="width: 5%;">--}}
{{--                                                                <col style="width: 85%;">--}}
{{--                                                                <col style="width: 10%;">--}}

{{--                                                            </colgroup>--}}
{{--                                                            <thead>--}}
{{--                                                            <tr role="row">--}}
{{--                                                                <th class="text-center" >STT</th>--}}
{{--                                                                <th class="text-center" >Nội Dung</th>--}}
{{--                                                                <th class="text-center">Tác vụ</th>--}}
{{--                                                            </tr>--}}
{{--                                                            </thead>--}}
{{--                                                            <tbody>--}}
{{--                                                            @forelse ($ds_vanBanDen as $key=>$vbDen)--}}
{{--                                                                <tr class="odd">--}}
{{--                                                                    <td>--}}
{{--                                                                        {{$key+1}}--}}
{{--                                                                    </td>--}}
{{--                                                                    <td>--}}
{{--                                                                        ({{$vbDen->vb_so_ky_hieu}}) <a href="{{route('van_ban_den.show',$vbDen->id)}}" title="{{$vbDen->vb_trich_yeu}}">{{$vbDen->vb_trich_yeu}}</a><br>--}}
{{--                                                                        - Cấp ban hành: {{$vbDen->coQuanBanHanh->ten_co_quan ?? ''}}<br>--}}
{{--                                                                        - Ban hành: {{dateFormat('d/m/Y',$vbDen->vb_ngay_ban_hanh)}}<br>--}}
{{--                                                                        - File văn bản đến: <br>--}}
{{--                                                                        <ul>--}}
{{--                                                                            @forelse( $vbDen->vanBanDenFile as $data)--}}
{{--                                                                                <a href="{{$data->getUrlFile()}}"><li>{{$data->ten_file ?? ''}}</li></a>--}}
{{--                                                                            @empty--}}
{{--                                                                                <span style="color: #0E0EFF"> Không có file nào !!</span>--}}
{{--                                                                            @endforelse--}}
{{--                                                                        </ul>--}}
{{--                                                                    </td>--}}
{{--                                                                    <td class="text-center">--}}
{{--                                                                        <a href="{{route('van_ban_den.create','id='.$vbDen->id.'&type=1')}}" class="btn btn-primary waves-effect btn-action"  role="button" title="Sửa">--}}
{{--                                                                            <i class="fa fa-edit"></i>--}}
{{--                                                                        </a>--}}
{{--                                                                        <a href="{{route('van_ban_den.delete',$vbDen->id.'?type=1')}}" class="btn btn-danger waves-effect btn-action"  role="button" title="Xóa">--}}
{{--                                                                            <i class="fa fa-trash"></i>--}}
{{--                                                                        </a>--}}
{{--                                                                        <a href="{{route('showfile',$vbDen->vb_den_id.'?type=1')}}" class="btn btn-primary waves-effect btn-action"  role="button" title="Sửa file">--}}
{{--                                                                            <i class="fa fa-file "></i>--}}
{{--                                                                        </a>--}}
{{--                                                                        <a href="{{route('vanbantruyen',$vbDen->id.'?type=1')}}" class="btn btn-danger waves-effect btn-action"  role="button" title="Truyền Văn Bản">--}}
{{--                                                                            <i class="fa fa-share"></i>--}}
{{--                                                                        </a>--}}
{{--                                                                    </td>--}}
{{--                                                                </tr>--}}
{{--                                                            @empty--}}
{{--                                                                <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>--}}
{{--                                                            @endforelse--}}
{{--                                                            </tbody>--}}
{{--                                                        </table>--}}
{{--                                                    <!-- pagination -->--}}
{{--                                                    {{$ds_vanBanDen->links()}}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-title mb-3">Văn bản đi {{$userAuth->donvi->ten_don_vi}}</h4>
                <ul class="nav nav-tabs o-tab">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="far fa-plus-square"></i> Thêm văn bản
                        </a>
                    </li>
                </ul>
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12">
                                @include('quanlyvanban::van_ban_di._form')
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">
                                                <div class="checkbox">
                                                    <input id="checkbox0" type="checkbox">
                                                    <label for="checkbox0"></label>
                                                </div>
                                            </th>
                                            <th>STT</th>
                                            <th class="sorting_asc" width="125">Ngày ban hành
                                                <div class="table-search">
                                                    <div class="app-search-box">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Tìm ngày ban hành">
                                                            <div class="input-group-append">
                                                                <button class="btn" type="submit">
                                                                    <i class="fe-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="sorting_asc" width="150">Số ký hiệu
                                                <div class="table-search">
                                                    <div class="app-search-box">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Tìm số ký hiệu">
                                                            <div class="input-group-append">
                                                                <button class="btn" type="submit">
                                                                    <i class="fe-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="sorting_asc" width="150">Đơn vị dự thảo
                                                <div class="table-search">
                                                    <div class="app-search-box">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Tìm cơ quan ban hành">
                                                            <div class="input-group-append">
                                                                <button class="btn" type="submit">
                                                                    <i class="fe-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="sorting_asc" width="450">Trích yếu
                                                <div class="table-search">
                                                    <div class="app-search-box">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Tìm trích yếu">
                                                            <div class="input-group-append">
                                                                <button class="btn" type="submit">
                                                                    <i class="fe-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th width="200">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($ds_vanBanDi as $key=>$vbDi)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="checkbox">
                                                        <input id="checkbox0" type="checkbox">
                                                        <label for="checkbox0"></label>
                                                    </div>
                                                </td><td>{{$key+1}}</td>
                                                <td>{{dateFormat('d/m/Y',$vbDi->vb_ngaybanhanh)}}</td>
                                                <td>{{$vbDi->vb_sokyhieu}}</td>
                                                <td>{{$vbDi->dvSoanThao->ten_don_vi ?? ''}}</td>
                                                <td><a href="{{route('van_ban_den.show',$vbDi->id)}}" title="{{$vbDi->vb_trichyeu}}">{{$vbDi->vb_trichyeu}}</a></td>
                                                <td>
                                                    <a href="{{route('vbdicoquan','id='.$vbDi->id.'&type=4')}}" class="btn btn-color-blue btn-icon btn-light"  role="button" title="Sửa">
                                                        <i class="fas fa-file-signature"></i>
                                                    </a><a href="{{route('van_ban_di.delete',$vbDi->id.'?type=4')}}" class="btn btn-color-red btn-icon btn-light"  role="button" title="Xóa">
                                                        <i class="far fa-trash-alt"></i></a>
                                                    <a href="{{route('vanbantruyenvbdi',$vbDi->id)}}" class="btn btn-color-red btn-icon btn-light"  role="button" title="Truyền Văn Bản">
                                                        <i class="fa fa-share"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="7" class="text-center">Không tìm thấy dữ liệu.</td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {!! $ds_vanBanDi->appends(['don_vi' => Request::get('don_vi'), 'chuc_vu' => Request::get('chuc_vu'), 'search' =>Request::get('search') ])->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection

