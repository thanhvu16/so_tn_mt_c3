<?php

namespace Modules\VanBanDen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDen\Entities\VanBanDen;

class ThongKeVanBanPhongController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('vanbanden::thong_ke.thong_ke_vb_phong');
    }

    public function vanBanGiaiQuyet($donVi)
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
            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->get();

        $danhSachVanBanDenChuaHoanThanh = VanBanDen::where(function ($query) use ($donViId) {
            if (!empty($donViId)) {
                return $query->where('don_vi_id', $donViId);
            }
        })
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
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
            'tong' => $tongVanBanDonViKhongDieuHanh
        ];
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
