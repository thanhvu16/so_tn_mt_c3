<?php

namespace Modules\BaoCaoThongKe\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\VanBanDi;
use Modules\VanBanDen\Entities\VanBanDen;
use Auth;

class BaoCaoThongKeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user = auth::user();
        $year = $request->get('year') ?? date('Y');
        $month = null;
        $donViId = null;
        $giayMoi = SoVanBan::where('ten_so_van_ban', 'like', 'giấy mời')->select('id')->first();

        if ($user->hasRole([CHU_TICH, PHO_CHU_TICH, VAN_THU_HUYEN])) {

            $type = VanBanDen::TYPE_VB_HUYEN;

            $totalVanBanDi = VanBanDi::where([
                'loai_van_ban_giay_moi' => 1,
                'don_vi_soan_thao' => null
                ])
                ->where('so_di', '!=', null)
                ->whereNull('deleted_at')
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->count();

            $totalGiayMoiDi = VanBanDi::where([
                'loai_van_ban_giay_moi' => 2,
                'loai_van_ban_id' => $giayMoi->id ?? null ,
                'don_vi_soan_thao' => null
                ])
                ->where('so_di', '!=', '')
                ->whereNull('deleted_at')
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->count();

        } else {
            $type = VanBanDen::TYPE_VB_DON_VI;
            $donViId = $user->don_vi_id;

            $totalVanBanDi = VanBanDi::where([
                'loai_van_ban_giay_moi' => 1,
                'van_ban_huyen_ky' => $user->don_vi_id])
                ->where('so_di', '!=', null)
                ->whereNull('deleted_at')
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->count();

            $totalGiayMoiDi = VanBanDi::where([
                'loai_van_ban_giay_moi' => 2,
                'loai_van_ban_id' => $giayMoi->id ?? null ,
                'van_ban_huyen_ky' => auth::user()->don_vi_id
            ])
                ->where('so_di', '!=', '')
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->whereNull('deleted_at')
                ->count();
        }

        $totalVanBanDen = VanBanDen::getListVanBanDen($giayMoi, $type, '!=', $month=null, $year, $donViId)->count();

        $totalGiayMoiDen = VanBanDen::getListVanBanDen($giayMoi, $type, '=', $month=null, $year, $donViId)->count();;

        // bao cao thong ke

        $dataLabel = [];
        $dataVanBanDen = [];
        $dataVanBanDi = [];

        for ($month = 1; $month<= 12; $month++) {
//
            array_push($dataLabel, 'Tháng '. $month);

            $danhSachVanBanDen = VanBanDen::getListVanBanDen(null, $type, null, $month, $year, $donViId)
                ->count();

            $danhSachVanBanDi = VanBanDi::where('so_di', '!=', null)
                ->where(function($query) use ($month) {
                    if (!empty($month)) {
                        return $query->whereMonth('created_at', $month);
                    }
                })
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->where(function($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('van_ban_huyen_ky', $donViId);
                    }
                })
                ->whereNull('deleted_at')
                ->count();

            array_push($dataVanBanDen, $danhSachVanBanDen);
            array_push($dataVanBanDi, $danhSachVanBanDi);
        }

        return view('baocaothongke::index',
            compact('totalVanBanDen', 'totalGiayMoiDen', 'totalVanBanDi',
                'totalGiayMoiDi', 'dataLabel', 'dataVanBanDen', 'dataVanBanDi', 'year'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('baocaothongke::create');
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
        return view('baocaothongke::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('baocaothongke::edit');
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
