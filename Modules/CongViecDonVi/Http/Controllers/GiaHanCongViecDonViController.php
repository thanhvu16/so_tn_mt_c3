<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonViGiaHan;
use auth;

class GiaHanCongViecDonViController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = auth::user();
        $date = $request->get('date') ?? null;

        $giaHanCongViecDonVi = CongViecDonViGiaHan::with('congViecDonVi', 'chuyenNhanCongViecDonVi',
            'CanBoChuyen', 'CanBoNhan')
            ->where('can_bo_nhan_id', $currentUser->id)
            ->where('status', CongViecDonViGiaHan::STATUS_CHO_DUYET)
            ->where(function ($query) use ($date) {
                if (!empty($date)) {
                    return $query->where('created_at', 'LIKE', "%$date");
                }
            })
            ->paginate(PER_PAGE);

        $order = ($giaHanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        return view('congviecdonvi::cong-viec-don-vi.gia-han.index', compact('giaHanCongViecDonVi', 'order'));
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
        $currentUser = auth::user();
        $data = $request->all();
        $data['can_bo_chuyen_id'] = $currentUser->id;
        $data['don_vi_id'] = $currentUser->don_vi_id;

        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $data['cong_viec_don_vi_id'])
            ->where('can_bo_nhan_id', $currentUser->id)
            ->whereNull('type')
            ->first();

        $data['can_bo_nhan_id'] = $chuyenNhanCongViecDonVi ? $chuyenNhanCongViecDonVi->can_bo_chuyen_id : null;
        $data['chuyen_nhan_cong_viec_don_vi_id'] = $chuyenNhanCongViecDonVi->id;

        $giaHanCongViec = new CongViecDonViGiaHan();
        $giaHanCongViec->fill($data);
        $giaHanCongViec->save();

        return redirect()->back()->with('success', 'Đã gửi đề xuất gia hạn.');
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
    public function duyetGiaHan(Request $request)
    {
        if ($request->ajax()) {
            $id = (int)$request->get('id');
            $status = (int)$request->get('status');
            $noiDung = $request->get('noiDung');
            $thoiHan = !empty($request->get('thoiHan')) ? $request->get('thoiHan') : null;
            $currentUser = auth::user();

            $giaHanCongViecDonVi = CongViecDonViGiaHan::where('id', $id)->where('status',
                CongViecDonViGiaHan::STATUS_CHO_DUYET)->first();

            if ($giaHanCongViecDonVi) {

                //update trang thai gia han cv
                $giaHanCongViecDonVi->status = $status;
                $giaHanCongViecDonVi->save();

                if ($status == CongViecDonViGiaHan::STATUS_DA_DUYET) {
                    $message = 'Đã duyệt thành công.';
                } else {
                    $message = "Đã gửi trả lại";
                }

                if ($status == CongViecDonViGiaHan::STATUS_TRA_LAI) {

                    $canBoNhan = CongViecDonViGiaHan::where([
                        'cong_viec_don_vi_id' => $giaHanCongViecDonVi->cong_viec_don_vi_id
                    ])
                        ->orderBy('id', 'ASC')
                        ->first();

                    $data['chuyen_nhan_cong_viec_don_vi_id'] = $giaHanCongViecDonVi->chuyen_nhan_cong_viec_don_vi_id;
                    $data['cong_viec_don_vi_id'] = $giaHanCongViecDonVi->cong_viec_don_vi_id;
                    $data['can_bo_chuyen_id'] = $currentUser->id;
                    $data['noi_dung'] = $noiDung;
                    $data['can_bo_nhan_id'] = $canBoNhan->can_bo_chuyen_id;
                    $data['han_cu'] = $giaHanCongViecDonVi->han_cu;
                    $data['don_vi_id'] = $currentUser->don_vi_id;
                    $data['thoi_han_de_xuat'] = $thoiHan;
                    $data['status'] = $status;

                    $giaHanVanBanTraLai = new  CongViecDonViGiaHan();
                    $giaHanVanBanTraLai->fill($data);
                    $giaHanVanBanTraLai->save();

                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        200
                    ]);
                }

                if ($currentUser->hasRole(TRUONG_PHONG) ||$currentUser->hasRole(CHANH_VAN_PHONG)||$currentUser->hasRole(CHU_TICH)) {
                    $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $giaHanCongViecDonVi->cong_viec_don_vi_id)
                        ->update(['han_xu_ly' => $thoiHan]);

                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        200
                    ]);
                } else {
                    $canBoNhanTruongPhong = User::role([TRUONG_PHONG, CHANH_VAN_PHONG,CHU_TICH])->where('don_vi_id', $currentUser->don_vi_id)
                        ->whereNull('deleted_at')->first();

                    $data['chuyen_nhan_cong_viec_don_vi_id'] = $giaHanCongViecDonVi->chuyen_nhan_cong_viec_don_vi_id;
                    $data['cong_viec_don_vi_id'] = $giaHanCongViecDonVi->cong_viec_don_vi_id;
                    $data['can_bo_chuyen_id'] = $currentUser->id;
                    $data['noi_dung'] = $noiDung;
                    $data['can_bo_nhan_id'] = $canBoNhanTruongPhong->id;
                    $data['don_vi_id'] = $currentUser->don_vi_id;
                    $data['han_cu'] = $giaHanCongViecDonVi->han_cu;
                    $data['thoi_han_de_xuat'] = $thoiHan;

                    $giaHanVanBanLenCapTren = new  CongViecDonViGiaHan();
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
