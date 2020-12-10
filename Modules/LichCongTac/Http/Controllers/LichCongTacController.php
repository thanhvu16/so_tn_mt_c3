<?php

namespace Modules\LichCongTac\Http\Controllers;

use App\Models\LichCongTac;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Auth;

class LichCongTacController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $tuan = $request->get('tuan');
        $year = date('Y');
        $week = $tuan ? $tuan : date('W');

        $lanhDaoId = $request->get('lanh_dao_id') ?? $currentUser->id;
        $donViId = null;

        $ngayTuan = [
            array('Thứ Hai', date('d/m/Y', strtotime($year . "W" . $week . 1))),
            array('Thứ Ba', date('d/m/Y', strtotime($year . "W" . $week . 2))),
            array('Thứ Tư', date('d/m/Y', strtotime($year . "W" . $week . 3))),
            array('Thứ Năm', date('d/m/Y', strtotime($year . "W" . $week . 4))),
            array('Thứ Sáu', date('d/m/Y', strtotime($year . "W" . $week . 5))),
            array('Thứ Bảy', date('d/m/Y', strtotime($year . "W" . $week . 6))),
            array('Chủ Nhật', date('d/m/Y', strtotime($year . "W" . $week . 7)))
        ];
        $start_date = strtotime($year . "W" . $week . 1);
        $end_date = strtotime($year . "W" . $week . 7);

        $ngaybd = date('Y-m-d', $start_date);
        $ngaykt = date('Y-m-d', $end_date);

        $totalWeekOfYear = max(date("W", strtotime($year . "-12-27")), date("W", strtotime($year . "-12-29")),
            date("W", strtotime($year . "-12-31")));

        $tuanTruoc = $week != 1 ? $week - 1 : 1;
        $tuanSau = $week != $totalWeekOfYear ? $week + 1 : $totalWeekOfYear;

        $roles = [CHU_TICH, PHO_CHUC_TICH];

        $danhSachLanhDao = User::whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->orderBy('id', 'ASC')
            ->get();


        $danhSachLichCongTac = LichCongTac::with('vanBanDen', 'vanBanDi')
            ->where('ngay', '>=', $ngaybd)
            ->where('ngay', '<=', $ngaykt)
            ->where(function ($query) use ($lanhDaoId) {
                if (!empty($lanhDaoId)) {
                    return $query->where('lanh_dao_id', $lanhDaoId);
                }
            })
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->orderBy('buoi', 'ASC')->get();

        if ($danhSachLichCongTac) {
            foreach ($danhSachLichCongTac as $lichCongTac) {

                $lichCongTac->CanBoChiDao = null;
                if ($lichCongTac->chuanBiTruocCuocHop()) {
                    $lichCongTac->CanBoChiDao = XuLyVanBanDen::where('van_ban_den_id', $lichCongTac->object_id)
                        ->where('id', '>=', $lichCongTac->chuanBiTruocCuocHop())->get();
                }

                $lichCongTac->truyenNhanVanBanDonVi = $lichCongTac->donViChuTri();
            }
        }



        return view('lichcongtac::index', compact('danhSachLichCongTac',
            'tuanTruoc', 'tuanSau', 'totalWeekOfYear', 'week', 'ngayTuan', 'danhSachLanhDao'));
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
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('lichcongtac::show');
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
