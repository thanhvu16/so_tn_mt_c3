<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\LoaiVanBan;
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
    public function show($id)
    {
        $vanBanDen = VanBanDen::with('loaiVanBan', 'soVanBan', 'doKhan', 'doBaoMat',
            'xuLyVanBanDen', 'XuLyVanBanDenTraLai', 'donViChuTri', 'donViPhoiHop',
            'giaHanVanBan', 'chuyenVienPhoiHopGiaiQuyet', 'duThaoVanBan')
            ->findOrFail($id);

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

        // data cua du thao
        $date = Carbon::now()->format('Y-m-d');
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong = User::role([TRUONG_PHONG, PHO_PHONG, TRUONG_PHONG, PHO_PHONG])->where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $lanhdaokhac = User::role([TRUONG_PHONG, PHO_PHONG, TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', '!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();

        switch (auth::user()->role_id) {
            case QUYEN_CHUYEN_VIEN:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHUC_TICH, TRUONG_PHONG, PHO_PHONG,])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_PHO_PHONG:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHUC_TICH, TRUONG_PHONG])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_TRUONG_PHONG:
                $ds_nguoiKy = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_PHO_CHUC_TICH:
                $ds_nguoiKy = User::role([CHU_TICH])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_CHANH_VAN_PHONG:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHUC_TICH])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_PHO_CHANH_VAN_PHONG:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_CHU_TICH:
                $ds_nguoiKy = User::role([CHU_TICH])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_VAN_THU_DON_VI:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHUC_TICH, TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;
            case QUYEN_VAN_THU_HUYEN:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHUC_TICH, TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
                break;

        }

        return view('dieuhanhvanbanden::van-ban-den.show',
            compact('vanBanDen', 'loaiVanBanGiayMoi', 'ds_loaiVanBan', 'ds_nguoiKy', 'lanhdaotrongphong', 'lanhdaokhac','date'));
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
