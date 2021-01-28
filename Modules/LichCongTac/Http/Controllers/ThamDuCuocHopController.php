<?php

namespace Modules\LichCongTac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LichCongTac;
use App\Models\NguoiDung;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Auth;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;

class ThamDuCuocHopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = auth::user();
        $year = date('Y');
        $week = date('W');
        $start_date = strtotime($year . "W" . $week . 1);
        $end_date = strtotime($year . "W" . $week . 7);

        $ngaybd = date('Y-m-d', $start_date);
        $ngaykt = date('Y-m-d', $end_date);

        $thamDuCuocHop = ThanhPhanDuHop::where('user_id', $user->id)
            ->whereNull('lanh_dao_id')
            ->select('lich_cong_tac_id')
            ->get();

        $lichConTacId = $thamDuCuocHop->pluck('lich_cong_tac_id');

        $danhSachLichCongTac = LichCongTac::with('lanhDao')
            ->whereIn('id', $lichConTacId)
            ->where('ngay', '>=', $ngaybd)
            ->where('ngay', '<=', $ngaykt)
            ->select('id', 'ngay', 'gio', 'noi_dung', 'dia_diem', 'lanh_dao_id', 'trang_thai_lich', 'ghi_chu')
            ->orderBy('ngay', 'ASC')
            ->paginate(PER_PAGE);

        foreach ($danhSachLichCongTac as $lichCongTac) {
            $lichCongTac->listThanhPhanDuHop = $lichCongTac->listThanhPhanDuHop();
            $lichCongTac->checkDaChuyenLichCaNhan = $lichCongTac->checkDaChuyenLichCaNhan();
            $lichCongTac->lichCaNhanDuHop = $lichCongTac->lichCaNhanDuHop();
        }

        $order = ($danhSachLichCongTac->currentPage() - 1) * PER_PAGE + 1;

        return view('lichcongtac::thanh-phan-du-hop.index',
            compact('danhSachLichCongTac', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('lichcongtac::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // giao du hop cap truong cap 2
        $user = $request->user();
        $data = $request->all();

        $caNhanDuHop = null;
        // check can bo hien tai
        $check = ThanhPhanDuHop::where('lich_cong_tac_id', $data['lich_cong_tac_id'])
            ->where('user_id', $user->id)
            ->first();

        if ($data['user_id'] && count($data['user_id']) > 0 && $check) {

            $xoaThanhPhanDuHopCu = ThanhPhanDuHop::where('lich_cong_tac_id', $data['lich_cong_tac_id'])
                ->where('don_vi_id', $user->don_vi_id)
                ->where('id', '>', $check->id)
                ->delete();

            foreach ($data['user_id'] as $userId) {
                if ($userId == $user->id) {
                    $caNhanDuHop = true;
                }
                if ($userId != $user->id) {
                    $thanhPhanDuHop = new ThanhPhanDuHop();
                    $thanhPhanDuHop->lich_cong_tac_id = $data['lich_cong_tac_id'];
                    $thanhPhanDuHop->user_id = $userId;
                    $thanhPhanDuHop->object_id = $check->object_id;
                    $thanhPhanDuHop->don_vi_id = $check->don_vi_id;
                    $thanhPhanDuHop->nguoi_tao_id = auth::user()->id;
                    $thanhPhanDuHop->save();
                }

            }

            //update ca nhan co di hop hay khong?
            if (empty($caNhanDuHop)) {
                $check->trang_thai = ThanhPhanDuHop::TRANG_THAI_BAN;
                //xoa lich cong tac ca nhan
                LichCongTac::where('thanh_phan_du_hop_id', $check->id)->delete();
                $check->trang_thai_lich = 1;

            } else {
                $check->trang_thai = ThanhPhanDuHop::TRANG_THAI_DI_HOP;
            }
            $check->save();

            return redirect()->back()->with('success', 'Cập nhật thành phần dự họp thành công.');
        }

        return redirect()->back()->with('error', 'Lỗi hệ thống.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $user = Auth::user();
        $role = null;

        if ($user->hasRole(CHU_TICH)) {
            $role = [CHU_TICH, PHO_CHUC_TICH, TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN];
        }

        if ($user->hasRole(PHO_CHUC_TICH)) {
            $role = [PHO_CHUC_TICH, TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN];
        }

        if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $role = [TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN, PHO_PHONG, CHUYEN_VIEN, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN];
        }

        if ($user->hasRole([PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN, PHO_PHONG])) {
            $role = [PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN, PHO_PHONG, CHUYEN_VIEN];
        }



        $canbophong = User::where('don_vi_id', $user->don_vi_id)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')
            ->select('id', 'ho_ten')
            ->get();

        $thanhPhanDuHop =  ThanhPhanDuHop::where(['lich_cong_tac_id' => $id,
                'don_vi_id' => $user->don_vi_id,
            ])
            ->where('trang_thai', ThanhPhanDuHop::TRANG_THAI_DI_HOP)
            ->get();

        $returnHTML = view('lichcongtac::thanh-phan-du-hop.can_bo_tham_du',
            compact('thanhPhanDuHop', 'canbophong', 'id'))->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('lichcongtac::edit');
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
