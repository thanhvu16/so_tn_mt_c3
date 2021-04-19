<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Exports\thongKeVanBanChiCucExport;
use App\Exports\thongKeVanBanPhongExport;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDen\Entities\VanBanDen;
use auth,Excel;

class ThongKeVanBanPhongController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $donViPhong = auth::user()->don_vi_id;

        $nguoiDung = User::where('don_vi_id',$donViPhong)
            ->get();
        foreach ($nguoiDung as $dataNguoiDung)
        {
            $dataNguoiDung->vanBanDaGiaiQuyet = $this->VanBanDenHoanThanhCuaDonVi($dataNguoiDung->id);
            $dataNguoiDung->vanBanChuaGiaiQuyet = $this->VanBanDenChuaHoanThanhCuaDonVi($dataNguoiDung);

        }
        $soDonvi = $nguoiDung->count();

        if ($request->get('type') == 'excel') {
            $tongSoVB = $request->sovanbanden;
            $fileName = 'thongkeVb'.date('d_m_Y') .'.xlsx';
            return Excel::download(new thongKeVanBanPhongExport($nguoiDung,$soDonvi,$tongSoVB),
                $fileName);
        }
        if ($request->ajax()) {
            $tongSoVB =$request->sovanbanden;
            $danhSachDonVi = $nguoiDung;
            $html = view('vanbanden::thong_ke.TK_vb_phong',compact('danhSachDonVi','tongSoVB' ) )->render();;
            return response()->json([
                'html' => $html,
            ]);
        }
        return view('vanbanden::thong_ke.thong_ke_vb_phong',compact('nguoiDung'));
    }











    public function VanBanDenChuaHoanThanhCuaDonVi($user)
    {
        $date = date('Y-m-d');
        $trinhTuNhanVanBan = 0;

        if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($user->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($user->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }



        $donViChuTri = DonViChuTri::where('can_bo_nhan_id', $user->id)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->select('van_ban_den_id')
            ->get();

        $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        $danhSachVanBanDenQuaHanDangXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '<', $date)
            ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->count();


        $danhSachVanBanDenTrongHanDangXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '>=', $date)
            ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->count();


        return [
            'hoan_thanh_dung_han' => $danhSachVanBanDenTrongHanDangXuLy,
            'hoan_thanh_qua_han' => $danhSachVanBanDenQuaHanDangXuLy,
            'tong' => $danhSachVanBanDenTrongHanDangXuLy + $danhSachVanBanDenQuaHanDangXuLy
        ];



    }

    public function VanBanDenHoanThanhCuaDonVi($userId)
    {
        $danhSachVanBanDenDaHoanThanhDungHan = VanBanDen::where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN)
            ->get();

        $arrIdVanBanDungHan  = $danhSachVanBanDenDaHoanThanhDungHan->pluck('id')->toArray();

        $donViChuTri = DonViChuTri::where('can_bo_nhan_id', $userId)->whereIn('van_ban_den_id', $arrIdVanBanDungHan)->get();

        $vanBanDaGiaiQuyet = $this->getVanBanDenDaGiaiQuyet($arrIdVanBanDungHan,$userId);



        return [
            'tong' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han']+$vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'giai_quyet_trong_han' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'],
            'giai_quyet_qua_han' => $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],



        ];

    }


    public function getVanBanDenDaGiaiQuyet($arrIdVanBanDungHan, $userId)
    {
        $danhSachVanBanDenDonViDaHoanThanhTrongHan = DonViChuTri::whereIn('van_ban_den_id', $arrIdVanBanDungHan)
            ->whereHas('vanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
            })
            ->where('can_bo_nhan_id', $userId)->distinct()->count();
        $vanBanTrongHan = $danhSachVanBanDenDonViDaHoanThanhTrongHan;

        $danhSachVanBanDenDonViDaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrIdVanBanDungHan)
            ->whereHas('vanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
            })
            ->where('don_vi_id', $userId)->distinct()->count();
        $vanBanQuaHan = $danhSachVanBanDenDonViDaHoanThanhQuaHan;

        $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;


        return [
            'hoan_thanh_dung_han' => $vanBanTrongHan,
            'hoan_thanh_qua_han' => $vanBanQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh
        ];
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('vanbanden::create');
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
        return view('vanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('vanbanden::edit');
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
