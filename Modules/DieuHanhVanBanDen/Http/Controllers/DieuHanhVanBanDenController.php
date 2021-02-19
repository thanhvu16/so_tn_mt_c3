<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\NhomDonVi;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\VanBanDen\Entities\VanBanDen;
use App\Http\Controllers\Controller;
use Auth;

class DieuHanhVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('dieuhanhvanbanden::index');
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
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id, Request $request)
    {
        $vanBanDen = VanBanDen::with([
            'loaiVanBan' => function ($query) {
                return $query->select('id', 'ten_loai_van_ban');
            },
            'soVanBan' => function ($query) {
                return $query->select('id', 'ten_so_van_ban');
            },
            'doKhan' => function ($query) {
                return $query->select('id', 'ten_muc_do');
            },
            'doBaoMat' => function ($query) {
                return $query->select('id', 'ten_muc_do');
            },
            'xuLyVanBanDen' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_chuyen_id', 'can_bo_nhan_id', 'noi_dung', 'status', 'created_at', 'han_xu_ly');
            },
            'XuLyVanBanDenTraLai',
            'donViChuTri' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_chuyen_id', 'can_bo_nhan_id', 'noi_dung', 'created_at', 'han_xu_ly_moi');
            },
            'donViPhoiHop' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_chuyen_id', 'can_bo_nhan_id', 'noi_dung', 'created_at');
            },
            'giaHanVanBan' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'can_bo_chuyen_id', 'can_bo_nhan_id', 'noi_dung',
                    'thoi_han_de_xuat', 'thoi_han_cu', 'status', 'created_at');
            },
            'chuyenVienPhoiHopGiaiQuyet',
            'duThaoVanBan' => function($query) {
                return $query->select('id', 'van_ban_den_don_vi_id', 'lan_du_thao', 'nguoi_tao', 'created_at', 'y_kien',
                    'so_ky_hieu', 'loai_van_ban_id', 'vb_trich_yeu');
            },
            'giaiQuyetVanBan' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'noi_dung', 'noi_dung_nhan_xet',
                    'user_id', 'can_bo_duyet_id', 'status', 'created_at', 'parent_id');
            },
            'vanBanDenFile' => function ($query) {
                return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
            },
            'donViPhoiHopGiaiquyet' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'user_id', 'don_vi_id', 'noi_dung');
            }
        ])->select('id', 'so_ky_hieu', 'loai_van_ban_id', 'so_den', 'ngay_ban_hanh', 'co_quan_ban_hanh',
            'nguoi_ky', 'nguoi_tao', 'han_xu_ly', 'trich_yeu', 'do_khan_cap_id', 'do_bao_mat_id',
            'noi_dung_hop', 'gio_hop', 'ngay_hop', 'dia_diem', 'noi_dung', 'trinh_tu_nhan_van_ban')
            ->findOrFail($id);

        $donViChuTri = $vanBanDen->checkDonViChuTri;

        $chuTich = User::role(CHU_TICH)->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten')
            ->first();

        $danhSachPhoChuTich = User::role(PHO_CHUC_TICH)
            ->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten')
            ->get();

        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN];

        $danhSachPhoPhong = User::where('don_vi_id', $donViChuTri->don_vi_id ?? null)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->select('id', 'ho_ten')
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->get();


        $danhSachChuyenVien = User::role(CHUYEN_VIEN)
            ->where('don_vi_id', $donViChuTri->don_vi_id ?? null)
            ->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten')
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $role = [TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN];
        $truongPhong = User::where('don_vi_id', $donViChuTri->don_vi_id ?? null)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->first();

        $danhSachPhoChuTichXa = User::role(PHO_CHUC_TICH)
            ->where('trang_thai', ACTIVE)
            ->where('don_vi_id', $donViChuTri->don_vi_id ?? null)
            ->select('id', 'ho_ten')
            ->get();

        $chuTichXa = User::role(CHU_TICH)
                    ->where('trang_thai', ACTIVE)
                    ->where('don_vi_id', $donViChuTri->don_vi_id ?? null)
                    ->select('id', 'ho_ten')
                    ->first();


        $vanBanDen->chuTich = $vanBanDen->checkCanBoNhan([$chuTich->id]) ?? null;
        $vanBanDen->PhoChuTich = $vanBanDen->checkCanBoNhan($danhSachPhoChuTich->pluck('id')->toArray());
        if (!empty($donViChuTri)) {
            $vanBanDen->chuTichXa = !empty($chuTichXa) ? $vanBanDen->getCanBoDonVi([$chuTichXa->id], $donViChuTri->don_vi_id) : null;
            $vanBanDen->phoChuTichXa = count($danhSachPhoChuTichXa) > 0 ? $vanBanDen->getCanBoDonVi($danhSachPhoChuTichXa->pluck('id')->toArray(), $donViChuTri->don_vi_id) : null;
            $vanBanDen->truongPhong = !empty($truongPhong) ? $vanBanDen->getCanBoDonVi([$truongPhong->id], $donViChuTri->don_vi_id) : null;
            $vanBanDen->phoPhong = count($danhSachPhoPhong) > 0 ? $vanBanDen->getCanBoDonVi($danhSachPhoPhong->pluck('id')->toArray(), $donViChuTri->don_vi_id) : null;
            $vanBanDen->chuyenVien = count($danhSachChuyenVien) > 0 ? $vanBanDen->getCanBoDonVi($danhSachChuyenVien->pluck('id')->toArray(), $donViChuTri->don_vi_id) : null;
        }
        //phoi hop
        $type = $request->get('status') ?? null;

        if ($vanBanDen) {
            $vanBanDen->hasChild = $vanBanDen->hasChild($type) ?? null;
        }

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id', 'ten_loai_van_ban')->first();

        $ds_loaiVanBan = null;
        $ds_nguoiKy = null;
        $lanhdaotrongphong = null;
        $lanhdaokhac = null;
        $date = null;

        if ($vanBanDen->trinh_tu_nhan_van_ban != VanBanDen::HOAN_THANH_VAN_BAN) {
            // data cua du thao
            $date = Carbon::now()->format('Y-m-d');
            $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
                ->orderBy('ten_loai_van_ban', 'desc')->get();
            $lanhdaotrongphong = User::role([TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN])->where(['don_vi_id' => auth::user()->don_vi_id])->where('id', '!=', auth::user()->id)->whereNull('deleted_at')->get();
            $lanhdaokhac = User::role([TRUONG_PHONG])->where('don_vi_id', '!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();
            $vanThuVanBanDiPiceCharts = [];
            $user = auth::user();
            $donVi = $user->donVi;
            $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi','LIKE',LANH_DAO_UY_BAN)->first();
            $donViCapHuyen = DonVi::where('nhom_don_vi',$nhomDonVi->id ?? null)->first();

            switch (auth::user()->roles->pluck('name')[0]) {
                case CHUYEN_VIEN:
                    if (empty($donVi->cap_xa)) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                        foreach ($truongpho as $data2) {
                            array_push($vanThuVanBanDiPiceCharts, $data2);
                        }
                        $chanvanphong = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                        $giamdoc = User::role([CHU_TICH, PHO_CHUC_TICH])->get();

                        foreach ($chanvanphong as $data) {
                            array_push($vanThuVanBanDiPiceCharts, $data);
                        }

                        foreach ($giamdoc as $data2) {
                            array_push($vanThuVanBanDiPiceCharts, $data2);
                        }
                        $ds_nguoiKy = $vanThuVanBanDiPiceCharts;
                    } else {
                        $ds_nguoiKy = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    }
                    break;
                case PHO_PHONG:
                    $truongpho = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    foreach ($truongpho as $data2) {
                        array_push($vanThuVanBanDiPiceCharts, $data2);
                    }
                    $chanvanphong = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                    foreach ($chanvanphong as $data) {
                        array_push($vanThuVanBanDiPiceCharts, $data);
                    }
                    $ds_nguoiKy = $vanThuVanBanDiPiceCharts;
                    break;
                case TRUONG_PHONG:
                    $ds_nguoiKy = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                    break;
                case PHO_CHUC_TICH:
                    if (empty($donVi->cap_xa)) {
                        $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                    }
                    break;
                case CHU_TICH:
                    if (empty($donVi->cap_xa)) {
                        $ds_nguoiKy = null;
                    } else {
                        $ds_nguoiKy = User::role([CHU_TICH, PHO_CHUC_TICH])->get();
                    }
                    break;
                case CHANH_VAN_PHONG:
                    $ds_nguoiKy = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donViCapHuyen->id ?? null)->get();
                    break;
                case PHO_CHANH_VAN_PHONG:
                    $ds_nguoiKy = User::role([CHANH_VAN_PHONG])->get();
                    break;
                case VAN_THU_DON_VI:
                    $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    break;
                case VAN_THU_HUYEN:
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                    break;
                case TRUONG_BAN:
                    $ds_nguoiKy = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                    break;
                case PHO_TRUONG_BAN:
                    $ds_nguoiKy = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    break;

            }
        }

        return view('dieuhanhvanbanden::van-ban-den.show',
            compact('vanBanDen', 'loaiVanBanGiayMoi', 'ds_loaiVanBan', 'ds_nguoiKy', 'lanhdaotrongphong', 'lanhdaokhac', 'date'));
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

    public function vanBanXemDeBiet(Request $request)
    {
        $year = $request->get('year') ?? date('Y');
        $month = $request->get('month') ?? date('m');
        $defaultSelect = 'all';

        $hanXuLy = $request->get('han_xu_ly') ? formatYMD($request->get('han_xu_ly')) : null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;

        $lanhDaoXemDeBiet = LanhDaoXemDeBiet::where('lanh_dao_id', auth::user()->id)
            ->where(function ($query) use ($month, $defaultSelect) {
                if ($month != $defaultSelect) {
                    return $query->whereMonth('created_at', $month);
                }
            })
            ->where(function ($query) use ($year, $defaultSelect) {
                if ($year != $defaultSelect) {
                    return $query->whereYear('created_at', $year);
                }
            })
            ->select('van_ban_den_id')
            ->orderBy('id', 'DESC')
            ->get();

        $arrVanBanDenId = $lanhDaoXemDeBiet->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with(['vanBanDenFile',
            'xuLyVanBanDen' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
            },
            'donViChuTri' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_nhan_id');
            }
        ])
            ->whereIn('id', $arrVanBanDenId)
            ->where(function ($query) use ($hanXuLy) {
                if (!empty($hanXuLy)) {
                    return $query->where('han_xu_ly', $hanXuLy);
                }
            })
            ->where(function ($query) use ($trichYeu) {
                if (!empty($trichYeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichYeu");
                }
            })
            ->where(function ($query) use ($soDen) {
                if (!empty($soDen)) {
                    return $query->where('so_den', $soDen);
                }
            })
            ->paginate(PER_PAGE);

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                $vanBanDen->giaiQuyetVanBanHoanThanh = $vanBanDen->giaiQuyetVanBanHoanThanh();
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();

        return view('dieuhanhvanbanden::van-ban-hoan-thanh.xem_de_biet',
            compact('danhSachVanBanDen', 'order', 'loaiVanBanGiayMoi', 'year', 'month'));
    }

    public function vanBanQuanTrong(Request $request)
    {
        $hanXuLy = $request->get('han_xu_ly') ? formatYMD($request->get('han_xu_ly')) : null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;

        $vanBanQuanTrong = VanBanQuanTrong::where('user_id', auth::user()->id)->orderBy('id', 'DESC')->get();

        $arrVanBanDenId = $vanBanQuanTrong->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with(['vanBanDenFile',
            'xuLyVanBanDen' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
            },
            'donViChuTri' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_nhan_id');
            }
        ])
            ->whereIn('id', $arrVanBanDenId)
            ->where(function ($query) use ($hanXuLy) {
                if (!empty($hanXuLy)) {
                    return $query->where('han_xu_ly', $hanXuLy);
                }
            })
            ->where(function ($query) use ($trichYeu) {
                if (!empty($trichYeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichYeu");
                }
            })
            ->where(function ($query) use ($soDen) {
                if (!empty($soDen)) {
                    return $query->where('so_den', $soDen);
                }
            })
            ->paginate(PER_PAGE);

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                $vanBanDen->giaiQuyetVanBanHoanThanh = $vanBanDen->giaiQuyetVanBanHoanThanh();
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        return view('dieuhanhvanbanden::van-ban-hoan-thanh.van_ban_quan_trong', compact('danhSachVanBanDen', 'order', 'loaiVanBanGiayMoi'));
    }
}
