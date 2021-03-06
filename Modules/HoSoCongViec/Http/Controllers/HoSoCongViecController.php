<?php

namespace Modules\HoSoCongViec\Http\Controllers;

use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\HoSoCongViec\Entities\DetailHoSoCV;
use Modules\HoSoCongViec\Entities\ListHoSoCV;
use auth;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDen\Entities\VanBanDenDonVi;
use Modules\VanBanDi\Entities\VanBanDi;

class HoSoCongViecController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $hoso = null;
        $id = (int)$request->get('id');
        if ($id) {
            $hoso = ListHoSoCV::where('id', $id)->first();
        }
        $ds_hoso = ListHoSoCV::where(['trang_thai' => 1, 'nguoi_tao' => auth::user()->id])->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        return view('hosocongviec::list_hscv.index', compact('hoso', 'ds_hoso'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('hosocongviec::list_hscv.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $hoso = new ListHoSoCV();
        $hoso->ten_ho_so = $request->ten_ho_so;
        $hoso->mo_ta = $request->mo_ta;
        $hoso->nguoi_tao = auth::user()->id;
        $hoso->save();
        UserLogs::saveUserLogs(' Tạo hồ sơ ', $hoso);
        return redirect()->back()->with('success', 'Tạo hồ sơ thành công !');
    }

    public function ds_van_ban_hs($id)
    {
        $ds_vb_hoso = DetailHoSoCV::where(['id_ho_so' => $id, 'trang_thai' => 1])->paginate(PER_PAGE);

        return view('hosocongviec::list_hscv.detail_hoso', compact('ds_vb_hoso', 'id'));
    }

    public function ds_tim_kiem_van_ban_hs($id, Request $request)
    {

        $van_ban = null;
        return view('hosocongviec::list_hscv.tim_kiem_van_ban_hs', compact('van_ban', 'id'));
    }

    public function lay_danh_sach_tim_kiem(Request $request)
    {
        $user = auth::user();
        $trich_yeu = $request->get('vb_trich_yeu');
        $so_ky_hieu = $request->get('vb_so_ky_hieu');
        $noi_gui_den = $request->get('noi_gui_den');
        $loai_van_ban = $request->get('loai_van_ban');
        $van_ban = null;
        $donVi = auth::user()->donVi;

        if ($loai_van_ban == 1) {
            if ($user->hasRole(VAN_THU_HUYEN) || ($user->hasRole(CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA) ||
                ($user->hasRole(PHO_CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA)) {
                $van_ban = VanBanDen::where(['type' => 1])
                    ->whereNull('deleted_at')->where(function ($query) use ($trich_yeu) {
                        if (!empty($trich_yeu)) {
                            return $query->where('trich_yeu', 'LIKE', "%$trich_yeu%");
                        }
                    })->where(function ($query) use ($so_ky_hieu) {
                        if (!empty($so_ky_hieu)) {
                            return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                        }
                    })
                    ->where(function ($query) use ($noi_gui_den) {
                        if (!empty($noi_gui_den)) {
                            return $query->where('co_quan_ban_hanh_id', 'LIKE', "%$noi_gui_den%");
                        }
                    })->orderBy('id', 'desc')->paginate(PER_PAGE);
            } else {
//                if ($user->hasRole(CHUYEN_VIEN) || $user->hasRole(PHO_PHONG) || $user->hasRole(TRUONG_PHONG) || $user->hasRole(VAN_THU_DON_VI)) {
                $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;
                $van_ban = VanBanDen::where(['don_vi_id' => $donViId, 'type' => VanBanDen::TYPE_VB_DON_VI])
                    ->whereNull('deleted_at')->where(function ($query) use ($trich_yeu) {
                        if (!empty($trich_yeu)) {
                            return $query->where('trich_yeu', 'LIKE', "%$trich_yeu%");
                        }
                    })->where(function ($query) use ($so_ky_hieu) {
                        if (!empty($so_ky_hieu)) {
                            return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                        }
                    })
                    ->where(function ($query) use ($noi_gui_den) {
                        if (!empty($noi_gui_den)) {
                            return $query->where('co_quan_ban_hanh_id', 'LIKE', "%$noi_gui_den%");
                        }
                    })->orderBy('id', 'desc')->paginate(PER_PAGE);
            }

        } elseif ($loai_van_ban == 2) {
            if ($user->hasRole(VAN_THU_HUYEN) || ($user->hasRole(CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA) || ($user->hasRole(PHO_CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA)) {

                $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })->first();
                $van_ban = $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $lanhDaoSo->don_vi_id])->where('so_di', '!=', null)->whereNull('deleted_at')
                    ->where(function ($query) use ($trich_yeu) {
                        if (!empty($trich_yeu)) {
                            return $query->where('trich_yeu', 'LIKE', "%$trich_yeu%");
                        }
                    })->where(function ($query) use ($so_ky_hieu) {
                        if (!empty($so_ky_hieu)) {
                            return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                        }
                    })
                    ->orderBy('id', 'desc')->paginate(PER_PAGE);
            } else {

//                if ($user->hasRole(CHUYEN_VIEN) || $user->hasRole(PHO_PHONG) || $user->hasRole(TRUONG_PHONG) || $user->hasRole(VAN_THU_DON_VI)) {
                $van_ban = VanBanDi::where(['van_ban_huyen_ky' => auth::user()->don_vi_id])->where('so_di', '!=', null)->whereNull('deleted_at')
                    ->where(function ($query) use ($trich_yeu) {
                        if (!empty($trich_yeu)) {
                            return $query->where('trich_yeu', 'LIKE', "%$trich_yeu%");
                        }
                    })->where(function ($query) use ($so_ky_hieu) {
                        if (!empty($so_ky_hieu)) {
                            return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                        }
                    })
                    ->orderBy('id', 'desc')->paginate(PER_PAGE);
            }
        }

        return response()->json(
            [
                'data' => $van_ban,
                'loaiVanBan' => $loai_van_ban
            ]
        );
    }

    public function luu_vao_detail(Request $request)
    {
        $arrVanBanId = $request->id_van_ban;

        foreach ($arrVanBanId as $vanBanId) {
            $detail = DetailHoSoCV::where([
                'id_van_ban' => $vanBanId,
                'id_ho_so' => $request->id_ho_so,
                'trang_thai' => 1])->first();

            if ($detail == null) {
                if ($request->loai_van_ban == 1) {
                    $vanBanDenDetail = VanBanDen::find($vanBanId);

                    if ($vanBanDenDetail->vanBanDi()) {
                        $vanbandi = new DetailHoSoCV();
                        $vanbandi->id_van_ban = $vanBanDenDetail->vanBanDi()->id;
                        $vanbandi->id_ho_so = $request->id_ho_so;
                        $vanbandi->loai_van_ban = 2;
                        $vanbandi->trang_thai = 1;
                        $vanbandi->save();
                    }
                    $vanbanden = new DetailHoSoCV();
                    $vanbanden->id_van_ban = $vanBanId;
                    $vanbanden->id_ho_so = $request->id_ho_so;
                    $vanbanden->loai_van_ban = 1;
                    $vanbanden->trang_thai = 1;
                    $vanbanden->save();
                    UserLogs::saveUserLogs(' Lưu văn bản vào hồ sơ ', $vanbanden);

                } elseif ($request->loai_van_ban == 2) {
                    $layVanBanDenTuVanBanDi = VanBanDi::findOrFail($vanBanId);

                    if ($layVanBanDenTuVanBanDi && $layVanBanDenTuVanBanDi->getListVanBanDen()) {
                        foreach ($layVanBanDenTuVanBanDi->getListVanBanDen() as $vanBanDen) {
                            $vanbanden = new DetailHoSoCV();
                            $vanbanden->id_van_ban = $vanBanDen->id;
                            $vanbanden->id_ho_so = $request->id_ho_so;
                            $vanbanden->loai_van_ban = 1;
                            $vanbanden->trang_thai = 1;
                            $vanbanden->save();
                        }
                    }
                    $vanbandi = new DetailHoSoCV();
                    $vanbandi->id_van_ban = $vanBanId;
                    $vanbandi->id_ho_so = $request->id_ho_so;
                    $vanbandi->loai_van_ban = 2;
                    $vanbandi->trang_thai = 1;
                    $vanbandi->save();
                    UserLogs::saveUserLogs(' Lưu văn bản vào hồ sơ ', $vanbandi);
                }
            }
        }

        return redirect()->route('ds_van_ban_hs', $request->id_ho_so)->with('success', 'Lưu thành công văn bản !');
    }

    public function delete_tai_lieu($id)
    {
        $tailieuhoso = DetailHoSoCV::where('id', $id)->first();
        $tailieuhoso->trang_thai = 0;
        $tailieuhoso->save();
        UserLogs::saveUserLogs('Xóa văn bản khỏi hồ sơ ', $tailieuhoso);
        return redirect()->back()->with('Xóa tài liệu thành công !');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hosocongviec::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $hoso = ListHoSoCV::where('id', $id)->first();
        return view('hosocongviec::list_hscv.edit', compact('hoso'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $hoso = ListHoSoCV::where('id', $id)->first();
        $hoso->ten_ho_so = $request->ten_ho_so;
        $hoso->mo_ta = $request->mo_ta;
        $hoso->nguoi_tao = auth::user()->id;
        $hoso->trang_thai = $request->trang_thai;
        $hoso->save();
        UserLogs::saveUserLogs('Cập nhật hồ sơ  ', $hoso);
        return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công !');
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
