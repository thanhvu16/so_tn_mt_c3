<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\GiaiQuyetCongViecDonVi;
use auth;

class CongViecHoanThanhController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $currentUser = auth::user();

        if ($currentUser->hasRole(TRUONG_PHONG)||  $currentUser->hasRole(PHO_PHONG) ||$currentUser->hasRole(CHANH_VAN_PHONG)||$currentUser->hasRole(CHU_TICH)  ) {

            $danhSachChuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')
                ->where('can_bo_nhan_id', $currentUser->id)
                ->whereNull('type')
                ->where('hoan_thanh', ChuyenNhanCongViecDonVi::HOAN_THANH_CONG_VIEC)
                ->paginate(PER_PAGE);

            $order = ($danhSachChuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

            return view('congviecdonvi::cong-viec-don-vi.hoan-thanh.index', compact('danhSachChuyenNhanCongViecDonVi', 'order'));
        } else {
//
//            $year = date('Y');
//            $week = date('W');
//            $start_date = strtotime($year . "W" . $week . 1);
//            $end_date = strtotime($year . "W" . $week . 7);
//
//            $ngaybd = date('Y-m-d', $start_date);
//            $ngaykt = date('Y-m-d', $end_date);
//
//            $danhSachLichCongTac = DieuHanhVanBanDenLichCongTac::with('vanBanDenDonVi', 'vanBanDi', 'congViecDonVi')
//                ->where('ngay', '>=', $ngaybd)
//                ->where('ngay', '<=', $ngaykt)
//                ->where('lanh_dao_id', $currentUser->id)
//                ->orderBy('buoi', 'ASC')->get();
//
//            $arrLichCongTacId = $danhSachLichCongTac->pluck('id')->toArray();
//
//            $danhSachCongViecDonVi = CongViecDonVi::has('ChuyenNhanCongViecDonViDaXuLy')
//                ->whereIn('lich_cong_tac_id', $arrLichCongTacId)
//                ->orderBy('id', 'ASC')
//                ->paginate($this->config['per_page']);
//
//            $order = ($danhSachCongViecDonVi->currentPage() - 1) * $this->config['per_page'] + 1;
//            $type = 'cv_hoan_thanh';
//
//            return view('congviecdonvi::cong-viec-don-vi.lanh-dao.da_xu_ly',
//                compact('danhSachCongViecDonVi', 'order', 'type'));
        }
    }

    public function hoanThanhChoDuyet()
    {
        $currentUser = auth::user();
        $giaiQuyetCongViecDonVi = GiaiQuyetCongViecDonVi::with('congViecDonVi', 'chuyenNhanCongViecDonVi')->where('lanh_dao_duyet_id', $currentUser->id)
            ->whereNull('status')->paginate();

        $order = ($giaiQuyetCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        return view('congviecdonvi::cong-viec-don-vi.hoan-thanh.cho_duyet', compact('giaiQuyetCongViecDonVi', 'order'));

    }

    public function duyetCongViec(Request $request)
    {
        if ($request->ajax()) {

            $id = $request->get('id');
            $noiDung = $request->get('noiDung');
            $status = $request->get('status');

            $giaiQuyetCongViec = GiaiQuyetCongViecDonVi::where('id', $id)->first();

            if ($giaiQuyetCongViec) {
                $chuyenNhanCongViecDonVi = $giaiQuyetCongViec->chuyenNhanCongViecDonVi;

                //update giai quyet cv
                $giaiQuyetCongViec->status = $status;
                $giaiQuyetCongViec->noi_dung_nhan_xet = $noiDung;
                $giaiQuyetCongViec->save();

                if ($status == GiaiQuyetCongViecDonVi::STATUS_DA_DUYET) {

                    ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $giaiQuyetCongViec->cong_viec_don_vi_id)
                        ->whereNull('type')
                        ->whereNull('hoan_thanh')
                        ->where('id', '>', $giaiQuyetCongViec->chuyen_nhan_cong_viec_don_vi_id)
                        ->delete();

                    //update chuyen nhan cv don vi
                    ChuyenNhanCongViecDonVi::where('don_vi_id', $giaiQuyetCongViec->don_vi_id)
                        ->whereNull('type')
                        ->where('cong_viec_don_vi_id', $giaiQuyetCongViec->cong_viec_don_vi_id)
                        ->update(['hoan_thanh' => ChuyenNhanCongViecDonVi::HOAN_THANH_CONG_VIEC]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Duyệt thành công, hoàn thành công việc',
                        200
                    ]);

                } else {

                    $chuyenNhanCongViecDonVi->chuyen_tiep = null;
                    $chuyenNhanCongViecDonVi->save();

                    return response()->json([
                        'success' => true,
                        'message' => "Đã gửi trả lại công việc.",
                        200
                    ]);

                }

            }

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dữ liệu'
            ]);

        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('congviecdonvi::create');
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
