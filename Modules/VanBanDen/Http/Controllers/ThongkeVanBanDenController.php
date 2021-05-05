<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Excel, PDF, DB;
use App\Exports\VanbandenExport;
use App\Exports\thongKeVanBanSoExport;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDen\Entities\VanBanDen;
use function GuzzleHttp\Promise\all;

class ThongkeVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request  $request)
    {
        $id = (int)$request->get('id');
        $sovanban = SoVanBan::whereNull('deleted_at')->whereIn('loai_so',[1,3])->get();
        $timloaiso = $request->get('so_van_ban');
        $vanbanden=null;
        $user = auth::user();
        if ($id) {
            $vanbanden = VanBanDen::where('id', $id)->first();
        }
        if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH)) {
            $ds_vanBanDen =  VanBanDen::where([
                'type' => 1])->where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')
                ->where(function ($query) use ($timloaiso) {
                    if (!empty($timloaiso)) {
                        return $query->where('so_van_ban_id',$timloaiso);
                    }
                })->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        } elseif ($user->hasRole(CHUYEN_VIEN) || $user->hasRole(PHO_PHONG) || $user->hasRole(TRUONG_PHONG) ||
            $user->hasRole(VAN_THU_DON_VI) ||
            $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
            $ds_vanBanDen =  VanBanDen::where([
                'don_vi_id' => auth::user()->don_vi_id,
                'type' => 2])
                ->where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')
                ->where(function ($query) use ($timloaiso) {
                    if (!empty($timloaiso)) {
                        return $query->where('so_van_ban_id',$timloaiso);
                    }
                })->orderBy('created_at', 'desc')->paginate(PER_PAGE);

        }

        $totalRecord = $ds_vanBanDen->count();

        if ($request->get('type') == 'pdf') {
            $fileName = 'in_so_van_ban_den'.date('d_m_Y') .'.pdf';

            $pdf = PDF::loadView('vanbanden::thong_ke.view_index',compact('vanbanden','ds_vanBanDen' ) );

            return $pdf->download($fileName)->header('Content-Type','application/pdf');
        }

        //export word
        if ($request->get('type') == 'word') {
            $fileName = 'in_so_van_ban_di'.date('d_m_Y') .'.doc';

            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$fileName
            );

            $content = view('vanbanden::thong_ke.view_index',compact('vanbanden','ds_vanBanDen' ) );


            return \Response::make($content,200, $headers);

        }
        if ($request->get('type') == 'excel') {
            $fileName = 'in_so_van_ban_den'.date('d_m_Y') .'.xlsx';
            return Excel::download(new vanbandenExport($ds_vanBanDen,$totalRecord),
                $fileName);
        }



        if ($request->ajax()) {

            $html = view('vanbanden::thong_ke.view_index',compact('vanbanden','ds_vanbanden' ) )->render();;
            return response()->json([
                'html' => $html,
            ]);
        }


        return view('vanbanden::thong_ke.index', compact('vanbanden','ds_vanBanDen','sovanban' ));
    }


    public function thongkevbso(Request $request)
    {
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        canPermission(AllPermission::thongKeVanBanSo());
        $danhSachDonVi = DonVi::whereNull('deleted_at')->orderBy('ten_don_vi')->get();

        foreach ($danhSachDonVi as $donVi)
        {
            $donVi->vanBanDaGiaiQuyet = $this->vanBanGiaiQuyet($donVi,$tu_ngay,$den_ngay);

        }
        $soDonvi = $danhSachDonVi->count();

        if ($request->get('type') == 'excel') {
            $tongSoVB = $request->sovanbanden;
            $fileName = 'thongkeVb'.date('d_m_Y') .'.xlsx';
            return Excel::download(new thongKeVanBanSoExport($danhSachDonVi,$soDonvi,$tongSoVB,$tu_ngay,$den_ngay),
                $fileName);
        }
        if ($request->ajax()) {
            $tongSoVB =$request->sovanbanden;
            $html = view('vanbanden::thong_ke.TK_vb_so',compact('danhSachDonVi','tongSoVB' ) )->render();;
            return response()->json([
                'html' => $html,
            ]);
        }
        return view('vanbanden::thong_ke.thong_ke_vb_so',compact('danhSachDonVi'));
    }

    public function vanBanGiaiQuyet($donVi,$tu_ngay,$den_ngay)
    {
        $donViId = null;
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;
            $type = DonVi::DIEU_HANH;
        }
//        $test = VanBanDen::where(function ($query) use ($donViId) {
//            if (!empty($donViId)) {
//                return $query->where('don_vi_id', 7);
//            }
//        })
////            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
//            ->get();
//        dd($test);

        $danhSachVanBanDenDaHoanThanh = VanBanDen::where(function ($query) use ($donViId) {
               if (!empty($donViId)) {
                   return $query->where('don_vi_id', $donViId);
               }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })

            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->get();

        $danhSachVanBanDenChuaHoanThanh = VanBanDen::whereNull('deleted_at')
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
//            ->orwhere('trinh_tu_nhan_van_ban','==' ,null)

            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })


            ->get();

        //hoan thanh
        $vanBanDaGiaiQuyet = $this->getVanBanDenDaGiaiQuyet($danhSachVanBanDenDaHoanThanh, $donVi->id, $type);
        //chưa hoàn thành
        $vanBanChuaGiaiQuyet = $this->getVanBanDenchuaGiaiQuyet($danhSachVanBanDenChuaHoanThanh, $donVi->id, $type);

        $tong = $danhSachVanBanDenDaHoanThanh->count() + $danhSachVanBanDenChuaHoanThanh->count();


        if (empty($type)) {
            $tong =  $vanBanDaGiaiQuyet['tong']+$vanBanChuaGiaiQuyet['tong'];
        }

;        return [
            'tong' => $tong,
            'giai_quyet_trong_han' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'],
            'giai_quyet_qua_han' => $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'chua_giai_quyet_giai_quyet_trong_han' => $vanBanChuaGiaiQuyet['chua_giai_quyet_hoan_thanh_dung_han'],
            'chua_giai_quyet_giai_quyet_qua_han' => $vanBanChuaGiaiQuyet['chua_giai_quyet_hoan_thanh_qua_han'],



        ];
    }




    public function getVanBanDenDaGiaiQuyet($danhSachVanBanDenDaHoanThanh, $donViId, $type)
    {
        $vanBanTrongHan = 0;
        $vanBanQuaHan = 0;
        $tongVanBanDonViKhongDieuHanh = 0;

        if ($type == DonVi::DIEU_HANH) {
            foreach ($danhSachVanBanDenDaHoanThanh as $vanBanDen) {
                if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_DUNG_HAN) {
                    $vanBanTrongHan += 1;
                }
                if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_QUA_HAN) {
                    $vanBanQuaHan += 1;
                }
            }
        } else {
            $arrVanBanDenId = $danhSachVanBanDenDaHoanThanh->pluck('id')->toArray();
            $danhSachVanBanDenDonViDaHoanThanhTrongHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->whereHas('vanBanDen', function ($query) {
                    return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
                })
                ->where('don_vi_id', $donViId)->distinct()->count();
            $vanBanTrongHan = $danhSachVanBanDenDonViDaHoanThanhTrongHan;

            $danhSachVanBanDenDonViDaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->whereHas('vanBanDen', function ($query) {
                    return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
                })
                ->where('don_vi_id', $donViId)->distinct()->count();
            $vanBanQuaHan = $danhSachVanBanDenDonViDaHoanThanhQuaHan;

            $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;
        }


        return [
            'hoan_thanh_dung_han' => $vanBanTrongHan,
            'hoan_thanh_qua_han' => $vanBanQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh
        ];
    }
    public function getVanBanDenchuaGiaiQuyet($danhSachVanBanDenChuaHoanThanh, $donViId, $type)
    {
        $vanBanTrongHan = 0;
        $vanBanQuaHan = 0;
        $tongVanBanDonViKhongDieuHanh = 0;
        if ($type == DonVi::DIEU_HANH) {
            foreach ($danhSachVanBanDenChuaHoanThanh as $vanBanDen) {
                if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_DUNG_HAN) {
                    $vanBanTrongHan += 1;
                }
                if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_QUA_HAN) {
                    $vanBanQuaHan += 1;
                }
            }
        } else {
            $arrVanBanDenId = $danhSachVanBanDenChuaHoanThanh->pluck('id')->toArray();
            $danhSachVanBanDenDonViDaHoanThanhTrongHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->whereHas('vanBanDen', function ($query) {
                    return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
                })
                ->where('don_vi_id', $donViId)->distinct()->count();
            $vanBanTrongHan = $danhSachVanBanDenDonViDaHoanThanhTrongHan;

            $danhSachVanBanDenDonViDaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->whereHas('vanBanDen', function ($query) {
                    return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
                })
                ->where('don_vi_id', $donViId)->distinct()->count();
            $vanBanQuaHan = $danhSachVanBanDenDonViDaHoanThanhQuaHan;
            $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;
        }


        return [
            'chua_giai_quyet_hoan_thanh_dung_han' => $vanBanTrongHan,
            'chua_giai_quyet_hoan_thanh_qua_han' => $vanBanQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh,
        ];
    }

    public function chiTietTongVanBanSo($id,Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id',$id)->first();
        $user = auth::user();
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        if($donVi->dieu_hanh == DonVi::DIEU_HANH )
        {
            if($donVi->cap_xa == null)
            {
                $type = null;
            } else{
                $type = 2;
            }
            $ds_vanBanDen= VanBanDen::
                whereNull('deleted_at')
                ->where(function ($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('don_vi_id', $donViId);
                    }
                })
                ->where(function ($query) use ($type) {
                    if (!empty($type)) {
                        return $query->where('type', $type);
                    }
                })
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();

        }else{
            $ds_vanBanDen = VanBanDen::
            whereNull('deleted_at')
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();
            $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
            $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->where('don_vi_id', $donVi->id)->distinct()->get();
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));


        }

        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }


    public function chiTietDaGiaiQuyetTrongHanVanBanSo($id,Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id',$id)->first();
        $user = auth::user();
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        if($donVi->dieu_hanh == DonVi::DIEU_HANH )
        {
            if($donVi->cap_xa == null)
            {
                $type = null;
            } else{
                $type = 2;
            }
            $ds_vanBanDen= VanBanDen::
            whereNull('deleted_at')
                ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
                ->where('hoan_thanh_dung_han',  VanBanDen::HOAN_THANH_DUNG_HAN)
                ->where(function ($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('don_vi_id', $donViId);
                    }
                })
                ->where(function ($query) use ($type) {
                    if (!empty($type)) {
                        return $query->where('type', $type);
                    }
                })
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();

        }else{
            $ds_vanBanDen = VanBanDen::
            whereNull('deleted_at')
                ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
                ->where('hoan_thanh_dung_han',  VanBanDen::HOAN_THANH_DUNG_HAN)
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();
            $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
            $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->where('don_vi_id', $donVi->id)->distinct()->get();
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));


        }

        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }
    public function chiTietChuaGiaiQuyetQuaHanVanBanSo($id,Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id',$id)->first();
        $user = auth::user();
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        if($donVi->dieu_hanh == DonVi::DIEU_HANH )
        {
            if($donVi->cap_xa == null)
            {
                $type = null;
            } else{
                $type = 2;
            }
            $ds_vanBanDen= VanBanDen::
            whereNull('deleted_at')

                ->where(function ($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('don_vi_id', $donViId);
                    }
                })
                ->where('trinh_tu_nhan_van_ban', '!=',VanBanDen::HOAN_THANH_VAN_BAN)
                ->where('hoan_thanh_dung_han',  VanBanDen::HOAN_THANH_QUA_HAN)
                ->where(function ($query) use ($type) {
                    if (!empty($type)) {
                        return $query->where('type', $type);
                    }
                })
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();

        }else{
            $ds_vanBanDen = VanBanDen::
            whereNull('deleted_at')
                ->where('trinh_tu_nhan_van_ban', '!=',VanBanDen::HOAN_THANH_VAN_BAN)
                ->where('hoan_thanh_dung_han',  VanBanDen::HOAN_THANH_QUA_HAN)
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();
            $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
            $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->where('don_vi_id', $donVi->id)->distinct()->get();
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));


        }

        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }
    public function chiTietChuaGiaiQuyetTrongHanVanBanSo($id,Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id',$id)->first();
        $user = auth::user();
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        if($donVi->dieu_hanh == DonVi::DIEU_HANH )
        {
            if($donVi->cap_xa == null)
            {
                $type = null;
            } else{
                $type = 2;
            }
            $ds_vanBanDen= VanBanDen::
            whereNull('deleted_at')
                ->where(function ($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('don_vi_id', $donViId);
                    }
                })
                ->where(function ($query) use ($type) {
                    if (!empty($type)) {
                        return $query->where('type', $type);
                    }
                })
                ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
                ->where('hoan_thanh_dung_han',  VanBanDen::HOAN_THANH_DUNG_HAN)




//                ->orwhere('trinh_tu_nhan_van_ban',null)
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();

        }else{
            $ds_vanBanDen = VanBanDen::
            whereNull('deleted_at')
                ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
                ->orwhere('trinh_tu_nhan_van_ban','==' ,null)
                ->where('hoan_thanh_dung_han',  VanBanDen::HOAN_THANH_QUA_HAN)
                ->where(function ($query) use ($tu_ngay, $den_ngay) {
                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                    }
                    if ($den_ngay == '' && $tu_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                    }
                    if ($tu_ngay == '' && $den_ngay != '') {
                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                    }
                })
                ->get();
            $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
            $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->where('don_vi_id', $donVi->id)->distinct()->get();
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));


        }

        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('vanbanden::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('vanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('vanbanden::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
