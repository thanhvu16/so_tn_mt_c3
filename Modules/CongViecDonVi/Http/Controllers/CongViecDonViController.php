<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use auth;
use Modules\Admin\Entities\DonVi;

class CongViecDonViController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('congviecdonvi::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $currentUser = auth::user();
        $danhSachDonViChutri = Donvi::
//        where('cap_don_vi', Donvi::CAP_3)->
//        where('trang_thai',
//            DonVi::TRANG_THAI_HOAT_DONG)
//            ->
        where('id', '!=', auth::user()->don_vi_id)
            ->whereNull('deleted_at')
            ->get();

        $donViChuTri = Donvi::where('id',auth::user()->don_vi_id)
//            ->where('trang_thai',
//            Donvi::TRANG_THAI_HOAT_DONG)
            ->whereNull('deleted_at')
            ->get();

        $danhSachPhoPhong = User::Role('phó phòng')->where('don_vi_id', $currentUser->don_vi_id)
//            ->whereNull('quyen_tham_muu')
//            ->where('trang_thai', User::TRANG_THAI_HOAT_DONG)
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien = User::Role('chuyên viên')->where('don_vi_id', $currentUser->don_vi_id)
//            ->whereNull('quyen_tham_muu')
//            ->where('trang_thai', User::TRANG_THAI_HOAT_DONG)
            ->orderBy('id', 'DESC')->get();

        return view('congviecdonvi::cong-viec-don-vi.create',
            compact('danhSachDonViChutri', 'donViChuTri', 'danhSachPhoPhong', 'danhSachChuyenVien'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function getDataDonVi(Request $request)
    {
//        dd($request->all());
        if ($request->ajax()) {

            $currentUser = auth::user();
            //don vi cap 2
            $danhSachDonViChutri = Donvi::
//            where('cap_don_vi', Donvi::CAP_3)
                whereNull('deleted_at')
                ->get();

            return response()->json([
                200,
                'danhSachDonViChutri' => $danhSachDonViChutri
            ]);

        }
    }

    public function getDonViPhoiHop($id, Request $request)
    {

        if ($request->ajax()) {

            $danhSachDonViChutri = Donvi::
                whereNotIn('id', json_decode($id))
                ->whereNull('deleted_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $danhSachDonViChutri
            ]);
        }
    }
    public function store(Request $request)
    {
        $currentUser = auth::user();
        $data = $request->all();
        $chuyenNhanCongViecIds = json_decode($data['van_ban_den_don_vi_id']);
        $danhSachPhoPhongIds = $data['pho_phong_id'] ?? null;
        $danhSachChuyenVienIds = $data['chuyen_vien_id'] ?? null;
        $textnoidungPhoPhong = $data['noi_dung_pho_phong'] ?? null;
        $textNoiDungChuyenVien = $data['noi_dung_chuyen_vien'] ?? null;
        $arrChuyenVienPhoiHopIds = $data['chuyen_vien_phoi_hop_id'] ?? null;
        $arrLanhDaoXemDeBiet = $data['lanh_dao_xem_de_biet'] ?? null;
        $typeDonViPhoiHop = $request->get('don_vi_phoi_hop') ?? null;

        if (isset($chuyenNhanCongViecIds) && count($chuyenNhanCongViecIds) > 0) {
            try {
                DB::beginTransaction();

                foreach ($chuyenNhanCongViecIds as $chuyenNhanCongViecId) {

                    // don vi phoi hop
                    if ($typeDonViPhoiHop && $typeDonViPhoiHop == ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP) {
                        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('id', $chuyenNhanCongViecId)
                            ->where('type', $typeDonViPhoiHop)
                            ->whereNull('hoan_thanh')
                            ->first();
                    } else {

                        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('id', $chuyenNhanCongViecId)
                            ->whereNull('type')
                            ->whereNull('hoan_thanh')
                            ->first();
                    }

                    if ($chuyenNhanCongViecDonVi) {

                        $chuyenNhanCongViecDonVi->chuyen_tiep = ChuyenNhanCongViecDonVi::CHUYEN_TIEP;
                        $chuyenNhanCongViecDonVi->save();

                        if ($typeDonViPhoiHop && $typeDonViPhoiHop == ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP) {
                            ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $chuyenNhanCongViecDonVi->cong_viec_don_vi_id)
                                ->where('id', '>', $chuyenNhanCongViecDonVi->id)
                                ->where('type', $typeDonViPhoiHop)
                                ->whereNull('hoan_thanh')
                                ->delete();
                        } else {
                            ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $chuyenNhanCongViecDonVi->cong_viec_don_vi_id)
                                ->where('id', '>', $chuyenNhanCongViecDonVi->id)
                                ->whereNull('type')
                                ->whereNull('hoan_thanh')
                                ->delete();
                        }
                    }

                    if (!empty($danhSachPhoPhongIds[$chuyenNhanCongViecId])) {
                        $dataChuyenNhanDonVi = [
                            'cong_viec_don_vi_id' => $chuyenNhanCongViecDonVi->cong_viec_don_vi_id ?? null,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoPhongIds[$chuyenNhanCongViecId],
                            'noi_dung' => $chuyenNhanCongViecDonVi->noi_dung ?? null,
                            'noi_dung_chuyen' => $textnoidungPhoPhong[$chuyenNhanCongViecId],
                            'don_vi_id' => $currentUser->donvi_id,
                            'han_xu_ly' => $chuyenNhanCongViecDonVi->han_xu_ly,
                            'parent_id' => $chuyenNhanCongViecDonVi ? $chuyenNhanCongViecDonVi->id : null,
                            'type' => $typeDonViPhoiHop
                        ];

                        //save pho phong
                        $chuyenNhanCongViecDonViPhoPhong = new ChuyenNhanCongViecDonVi();
                        $chuyenNhanCongViecDonViPhoPhong->fill($dataChuyenNhanDonVi);
                        $chuyenNhanCongViecDonViPhoPhong->save();
                    }

                    // save chuyen vien thuc hien
                    if (!empty($danhSachChuyenVienIds[$chuyenNhanCongViecId])) {
                        $dataChuyenNhanDonViChuyenVien = [
                            'cong_viec_don_vi_id' => $chuyenNhanCongViecDonVi->cong_viec_don_vi_id ?? null,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachChuyenVienIds[$chuyenNhanCongViecId],
                            'noi_dung' => $chuyenNhanCongViecDonVi->noi_dung ?? null,
                            'noi_dung_chuyen' => $textNoiDungChuyenVien[$chuyenNhanCongViecId],
                            'don_vi_id' => $currentUser->donvi_id,
                            'han_xu_ly' => $chuyenNhanCongViecDonVi->han_xu_ly,
                            'type' => $typeDonViPhoiHop,
                            'parent_id' => $chuyenNhanCongViecDonVi ? $chuyenNhanCongViecDonVi->id : null
                        ];

                        //save pho phong
                        $chuyenNhanCongViecDonViChuyenVien = new ChuyenNhanCongViecDonVi();
                        $chuyenNhanCongViecDonViChuyenVien->fill($dataChuyenNhanDonViChuyenVien);
                        $chuyenNhanCongViecDonViChuyenVien->save();
                    }
                    // save chuyen vien phoi hop
                    if (!empty($arrChuyenVienPhoiHopIds[$chuyenNhanCongViecId])) {
                        //save chuyen vien phoi hop
                        CongViecDonViPhoiHop::savechuyenVienPhoiHop($arrChuyenVienPhoiHopIds[$chuyenNhanCongViecId],
                            $chuyenNhanCongViecDonVi->cong_viec_don_vi_id, $chuyenNhanCongViecDonVi->id, $currentUser->donvi_id);
                    }

                    if (!empty($arrLanhDaoXemDeBiet[$chuyenNhanCongViecId])) {

                        CongViecDonViPhoiHop::saveCanBoXemDeBiet($arrLanhDaoXemDeBiet[$chuyenNhanCongViecId], $chuyenNhanCongViecDonVi->cong_viec_don_vi_id, $chuyenNhanCongViecDonVi->id);
                    }

                }

                DB::commit();

                return redirect()->back()->with('success', 'Đã gửi thành công.');

            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }

        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('congviecdonvi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('congviecdonvi::edit');
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
