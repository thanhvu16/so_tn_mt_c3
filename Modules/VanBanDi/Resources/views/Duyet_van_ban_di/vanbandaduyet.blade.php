@extends('administrator::layouts.master')
@section('page_title', 'Văn bản đi đã duyệt')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs o-tab">
                                    <li class="nav-item">
                                        <a href="{{route('vb_di_cho_duyet')}}" aria-expanded="false" class="nav-link {{ Route::is('vb_di_cho_duyet')
                             ? 'active'  : '' }} ">
                                            <i class="far fa-plus-square"></i> Văn bản đi chờ duyệt
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('vb_di_da_duyet')}}" aria-expanded="false" class="nav-link {{ Route::is('vb_di_da_duyet')
                             ? 'active'  : '' }}">
                                            <i class="far fa-plus-square"></i> Văn bản đi đã duyệt
                                        </a>
                                    </li>
                                </ul>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th style="width: 5%; vertical-align: middle;" class="text-center">STT</th>
                                            <th width="10%" class="text-center" style="vertical-align: middle;">Số ký
                                                hiệu
                                            </th>
                                            <th width="25%" class="text-center" style="vertical-align: middle;">Trích
                                                yếu
                                            </th>
                                            <th width="20%" class="text-center" style="vertical-align: middle;">Ý kiến</th>
                                            <th width="20%" class="visible-lg text-center"
                                                style="vertical-align: middle;">Nơi nhận
                                            </th>
                                            <th width="10%" class="text-center" style="vertical-align: middle;">Tệp
                                                tin
                                            </th>
                                            <th width="10%" class="text-center" style="vertical-align: middle;">Xóa</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($vanbandichoduyet as $key=>$vanban)
                                            <form action="{{route('duyet-vbdi')}}" method="post">
                                                @csrf
                                                <tr class="duyet-gia-han">
                                                    <td> {{$key+1}}</td>
                                                    <td>{{$vanban->vanbandi->vb_sokyhieu ?? ''}}</td>
                                                    <td><a href="{{route('Quytrinhxulyvanbandi',$vanban->van_ban_di_id)}}">{{$vanban->vanbandi->vb_trichyeu ?? ''}}</a><br>
                                                        <span style="font-style: italic">
                                                        - Người ký: {{$vanban->vanbandi->nguoidung2->ho_ten ?? ''}} <br>
                                                         - Người nhập: {{$vanban->vanbandi->nguoitao->ho_ten}} - {{dateFormat('d/m/Y',$vanban->vanbandi->vb_ngaybanhanh)}}
                                                        </span> </td>
                                                    <td>

                                                        @if($vanban->trang_thai == 0)
                                                            <div class="col-md-12 form-group">
                                                                <span style="font-weight: bold;color: black">Lí do trả lại :</span>
                                                                <span
                                                                    style="font-style: italic">{{$vanbandidautien->y_kien_gop_y ?? ''}}</span><br>
                                                                ({{$vanbandidautien->nguoitralai->ho_ten ?? ''}}
                                                                - {{date_format($vanbandidautien->created_at, 'd-m-Y H:i:s') ?? ''}})
                                                            </div>

                                                        @elseif($vanban->trang_thai == 10)
                                                            <div class="col-md-12 form-group">
                                                                <span style="font-weight: bold;color: black">Đã duyệt :</span>
                                                                <span
                                                                    style="font-style: italic">{{$vanbandicuoicung->y_kien_gop_y ?? ''}}</span><br>
                                                                ({{$vanbandicuoicung->nguoitralai->ho_ten ?? ''}}
                                                                - {{date_format($vanbandicuoicung->created_at, 'd-m-Y H:i:s') ?? ''}})
                                                            </div>
                                                        @else
                                                            - {{$vanban->y_kien_gop_y}} <br>
                                                            - {{$vanban->vanbandi->nguoidung2->ho_ten ?? ''}} <br>
                                                            - {{$vanban->vanbandi->vb_ngaybanhanh ?? ''}}
                                                        @endif


                                                    </td>
                                                    <td>
                                                        @forelse($vanban->vanbandi->mailtrongtp as $key=>$item)
                                                            - {{$item->laytendonvi->ten_don_vi}}<br>
                                                        @empty
                                                        @endforelse
                                                        @forelse($vanban->vanbandi->mailngoaitp as $key=>$item)
                                                            - {{$item->laytendonvingoai->ten_don_vi}}<br>
                                                        @empty
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @forelse($vanban->vanbandi->filetrinhky as $filedata)
                                                            <a href="{{$filedata->getUrlFile()}}">[File trình ký]</a><br>
                                                        @empty
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @if($vanban->trang_thai == 1)
                                                            <button
                                                                class="btn waves-effect btn-primary btn-choose-status"
                                                                name="submit_Duyet" value="1" data-type="3">Duyệt
                                                            </button>
                                                            <br>
                                                            <br>
                                                            <button
                                                                class="btn waves-effect btn-danger btn-choose-status"
                                                                name="submit_tralai" value="2" data-type="2">Trả lại
                                                            </button>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                </tr>
                                            </form>
                                        @empty
                                            <td colspan="6" class="text-center">Không tìm
                                                thấy dữ liệu.
                                            </td>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row col-md-12 mb-1">
                                        {{--                                        <div class="float-left">--}}
                                        {{--                                            Tổng số văn bản: <b></b>--}}
                                        {{--                                        </div>--}}
                                        <br>
                                    </div><!--col-->
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
    </script>
@endsection
