<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBanFile;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Auth;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanDenHoanThanhController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = auth::user();

        $hanXuLy = $request->get('han_xu_ly') ? formatYMD($request->get('han_xu_ly')) : null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;

        if ($currentUser->hasRole([TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN, PHO_CHANH_VAN_PHONG, CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {

            $xuLyVanBanDen = DonViChuTri::where([
                'don_vi_id' => $currentUser->don_vi_id,
                'can_bo_nhan_id' => $currentUser->id,
                'hoan_thanh' => DonViChuTri::HOAN_THANH_VB
            ])->select('van_ban_den_id')->get();

        } else {
            if ($currentUser->donVi->cap_xa == DonVi::CAP_XA) {
                $xuLyVanBanDen = DonViChuTri::where([
                    'don_vi_id' => $currentUser->don_vi_id,
                    'can_bo_nhan_id' => $currentUser->id,
                    'hoan_thanh' => DonViChuTri::HOAN_THANH_VB
                ])->select('van_ban_den_id')->get();
            } else {
                $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $currentUser->id)
                    ->where('hoan_thanh', XuLyVanBanDen::HOAN_THANH_VB)
                    ->select('van_ban_den_id')
                    ->get();
            }
        }


        $arrVanBanDenId = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with(['vanBanDenFile', 'vanBanDi',
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
            ->select('id', 'so_ky_hieu', 'loai_van_ban_id', 'so_den', 'ngay_ban_hanh', 'co_quan_ban_hanh',
                'nguoi_ky', 'nguoi_tao', 'han_xu_ly', 'trich_yeu', 'do_khan_cap_id', 'do_bao_mat_id', 'van_ban_can_tra_loi',
                'noi_dung_hop', 'gio_hop', 'ngay_hop', 'dia_diem', 'noi_dung', 'trinh_tu_nhan_van_ban', 'created_at')
            ->paginate(PER_PAGE);

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        return view('dieuhanhvanbanden::van-ban-hoan-thanh.index', compact('danhSachVanBanDen', 'order', 'loaiVanBanGiayMoi'));
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

    public function choDuyet()
    {

        $currentUser = auth::user();

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, PHO_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {
            $giaiQuyetVanBan = GiaiQuyetVanBan::where('can_bo_duyet_id', $currentUser->id)
                ->whereNull('status')->select('id', 'van_ban_den_id')->get();

            $view = 'dieuhanhvanbanden::van-ban-hoan-thanh.truong_phong_cho_duyet';

        } else {

            $giaiQuyetVanBan = GiaiQuyetVanBan::where('user_id', $currentUser->id)
                ->whereNull('status')->select('id', 'van_ban_den_id')->get();

            $view = 'dieuhanhvanbanden::van-ban-hoan-thanh.chuyen_vien_cho_duyet';
        }

        $arrVanBanDenId = $giaiQuyetVanBan->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with('vanBanDenFile', 'nguoiDung', 'donViChuTri', 'xuLyVanBanDen')
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                $vanBanDen->giaiQuyetVanBanHoanThanhChoDuyet = $vanBanDen->giaiQuyetVanBanHoanThanhChoDuyet() ?? null;
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        return view($view, compact('danhSachVanBanDen', 'order', 'loaiVanBanGiayMoi'));
    }

    public function duyetVanBan(Request $request)
    {
        if ($request->ajax()) {
            $currentUser = auth::user();
            $donVi = $currentUser->donVi;
            $id = (int)$request->get('id');
            $status = (int)$request->get('status');
            $noiDung = $request->get('noiDung');
            $giaiQuyetVanBanId = $request->get('giaiQuyet');

            $vanBanDen = VanBanDen::where('id', $id)->first();
            $giaiQuyetVanBan = GiaiQuyetVanBan::where('id', $giaiQuyetVanBanId)
                ->whereNull('status')->first();

            // update giai quyet vb
            if ($giaiQuyetVanBan) {
                $giaiQuyetVanBan->noi_dung_nhan_xet = $noiDung ?? null;
                $giaiQuyetVanBan->ngay_duyet = date('Y-m-d H:i:s');
                $giaiQuyetVanBan->status = $status;
                $giaiQuyetVanBan->save();
            }

            if ($status == GiaiQuyetVanBan::STATUS_DA_DUYET) {
                if ($vanBanDen) {
                    if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN, CHU_TICH, PHO_CHUC_TICH])) {

//                        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
//                            // chu tich xa duyet van ban
//                            if ($currentUser->hasRole(CHU_TICH)) {
//                                $this->updateVanBanDen($vanBanDen);
//
//                                return response()->json([
//                                    'success' => true,
//                                    'message' => 'Duyệt thành công, hoàn thành văn bản',
//                                    200
//                                ]);
//
//                            } else {
//                                $chuyenNhanVanBanDonVi = DonViChuTri::where('van_ban_den_id', $id)
//                                    ->where('can_bo_nhan_id', auth::user()->id)
//                                    ->whereNull('hoan_thanh')->first();
//
//                                $canBoDuyetId = $chuyenNhanVanBanDonVi->can_bo_chuyen_id;
//
//                                // gửi duyệt trưởng phòng
//                                $giaiQuyet = new GiaiQuyetVanBan();
//                                $giaiQuyet->van_ban_den_id = $giaiQuyetVanBan->van_ban_den_id;
//                                $giaiQuyet->noi_dung = $giaiQuyetVanBan->noi_dung;
//                                $giaiQuyet->user_id = auth::user()->id;
//                                $giaiQuyet->parent_id = $giaiQuyetVanBan->id;
//                                $giaiQuyet->can_bo_duyet_id = $canBoDuyetId;
//                                $giaiQuyet->save();
//
//                                //save file giai quyet
//                                $giaiQuyetVanBanFiles = $giaiQuyetVanBan->giaiQuyetVanBanFile;
//                                GiaiQuyetVanBanFile::saveGiaiQuyetVanBanFile($giaiQuyet->id, $giaiQuyetVanBanFiles);
//
//                                return response()->json([
//                                    'success' => true,
//                                    'message' => 'Thành công, đã gửi trưởng phòng duyệt',
//                                    200
//                                ]);
//                            }
//                        } else {
                        $this->updateVanBanDen($vanBanDen);

                        return response()->json([
                            'success' => true,
                            'message' => 'Duyệt thành công, hoàn thành văn bản',
                            200
                        ]);
//                        }
                    } else {
                        $roles = [TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN];
                        $truongPhongDonVi = User::where('don_vi_id', $currentUser->don_vi_id)
                            ->whereHas('roles', function ($query) use ($roles) {
                                return $query->whereIn('name', $roles);
                            })
                            ->where('trang_thai', ACTIVE)
                            ->whereNull('deleted_at')->first();

                        // gửi duyệt trưởng phòng
                        $giaiQuyet = new GiaiQuyetVanBan();
                        $giaiQuyet->van_ban_den_id = $giaiQuyetVanBan->van_ban_den_id;
                        $giaiQuyet->noi_dung = $giaiQuyetVanBan->noi_dung;
                        $giaiQuyet->user_id = auth::user()->id;
                        $giaiQuyet->parent_id = $giaiQuyetVanBan->id;
                        $giaiQuyet->can_bo_duyet_id = $truongPhongDonVi->id;
                        $giaiQuyet->save();

                        //save file giai quyet
                        $giaiQuyetVanBanFiles = $giaiQuyetVanBan->giaiQuyetVanBanFile;
                        GiaiQuyetVanBanFile::saveGiaiQuyetVanBanFile($giaiQuyet->id, $giaiQuyetVanBanFiles);

                        return response()->json([
                            'success' => true,
                            'message' => 'Thành công, đã gửi trưởng phòng duyệt',
                            200
                        ]);
                    }

                }

                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy dữ liệu'
                ]);

            } else {

                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHUYEN_VIEN_NHAN_VB;
                $vanBanDen->save();

                return response()->json([
                    'success' => true,
                    'message' => "Đã gửi trả lại văn bản.",
                    200
                ]);
            }
        }
    }

    public function updateVanBanDen($vanBanDen)
    {
        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_VAN_BAN;
        $vanBanDen->hoan_thanh_dung_han = VanBanDen::checkHoanThanhVanBanDungHan($vanBanDen->han_xu_ly);
        $vanBanDen->ngay_hoan_thanh = date('Y-m-d H:i:s');
        $vanBanDen->save();

        // update van ban co parent_id
        if ($vanBanDen->hasChild()) {
            $vanBanDenDonVi = $vanBanDen->hasChild();
            $vanBanDenDonVi->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_VAN_BAN;
            $vanBanDenDonVi->hoan_thanh_dung_han = VanBanDen::checkHoanThanhVanBanDungHan($vanBanDenDonVi->han_xu_ly);
            $vanBanDenDonVi->ngay_hoan_thanh = date('Y-m-d H:i:s');
            $vanBanDenDonVi->save();
        }

        //update luu vet van ban
        XuLyVanBanDen::where('van_ban_den_id', $vanBanDen->id)
            ->update(['hoan_thanh' => XuLyVanBanDen::HOAN_THANH_VB]);

        //update chuyen nhan vb don vi
        DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->update(['hoan_thanh' => DonViChuTri::HOAN_THANH_VB]);
    }
}
