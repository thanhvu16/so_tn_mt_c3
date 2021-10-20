<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Models\LichCongTac;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Auth, DB;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
use Modules\LichCongTac\Entities\NguoiThamDu;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\VanBanDenDonVi;

class VanBanDenDonViController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = auth::user();
        $donVi = $currentUser->donVi;
        $trinhTuNhanVanBan = null;

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }

        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDenStart = $request->get('so_den_start') ?? null;
        $soKyHieu = $request->get('so_ky_hieu') ?? null;
        $date = $request->get('date') ?? null;
        $noiGui = $request->get('co_quan_ban_hanh') ?? null;

        $donViChuTri = DonViChuTri::where('don_vi_id', $currentUser->don_vi_id)
            ->where('can_bo_nhan_id', $currentUser->id)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->select('id', 'van_ban_den_id')
            ->get();

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
//        dd()

        if($request->type != null)
        {
            $danhSachVanBanDen = VanBanDen::with(['lanhDaoXemDeBiet' => function($query) {
                return $query->select('id', 'van_ban_den_id', 'lanh_dao_id');
            }])
                ->where(function ($query) use ($loaiVanBanGiayMoi) {
                    if (!empty($loaiVanBanGiayMoi)) {
                        return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                    }
                })
                ->where(function ($query) use ($soDenStart) {
                    if (!empty($soDenStart)) {
                        return $query->where('so_den', $soDenStart);
                    }
                })
                ->where(function ($query) use ($trichYeu) {
                    if (!empty($trichYeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                    }
                })
                ->where(function ($query) use ($soKyHieu) {
                    if (!empty($soKyHieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($soKyHieu) . "%");
                    }
                })
                ->where(function ($query) use ($date) {
                    if (!empty($date)) {
                        return $query->where('updated_at', "LIKE", $date);
                    }
                })
                ->whereIn('id', $arrVanBanDenId)
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->paginate(PER_PAGE);
        }else{
            $danhSachVanBanDen = VanBanDen::with(['lanhDaoXemDeBiet' => function($query) {
                return $query->select('id', 'van_ban_den_id', 'lanh_dao_id');
            }])
                ->where(function ($query) use ($loaiVanBanGiayMoi) {
                    if (!empty($loaiVanBanGiayMoi)) {
                        return $query->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id);
                    }
                })
                ->where(function ($query) use ($trichYeu) {
                    if (!empty($trichYeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                    }
                })
                ->where(function ($query) use ($noiGui) {
                    if (!empty($noiGui)) {
                        return $query->where(DB::raw('lower(co_quan_ban_hanh)'), 'LIKE', "%" . mb_strtolower($noiGui) . "%");
                    }
                })
                ->where(function ($query) use ($soKyHieu) {
                    if (!empty($soKyHieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($soKyHieu) . "%");
                    }
                })
                ->where(function ($query) use ($date) {
                    if (!empty($date)) {
                        return $query->where('updated_at', "LIKE", $date);
                    }
                })
                ->whereIn('id', $arrVanBanDenId)
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->paginate(PER_PAGE);
        }


        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN];

        $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->select('id', 'ho_ten')
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien = User::role(CHUYEN_VIEN)
            ->where('don_vi_id', $currentUser->don_vi_id)
            ->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten')
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        if (!empty($danhSachVanBanDen)) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
                if ($trinhTuNhanVanBan != VanBanDen::CHUYEN_VIEN_NHAN_VB) {
                    $vanBanDen->getChuyenVienPhoiHop = $vanBanDen->getChuyenVienPhoiHop() ?? null;
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonVi();
                    $vanBanDen->phoPhong = $vanBanDen->getChuyenVienThucHien(count($danhSachPhoPhong) > 0 ? $danhSachPhoPhong->pluck('id')->toArray() : [0]);
                    $vanBanDen->chuyenVien = $vanBanDen->getChuyenVienThucHien(count($danhSachChuyenVien) > 0 ? $danhSachChuyenVien->pluck('id')->toArray() : [0]);
                    $vanBanDen->truongPhong = $vanBanDen->getChuyenVienThucHien([$currentUser->id]);
                }

                if ($trinhTuNhanVanBan == VanBanDen::CHUYEN_VIEN_NHAN_VB) {
                    $vanBanDen->chuyenVien = $vanBanDen->getChuyenVienThucHien(count($danhSachChuyenVien) > 0 ? $danhSachChuyenVien->pluck('id')->toArray() : [0]);
                    $vanBanDen->giaHanVanBanTraLai = $vanBanDen->giaHanVanBanTraLai();
                    $vanBanDen->giaiQuyetVanBanTraLai = $vanBanDen->giaiQuyetVanBanTraLai();
                    $vanBanDen->giaHanVanBanLanhDaoChoDuyet = $vanBanDen->giaHanVanBanLanhDaoDuyet(GiaHanVanBan::STATUS_CHO_DUYET);
                    $vanBanDen->giaHanVanBanLanhDaoDaDuyet = $vanBanDen->giaHanVanBanLanhDaoDuyet(GiaHanVanBan::STATUS_DA_DUYET);
                }
            }
        }


        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        if ($trinhTuNhanVanBan == VanBanDen::CHUYEN_VIEN_NHAN_VB) {

            return view('dieuhanhvanbanden::don-vi.chuyen_vien', compact('danhSachVanBanDen', 'danhSachPhoPhong',
                'danhSachChuyenVien', 'trinhTuNhanVanBan', 'order', 'loaiVanBanGiayMoi', 'donVi'));
        }
        return view('dieuhanhvanbanden::don-vi.index', compact('danhSachVanBanDen', 'danhSachPhoPhong',
            'danhSachChuyenVien', 'trinhTuNhanVanBan', 'order', 'loaiVanBanGiayMoi', 'donVi'));
    }

    public function vanBanDaChiDao(Request $request)
    {
        $currentUser = auth::user();
        $trinhTuNhanVanBan = null;

        $trichYeu = $request->get('trich_yeu') ?? null;
        $soKyHieu = $request->get('so_ky_hieu') ?? null;
        $soDen = (int)$request->get('so_den') ?? null;
        $date = $request->get('date') ?? null;

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole([PHO_PHONG,PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        $donViChuTri = DonViChuTri::where('don_vi_id', $currentUser->don_vi_id)->where('can_bo_chuyen_id', $currentUser->id)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->select('van_ban_den_id')
            ->get();

        $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();


        if($request->type != null)
        {
            $danhSachVanBanDen = VanBanDen::with(['checkLuuVetVanBanDen',
                'lanhDaoXemDeBiet' => function($query) {
                    return $query->select('id', 'van_ban_den_id', 'lanh_dao_id');
                }
            ])
//            ->doesntHave('vanBanDaGuiTraLai')
                ->where(function ($query) use ($loaiVanBanGiayMoi) {
                    if (!empty($loaiVanBanGiayMoi)) {
                        return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                    }
                })
                ->whereIn('id', $arrVanBanDenId)
                ->where(function ($query) use ($trichYeu) {
                    if (!empty($trichYeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                    }
                })
                ->where(function ($query) use ($soKyHieu) {
                    if (!empty($soKyHieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($soKyHieu) . "%");
                    }
                })
                ->where(function ($query) use ($soDen) {
                    if (!empty($soDen)) {
                        return $query->where('so_den', $soDen);
                    }
                })
                ->where(function ($query) use ($date) {
                    if (!empty($date)) {
                        return $query->where('updated_at', "LIKE", $date);
                    }
                })
                ->paginate(PER_PAGE);
        }else{
            $danhSachVanBanDen = VanBanDen::with(['checkLuuVetVanBanDen',
                'lanhDaoXemDeBiet' => function($query) {
                    return $query->select('id', 'van_ban_den_id', 'lanh_dao_id');
                }
            ])
//            ->doesntHave('vanBanDaGuiTraLai')
                ->where(function ($query) use ($loaiVanBanGiayMoi) {
                    if (!empty($loaiVanBanGiayMoi)) {
                        return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                    }
                })
                ->whereIn('id', $arrVanBanDenId)
                ->where(function ($query) use ($trichYeu) {
                    if (!empty($trichYeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                    }
                })
                ->where(function ($query) use ($soDen) {
                    if (!empty($soDen)) {
                        return $query->where('so_den', $soDen);
                    }
                })
                ->where(function ($query) use ($soKyHieu) {
                    if (!empty($soKyHieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($soKyHieu) . "%");
                    }
                })
                ->where(function ($query) use ($date) {
                    if (!empty($date)) {
                        return $query->where('updated_at', "LIKE", $date);
                    }
                })
                ->paginate(PER_PAGE);
        }

        $danhSachPhoPhong = User::role([PHO_PHONG, PHO_TRUONG_BAN, PHO_CHANH_VAN_PHONG])
            ->where('don_vi_id', $currentUser->don_vi_id)
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')
            ->select('id', 'ho_ten')
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien = User::role(CHUYEN_VIEN)
            ->where('don_vi_id', $currentUser->don_vi_id)
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')
            ->select('id', 'ho_ten')
            ->orderBy('id', 'DESC')->get();

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;

                $vanBanDen->getChuyenVienPhoiHop = $vanBanDen->getChuyenVienPhoiHop() ?? null;
                $vanBanDen->getChuyenVienThucHien = $vanBanDen->getDonViChuTriThucHien() ?? null;
                $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonVi();

                $vanBanDen->phoPhong = $vanBanDen->getChuyenVienThucHien($danhSachPhoPhong->pluck('id')->toArray());
                $vanBanDen->chuyenVien = $vanBanDen->getChuyenVienThucHien($danhSachChuyenVien->pluck('id')->toArray());
                $vanBanDen->truongPhong = $vanBanDen->getChuyenVienThucHien([$currentUser->id]);
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        return view('dieuhanhvanbanden::don-vi.da_chi_dao', compact('danhSachVanBanDen', 'danhSachPhoPhong',
            'danhSachChuyenVien', 'trinhTuNhanVanBan', 'order', 'loaiVanBanGiayMoi'));
    }

    public function dangXuLy(Request $request)
    {
        $currentUser = auth::user();
        $donVi = $currentUser->donVi;
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        $trinhTuNhanVanBan = null;

        $quaHan = !empty($request->get('qua_han')) ? date('Y-m-d') : null;

        $trichYeu = $request->get('trich_yeu') ?? null;
        $soKyHieu = $request->get('so_ky_hieu') ?? null;
        $soDen = $request->get('so_den') ?? null;
        $date = $request->get('date') ? formatYMD($request->get('date')) : null;


        $trinhTuNhanVanBan = null;
        if ($currentUser->hasRole(CHU_TICH)) {
            $trinhTuNhanVanBan = VanBanDen::CHU_TICH_NHAN_VB;

            if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                $trinhTuNhanVanBan = VanBanDen::CHU_TICH_XA_NHAN_VB;
            }
        }

        if ($currentUser->hasRole(PHO_CHU_TICH)) {
            $trinhTuNhanVanBan = VanBanDen::PHO_CHU_TICH_NHAN_VB;

            if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                $trinhTuNhanVanBan = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
            }
        }

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }

        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $currentUser->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->select('van_ban_den_id')
            ->get();

        $arrVanBanDenId = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, CHUYEN_VIEN, PHO_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {

            $donViChuTri = DonViChuTri::where('don_vi_id', $currentUser->don_vi_id)
                ->where('can_bo_nhan_id', $currentUser->id)
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        }

        if ($donVi->cap_xa = DonVi::CAP_XA) {

            $donViChuTri = DonViChuTri::where('don_vi_id', $currentUser->don_vi_id)
                ->where('can_bo_nhan_id', $currentUser->id)
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->select('van_ban_den_id')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        }
        if($request->type != null)
        {
            $danhSachVanBanDen = VanBanDen::with([
                'xuLyVanBanDen' => function ($query) {
                    return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
                },
                'donViChuTri' => function ($query) {
                    return $query->select('van_ban_den_id', 'can_bo_nhan_id');
                }
            ])
                ->where(function ($query) use ($loaiVanBanGiayMoi) {
                    if (!empty($loaiVanBanGiayMoi)) {
                        return $query->where('loai_van_ban_id',$loaiVanBanGiayMoi->id);
                    }
                })
                ->whereIn('id', $arrVanBanDenId)
                ->where(function ($query) use ($quaHan) {
                    if (!empty ($quaHan)) {
                        return $query->where('han_xu_ly', '<', $quaHan);
                    }
                })
                ->where(function ($query) use ($trichYeu) {
                    if (!empty($trichYeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                    }
                })
                ->where(function ($query) use ($soKyHieu) {
                    if (!empty($soKyHieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($soKyHieu) . "%");
                    }
                })
                ->where(function ($query) use ($soDen) {
                    if (!empty($soDen)) {
                        return $query->where('so_den', $soDen);
                    }
                })
                ->where(function ($query) use ($date) {
                    if (!empty($date)) {
                        return $query->where('created_at', "LIKE", $date);
                    }
                })
                ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
                ->select('id', 'so_ky_hieu', 'loai_van_ban_id', 'so_den', 'ngay_ban_hanh', 'co_quan_ban_hanh',
                    'nguoi_ky', 'nguoi_tao', 'han_xu_ly', 'trich_yeu', 'do_khan_cap_id', 'do_bao_mat_id', 'van_ban_can_tra_loi',
                    'noi_dung_hop', 'gio_hop', 'ngay_hop', 'dia_diem', 'noi_dung', 'trinh_tu_nhan_van_ban', 'created_at')
                ->paginate(PER_PAGE_10);
        }else{
            $danhSachVanBanDen = VanBanDen::with([
                'xuLyVanBanDen' => function ($query) {
                    return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
                },
                'donViChuTri' => function ($query) {
                    return $query->select('van_ban_den_id', 'can_bo_nhan_id');
                }
            ])
                ->where(function ($query) use ($loaiVanBanGiayMoi) {
                    if (!empty($loaiVanBanGiayMoi)) {
                        return $query->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id);
                    }
                })
                ->whereIn('id', $arrVanBanDenId)
                ->where(function ($query) use ($quaHan) {
                    if (!empty ($quaHan)) {
                        return $query->where('han_xu_ly', '<', $quaHan);
                    }
                })
                ->where(function ($query) use ($trichYeu) {
                    if (!empty($trichYeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                    }
                })
                ->where(function ($query) use ($soKyHieu) {
                    if (!empty($soKyHieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($soKyHieu) . "%");
                    }
                })
                ->where(function ($query) use ($soDen) {
                    if (!empty($soDen)) {
                        return $query->where('so_den', $soDen);
                    }
                })
                ->where(function ($query) use ($date) {
                    if (!empty($date)) {
                        return $query->where('created_at', "LIKE", $date);
                    }
                })
                ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
                ->select('id', 'so_ky_hieu', 'loai_van_ban_id', 'so_den', 'ngay_ban_hanh', 'co_quan_ban_hanh',
                    'nguoi_ky', 'nguoi_tao', 'han_xu_ly', 'trich_yeu', 'do_khan_cap_id', 'do_bao_mat_id', 'van_ban_can_tra_loi',
                    'noi_dung_hop', 'gio_hop', 'ngay_hop', 'dia_diem', 'noi_dung', 'trinh_tu_nhan_van_ban', 'created_at')
                ->paginate(PER_PAGE_10);
        }



        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
            }
        }


        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

        return view('dieuhanhvanbanden::don-vi.dang_xu_ly', compact('danhSachVanBanDen', 'order'));

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
        $data = $request->all();
        $currentUser = auth::user();
        $donVi = $currentUser->donVi;

//        $currentUser->can(AllPermission::thamMuu()) ? $currentUser->donVi->parent_id : $currentUser->don_vi_id,

        $vanBanDenIds = json_decode($data['van_ban_den_id']);
        $danhSachPhoChuTichIds = $data['pho_chu_tich_id'] ?? null;
        $danhSachChuTichIds = $data['chu_tich_id'] ?? null;
        $danhSachTruongPhongIds = $data['truong_phong_id'] ?? null;
        $danhSachPhoPhongIds = $data['pho_phong_id'] ?? null;
        $danhSachChuyenVienIds = $data['chuyen_vien_id'] ?? null;
        $vanBanTraLoi = $data['van_ban_tra_loi'] ?? null;
        $textnoidunChuTich = $data['noi_dung_chu_tich'] ?? null;
        $textnoidungPhoChuTich = $data['noi_dung_pho_chu_tich'] ?? null;
        $textnoidungTruongPhong = $data['noi_dung_truong_phong'] ?? null;
        $textnoidungPhoPhong = $data['noi_dung_pho_phong'] ?? null;
        $textNoiDungChuyenVien = $data['noi_dung_chuyen_vien'] ?? null;
        $arrChuyenVienPhoiHopIds = $data['chuyen_vien_phoi_hop_id'] ?? null;
        $lanhDaoDuHopId = $data['lanh_dao_du_hop_id'] ?? null;
        $arrLanhDaoXemDeBiet = $data['lanh_dao_xem_de_biet'] ?? null;
        $dataHanXuLy = $data['han_xu_ly'] ?? null;

        // them moi
        $danhSachDonViChuTriIds = $data['don_vi_chu_tri_id'] ?? null;
        $danhSachDonViPhoiHopIds = $data['don_vi_phoi_hop_id'] ?? null;
        $textDonViChuTri = $data['don_vi_chu_tri'] ?? null;
        $textDonViPhoiHop = $data['don_vi_phoi_hop'] ?? null;
        $dataVanBanQuanTrong = $data['van_ban_quan_trong'] ?? null;
        $donViDuHop = $data['don_vi_du_hop'] ?? null;

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        /** van ban da gui tra lai cho lanh dao cho duyet,
         * lanh dao chua duyet nhung van ci dao tiep van ban
         **/
        $chiDaoVanBanTraLaiChoDuyet = $data['chi_dao_tu_van_ban_tra_lai_cho_duyet'] ?? null;

        if (isset($vanBanDenIds) && count($vanBanDenIds) > 0) {

            try {
                DB::beginTransaction();

                foreach ($vanBanDenIds as $vanBanDenId) {

                    $donViChuTri = DonViChuTri::where('van_ban_den_id', $vanBanDenId)
                        ->where('can_bo_nhan_id', $currentUser->id)
                        ->whereNull('hoan_thanh')->first();

                    // tham muu chi cuc gui van ban len cho giam doc so nhan van ban
                    if ($currentUser->can(AllPermission::thamMuu()) && $donVi->parent_id != 0) {
                        $donViChuTri = DonViChuTri::where('van_ban_den_id', $vanBanDenId)
                            ->where('don_vi_id', $currentUser->donVi->parent_id)
                            ->where('can_bo_nhan_id', $currentUser->id)
                            ->whereNotNull('vao_so_van_ban')
                            ->whereNull('hoan_thanh')
                            ->first();
                    }

                    if ($donViChuTri) {
                        $donViChuTri->chuyen_tiep = DonViChuTri::CHUYEN_TIEP;
                        if ($currentUser->can(AllPermission::thamMuu())) {
                            $donViChuTri->da_tham_muu = DonViChuTri::DA_THAM_MUU;
                        }
                        $donViChuTri->save();

                        DonViChuTri::where('van_ban_den_id', $vanBanDenId)
                            ->where('id', '>', $donViChuTri->id)
                            ->whereNull('hoan_thanh')
                            ->delete();
                    }

                    $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
                    if ($vanBanDen) {
                        if (isset($vanBanTraLoi[$vanBanDenId]) && !empty($vanBanTraLoi[$vanBanDenId])) {
                            $vanBanDen->van_ban_can_tra_loi = VanBanDen::VB_TRA_LOI;
                            $vanBanDen->save();
                            // update van ban con co parent_id = vanbanden->id
                            VanBanDen::where('parent_id', $vanBanDen->id)->update([
                               'van_ban_can_tra_loi' => VanBanDen::VB_TRA_LOI
                            ]);
                        } else {
                            $vanBanDen->van_ban_can_tra_loi = null;
                            $vanBanDen->save();
                            // update van ban con co parent_id = vanbanden->id
                            VanBanDen::where('parent_id', $vanBanDen->id)->update([
                                'van_ban_can_tra_loi' => null
                            ]);
                        }

                        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA || ($currentUser->can(AllPermission::thamMuu()) && $donVi->parent_id != 0)) {
                            if (!empty($danhSachChuTichIds[$vanBanDenId])) {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                                $vanBanDen->save();
                            }

                            if (!empty($danhSachPhoChuTichIds[$vanBanDenId]) && empty($danhSachChuTichIds[$vanBanDenId])) {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
                                $vanBanDen->save();
                            }

                            if (!empty($danhSachDonViChuTriIds[$vanBanDenId]) && empty($danhSachChuTichIds[$vanBanDenId]) && empty($danhSachPhoChuTichIds[$vanBanDenId]))
                            {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                                $vanBanDen->save();
                            }

                        } else {
                            if (!empty($danhSachPhoPhongIds[$vanBanDenId])) {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_PHONG_NHAN_VB;
                                $vanBanDen->save();
                            }

                            if (!empty($danhSachChuyenVienIds[$vanBanDenId]) && empty($danhSachPhoPhongIds[$vanBanDenId])) {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHUYEN_VIEN_NHAN_VB;
                                $vanBanDen->save();
                            }
                        }
                    }

                    //check van ban tra lai
                    $canBoNhan = 'can_bo_nhan_id';
                    if (!empty($chiDaoVanBanTraLaiChoDuyet)) {
                        $canBoNhan = 'can_bo_chuyen_id';
                    }
                    $vanBanTraLai = VanBanTraLai::where('van_ban_den_id', $vanBanDenId)
                        ->where($canBoNhan, $currentUser->id)
                        ->whereNull('status')->first();
                    if ($vanBanTraLai) {
                        VanBanTraLai::updateStatusVanBanTraLai($vanBanTraLai);
                    }

                    if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {
                        if ($currentUser->hasRole([CHU_TICH, PHO_CHU_TICH])) {
                            if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                                LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], $donViDuHop[$vanBanDenId], $danhSachDonViChuTriIds[$vanBanDenId], $chuyenTuDonVi = 1);
                            }
                        } else {
                            if (!empty($lanhDaoDuHopId) > 0 && !empty($lanhDaoDuHopId[$vanBanDenId])) {
                                if ($currentUser->can(AllPermission::thamMuu())) {
                                    LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], $donViDuHop ? $donViDuHop[$vanBanDenId] : null, $danhSachDonViChuTriIds ? $danhSachDonViChuTriIds[$vanBanDenId] : null, $chuyenTuDonVi = 1);

                                } else {
                                    LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], 1, $currentUser->don_vi_id, $chuyenTuDonVi = 1);
                                }
                            }
                        }
                    }

                    // cap xa
                    if (isset($danhSachChuTichIds) && !empty($danhSachChuTichIds[$vanBanDenId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachChuTichIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->can(AllPermission::thamMuu()) ? $currentUser->donVi->parent_id : $currentUser->don_vi_id,
                            'parent_id' => $donViChuTri ? $donViChuTri->id : null,
                            'noi_dung' => $textnoidunChuTich[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViChuTri->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViChuTri->vao_so_van_ban,
                            'han_xu_ly_cu' => $vanBanDen->han_xu_ly ?? null,
                            'han_xu_ly_moi' => isset($dataHanXuLy[$vanBanDenId]) ? formatYMD($dataHanXuLy[$vanBanDenId]) : $donViChuTri->han_xu_ly_moi ?? null,
                            'da_chuyen_xuong_don_vi' => $donViChuTri->da_chuyen_xuong_don_vi,
                            'user_id' => $currentUser->id,
                            'da_tham_muu' => $donViChuTri->da_tham_muu ?? null
                        ];

                        $chuyenNhanVanBanChuTich = new DonViChuTri();
                        $chuyenNhanVanBanChuTich->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanChuTich->save();

                        // luu log dh van ban den pho phong
                        $luuVetVanBanDen = new LogXuLyVanBanDen();
                        $luuVetVanBanDen->fill($dataChuyenNhanVanBanDonVi);
                        $luuVetVanBanDen->save();
                    }

                    //pho chu tich
                    if (isset($danhSachPhoChuTichIds) && !empty($danhSachPhoChuTichIds[$vanBanDenId])) {
                        $dataChuyenNhanVanPCT = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoChuTichIds[$vanBanDenId],
//                            'don_vi_id' => $currentUser->can(AllPermission::thamMuu()) ? $currentUser->donVi->parent_id : $currentUser->don_vi_id,
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViChuTri ? $donViChuTri->id : null,
                            'noi_dung' => $textnoidungPhoChuTich[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViChuTri->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViChuTri->vao_so_van_ban,
                            'han_xu_ly_cu' => $vanBanDen->han_xu_ly ?? null,
                            'han_xu_ly_moi' => isset($dataHanXuLy[$vanBanDenId]) ? formatYMD($dataHanXuLy[$vanBanDenId]) : $donViChuTri->han_xu_ly_moi,
                            'da_chuyen_xuong_don_vi' => $donViChuTri->da_chuyen_xuong_don_vi,
                            'user_id' => $currentUser->id,
                            'da_tham_muu' => $donViChuTri->da_tham_muu ?? null
                        ];

                        $chuyenNhanVanBanPCT = new DonViChuTri();
                        $chuyenNhanVanBanPCT->fill($dataChuyenNhanVanPCT);
                        $chuyenNhanVanBanPCT->save();

                        // luu log dh van ban den PCT
                        LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataChuyenNhanVanPCT);
                    }

                    // luu don vi chu tri tu cap xa
                    if ($currentUser->hasRole([CHU_TICH, PHO_CHU_TICH]) || ($currentUser->can(AllPermission::thamMuu()) && $donVi->parent_id != 0)) {
                        $donViId = $currentUser->can(AllPermission::thamMuu()) ? $donVi->parent_id : auth::user()->don_vi_id;
                        // van ban quan trong
                        if(!empty($dataVanBanQuanTrong[$vanBanDenId])) {
                            VanBanQuanTrong::saveVanBanQuanTrong($vanBanDenId, $dataVanBanQuanTrong[$vanBanDenId]);
                        }
                        //luu don vi chu tri
                        if (!empty($danhSachDonViChuTriIds[$vanBanDenId])) {
                            DonViChuTri::luuDonViCapXa($danhSachDonViChuTriIds[$vanBanDenId], $textDonViChuTri[$vanBanDenId], $vanBanDenId, $donViChuTri, $vanBanDen->han_xu_ly, $dataHanXuLy[$vanBanDenId] ?? null);
                        }
                        //data don vi phoi hop
                        $dataLuuDonViPhoiHop = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $nguoiDung->id ?? null,
                            'noi_dung' => $textDonViPhoiHop[$vanBanDenId],
                            'don_vi_phoi_hop_id' => isset($danhSachDonViPhoiHopIds[$vanBanDenId]) ? \GuzzleHttp\json_encode($danhSachDonViPhoiHopIds[$vanBanDenId]) : null,
                            'user_id' => $currentUser->id
                        ];

                        DonViPhoiHop::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'hoan_thanh' => null
                        ])->where('parent_don_vi_id', $donViId)
                            ->delete();

                        if (!empty($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                            DonViPhoiHop::luuDonViPhoiHopCapXa($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId, !empty($danhSachPhoChuTichIds[$vanBanDenId]) ? $danhSachPhoChuTichIds[$vanBanDenId] : null);

                            // luu vet van ban den
                            LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataLuuDonViPhoiHop);
                        }

                    }

                    if (isset($danhSachTruongPhongIds) && !empty($danhSachTruongPhongIds[$vanBanDenId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachTruongPhongIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViChuTri ? $donViChuTri->id : null,
                            'parent_don_vi_id' => $donViChuTri->parent_don_vi_id ?? null,
                            'noi_dung' => $textnoidungTruongPhong[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViChuTri->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViChuTri->vao_so_van_ban,
                            'han_xu_ly_cu' => $vanBanDen->han_xu_ly ?? null,
                            'han_xu_ly_moi' => isset($dataHanXuLy[$vanBanDenId]) ? formatYMD($dataHanXuLy[$vanBanDenId]) : $donViChuTri->han_xu_ly_moi ?? null,
                            'da_chuyen_xuong_don_vi' => $donViChuTri->da_chuyen_xuong_don_vi,
                            'user_id' => $currentUser->id,
                            'da_tham_muu' => $donViChuTri->da_tham_muu ?? null
                        ];

                        $chuyenNhanVanBanPhoPhong = new DonViChuTri();
                        $chuyenNhanVanBanPhoPhong->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanPhoPhong->save();

                        // luu log dh van ban den pho phong
                        LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataChuyenNhanVanBanDonVi);

                        if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {
                            NguoiThamDu::taoNguoiDuHop($vanBanDenId, $danhSachTruongPhongIds[$vanBanDenId]);
                        }
                    }

                    //chuyen nhan van ban don vi
                    if (!empty($danhSachPhoPhongIds[$vanBanDenId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoPhongIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViChuTri ? $donViChuTri->id : null,
                            'parent_don_vi_id' => $donViChuTri->parent_don_vi_id ?? null,
                            'noi_dung' => $textnoidungPhoPhong[$vanBanDenId],
                            'han_xu_ly_cu' => $vanBanDen->han_xu_ly ?? null,
                            'han_xu_ly_moi' => isset($dataHanXuLy[$vanBanDenId]) ? formatYMD($dataHanXuLy[$vanBanDenId]) : $donViChuTri->han_xu_ly_moi ?? null,
                            'don_vi_co_dieu_hanh' => $donViChuTri->don_vi_co_dieu_hanh ?? 0,
                            'vao_so_van_ban' => $donViChuTri->vao_so_van_ban,
                            'da_chuyen_xuong_don_vi' => $donViChuTri->da_chuyen_xuong_don_vi,
                            'user_id' => $currentUser->id,
                            'da_tham_muu' => $donViChuTri->da_tham_muu ?? null
                        ];

                        $chuyenNhanVanBanPhoPhong = new DonViChuTri();
                        $chuyenNhanVanBanPhoPhong->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanPhoPhong->save();

                        // luu log dh van ban den pho phong
                        LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataChuyenNhanVanBanDonVi);
                        if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {
                            NguoiThamDu::taoNguoiDuHop($vanBanDenId, $danhSachPhoPhongIds[$vanBanDenId]);
                        }
                    }

                    if (isset($danhSachChuyenVienIds[$vanBanDenId])) {
                        //save chuyen vien thuc hien
                        $dataChuyenNhanVanBanChuyenVien = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachChuyenVienIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViChuTri ? $donViChuTri->id : null,
                            'parent_don_vi_id' => $donViChuTri->parent_don_vi_id ?? null,
                            'noi_dung' => $textNoiDungChuyenVien[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViChuTri->don_vi_co_dieu_hanh ?? 0,
                            'vao_so_van_ban' => $donViChuTri->vao_so_van_ban,
                            'han_xu_ly_cu' => $vanBanDen->han_xu_ly ?? null,
                            'han_xu_ly_moi' => isset($dataHanXuLy[$vanBanDenId]) ? formatYMD($dataHanXuLy[$vanBanDenId]) : $donViChuTri->han_xu_ly_moi ?? null,
                            'da_chuyen_xuong_don_vi' => $donViChuTri->da_chuyen_xuong_don_vi,
                            'user_id' => $currentUser->id,
                            'da_tham_muu' => $donViChuTri->da_tham_muu ?? null

                        ];

                        $chuyenNhanVanBanChuyenVienDonVi = new DonViChuTri();
                        $chuyenNhanVanBanChuyenVienDonVi->fill($dataChuyenNhanVanBanChuyenVien);
                        $chuyenNhanVanBanChuyenVienDonVi->save();

                        // luu log dh van ban den chuyen vien
                        LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataChuyenNhanVanBanChuyenVien);
                        if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {
                            NguoiThamDu::taoNguoiDuHop($vanBanDenId, $danhSachChuyenVienIds[$vanBanDenId]);
                        }
                    }

                    //delete chuyen vien phoi hop
                    ChuyenVienPhoiHop::where('van_ban_den_id', $vanBanDenId)
                        ->where('don_vi_id', $currentUser->don_vi_id)
                        ->delete();

                    if (!empty($arrChuyenVienPhoiHopIds[$vanBanDenId]) && count($arrChuyenVienPhoiHopIds[$vanBanDenId]) > 0) {
                        //save chuyen vien phoi hop
                        ChuyenVienPhoiHop::savechuyenVienPhoiHop($arrChuyenVienPhoiHopIds[$vanBanDenId],
                            $vanBanDenId, $currentUser->don_vi_id);
                    }
                    if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {
                        // save thanh phan du hop
                        ThanhPhanDuHop::store($giayMoi, $vanBanDen, [!empty($danhSachPhoChuTichIds[$vanBanDenId]) ? $danhSachPhoChuTichIds[$vanBanDenId] : null, !empty($danhSachTruongPhongIds[$vanBanDenId]) ? $danhSachTruongPhongIds[$vanBanDenId] : null,
                            !empty($danhSachPhoPhongIds[$vanBanDenId]) ? $danhSachPhoPhongIds[$vanBanDenId] : null, !empty($danhSachChuyenVienIds[$vanBanDenId]) ? $danhSachChuyenVienIds[$vanBanDenId]: null], null, $donVi->id);
                    }
                    //luu can bo xem de biet
                    if($currentUser->can(AllPermission::thamMuu())) {
                        LanhDaoXemDeBiet::where('van_ban_den_id', $vanBanDenId)
                            ->where('don_vi_id', $currentUser->donVi->parent_id)
                            ->delete();
                    } else {
                        LanhDaoXemDeBiet::where('van_ban_den_id', $vanBanDenId)
                            ->where('don_vi_id', auth::user()->don_vi_id)
                            ->delete();
                    }


                    if (!empty($arrLanhDaoXemDeBiet[$vanBanDenId])) {
                        LanhDaoXemDeBiet::saveLanhDaoXemDeBiet($arrLanhDaoXemDeBiet[$vanBanDenId], $vanBanDenId, $type = 1);
                    }
                }

                DB::commit();

                return redirect()->back()->with('success', 'Đã gửi thành công.');

            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }

        }

        return redirect()->back()->with('warning', 'Không có dữ liệu.');

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

    public function getCanBoPhoiHop($id, Request $request)
    {
        if ($request->ajax()) {

            $currentUser = auth::user();

            $donVi = $currentUser->donVi;

            $danhSachNguoiDung = User::role(CHUYEN_VIEN)
                ->where('don_vi_id', $currentUser->don_vi_id)
                ->whereNotIn('id', json_decode($id))
                ->where('trang_thai', ACTIVE)
                ->whereNull('deleted_at')
                ->select(['id', 'ho_ten'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $danhSachNguoiDung
            ]);
        }
    }
}
