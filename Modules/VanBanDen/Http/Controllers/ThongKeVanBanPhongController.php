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
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $donViPhong = auth::user()->don_vi_id;

        $nguoiDung = User::where('don_vi_id',$donViPhong)
            ->get();
        foreach ($nguoiDung as $dataNguoiDung)
        {
            $dataNguoiDung->vanBanDaGiaiQuyet = $this->VanBanDenHoanThanhCuaDonVi($dataNguoiDung->id,$tu_ngay,$den_ngay);
            $dataNguoiDung->vanBanChuaGiaiQuyet = $this->VanBanDenChuaHoanThanhCuaDonVi($dataNguoiDung,$tu_ngay,$den_ngay);

        }
        $soDonvi = $nguoiDung->count();

        if ($request->get('type') == 'excel') {
            $tongSoVB = $request->sovanbanden;
            $fileName = 'thongkeVb'.date('d_m_Y') .'.xlsx';
            return Excel::download(new thongKeVanBanPhongExport($nguoiDung,$soDonvi,$tongSoVB,$tu_ngay,$den_ngay),
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











    public function VanBanDenChuaHoanThanhCuaDonVi($user,$tu_ngay,$den_ngay)
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


        $donViChuTri = DonViChuTri::where('can_bo_nhan_id',9)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->select('van_ban_den_id')
            ->get();

        $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        $danhSachVanBanDenQuaHanDangXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '<', $date)
            ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->count();




        $danhSachVanBanDenTrongHanDangXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '>=', $date)
            ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {
                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));


                }
            })
            ->count();



        return [
            'hoan_thanh_dung_han' => $danhSachVanBanDenTrongHanDangXuLy,
            'hoan_thanh_qua_han' => $danhSachVanBanDenQuaHanDangXuLy,
            'tong' => $danhSachVanBanDenTrongHanDangXuLy + $danhSachVanBanDenQuaHanDangXuLy
        ];



    }

    public function VanBanDenHoanThanhCuaDonVi($userId,$tu_ngay,$den_ngay)
    {
        $danhSachVanBanDenDaHoanThanhDungHan = VanBanDen::where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN)
            ->get();

        $arrIdVanBanDungHan  = $danhSachVanBanDenDaHoanThanhDungHan->pluck('id')->toArray();


        $vanBanDaGiaiQuyet = $this->getVanBanDenDaGiaiQuyet($arrIdVanBanDungHan,$userId);



        return [
            'tong' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han']+$vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'giai_quyet_trong_han' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'],
            'giai_quyet_qua_han' => $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],



        ];

    }


    public function getVanBanDenDaGiaiQuyet($arrIdVanBanDungHan, $userId)
    {
        $users = User::where('id',$userId)->first();
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
            ->where('don_vi_id', $users->don_vi_id)->distinct()->count();
        $vanBanQuaHan = $danhSachVanBanDenDonViDaHoanThanhQuaHan;

        $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;


        return [
            'hoan_thanh_dung_han' => $vanBanTrongHan,
            'hoan_thanh_qua_han' => $vanBanQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh
        ];
    }



    public function chiTietDaGiaiQuyetTrongHanVanBanphong($id,Request $request)
    {
        $donViId = null;
        $type = null;
        $users = User::where('id',$id)->first();

        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;


        $danhSachVanBanDenDaHoanThanhDungHan = VanBanDen::where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN)
            ->get();

        $arrIdVanBanDungHan  = $danhSachVanBanDenDaHoanThanhDungHan->pluck('id')->toArray();

        $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrIdVanBanDungHan)
            ->whereHas('vanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
            })
            ->where('can_bo_nhan_id', $users)->distinct()->get();






        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));
    }
    public function chiTietDaGiaiQuyetQuaHanVanBanphong($id,Request $request)
    {
        $donViId = null;
        $type = null;
        $users = User::where('id',$id)->first();

        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;


        $danhSachVanBanDenDaHoanThanhDungHan = VanBanDen::where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN)
            ->get();

        $arrIdVanBanDungHan  = $danhSachVanBanDenDaHoanThanhDungHan->pluck('id')->toArray();

        $ds_vanBanDen =  DonViChuTri::whereIn('van_ban_den_id', $arrIdVanBanDungHan)
            ->whereHas('vanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
            })
            ->where('don_vi_id', $users->don_vi_id)->distinct()->get();
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong',compact('ds_vanBanDen'));



        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }
    public function chiTietChuaGiaiQuyetQuaHanVanBanphong($id,Request $request)
    {
        $donViId = null;
        $type = null;
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $user = User::where('id',$id)->first();

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
        $ds_vanBanDen = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '<', $date)
            ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->get();
            return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));


        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }
    public function chiTietChuaGiaiQuyetTrongHanVanBanphong($id,Request $request)
    {


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        $user = User::where('id',$id)->first();

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

        $ds_vanBanDen = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '>=', $date)
            ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->get();



        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
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
