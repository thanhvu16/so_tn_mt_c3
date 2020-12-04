<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Auth;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;

class GiaHanVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = auth::user();
        $date = $request->get('date') ?? null;

        $giaHanVanBanDonVi = GiaHanVanBan::with('vanBanDen',
            'CanBoChuyen', 'CanBoNhan')
            ->where('can_bo_nhan_id', $currentUser->id)
            ->where('status', GiaHanVanBan::STATUS_CHO_DUYET)
            ->where(function ($query) use ($date) {
                if (!empty($date)) {
                    return $query->where('created_at', 'LIKE', "%$date");
                }
            })
            ->paginate(PER_PAGE);

        $order = ($giaHanVanBanDonVi->currentPage() - 1) * PER_PAGE + 1;

        return view('dieuhanhvanbanden::gia-han.index', compact('order', 'giaHanVanBanDonVi'));
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
        $currentUser = auth::user();
        $data = $request->all();
        $data['can_bo_chuyen_id'] = $currentUser->id;
        $data['thoi_han_cu'] = !empty($request->get('thoi_han_cu')) ? date('Y-m-d', strtotime($request->get('thoi_han_cu'))) : null;

        $chuyenNhanVanBanDonVi = DonViChuTri::where('van_ban_den_id', $data['van_ban_den_id'])
            ->where('can_bo_nhan_id', $currentUser->id)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->first();

        $data['can_bo_nhan_id'] = $chuyenNhanVanBanDonVi ? $chuyenNhanVanBanDonVi->can_bo_chuyen_id : null;

        $deXuatGiaHan = new GiaHanVanBan();
        $deXuatGiaHan->fill($data);
        $deXuatGiaHan->save();

        return redirect()->back()->with('success', 'Đã gửi đề xuất gia hạn.');
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

    public function duyetGiaHan(Request $request)
    {
        if ($request->ajax()) {
            $id = (int)$request->get('id');
            $status = (int)$request->get('status');
            $noiDung = $request->get('noiDung');
            $thoiHan = !empty($request->get('thoiHan')) ? $request->get('thoiHan') : null;
            $currentUser = auth::user();

            $giaHanVanBanDonVi = GiaHanVanBan::where('id', $id)
                ->where('status', GiaHanVanBan::STATUS_CHO_DUYET)->first();

            if ($giaHanVanBanDonVi) {

                //update trang thai gia han cv
                $giaHanVanBanDonVi->status = $status;
                $giaHanVanBanDonVi->save();

                if ($status == GiaHanVanBan::STATUS_DA_DUYET) {
                    $message = 'Đã duyệt thành công.';
                } else {
                    $message = "Đã gửi trả lại";
                }

                if ($status == GiaHanVanBan::STATUS_TRA_LAI) {

                    $canBoNhan = GiaHanVanBan::where([
                        'van_ban_den_id' => $giaHanVanBanDonVi->van_ban_den_id
                    ])
                        ->orderBy('id', 'ASC')
                        ->first();

                    $data['van_ban_den_id'] = $giaHanVanBanDonVi->van_ban_den_id;
                    $data['can_bo_chuyen_id'] = $currentUser->id;
                    $data['noi_dung'] = $noiDung;
                    $data['can_bo_nhan_id'] = $canBoNhan->can_bo_chuyen_id;
                    $data['parent_id'] = !empty($giaHanVanBanDonVi->parent_id) ? $giaHanVanBanDonVi->parent_id : $giaHanVanBanDonVi->id;
                    $data['thoi_han_cu'] = $giaHanVanBanDonVi->thoi_han_cu;
                    $data['thoi_han_de_xuat'] = $thoiHan;
                    $data['status'] = $status;

                    $giaHanVanBanTraLai = new  GiaHanVanBan();
                    $giaHanVanBanTraLai->fill($data);
                    $giaHanVanBanTraLai->save();

                    $giaHanVanBanDonVi->lanh_dao_duyet = $status;
                    $giaHanVanBanDonVi->save();

                    GiaHanVanBan::where('id', $giaHanVanBanDonVi->parent_id)
                        ->update(['lanh_dao_duyet' => $status]);

                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        200
                    ]);
                }

                // check lanh dao chi dao cv
                $xuLyVanBanDen = XuLyVanBanDen::where('van_ban_den_id',
                    $giaHanVanBanDonVi->van_ban_den_id)
                    ->where('can_bo_nhan_id', $currentUser->id)
                    ->where('lanh_dao_chi_dao', XuLyVanBanDen::LA_CAN_BO_CHI_DAO)
                    ->whereNull('status')->first();

                if (!empty($xuLyVanBanDen) && $currentUser->id == $xuLyVanBanDen->can_bo_nhan_id) {

                    $xuLyVanBanDen->han_xu_ly = $thoiHan;
                    $xuLyVanBanDen->save();

                    $vanBanDenDonVi = $giaHanVanBanDonVi->vanBanDen;
                    if ($vanBanDenDonVi) {
                        $vanBanDenDonVi->han_xu_ly = $thoiHan;
                        $vanBanDenDonVi->save();
                    }

                    // update gia han ban ghi null parent
                    GiaHanVanBan::where('id', $giaHanVanBanDonVi->parent_id)
                        ->update(['lanh_dao_duyet' => $status]);

                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        200
                    ]);
                }

                $chuyenNhanVanBanDonVi = DonViChuTri::where([
                    'van_ban_den_id' => $giaHanVanBanDonVi->van_ban_den_id,
                    'can_bo_nhan_id' => $currentUser->id
                ])->first();


                if ($chuyenNhanVanBanDonVi && $chuyenNhanVanBanDonVi->parent_id) {
                    $data['van_ban_den_id'] = $giaHanVanBanDonVi->van_ban_den_id;
                    $data['can_bo_chuyen_id'] = $currentUser->id;
                    $data['noi_dung'] = $noiDung;
                    $data['can_bo_nhan_id'] = $chuyenNhanVanBanDonVi->can_bo_chuyen_id;
                    $data['parent_id'] = !empty($giaHanVanBanDonVi->parent_id) ? $giaHanVanBanDonVi->parent_id : $giaHanVanBanDonVi->id;
                    $data['thoi_han_cu'] = $giaHanVanBanDonVi->thoi_han_cu;
                    $data['thoi_han_de_xuat'] = $thoiHan;

                    $giaHanVanBanLenCapTren = new  GiaHanVanBan();
                    $giaHanVanBanLenCapTren->fill($data);
                    $giaHanVanBanLenCapTren->save();
                } else {
                    if ($chuyenNhanVanBanDonVi) {
                        $canBoNhan = $chuyenNhanVanBanDonVi->can_bo_chuyen_id;
                    } else {
                        $xuLyVanBanDenCanBo = XuLyVanBanDen::where('van_ban_den_id',
                            $giaHanVanBanDonVi->van_ban_den_id)
                            ->where('can_bo_nhan_id', $currentUser->id)
                            ->whereNull('lanh_dao_chi_dao')
                            ->whereNull('status')
                            ->first();
                        $canBoNhan = $xuLyVanBanDenCanBo->can_bo_chuyen_id;
                    }
                    $data['van_ban_den_id'] = $giaHanVanBanDonVi->van_ban_den_id;
                    $data['can_bo_chuyen_id'] = $currentUser->id;
                    $data['noi_dung'] = $noiDung;
                    $data['can_bo_nhan_id'] = $canBoNhan;
                    $data['parent_id'] = !empty($giaHanVanBanDonVi->parent_id) ? $giaHanVanBanDonVi->parent_id : $giaHanVanBanDonVi->id;
                    $data['thoi_han_cu'] = $giaHanVanBanDonVi->thoi_han_cu;
                    $data['thoi_han_de_xuat'] = $thoiHan;

                    $giaHanVanBanLenCapTren = new  GiaHanVanBan();
                    $giaHanVanBanLenCapTren->fill($data);
                    $giaHanVanBanLenCapTren->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    200
                ]);
            }
        }
    }
}
