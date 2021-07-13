<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\VanBanDen\Entities\VanBanDen;
use Auth;

class PhanLoaiVanBanPhoiHopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = auth::user();
        $donVi = $currentUser->donVi;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;
        $date = $request->get('date') ?? null;

        $trinhTuNhanVanBan = null;

        $chuyenTiep = $request->get('chuyen_tiep') ?? null;

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }

        $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;

        $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $donViId)
            ->where('can_bo_nhan_id', $currentUser->id)
            ->where(function ($query) use ($chuyenTiep) {
                if (!empty($chuyenTiep)) {
                    return $query->where('chuyen_tiep', $chuyenTiep);
                } else {
                    return $query->whereNull('chuyen_tiep');
                }
            })
            ->where('active', DonViPhoiHop::ACTIVE)
            ->whereNull('hoan_thanh')
            ->whereNotNull('vao_so_van_ban')
            ->select('id', 'van_ban_den_id')
            ->get();

        $arrVanBanDenId = $donViPhoiHop->pluck('van_ban_den_id')->toArray();

        $danhSachDonVi = DonVi::whereNull('deleted_at')
            ->whereHas('user')
            ->where('parent_id', $donViId)
            ->select('id', 'ten_don_vi')
            ->orderBy('thu_tu','asc')
            ->get();

        $chuTich = User::role(CHU_TICH)
            ->where('trang_thai', ACTIVE)
            ->where('don_vi_id', $donViId)
            ->select('id', 'ho_ten')
            ->first();

        $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
            ->where('trang_thai', ACTIVE)
            ->where('don_vi_id', $donViId)
            ->select('id', 'ho_ten')
            ->get();

        $danhSachVanBanDen = VanBanDen::with([
            'xuLyVanBanDen' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
            },
            'donViChuTri' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_nhan_id');
            }
        ])
            ->where(function ($query) use ($trichYeu) {
                if (!empty($trichYeu)) {
                    return $query->where('trich_yeu', "LIKE", $trichYeu);
                }
            })
            ->where(function ($query) use ($soDen) {
                if (!empty($soDen)) {
                    return $query->where('so_den', $soDen);
                }
            })
            ->where(function ($query) use ($date) {
                if (!empty($date)) {
                    return $query->where('updated_at', "LIKE", $date);
                }
            })
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

        if (!empty($danhSachVanBanDen)) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild(VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP) ?? null;
                // chu tich
                $vanBanDen->chuTich = $vanBanDen->donViPhoiHopVanBan([$chuTich->id]);
                $vanBanDen->phoChuTich = $vanBanDen->donViPhoiHopVanBan($danhSachPhoChuTich->pluck('id')->toArray());
                $vanBanDen->lanhDaoXemDeBiet = $vanBanDen->lanhDaoXemDeBiet ?? null;

            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        if ($currentUser->hasrole(CHUYEN_VIEN)) {

            return view('dieuhanhvanbanden::don-vi-phoi-hop.chuyen-vien',
                compact('danhSachVanBanDen', 'order'));

        }
        // view da chi dao
        if (!empty($chuyenTiep)) {

            return view('dieuhanhvanbanden::don-vi-phoi-hop.cap_xa.da_phan_loai',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich',
                    'donVi', 'order', 'trinhTuNhanVanBan', 'chuTich', 'danhSachDonVi'));
        }

        return view('dieuhanhvanbanden::don-vi-phoi-hop.cap_xa.phan_loai_van_ban',
            compact('danhSachVanBanDen', 'danhSachPhoChuTich',
            'donVi', 'order', 'trinhTuNhanVanBan', 'chuTich', 'danhSachDonVi'));
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
        //
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
