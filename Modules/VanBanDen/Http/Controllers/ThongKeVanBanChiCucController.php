<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Exports\thongKeVanBanChiCucExport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDen\Entities\VanBanDen;
use Excel, auth;

class ThongKeVanBanChiCucController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $currentDonVi = auth::user()->donVi;
        $donViId = $currentDonVi->parent_id != 0 ? $currentDonVi->parent_id : $currentDonVi->id;
        $donViChiCuc = null;

        $donViChiCuc = DonVi::where('id', $donViId)->whereNull('deleted_at')->first();

        canPermission(AllPermission::thongKeVanBanChiCuc());

        if($request->don_vi == null)
        {
            $danhSachDonVi = DonVi::whereNull('deleted_at')->orderBy('ten_don_vi')
                ->where('cap_xa',1)
                ->where('id', $donViId)
                ->orWhere('parent_id', $donViId)
                ->orderBy('thu_tu','asc')
                ->paginate(10);
        }else{
            $danhSachDonVi = DonVi::whereNull('deleted_at')->orderBy('ten_don_vi')
                ->where('cap_xa',1)
                ->where('id', $request->don_vi)
                ->orWhere('parent_id', $request->don_vi)
                ->orderBy('thu_tu','asc')
                ->paginate(10);
        }

        foreach ($danhSachDonVi as $donVi)
        {
            $donVi->vanBanDaGiaiQuyet = $this->vanBanGiaiQuyet($donVi,$tu_ngay,$den_ngay);
        }

        $soDonvi = $danhSachDonVi->count();

        if ($request->get('type') == 'excel') {
            $tongSoVB = $request->sovanbanden;
            $fileName = 'thongkeVb'.date('d_m_Y') .'.xlsx';
            return Excel::download(new thongKeVanBanChiCucExport($danhSachDonVi,$soDonvi,$tongSoVB,$donViChiCuc,$tu_ngay,$den_ngay),
                $fileName);
        }
        if ($request->ajax()) {
            $tongSoVB =$request->sovanbanden;
            $html = view('vanbanden::thong_ke.TK_vb_chi_cuc',compact('danhSachDonVi','tongSoVB','donViChiCuc','tu_ngay','den_ngay' ) )->render();;
            return response()->json([
                'html' => $html,
            ]);
        }
        return view('vanbanden::thong_ke.thong_ke_vb_chi_cuc',compact('danhSachDonVi','donViChiCuc'));

    }

    public function vanBanGiaiQuyet($donVi,$tu_ngay,$den_ngay)
    {
        $donViId = null;
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;
            $type = DonVi::DIEU_HANH;
        }

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

        $danhSachVanBanDenChuaHoanThanh = VanBanDen::where(function ($query) use ($donViId) {
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
//            ->orwhere('trinh_tu_nhan_van_ban',Null)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->get();

        //hoan thanh
        $vanBanDaGiaiQuyet = $this->getVanBanDenDaGiaiQuyet($danhSachVanBanDenDaHoanThanh, $donVi->id, $type);
        //ch??a ho??n th??nh
        $vanBanChuaGiaiQuyet = $this->getVanBanDenchuaGiaiQuyet($danhSachVanBanDenChuaHoanThanh, $donVi->id, $type);

//        $tong = $danhSachVanBanDenDaHoanThanh->count() + $danhSachVanBanDenChuaHoanThanh->count();
//
//
//        if (empty($type)) {
            $tong =  $vanBanDaGiaiQuyet['tong']+$vanBanChuaGiaiQuyet['tong'];
//        }

        return [
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

//        if ($type == DonVi::DIEU_HANH) {
//            foreach ($danhSachVanBanDenDaHoanThanh as $vanBanDen) {
//                if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_DUNG_HAN) {
//                    $vanBanTrongHan += 1;
//                }
//                if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_QUA_HAN) {
//                    $vanBanQuaHan += 1;
//                }
//            }
//        } else {
            $arrVanBanDenId = $danhSachVanBanDenDaHoanThanh->pluck('id')->toArray();
            $danhSachVanBanDenDonViDaHoanThanhTrongHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->whereHas('vanBanDen', function ($query) {
                    return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
                })
                ->where('don_vi_id', $donViId)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id')->count();
//            dd($danhSachVanBanDenDonViDaHoanThanhTrongHan, $donViId);
            $vanBanTrongHan = $danhSachVanBanDenDonViDaHoanThanhTrongHan;

            $danhSachVanBanDenDonViDaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->whereHas('vanBanDen', function ($query) {
                    return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
                })
                ->where('don_vi_id', $donViId)->get()->unique('van_ban_den_id')->count();
            $vanBanQuaHan = $danhSachVanBanDenDonViDaHoanThanhQuaHan;

            $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;
//        }


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
        $currentDate = date('Y-m-d');
//        if ($type == DonVi::DIEU_HANH) {
//            foreach ($danhSachVanBanDenChuaHoanThanh as $vanBanDen) {
//                if ($vanBanDen->han_xu_ly >= $currentDate || $vanBanDen->han_xu_ly == null) {
//                    $vanBanTrongHan += 1;
//                }
//                if ($vanBanDen->han_xu_ly < $currentDate && $vanBanDen->han_xu_ly != null) {
//                    $vanBanQuaHan += 1;
//                }
//            }
//            $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;
//
//        } else {
        $arrVanBanDenId = $danhSachVanBanDenChuaHoanThanh->pluck('id')->toArray();
        $danhSachVanBanDenDonViChuaHoanThanhTrongHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
            ->whereHas('vanBanDen', function ($query) use ($currentDate) {
                return $query->where('han_xu_ly', '>=', $currentDate);
            })
            ->where('don_vi_id', $donViId)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id')->count();
        $vanBanTrongHan = $danhSachVanBanDenDonViChuaHoanThanhTrongHan;

        $danhSachVanBanDenDonViChuaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
            ->whereHas('vanBanDen', function ($query) use ($currentDate) {
                return $query->where('han_xu_ly', '<', $currentDate);
            })
            ->where('don_vi_id', $donViId)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id')->count();
        $vanBanQuaHan = $danhSachVanBanDenDonViChuaHoanThanhQuaHan;
        $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;
//        }


        return [
            'chua_giai_quyet_hoan_thanh_dung_han' => $vanBanTrongHan,
            'chua_giai_quyet_hoan_thanh_qua_han' => $vanBanQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh
        ];
    }



    public function chiTietDaGiaiQuyetTrongHanVanBanChiCuc($id,Request $request)
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
                ->where('don_vi_id', $donVi->id)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id');
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));


        }

        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }
    public function chiTietDaGiaiQuyetQuaHanVanBanChiCuc($id,Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id',$id)->first();
        $date = date('Y-m-d');
        $user = auth::user();
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        if($donVi->dieu_hanh == DonVi::DIEU_HANH )
        {

            $ds_vanBanDen= VanBanDen::
            whereNull('deleted_at')
                ->where(function ($query){
                    return  $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
                        ->orWhereNull('trinh_tu_nhan_van_ban');
                })
                ->where('han_xu_ly', '>', $date)
                ->where(function ($query) use ($donViId) {
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
                ->get();

        }else{
            $ds_vanBanDen = VanBanDen::
            whereNull('deleted_at')
                ->where(function ($query){
                    return  $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
                        ->orWhereNull('trinh_tu_nhan_van_ban');
                })
                ->where('han_xu_ly', '>', $date)
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
                ->where('don_vi_id', $donVi->id)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id');
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));


        }

        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }
    public function chiTietChuaGiaiQuyetQuaHanVanBanChiCuc($id,Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id',$id)->first();
        $date = date('Y-m-d');
        $type = null;
        if( $donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        if($donVi->dieu_hanh == DonVi::DIEU_HANH )
        {

            $ds_vanBanDen= VanBanDen::
            whereNull('deleted_at')

                ->where(function ($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('don_vi_id', $donViId);
                    }
                })
                ->where(function ($query){
                    return  $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
                        ->orWhereNull('trinh_tu_nhan_van_ban');
                })
                ->where('han_xu_ly', '<', $date)
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
                ->where(function ($query){
                    return  $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
                        ->orWhereNull('trinh_tu_nhan_van_ban');
                })
                ->where('han_xu_ly', '<', $date)
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
                ->where('don_vi_id', $donVi->id)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id');
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));


        }

        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }
    public function chiTietChuaGiaiQuyetTrongHanVanBanChiCuc($id,Request $request)
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
        $date = date('Y-m-d');

        if($donVi->dieu_hanh == DonVi::DIEU_HANH )
        {

            $ds_vanBanDen= VanBanDen::
            whereNull('deleted_at')
                ->where(function ($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('don_vi_id', $donViId);
                    }
                })

                ->where(function ($query){
                    return  $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
                        ->orWhereNull('trinh_tu_nhan_van_ban');
                })
                ->where(function ($query) use ($date){
                    return  $query->where('han_xu_ly', '>=',$date)
                        ->orWhereNull('han_xu_ly');
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
                ->where(function ($query){
                    return  $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
                        ->orWhereNull('trinh_tu_nhan_van_ban');
                })
                ->where(function ($query) use ($date){
                    return  $query->where('han_xu_ly', '>=',$date)
                        ->orWhereNull('han_xu_ly');
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
            $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
            $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
                ->where('don_vi_id', $donVi->id)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id');
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
