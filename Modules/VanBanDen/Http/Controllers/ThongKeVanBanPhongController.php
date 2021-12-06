<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Exports\thongKeVanBanChiCucExport;
use App\Exports\thongKeVanBanPhongExport;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDen\Entities\VanBanDen;
use auth, Excel;

class ThongKeVanBanPhongController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $currentUser = auth::user();
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $donViPhong = auth::user()->don_vi_id;
        $arrNguoiDungId = [];

        $nguoiDung = User::where('don_vi_id', $donViPhong)
            ->whereNull('deleted_at')
            ->get();

        if ($currentUser->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $danhSachChuyenVien = User::role(CHUYEN_VIEN)
                ->where('don_vi_id', $donViPhong)
                ->whereNull('deleted_at')
                ->select('id')
                ->get();
            if ($danhSachChuyenVien) {
                foreach ($danhSachChuyenVien as $chuyenVien) {
                    array_push($arrNguoiDungId, $chuyenVien->id);
                }
            }

            array_push($arrNguoiDungId, $currentUser->id);

            $nguoiDung = User::whereIn('id', $arrNguoiDungId)
                ->whereNull('deleted_at')
                ->get();
        }

        if ($currentUser->hasRole(CHUYEN_VIEN)) {
            $nguoiDung = User::where('id', $currentUser->id)
                ->whereNull('deleted_at')
                ->get();
        }

        foreach ($nguoiDung as $dataNguoiDung) {
            $dataNguoiDung->vanBanDaGiaiQuyet = $this->VanBanDenHoanThanhCuaDonVi($dataNguoiDung->id, $tu_ngay, $den_ngay);
            $dataNguoiDung->vanBanChuaGiaiQuyet = $this->VanBanDenChuaHoanThanhCuaDonVi($dataNguoiDung, $tu_ngay, $den_ngay);

        }

        $soDonvi = $nguoiDung->count();

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

        return view('vanbanden::thong_ke.thong_ke_vb_phong',
            compact('nguoiDung'));
    }


    public function VanBanDenChuaHoanThanhCuaDonVi($user, $tu_ngay, $den_ngay)
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


//        $donViChuTri = DonViChuTri::where('can_bo_nhan_id', $user->id)
//            ->where('don_vi_id', $user->don_vi_id)
//            ->whereNotNull('vao_so_van_ban')
//            ->whereNull('hoan_thanh')
//            ->select('id', 'van_ban_den_id', 'can_bo_nhan_id')
//            ->orderBy('id', 'DESC')
//            ->get()->unique('van_ban_den_id');
//
//
//        $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $danhSachVanBanDenQuaHanDangXuLy = VanBanDen::where(function ($query) use ($user) {
                if (!empty($user)) {
                    return $query->whereHas('vanBanDangXuLy', function ($q) use($user) {
                        return $q->where('can_bo_nhan_id', $user->id);
                    });
                }
            })
            ->where('han_xu_ly', '<', $date)
//            ->where(function ($query) use ($trinhTuNhanVanBan) {
//                return $query->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan);
//            })
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
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->select('id')
            ->get();

//        dd($trinhTuNhanVanBan);


        $danhSachVanBanDenTrongHanDangXuLy = VanBanDen::where(function ($query) use ($user) {
                if (!empty($user)) {
                    return $query->whereHas('vanBanDangXuLy', function ($q) use($user) {
                        return $q->where('can_bo_nhan_id', $user->id);
                    });
                }
            })
            ->where('han_xu_ly', '>=', $date)
//            ->where(function ($query) use ($trinhTuNhanVanBan) {
//                return $query->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan);
//            })
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
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
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
        $user = auth::user();
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $danhSachVanBanDenDaHoanThanhDungHan = VanBanDen::where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
//           ->wherehas('tkhoanThanhVBTrongHan')
            ->where(function ($query) use ($userId)  {
                    return $query->whereHas('tkhoanThanhVBTrongHan', function ($q) use($userId) {
                        return $q->where('can_bo_nhan_id', $userId);
                    });
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
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
//            ->where('type', 1)
            ->whereNull('deleted_at')
            ->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN)
            ->count();

        $danhSachVanBanDenDaHoanThanhQuaHan = VanBanDen::where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($userId)  {
                return $query->whereHas('hoanThanhVBQuaHan', function ($q) use($userId) {
                    return $q->where('can_bo_nhan_id', $userId);
                });
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
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN)
            ->whereNull('deleted_at')
            ->count();

//        $arrIdVanBanDaHoanThanh = $danhSachVanBanDenDaHoanThanh->pluck('id')->toArray();


        $vanBanDaGiaiQuyet = $this->getVanBanDenDaGiaiQuyet($userId,$danhSachVanBanDenDaHoanThanhDungHan,$danhSachVanBanDenDaHoanThanhQuaHan);

        return [
            'tong' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'] + $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'giai_quyet_trong_han' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'],
            'giai_quyet_qua_han' => $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'id_van_ban_trong_han' => \GuzzleHttp\json_encode($vanBanDaGiaiQuyet['id_van_ban_trong_han']),
            'id_van_ban_qua_han' => \GuzzleHttp\json_encode($vanBanDaGiaiQuyet['id_van_ban_qua_han'])
        ];

    }


    public function getVanBanDenDaGiaiQuyet( $userId,$danhSachVanBanDenDaHoanThanhDungHan,$danhSachVanBanDenDaHoanThanhQuaHan)
    {
        $users = User::where('id', $userId)->first();
        $danhSachVanBanDenDonViDaHoanThanhTrongHan = [];
        $danhSachVanBanDenDonViDaHoanThanhQuaHan = [];

//        foreach ($danhSachVanBanDaHoanThanh as $vanBanDen) {
//            if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_DUNG_HAN) {
//                $donViChuTri = DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
//                    ->orderBy('id', 'DESC')->select('id', 'van_ban_den_id', 'can_bo_nhan_id')->first();
//
//                if (!empty($donViChuTri) && $donViChuTri->can_bo_nhan_id == $userId) {
//                    $danhSachVanBanDenDonViDaHoanThanhTrongHan[] = $donViChuTri->van_ban_den_id;
//                }
//            }
//
//            if ($vanBanDen->hoan_thanh_dung_han == VanBanDen::HOAN_THANH_QUA_HAN) {
//                $donViChuTri = DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
//                    ->select('id', 'van_ban_den_id', 'can_bo_nhan_id')
//                    ->orderBy('id', 'DESC')->first();
//
//                if (!empty($donViChuTri) && $donViChuTri->can_bo_nhan_id == $userId) {
//                    $danhSachVanBanDenDonViDaHoanThanhQuaHan[] = $donViChuTri->van_ban_den_id;
//                }
//            }
//
//
//        }


//        dd($userId, $danhSachVanBanDenDonViDaHoanThanhTrongHan);
//        $vanBanTrongHan = count($danhSachVanBanDenDonViDaHoanThanhTrongHan);

//        $danhSachVanBanDenDonViDaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrIdVanBanDungHan)
//            ->whereHas('vanBanDen', function ($query) {
//                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
//            })
//            ->where('don_vi_id', $users->don_vi_id)->distinct()->count();
//        $vanBanQuaHan = count($danhSachVanBanDenDonViDaHoanThanhQuaHan);

        $tongVanBanDonViKhongDieuHanh = $danhSachVanBanDenDaHoanThanhDungHan + $danhSachVanBanDenDaHoanThanhQuaHan;

        return [
            'hoan_thanh_dung_han' => $danhSachVanBanDenDaHoanThanhDungHan,
            'hoan_thanh_qua_han' => $danhSachVanBanDenDaHoanThanhQuaHan,
            'id_van_ban_trong_han' => $danhSachVanBanDenDonViDaHoanThanhTrongHan,
            'id_van_ban_qua_han' => $danhSachVanBanDenDonViDaHoanThanhQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh
        ];
    }

    public function chiTietDaGiaiQuyetTrongHanVanBanphong($id, Request $request)
    {
        $donViId = null;
        $type = null;
        $users = User::where('id', $id)->first();

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

        $arrIdVanBanDungHan = $danhSachVanBanDenDaHoanThanhDungHan->pluck('id')->toArray();

        $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrIdVanBanDungHan)
            ->whereHas('vanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
            })
            ->where('can_bo_nhan_id', $users)->orderBy('id', 'DESC')->get()->unique('van_ban_den_id');


        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('ds_vanBanDen'));
    }

    public function chiTietDaGiaiQuyetQuaHanVanBanphong($id, Request $request)
    {
        $donViId = null;
        $type = null;
        $users = User::where('id', $id)->first();

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

        $arrIdVanBanDungHan = $danhSachVanBanDenDaHoanThanhDungHan->pluck('id')->toArray();

        $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrIdVanBanDungHan)
            ->whereHas('vanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
            })
            ->where('don_vi_id', $users->don_vi_id)->distinct()->get();
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('ds_vanBanDen'));


        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso', compact('ds_vanBanDen'));
    }

    public function chiTietChuaGiaiQuyetQuaHanVanBanphong($id, Request $request)
    {
        $donViId = null;
        $type = null;
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $user = User::where('id', $id)->first();

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
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso', compact('ds_vanBanDen'));


    }

    public function chiTietChuaGiaiQuyetTrongHanVanBanphong($id, Request $request)
    {


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        $user = User::where('id', $id)->first();

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


        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso', compact('ds_vanBanDen'));
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
     * @param Request $request
     * @return Renderable
     */
    public function show($id, Request $request)
    {
        $user = User::findOrFail($id);
        $arrVanBanDenId = \GuzzleHttp\json_decode($request->get('arr_id'));
        $type = $request->get('type') ?? 1;

        switch ($type) {
            case 1:
                $title = 'Văn bản đã giải quyết trong hạn';
                break;

            case 2:
                $title = 'Văn bản đã giải quyết quá hạn';
                break;

            case 3:
                $title = 'Văn bản đang giải quyết trong hạn';
                break;

            case 4:
                $title = 'Văn bản đang giải quyết quá hạn';
                break;
        }

        $danhSachVanBanDen = VanBanDen::whereIn('id', $arrVanBanDenId)->paginate(PER_PAGE);

        return view('vanbanden::chi-tiet-thong-ke.chi_tiet_van_ban',
            compact('user', 'type', 'danhSachVanBanDen', 'user', 'title'));
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
