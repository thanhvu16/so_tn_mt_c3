<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Exports\thongKeVanBanPhongExport;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;
use auth,Exce;

class ThongkeCapDuoiPhoChuTichController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $nguoiDung = null;
        $dataNguoiDung = [];
        $user = auth::user();
        $donVi = $user->donVi;

        switch (auth::user()->roles->pluck('name')[0]) {
            case PHO_PHONG:
                $chiCuc1 = User::where('id', auth::user()->id)->get();
                $phong = User::role([  CHUYEN_VIEN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($chiCuc1 as $data4) {
                    array_push($dataNguoiDung, $data4);
                }
                foreach ($phong as $data3) {
                    array_push($dataNguoiDung, $data3);
                }
                $nguoiDung = $dataNguoiDung;
                break;
            case TRUONG_PHONG:
                $chiCuc1 = User::where('id', auth::user()->id)->get();
                $phong = User::role([ PHO_PHONG , CHUYEN_VIEN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($chiCuc1 as $data4) {
                    array_push($dataNguoiDung, $data4);
                }
                foreach ($phong as $data3) {
                    array_push($dataNguoiDung, $data3);
                }
                $nguoiDung = $dataNguoiDung;
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0 && auth::user()->cap_xa == null) {
                    //phó giám đốc sở lấy tất cả TP,PP,CV,giám đôc TT,phó GD TT
                    $chiCuc1 = User::where('id', auth::user()->id)->get();
                    $chiCuc = User::role([CHU_TICH , PHO_CHU_TICH , CHUYEN_VIEN ,TRUONG_BAN,PHO_TRUONG_BAN])->where('cap_xa', 1)->get();
                    $phong = User::role([TRUONG_PHONG , PHO_PHONG , CHUYEN_VIEN])->where('cap_xa', null)->get();
                    foreach ($chiCuc as $data2) {
                        array_push($dataNguoiDung, $data2);
                    }
                    foreach ($chiCuc1 as $data4) {
                        array_push($dataNguoiDung, $data4);
                    }
                    foreach ($phong as $data3) {
                        array_push($dataNguoiDung, $data3);
                    }
                    $nguoiDung = $dataNguoiDung;


                } else {
                    $chiCuc1 = User::where('id', auth::user()->id)->get();
                    $chiCuc = User::role([ CHUYEN_VIEN ,TRUONG_BAN,PHO_TRUONG_BAN])->where('cap_xa', 1)->get();
                    foreach ($chiCuc as $data2) {
                        array_push($dataNguoiDung, $data2);
                    }
                    foreach ($chiCuc1 as $data4) {
                        array_push($dataNguoiDung, $data4);
                    }

                    $nguoiDung = $dataNguoiDung;
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0 && auth::user()->cap_xa == 1) {
                    $chiCuc1 = User::where('id', auth::user()->id)->get();
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN])->where('cap_xa', 1)->get();
                    $phong = User::role([TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN])->where('cap_xa', 1)->get();
                    foreach ($chiCuc as $data2) {
                        array_push($dataNguoiDung, $data2);
                    }
                    foreach ($chiCuc1 as $data4) {
                        array_push($dataNguoiDung, $data4);
                    }
                    foreach ($phong as $data3) {
                        array_push($dataNguoiDung, $data3);
                    }
                    $nguoiDung = $dataNguoiDung;
                }


                break;
            case CHANH_VAN_PHONG:
                    $chiCuc1 = User::where('id', auth::user()->id)->get();
                    $phong = User::role([ PHO_CHANH_VAN_PHONG , CHUYEN_VIEN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    foreach ($chiCuc1 as $data4) {
                        array_push($dataNguoiDung, $data4);
                    }
                    foreach ($phong as $data3) {
                        array_push($dataNguoiDung, $data3);
                    }
                    $nguoiDung = $dataNguoiDung;
                break;
            case PHO_CHANH_VAN_PHONG:
                $chiCuc1 = User::where('id', auth::user()->id)->get();
                $phong = User::role([  CHUYEN_VIEN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($chiCuc1 as $data4) {
                    array_push($dataNguoiDung, $data4);
                }
                foreach ($phong as $data3) {
                    array_push($dataNguoiDung, $data3);
                }
                $nguoiDung = $dataNguoiDung;
                break;
            case TRUONG_BAN:
                $chiCuc1 = User::where('id', auth::user()->id)->get();
                $phong = User::role([ PHO_TRUONG_BAN , CHUYEN_VIEN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($chiCuc1 as $data4) {
                    array_push($dataNguoiDung, $data4);
                }
                foreach ($phong as $data3) {
                    array_push($dataNguoiDung, $data3);
                }
                $nguoiDung = $dataNguoiDung;
                break;
            case PHO_TRUONG_BAN:
                $chiCuc1 = User::where('id', auth::user()->id)->get();
                $phong = User::role([  CHUYEN_VIEN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($chiCuc1 as $data4) {
                    array_push($dataNguoiDung, $data4);
                }
                foreach ($phong as $data3) {
                    array_push($dataNguoiDung, $data3);
                }
                $nguoiDung = $dataNguoiDung;
                break;

        }
        $soDonvi = count($nguoiDung);



        foreach ($nguoiDung as $dataNguoiDung) {
            $dataNguoiDung->vanBanDaGiaiQuyet = $this->VanBanDenHoanThanhCuaDonVi($dataNguoiDung->id, $tu_ngay, $den_ngay);
            $dataNguoiDung->vanBanChuaGiaiQuyet = $this->VanBanDenChuaHoanThanhCuaDonVi($dataNguoiDung->id, $tu_ngay, $den_ngay);

        }


        if ($request->get('type') == 'excel') {
            $tongSoVB = $request->sovanbanden;
            $fileName = 'thongkeVb' . date('d_m_Y') . '.xlsx';
            return Excel::download(new thongKeVanBanPhongExport($nguoiDung, $soDonvi, $tongSoVB, $tu_ngay, $den_ngay),
                $fileName);
        }
        if ($request->ajax()) {
            $tongSoVB = $request->sovanbanden;
            $danhSachDonVi = $nguoiDung;
            $html = view('vanbanden::thong_ke.TK_vb_phong', compact('danhSachDonVi', 'tongSoVB'))->render();;
            return response()->json([
                'html' => $html,
            ]);
        }

        return view('vanbanden::thong_ke.thong_ke_vb_cap_duoi_pho_ct',
            compact('nguoiDung'));
    }

    public function VanBanDenChuaHoanThanhCuaDonVi($user, $tu_ngay, $den_ngay)
    {
        $date = date('Y-m-d');
        $users = User::where('id',$user)->first();
        $donVi = $users->donVi;
        $trinhTuNhanVanBan = 0;
        if ($donVi->parent_id == 0 && $users->cap_xa == null) {
            if ($users->hasRole([CHU_TICH])) {
                $trinhTuNhanVanBan = VanBanDen::CHU_TICH_NHAN_VB;
            }
            if ($users->hasRole([PHO_CHU_TICH]) && $users->cap_xa != null) {
                $trinhTuNhanVanBan = VanBanDen::PHO_CHU_TICH_NHAN_VB;
            }
        } else {
            if ($users->hasRole([CHU_TICH]) && $users->cap_xa == null) {
                $trinhTuNhanVanBan = VanBanDen::CHU_TICH_XA_NHAN_VB;
            }
            if ($users->hasRole([PHO_CHU_TICH]) && $users->cap_xa != null) {
                $trinhTuNhanVanBan = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
            }

        }

        if ($users->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($users->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($users->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }

        $donViChuTri = DonViChuTri::where('can_bo_nhan_id', $users->id)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->select('id', 'van_ban_den_id', 'can_bo_nhan_id')
            ->orderBy('id', 'DESC')
            ->get()->unique('van_ban_den_id');

        $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        $danhSachVanBanDenQuaHanDangXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '<', $date)
            ->where(function ($query) use ($trinhTuNhanVanBan) {
                return $query->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan);
            })
            ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
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
            })->select('id')
            ->get();

        $danhSachVanBanDenTrongHanDangXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('han_xu_ly', '>=', $date)
            ->where(function ($query) use ($trinhTuNhanVanBan) {
                return $query->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan);
            })
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
            ->select('id')
            ->get();


        return [
            'hoan_thanh_dung_han' => $danhSachVanBanDenTrongHanDangXuLy->count(),
            'hoan_thanh_qua_han' => $danhSachVanBanDenQuaHanDangXuLy->count(),
            'tong' => $danhSachVanBanDenTrongHanDangXuLy->count() + $danhSachVanBanDenQuaHanDangXuLy->count(),
            'id_van_ban_trong_han' => \GuzzleHttp\json_encode($danhSachVanBanDenTrongHanDangXuLy->pluck('id')->toArray()),
            'id_van_ban_qua_han' => \GuzzleHttp\json_encode($danhSachVanBanDenQuaHanDangXuLy->pluck('id')->toArray())
        ];

    }

    public function VanBanDenHoanThanhCuaDonVi($userId, $tu_ngay, $den_ngay)
    {
        $danhSachVanBanDenDaHoanThanh = VanBanDen::where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
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



        $vanBanDaGiaiQuyet = $this->getVanBanDenDaGiaiQuyet($danhSachVanBanDenDaHoanThanh, $userId);

        return [
            'tong' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'] + $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'giai_quyet_trong_han' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'],
            'giai_quyet_qua_han' => $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'id_van_ban_trong_han' => \GuzzleHttp\json_encode($vanBanDaGiaiQuyet['id_van_ban_trong_han']),
            'id_van_ban_qua_han' => \GuzzleHttp\json_encode($vanBanDaGiaiQuyet['id_van_ban_qua_han'])
        ];

    }


    public function getVanBanDenDaGiaiQuyet($danhSachVanBanDaHoanThanh, $userId)
    {
        $danhSachVanBanDenDonViDaHoanThanhTrongHan = [];
        $danhSachVanBanDenDonViDaHoanThanhQuaHan = [];

        foreach ($danhSachVanBanDaHoanThanh as $vanBanDen) {
            if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_DUNG_HAN) {
                $donViChuTri = DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
                    ->orderBy('id', 'DESC')->select('id', 'van_ban_den_id', 'can_bo_nhan_id')->first();

                if (!empty($donViChuTri) && $donViChuTri->can_bo_nhan_id == $userId) {
                    $danhSachVanBanDenDonViDaHoanThanhTrongHan[] = $donViChuTri->van_ban_den_id;
                }
            }

            if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_QUA_HAN) {
                $donViChuTri = DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
                    ->select('id', 'van_ban_den_id', 'can_bo_nhan_id')
                    ->orderBy('id', 'DESC')->first();

                if (!empty($donViChuTri) && $donViChuTri->can_bo_nhan_id == $userId) {
                    $danhSachVanBanDenDonViDaHoanThanhQuaHan[] = $donViChuTri->van_ban_den_id;
                }
            }


        }


        $vanBanTrongHan = count($danhSachVanBanDenDonViDaHoanThanhTrongHan);
        $vanBanQuaHan = count($danhSachVanBanDenDonViDaHoanThanhQuaHan);

        $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;


        return [
            'hoan_thanh_dung_han' => $vanBanTrongHan,
            'hoan_thanh_qua_han' => $vanBanQuaHan,
            'id_van_ban_trong_han' => $danhSachVanBanDenDonViDaHoanThanhTrongHan,
            'id_van_ban_qua_han' => $danhSachVanBanDenDonViDaHoanThanhQuaHan,
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
