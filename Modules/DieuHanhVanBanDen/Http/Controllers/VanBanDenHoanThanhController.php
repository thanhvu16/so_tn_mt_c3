<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
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

        $date = !empty($request->get('date')) ? $request->get('date') : null;


        if ($currentUser->hasRole([TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN])) {

            $xuLyVanBanDen = DonViChuTri::where([
                'don_vi_id' => $currentUser->don_vi_id,
                'can_bo_nhan_id' => $currentUser->id,
                'hoan_thanh' => DonViChuTri::HOAN_THANH_VB
            ])->get();

        } else {
            $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $currentUser->id)
                ->where('hoan_thanh', XuLyVanBanDen::HOAN_THANH_VB)
                ->get();
        }


        $arrVanBanDenId = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with('vanBanDenFile', 'nguoiDung', 'xuLyVanBanDen', 'donViChuTri')
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

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

        if ($currentUser->hasRole(TRUONG_PHONG)) {
            $giaiQuyetVanBan = GiaiQuyetVanBan::where('can_bo_duyet_id', $currentUser->id)
                ->whereNull('status')->get();

            $view = 'dieuhanhvanbanden::van-ban-hoan-thanh.truong_phong_cho_duyet';

        } else {

            $giaiQuyetVanBan = GiaiQuyetVanBan::where('user_id', $currentUser->id)
                ->whereNull('status')->get();

            $view = 'dieuhanhvanbanden::van-ban-hoan-thanh.chuyen_vien_cho_duyet';
        }

        $arrVanBanDenId = $giaiQuyetVanBan->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with('vanBanDenFile', 'nguoiDung', 'donViChuTri', 'xuLyVanBanDen')
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

        return view($view, compact('danhSachVanBanDen', 'order', 'loaiVanBanGiayMoi'));
    }

    public function duyetVanBan(Request $request)
    {
        if ($request->ajax()) {

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

                    //xoa chuyen nhan vb
//                    $chuyenNhanVanBanDonVi = DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
//                        ->where('can_bo_nhan_id', auth::user()->id)
//                        ->whereNull('hoan_thanh')->first();

//                    if ($chuyenNhanVanBanDonVi) {
//                        DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
//                            ->where('id', '>', $chuyenNhanVanBanDonVi->id)
//                            ->where('don_vi_id', auth::user()->don_vi_id)
//                            ->whereNull('hoan_thanh')->delete();
//                    }


                    //update luu vet van ban
                    XuLyVanBanDen::where('van_ban_den_id', $vanBanDen->id)
                        ->update(['hoan_thanh' => XuLyVanBanDen::HOAN_THANH_VB]);

                    //update chuyen nhan vb don vi
                    DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
                        ->where('don_vi_id', auth::user()->don_vi_id)
                        ->update(['hoan_thanh' => DonViChuTri::HOAN_THANH_VB]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Duyệt thành công, hoàn thành văn bản',
                        200
                    ]);
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
}
