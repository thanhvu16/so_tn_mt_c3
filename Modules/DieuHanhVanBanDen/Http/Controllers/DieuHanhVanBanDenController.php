<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\VanBanDen\Entities\VanBanDen;
use Auth;

class DieuHanhVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('dieuhanhvanbanden::index');
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
    public function show($id, Request $request)
    {
        $vanBanDen = VanBanDen::with(['loaiVanBan', 'soVanBan', 'doKhan', 'doBaoMat',
            'xuLyVanBanDen', 'XuLyVanBanDenTraLai', 'donViChuTri', 'donViPhoiHop',
            'giaHanVanBan', 'chuyenVienPhoiHopGiaiQuyet', 'duThaoVanBan',
            'giaiQuyetVanBan' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'noi_dung', 'noi_dung_nhan_xet',
                    'user_id', 'can_bo_duyet_id', 'status', 'created_at', 'parent_id');
            }
            ])
            ->findOrFail($id);

        //phoi hop
        $type = $request->get('status') ?? null;

        if ($vanBanDen) {
            $vanBanDen->hasChild = $vanBanDen->hasChild($type) ?? null;
        }

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        // data cua du thao
        $date = Carbon::now()->format('Y-m-d');
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong =  User::role([ TRUONG_PHONG,PHO_PHONG,CHUYEN_VIEN])->where(['don_vi_id' => auth::user()->don_vi_id])->where('id','!=',auth::user()->id)->whereNull('deleted_at')->get();
        $lanhdaokhac = User::role([ TRUONG_PHONG])->where('don_vi_id' ,'!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();
        $ds_nguoiKy = null;
        $vanThuVanBanDiPiceCharts = [];
        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                $truongpho = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($truongpho as $data2)
                {
                    array_push($vanThuVanBanDiPiceCharts, $data2);
                }
                $chanvanphong =  User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG,CHU_TICH, PHO_CHUC_TICH])->get();
                foreach ($chanvanphong as $data)
                {
                    array_push($vanThuVanBanDiPiceCharts, $data);
                }
                $ds_nguoiKy = $vanThuVanBanDiPiceCharts;

                break;
            case PHO_PHONG:
                $truongpho = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($truongpho as $data2)
                {
                    array_push($vanThuVanBanDiPiceCharts, $data2);
                }
                $chanvanphong =  User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                foreach ($chanvanphong as $data)
                {
                    array_push($vanThuVanBanDiPiceCharts, $data);
                }
                $ds_nguoiKy = $vanThuVanBanDiPiceCharts;
                break;
            case TRUONG_PHONG:
                $ds_nguoiKy = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case PHO_CHUC_TICH:
                $ds_nguoiKy = User::role([CHU_TICH])->get();
                break;
            case CHU_TICH:
                $ds_nguoiKy = null;
                break;
            case CHANH_VAN_PHONG:
                $ds_nguoiKy = User::role([PHO_CHUC_TICH, CHU_TICH])->get();
                break;
            case PHO_CHANH_VAN_PHONG:
                $ds_nguoiKy = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;

        }

        return view('dieuhanhvanbanden::van-ban-den.show',
            compact('vanBanDen', 'loaiVanBanGiayMoi', 'ds_loaiVanBan', 'ds_nguoiKy', 'lanhdaotrongphong', 'lanhdaokhac', 'date'));
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

    public function vanBanXemDeBiet(Request $request)
    {
        $hanXuLy = $request->get('han_xu_ly') ? formatYMD($request->get('han_xu_ly')) : null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;

        $lanhDaoXemDeBiet = LanhDaoXemDeBiet::where('lanh_dao_id', auth::user()->id)->orderBy('id', 'DESC')->get();

        $arrVanBanDenId = $lanhDaoXemDeBiet->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with(['vanBanDenFile',
                'xuLyVanBanDen' => function ($query) {
                    return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
                },
                'donViChuTri' => function ($query) {
                    return $query->select('van_ban_den_id', 'can_bo_nhan_id');
                }
            ])
            ->whereIn('id', $arrVanBanDenId)
            ->where(function ($query) use ($hanXuLy) {
                if (!empty($hanXuLy)) {
                    return $query->where('han_xu_ly', $hanXuLy);
                }
            })
            ->where(function ($query) use ($trichYeu) {
                if (!empty($trichYeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichYeu");
                }
            })
            ->where(function ($query) use ($soDen) {
                if (!empty($soDen)) {
                    return $query->where('so_den', $soDen);
                }
            })
            ->paginate(PER_PAGE);

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();

        return view('dieuhanhvanbanden::van-ban-hoan-thanh.xem_de_biet', compact('danhSachVanBanDen', 'order', 'loaiVanBanGiayMoi'));
    }

    public function vanBanQuanTrong(Request $request)
    {
        $hanXuLy = $request->get('han_xu_ly') ? formatYMD($request->get('han_xu_ly')) : null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;

        $vanBanQuanTrong = VanBanQuanTrong::where('user_id', auth::user()->id)->orderBy('id', 'DESC')->get();

        $arrVanBanDenId = $vanBanQuanTrong->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with(['vanBanDenFile',
            'xuLyVanBanDen' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
            },
            'donViChuTri' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_nhan_id');
            }
        ])
            ->whereIn('id', $arrVanBanDenId)
            ->where(function ($query) use ($hanXuLy) {
                if (!empty($hanXuLy)) {
                    return $query->where('han_xu_ly', $hanXuLy);
                }
            })
            ->where(function ($query) use ($trichYeu) {
                if (!empty($trichYeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichYeu");
                }
            })
            ->where(function ($query) use ($soDen) {
                if (!empty($soDen)) {
                    return $query->where('so_den', $soDen);
                }
            })
            ->paginate(PER_PAGE);

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        return view('dieuhanhvanbanden::van-ban-hoan-thanh.van_ban_quan_trong', compact('danhSachVanBanDen', 'order', 'loaiVanBanGiayMoi'));
    }
}
