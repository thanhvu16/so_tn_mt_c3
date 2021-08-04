@extends('admin::layouts.master')
@section('page_title', 'Văn bản đi đã duyệt')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Văn bản đi đã duyệt</h3>
                    </div>
                    <div class="box-body" style=" width: 100%;overflow-x: auto;">
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
                                <form action="" method="post">
                                    @csrf
                                    <tr class="duyet-gia-han">
                                        <td> {{$key+1}}</td>
                                        <td>{{$vanban->vanbandi->so_ky_hieu ?? ''}}</td>
                                        <td><a href="{{route('Quytrinhxulyvanbandi',$vanban->van_ban_di_id)}}">{{$vanban->vanbandi->trich_yeu ?? ''}}</a><br>
                                            <span style="font-style: italic">
                                                        - Người ký: {{$vanban->vanbandi->nguoidung2->ho_ten ?? ''}} <br>
                                                         - Người nhập: {{$vanban->vanbandi->nguoitao->ho_ten ?? ''}} -
                                                {{isset($vanban->vanbandi->ngay_ban_hanh)? date('d/m/Y', strtotime($vanban->vanbandi->ngay_ban_hanh)) : ''}}
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
                                                - {{$vanban->vanbandi->ngay_ban_hanh ?? ''}}
                                            @endif


                                        </td>
                                        <td>
{{--                                            @forelse($vanban->vanbandi->mailtrongtp as $key=>$item)--}}
{{--                                                - {{$item->laytendonvi->ten_don_vi}}<br>--}}
{{--                                            @empty--}}
{{--                                            @endforelse--}}
{{--                                            @forelse($vanban->vanbandi->mailngoaitp as $key=>$item)--}}
{{--                                                - {{$item->laytendonvingoai->ten_don_vi}}<br>--}}
{{--                                            @empty--}}
{{--                                            @endforelse--}}
                                        </td>
                                        <td>
                                            @if (isset($vanban->vanbandi->filetrinhky))
                                                @forelse($vanban->vanbandi->filetrinhky as $filedata)
                                                    <a href="{{$filedata->getUrlFile()}}" class="seen-new-window">[File trình ký]</a>
                                                @empty
                                                @endforelse
                                            @endif
                                            @if (isset($vanban->vanbandi->filephieutrinh))
                                                @forelse($vanban->vanbandi->filephieutrinh as $filedata)
                                                    &nbsp; | <a href="{{$filedata->getUrlFile()}}" class="seen-new-window" target="popup">[File phiếu trình]</a>
                                                @empty
                                                @endforelse
                                            @endif
                                            @if (isset($vanban->vanbandi->filehoso))
                                                @forelse($vanban->vanbandi->filehoso as $filedata)
                                                    &nbsp; | <a href="{{$filedata->getUrlFile()}}" >[File hồ sơ]</a>
                                                @empty
                                                @endforelse
                                            @endif
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
                                <td colspan="7" class="text-center">Không tìm
                                    thấy dữ liệu.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
@endsection
