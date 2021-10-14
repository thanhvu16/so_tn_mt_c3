<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoChiDao;
use DB, auth, File;
use Modules\VanBanDen\Entities\VanBanDen;

class LanhDaoChiDaoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $danhSachLoaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $danhSachSoVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $danhSachDonViXuLy = DonVi::whereNull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        //search
        $soDenStart = $request->get('so_den_start') ?? null;
        $soDenEnd = $request->get('so_den_end') ?? null;
        $ngayDenStart = !empty($request->get('ngay_den_start')) ? formatYMD($request->get('ngay_den_start')) : null;
        $ngayDenEnd = !empty($request->get('ngay_den_end')) ? formatYMD($request->get('ngay_den_end')) : null;
        $ngayBanHanhStart = !empty($request->get('ngay_ban_hanh_start')) ? formatYMD($request->get('ngay_ban_hanh_start')) : null;
        $ngayBanHanhEnd = !empty($request->get('ngay_ban_hanh_end')) ? formatYMD($request->get('ngay_ban_hanh_end')) : null;
        $soKyHieu = $request->get('so_ky_hieu') ?? null;
        $nguoiKy = $request->get('nguoi_ky') ?? null;
        $loaiVanBanId = $request->get('loai_van_ban_id') ?? null;
        $soVanBanId = $request->get('so_van_ban_id') ?? null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $tomTat = $request->get('tom_tat') ?? null;
        $coQuanBanHanh = $request->get('co_quan_ban_hanh') ?? null;
        $searchDonVi = $request->get('don_vi_id') ?? null;
        $searchDonViPhoiHop = $request->get('don_vi_phoi_hop_id') ?? null;
        $arrVanBanDenIdChuTri = null;
        $arrVanBanDenIdPhoiHop = null;
        if (!empty($searchDonVi)) {
            $donViChuTri = DonViChuTri::where('don_vi_id', $searchDonVi)
                ->select('id', 'van_ban_den_id')
                ->get();


            $arrVanBanDenIdChuTri = $donViChuTri->pluck('van_ban_den_id')->toArray();

        }
        if (!empty($searchDonViPhoiHop)) {
            $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $searchDonViPhoiHop)
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenIdPhoiHop = $donViPhoiHop->pluck('van_ban_den_id')->toArray();
        }


        $danhSachVanBan = VanBanDen::whereNull('deleted_at')
            ->where(function ($query) use ($searchDonVi) {
                if (!empty($searchDonVi)) {
                    return $query->whereHas('searchDonViChuTri', function ($q) use($searchDonVi) {
                        return $q->where('don_vi_id', $searchDonVi);
                    });
                }
            })
            ->where(function ($query) use ($searchDonViPhoiHop, $arrVanBanDenIdPhoiHop) {
                if (!empty($searchDonViPhoiHop)) {
                    return $query->whereIn('id', $arrVanBanDenIdPhoiHop);
                }
            })
            ->where(function ($query) use ($soDenStart, $soDenEnd) {
                if (!empty($soDenStart)) {
                    return $query->whereBetween('so_den', [$soDenStart, $soDenEnd]);
                }
            })
            ->where(function ($query) use ($ngayDenStart, $ngayDenEnd) {
                if (!empty($ngayDenStart)) {
                    return $query->whereBetween('ngay_nhan', [$ngayDenStart, $ngayDenEnd]);
                }
            })
            ->where(function ($query) use ($ngayBanHanhStart, $ngayBanHanhEnd) {
                if (!empty($ngayBanHanhStart)) {
                    return $query->whereBetween('ngay_ban_hanh', [$ngayBanHanhStart, $ngayBanHanhEnd]);
                }
            })
            ->where(function ($query) use ($soKyHieu) {
                if (!empty($soKyHieu)) {
                    return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($soKyHieu) . "%");
                }
            })
            ->where(function ($query) use ($nguoiKy) {
                if (!empty($nguoiKy)) {
                    return $query->where(DB::raw('lower(nguoi_ky)'), 'LIKE', "%" . mb_strtolower($nguoiKy) . "%");
                }
            })
            ->where(function ($query) use ($loaiVanBanId) {
                if (!empty($loaiVanBanId)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanId);
                }
            })
            ->where(function ($query) use ($soVanBanId) {
                if (!empty($soVanBanId)) {
                    return $query->where('so_van_ban_id', $soVanBanId);
                }
            })
            ->where(function ($query) use ($trichYeu) {
                if (!empty($trichYeu)) {
                    return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                }
            })
            ->where(function ($query) use ($tomTat) {
                if (!empty($tomTat)) {
                    return $query->where(DB::raw('lower(tom_tat)'), 'LIKE', "%" . mb_strtolower($tomTat) . "%");
                }
            })
            ->where(function ($query) use ($coQuanBanHanh) {
                if (!empty($coQuanBanHanh)) {
                    return $query->where(DB::raw('lower(co_quan_ban_hanh)'), 'LIKE', "%" . mb_strtolower($coQuanBanHanh) . "%");
                }
            })
            ->get();

        $arrVanBanDen = $danhSachVanBan->pluck('id')->toArray();

        $danhSachVanBanDen = LanhDaoChiDao::where('lanh_dao_id', auth::user()->id)
            ->whereHas('vanBanDenID')
            ->whereNull('trang_thai')
//            ->whereIn('van_ban_den_id', $arrVanBanDen)
            ->paginate(PER_PAGE_10);
        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;
        return view('dieuhanhvanbanden::lanh-dao-chi-dao.index', compact('danhSachVanBanDen', 'loaiVanBanGiayMoi', 'order', 'danhSachLoaiVanBan',
            'danhSachSoVanBan', 'danhSachDonViXuLy'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dieuhanhvanbanden::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $yKien = LanhDaoChiDao::where('id', $request->id_lanh_dao)->first();
        $yKien->y_kien = $request->y_kien;
        $yKien->trang_thai = 1;
        $yKien->save();
        return redirect()->back()->with('success', 'Chỉ đạo thành công');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('dieuhanhvanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dieuhanhvanbanden::edit');
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
