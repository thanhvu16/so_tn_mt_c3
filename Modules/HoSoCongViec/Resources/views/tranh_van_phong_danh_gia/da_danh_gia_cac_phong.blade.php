@extends('administrator::layouts.master')

@section('page_title', 'Cán bộ đã đánh giá')

@section('content')
    <div class="container-fluid" >

        <div class="row">
            <div class="col-md-12" >
                <div class="card-box pd-0">
                    <div class="tab-content pd-0">
                        <div class="tab-pane active" id="home">

                            <div class="col-md-12 ">
                                <div class="row">
                                    <div class="col-md-2 text-left">

                                        <div class="row">
                                            <div class="col-md-12"><h4 class="header-title pt-2"><i
                                                        class="fas fa-user-check"></i> Đã đánh
                                                    giá
                                                </h4></div>

                                        </div>

                                    </div>

                                    <div class="col-md-7 mb-2" style="margin-top: 5px">
                                        <form action="{{route('dadanhgia')}}">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select
                                                        class="form-control show-tick dropdown-search"
                                                        name="thang" onchange="this.form.submit()"
                                                        id="thang"
                                                        required>
                                                        <option value="0">--Tháng đánh giá--</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option
                                                                value="{{ $i }}" {{ empty(Request::get('thang')) && $i == $month ? 'selected' : Request::get('thang') == $i ? 'selected' : '' }}>
                                                                Tháng {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control select2-search"
                                                            onchange="this.form.submit()" name="phong">
                                                        <option value="">Chọn phòng đánh giá</option>
                                                        @foreach($phongdanhgia as $data)
                                                            <option
                                                                value="{{$data->ma_id}}"{{ Request::get('phong') == $data->ma_id ? 'selected' : '' }}>{{$data->ten_don_vi}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control select2-search"
                                                            onchange="this.form.submit()" name="ca_nhan">
                                                        <option value="">Chọn cán bộ đánh giá</option>
                                                        @foreach($nguoidung as $data2)
                                                            <option
                                                                value="{{$data2->id}}"{{ Request::get('ca_nhan') == $data2->id ? 'selected' : '' }}>{{$data2->ho_ten}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3"><select class="form-control  select2-search"
                                                                              onchange="this.form.submit()"
                                                                              name="muc_xep_loai">
                                                        <option value="">Chọn mức xếp loại</option>
                                                        <option
                                                            value="1" {{ Request::get('muc_xep_loai') == 1 ? 'selected' : '' }}>
                                                            HTXS
                                                        </option>
                                                        <option
                                                            value="2" {{ Request::get('muc_xep_loai') == 2 ? 'selected' : '' }}>
                                                            HTT
                                                        </option>
                                                        <option
                                                            value="3" {{ Request::get('muc_xep_loai') == 3 ? 'selected' : '' }}>
                                                            HT
                                                        </option>
                                                        <option
                                                            value="4" {{ Request::get('muc_xep_loai') == 4 ? 'selected' : '' }}>
                                                            KHT
                                                        </option>
                                                    </select></div>


                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-md-1  text-left">
                                        <form action method="GET" action="{{ route('dadanhgia') }}" class="form-export">
                                            <input type="hidden" name="ca_nhan" value="{{ request('ca_nhan') }}">
                                            <input type="hidden" name="phong" value="{{ request('phong') }}">
                                            <input type="hidden" name="thang" value="{{ request('thang') }}">
                                            <input type="hidden" name="thang" value="{{ request('thang') }}">
                                            <input type="hidden" name="muc_xep_loai" value="{{ request('muc_xep_loai') }}">
                                            <input type="hidden" name="type" value="">
                                            <button type="button" data-type="excel"
                                                    class="btn btn-success waves-effect waves-light btn-sm btn-export-data mt-1"><i
                                                    class="far fa-file-excel"></i> Xuất Excel
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-2 text-right">
{{--                                        <button type="submit" form="data2"--}}
{{--                                                class="btn btn-sm mt-1 btn-submit btn-primary waves-effect waves-light pull-right btn-duyet-all btn-sm mb-2"--}}
{{--                                                data-original-title="" title=""><i class="fa fa-check"></i> Duyệt--}}
{{--                                        </button>--}}
                                    </div>
                                </div>
                            </div>

                            <form action="{{route('trvpdanhgia')}}" id="data2" method="POST">
                                <div class="col-md-12">
                                    @csrf
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                        <tr style="background-color: rgb(60, 141, 188); color: rgb(255, 255, 255);">
                                            <th width="4%" class="text-center" style="vertical-align: middle;">STT</th>
                                            <th width="13%" class="text-center" style="vertical-align: middle;">Cá
                                                nhân
                                            </th>
                                            <th width="6%" class="text-center" style="vertical-align: middle;">Số điểm
                                            </th>
                                            <th width="13%" class="text-center" style="vertical-align: middle;">Phó
                                                phòng
                                            </th>
                                            <th width="13%" class="text-center" style="vertical-align: middle;">Trưởng
                                                phòng
                                            </th>
                                            <th width="8%" class="text-center" style="vertical-align: middle;">Mức xếp
                                                loại
                                            </th>
                                            <th width="5%" class="text-center" style="vertical-align: middle;">Tháng
                                            </th>
                                            <th class="text-center" style="vertical-align: middle;">Đánh giá của tránh
                                                văn
                                                phòng
                                            </th>
                                            <th width="6%" class="text-center" style="vertical-align: middle;">Điểm
                                            </th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($newArr as $key =>$item)
                                            <tr>
                                                <td class="text-center"><b>{{ soLaMa($key+1) }}</b></td>
                                                <td colspan="8"><b>{{$item['ten_don_vi']}}</b></td>
                                            </tr>

                                            @forelse($item['can_bo'] as $key => $data)

                                                <tr>
                                                    <td class="text-center"
                                                        style="vertical-align: middle;">{{$key+1}}<input
                                                            type="text" class="hidden" name="id[]"
                                                            value="{{$data->id}}"><input
                                                            type="text" class="hidden" name="phong[]"
                                                            value="{{$data->phong}}"></td>
                                                    <td style="vertical-align: middle;">
                                                        <a href="{{route('dadanhgiatvp',$data->ca_nhan.'?thangdanhgia='.$data->thang)}}">{{$data->nguoidung->ho_ten}}</a>

                                                        <input type="text" class="hidden" name="ca_nhan[]"
                                                               value="{{$data->diem_ca_nhan}}"></td>
                                                    <td style="vertical-align: middle;"
                                                        class="text-center">{{$data->xep_loai }}<input type="text"
                                                                                                        class="hidden"
                                                                                                        name="diem[]"
                                                                                                        value="{{$data->xep_loai}}">
                                                    </td>
                                                    <td class="text-left"
                                                        style="vertical-align: middle;">{{$data->nhan_xet_pho_phong}}
                                                        <input type="text" class="hidden"
                                                               name="nhan_xet_pho_phong[]"
                                                               value="{{$data->nhan_xet_pho_phong }}">
                                                        <input type="text" class="hidden" name="diem_pho_phong[]"
                                                               value="{{$data->diem_pho_phong_cham }}">
                                                    </td>
                                                    <td class="text-left"
                                                        style="vertical-align: middle;">{{$data->nhan_xet_truong_phong }}
                                                        <input type="text" class="hidden"
                                                               name="nhan_xet_truong_phong[]"
                                                               value="{{$data->nhan_xet_truong_phong}}"><input
                                                            type="text" class="hidden" name="diem_truong_phong[]"
                                                            value="{{$data->diem_truong_phong_cham }}">
                                                    </td>

                                                    <td class="text-center"
                                                        style="vertical-align: middle;">
                                                        @if($data->diem_ca_nhan >90)
                                                            HTXS
                                                        @elseif($data->diem_ca_nhan > 70 && $data->diem_ca_nhan <90)
                                                            HTT
                                                        @elseif($data->diem_ca_nhan > 50 && $data->diem_ca_nhan <70)
                                                            HT
                                                        @elseif($data->diem_ca_nhan < 50)
                                                            KHT
                                                        @endif
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <b>{{$data->thang}}<input type="text" class="hidden"
                                                                                  name="thang[]"
                                                                                  value="{{$data->thang}}"></b>
                                                    </td>
                                                    <td class="text-center">{{$data->nhan_xet_tranh_vp}}
                                                    </td>
                                                    <td class="text-center" style="color: red;font-weight: bold">
                                                        {{$data->xep_loai}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <td class="text-center" colspan="9">Chưa có dữ liệu</td>
                                            @endforelse
                                        @empty
                                            <td class="text-center" colspan="9">Chưa có dữ liệu</td>


                                        @endforelse

                                        </tbody>
                                    </table>
                                    <!-- <div><b>Tổng văn bản: </b></div> -->
                                    <div class="text-right">

                                    </div>
                                </div>
                                <div class="col-md-12">

                                    <br>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $('.btn-export-data').on('click', function () {
            let type = $(this).data('type');
            $('input[name="type"]').val(type);
            $('.form-export').submit();
            hideLoading();
        });

    </script>
@endsection
