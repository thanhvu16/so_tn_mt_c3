@extends('administrator::layouts.master')
@section('page_title', 'Văn bản đi chờ duyệt')
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
                                        <a href="{{route('Danhsachvanbanditrinhky')}}" aria-expanded="false"
                                           class="nav-link {{ Route::is('Danhsachvanbanditrinhky')
                                            ? 'active'  : '' }} ">
                                            <i class="far fa-plus-square"></i> Văn bản đi trình ký
                                        </a>
                                    </li>

                                </ul>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable table-hover data-row">
                                        <thead>
                                        <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th width="5%" class="text-center">STT</th>
                                            <th width="12%" class="text-center">Số ký
                                                hiệu
                                            </th>
                                            <th width="35%" class="text-center">Trích
                                                yếu
                                            </th>
                                            <th width="35%" class="visible-lg text-center">Trạng thái văn bản
                                            </th>
                                            <th width="15%" class="text-center">Tệp
                                                tin
                                            </th>

                                        </thead>
                                        <tbody>

                                        @forelse($vanbanditrinhky as $key=>$vanban)
                                            <form action="{{route('duyet-vbdi')}}" method="post">
                                                @csrf
                                                <tr class="duyet-gia-han">
                                                    <td class="text-center"> {{$key+1}}</td>
                                                    <td>{{$vanban->vanbandi->vb_sokyhieu ?? ''}}</td>
                                                    <td style="text-align: justify"><a
                                                            href="{{route('Quytrinhxulyvanbandi',$vanban->van_ban_di_id)}}">{{$vanban->vanbandi->vb_trichyeu ?? ''}}</a><br>
                                                        <span style="font-style: italic">
                                                        - Người ký: {{$vanban->vanbandi->nguoidung2->ho_ten ?? ''}} <br>
                                                         - Người dự thảo: {{$vanban->vanbandi->nguoitao->ho_ten ?? ''}}<br>
                                                            -Ngày: {{dateFormat('d/m/Y',$vanban->vanbandi->vb_ngaybanhanh ?? '')}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @forelse($vanban->vanbanditrinhky as $key=>$trinhky)
                                                            {{$key+1}}. <span
                                                                style="font-weight: bold">Người gửi:</span> <span
                                                                style="font-style: italic">{{$trinhky->canbochuyen->ho_ten ?? ''}}</span><br>
                                                            <span style="font-weight: bold">-Người nhận: </span><span
                                                                style="font-style: italic"> {{$trinhky->canbonhan->ho_ten ?? ''}}</span>
                                                            <br>
                                                            <span style="font-weight: bold">-Ý kiến</span> : <span
                                                                style="font-style: italic">{{$trinhky->y_kien_gop_y ?? 'N/A'}}</span>
                                                            <br>
                                                        @empty
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @forelse($vanban->file as $filedata)
                                                            <p><a href="{{$filedata->getUrlFile()}}">File Trình Ký</a></p>
                                                        @empty
                                                        @endforelse
                                                    </td>


                                                </tr>
                                            </form>
                                        @empty
                                            <td colspan="7" class="text-center">Không tìm
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

