<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Http\Controllers\Controller;
use App\Models\LichCongTac;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Auth, DB;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoChiDao;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\LichCongTac\Entities\FileCuocHop;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;

class PhanLoaiVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        canPermission(AllPermission::thamMuu());
        $ngayDen = $request->get('ngay_den') ? formatYMD($request->get('ngay_den')) : null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;

        $user = auth::user();
        $donVi = $user->donVi;
        $active = null;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        if ($donVi->parent_id != 0) {
            $parentDonVi = DonVi::where('id', $donVi->parent_id)
                ->select('id', 'type')
                ->whereNull('deleted_at')->first();

            $active = VanBanDen::THAM_MUU_CHI_CUC_NHAN_VB;
            // tham muu cap chi cuc
            $donViChuTri = DonViChuTri::where('don_vi_id', $donVi->parent_id)
                ->whereNull('da_tham_muu')
                ->select('id', 'van_ban_den_id')
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $danhSachVanBanDen = VanBanDen::with([
                'donViCapXaChuTri',
                'DonViCapXaPhoiHop' => function ($query) {
                    return $query->select('id', 'don_vi_id', 'van_ban_den_id');
                }
            ])
                ->whereIn('id', $arrVanBanDenId)
                ->where('trinh_tu_nhan_van_ban', $active)
                ->paginate(PER_PAGE_10);

            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $donVi->parent_id)
                ->select('id', 'ho_ten')
                ->get();

            $chuTich = User::role(CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $donVi->parent_id)
                ->select('id', 'ho_ten')
                ->first();


            // sua o day
            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->whereHas('user')
                ->where('parent_id', $donVi->parent_id)
                ->select('id', 'ten_don_vi')
                ->orderBy('thu_tu', 'asc')
                ->get();

            if (!empty($danhSachVanBanDen)) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
                    $vanBanDen->chuTich = $vanBanDen->getChuyenVienThucHien([$chuTich->id]);
                    $vanBanDen->phoChuTich = $vanBanDen->getChuyenVienThucHien($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]) ?? null;
                    $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonViCapXa();
                    $vanBanDen->lanhDaoXemDeBiet = $vanBanDen->lanhDaoXemDeBiet ?? null;
                }
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            return view('dieuhanhvanbanden::don-vi-cap-xa.lanh-dao.phan_loai_van_ban',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'danhSachDonVi',
                    'loaiVanBanGiayMoi', 'order', 'chuTich', 'active', 'parentDonVi'));
        } else {
            //tham muu cap so
            if ($request->type != null) {
//                dd(1)
                $danhSachVanBanDen = VanBanDen::
                where('lanh_dao_tham_muu', $user->id)->
                with([
                        'vanBanDenFile' => function ($query) {
                            return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                        }
                    ])
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {

                            return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->where(function ($query) use ($trichYeu) {
                        if (!empty($trichYeu)) {
                            return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                        }
                    })
                    ->whereNull('trinh_tu_nhan_van_ban')
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
                    ->where(function ($query) use ($soDen) {
                        if (!empty($soDen)) {
                            return $query->where('so_den', $soDen);
                        }
                    })
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
                    ->paginate(PER_PAGE_10);

            } else {
                $danhSachVanBanDen = VanBanDen::
                where('lanh_dao_tham_muu', $user->id)->
                with([
                        'vanBanDenFile' => function ($query) {
                            return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                        }
                    ])
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {

                            return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->whereNull('trinh_tu_nhan_van_ban')
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
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
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
                    ->paginate(PER_PAGE_10);
            }

            if (count($danhSachVanBanDen) > 0) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                }
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            $chuTich = User::role(CHU_TICH)
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })
                ->select('id', 'ho_ten', 'don_vi_id')->first();

            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('don_vi_id', $chuTich->don_vi_id)
                ->select('id', 'ho_ten')->get();

            $danhSachDonVi = DonVi::whereHas('user')
                ->whereNull('deleted_at')
                ->where('parent_id', DonVi::NO_PARENT_ID)
                ->select('id', 'ten_don_vi')
                ->orderBy('thu_tu', 'asc')
                ->get();

            return view('dieuhanhvanbanden::phan-loai-van-ban.index',
                compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                    'danhSachPhoChuTich', 'chuTich', 'danhSachDonVi'));
        }
    }
    public function phanLoaiGiayMoi(Request $request)
    {
        canPermission(AllPermission::thamMuu());
        $ngayDen = $request->get('ngay_den') ? formatYMD($request->get('ngay_den')) : null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;

        $user = auth::user();
        $donVi = $user->donVi;
        $active = null;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        if ($donVi->parent_id != 0) {
            $parentDonVi = DonVi::where('id', $donVi->parent_id)
                ->select('id', 'type')
                ->whereNull('deleted_at')->first();

            $active = VanBanDen::THAM_MUU_CHI_CUC_NHAN_VB;
            // tham muu cap chi cuc
            $donViChuTri = DonViChuTri::where('don_vi_id', $donVi->parent_id)
                ->whereNull('da_tham_muu')
                ->select('id', 'van_ban_den_id')
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $danhSachVanBanDen = VanBanDen::with([
                'donViCapXaChuTri',
                'DonViCapXaPhoiHop' => function ($query) {
                    return $query->select('id', 'don_vi_id', 'van_ban_den_id');
                }
            ])
                ->whereIn('id', $arrVanBanDenId)
                ->where('trinh_tu_nhan_van_ban', $active)
                ->paginate(PER_PAGE_10);

            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $donVi->parent_id)
                ->select('id', 'ho_ten')
                ->get();

            $chuTich = User::role(CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $donVi->parent_id)
                ->select('id', 'ho_ten')
                ->first();

            // sua o day
            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->whereHas('user')
                ->where('parent_id', $donVi->parent_id)
                ->select('id', 'ten_don_vi')
                ->orderBy('thu_tu', 'asc')
                ->get();

            if (!empty($danhSachVanBanDen)) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
                    $vanBanDen->chuTich = $vanBanDen->getChuyenVienThucHien([$chuTich->id]);
                    $vanBanDen->phoChuTich = $vanBanDen->getChuyenVienThucHien($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]) ?? null;
                    $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonViCapXa();
                    $vanBanDen->lanhDaoXemDeBiet = $vanBanDen->lanhDaoXemDeBiet ?? null;
                }
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            return view('dieuhanhvanbanden::don-vi-cap-xa.lanh-dao.phan_loai_van_ban',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'danhSachDonVi',
                    'loaiVanBanGiayMoi', 'order', 'chuTich', 'active', 'parentDonVi'));
        } else {
            //tham muu cap so
            if ($request->type != null) {
//                dd(1);
                $danhSachVanBanDen = VanBanDen::
                where('lanh_dao_tham_muu', 10551)->
                with([
                        'vanBanDenFile' => function ($query) {
                            return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                        }
                    ])
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {

                            return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->where(function ($query) use ($trichYeu) {
                        if (!empty($trichYeu)) {
                            return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichYeu) . "%");
                        }
                    })
                    ->whereNull('trinh_tu_nhan_van_ban')
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
                    ->where(function ($query) use ($soDen) {
                        if (!empty($soDen)) {
                            return $query->where('so_den', $soDen);
                        }
                    })
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
                    ->orderBy('created_at','desc')
                    ->paginate(PER_PAGE_10);

            } else {
                $danhSachVanBanDen = VanBanDen::where('lanh_dao_tham_muu', $user->id)
                    ->with([
                        'vanBanDenFile' => function ($query) {
                            return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                        }
                    ])
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {

                            return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->whereNull('trinh_tu_nhan_van_ban')
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
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
                    ->where(function ($query) use ($ngayDen) {
                        if (!empty($ngayDen)) {
                            return $query->where('created_at', $ngayDen);
                        }
                    })
                    ->paginate(PER_PAGE_10);
            }

            if (count($danhSachVanBanDen) > 0) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                }
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            $chuTich = User::role(CHU_TICH)
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })
                ->select('id', 'ho_ten', 'don_vi_id')->first();

            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('don_vi_id', $chuTich->don_vi_id)
                ->select('id', 'ho_ten')->get();

            $danhSachDonVi = DonVi::whereHas('user')
                ->whereNull('deleted_at')
                ->where('parent_id', DonVi::NO_PARENT_ID)
                ->select('id', 'ten_don_vi')
                ->orderBy('thu_tu', 'asc')
                ->get();
            $active = null;
            if ($user->hasRole(AllPermission::chuTich())) {
                $active = VanBanDen::CHU_TICH_NHAN_VB;
            }

            return view('dieuhanhvanbanden::phan-loai-van-ban.phan_loai_giay_moi',
                compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                    'danhSachPhoChuTich', 'chuTich', 'danhSachDonVi','active'));
        }
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
        $currentUser = auth::user();

        $data = $request->all();
        $vanBanDenIds = json_decode($data['van_ban_den_id']);

        $arrChuTich = $data['chu_tich_id'] ?? null;
        $arrPhoChuTich = $data['pho_chu_tich_id'] ?? null;
        $arrLanhDaoXemDeBiet = $data['lanh_dao_xem_de_biet'] ?? null;
        $arrLanhDaoChiDao = $data['lanh_dao_chi_dao'] ?? null;
        $giamDocChiDao = $data['giam_doc_id'] ?? null;
        $tomTatVanBan = $data['tom_tat'] ?? null;
        $noiDungChuTich = $data['noi_dung_chu_tich'] ?? null;
        $noiDungPhoChuTich = $data['noi_dung_pho_chu_tich'] ?? null;
        $canBoChiDao = null;
        $vbquantrong = null;
        $type = $request->get('type') ?? null;
        $statusTraiLai = $request->get('van_ban_tra_lai') ?? null;
        $lanhDaoDuHopId = $data['lanh_dao_du_hop_id'] ?? null;
        $danhSachDonViChuTriIds = $data['don_vi_chu_tri_id'] ?? null;
        $danhSachDonViPhoiHopIds = $data['don_vi_phoi_hop_id'] ?? null;
        $vanBanQuanTrongIds = $data['van_ban_quan_trong'] ?? null;
        $textDonViChuTri = $data['don_vi_chu_tri'] ?? null;
        $donViDuHop = $data['don_vi_du_hop'] ?? null;
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        if (isset($vanBanDenIds) && count($vanBanDenIds) > 0) {
            try {
                DB::beginTransaction();

                foreach ($vanBanDenIds as $vanBanDenId) {
                    $vanBanDenTY = VanBanDen::where('id',$vanBanDenId)->first();
                    $checkLogXuLyVanBanDen = LogXuLyVanBanDen::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_chuyen_id' => $currentUser->id
                    ])->orderBy('id', 'DESC')->first();

                    //check xem có tồn tại vb_quan trọng không

                    if ($request->sua_phan_loai == 1) {
                        $checkDonViChuTri = DonViChuTri::where('van_ban_den_id', $vanBanDenId)->first();
                        if ($checkDonViChuTri) {
                            if (!empty($vanBanQuanTrongIds[$vanBanDenId])) {
                                if ($checkDonViChuTri->van_ban_quan_trong == null) {
                                    $checkDonViChuTri->van_ban_quan_trong = 1;
                                    $checkDonViChuTri->save();

                                }
                            } else {
                                if ($checkDonViChuTri->van_ban_quan_trong == 1) {
                                    $checkDonViChuTri->van_ban_quan_trong = null;
                                    $checkDonViChuTri->save();

                                }
                            }
                        }

                    }

                    if (isset($type) && $type == 'update' && empty($checkLogXuLyVanBanDen)) {

                        return redirect()->back()->with('danger', 'Văn bản này đang xử lý, không thể cập nhật.');
                    }

                    //check xu ly van ban den
                    XuLyVanBanDen::where('van_ban_den_id', $vanBanDenId)
                        ->whereNull('status')->delete();

                    //check van ban tra lai
                    if (!is_null($statusTraiLai)) {
                        $vanBanTraLai = VanBanTraLai::where('van_ban_den_id', $vanBanDenId)
                            ->where('can_bo_nhan_id', $currentUser->id)
                            ->whereNull('status')->first();

                        if ($vanBanTraLai) {
                            $vanBanTraLai->status = VanBanTraLai::STATUS_GIAI_QUYET;
                            $vanBanTraLai->save();
                        }
                    }

                    // active van ban den
                    $chuyenVanBanXuongDonVi = null;
                    $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
                    if ($vanBanDen) {

                        $vanBanDen->tom_tat = $tomTatVanBan[$vanBanDenId];
                        $vanBanDen->save();

                        if (!empty($arrChuTich[$vanBanDenId])) {
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_NHAN_VB;
                            $vanBanDen->save();
                        }

                        if (!empty($arrPhoChuTich[$vanBanDenId]) && empty($arrChuTich[$vanBanDenId])) {
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_NHAN_VB;
                            $vanBanDen->save();
                        }

                        if (empty($arrPhoChuTich[$vanBanDenId]) && empty($arrChuTich[$vanBanDenId])) {
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                            $vanBanDen->save();
                            $chuyenVanBanXuongDonVi = DonViChuTri::VB_DA_CHUYEN_XUONG_DON_VI;
                        }
                    }

                    // check quyen gia han van ban
                    $quyenGiaHan = null;
                    // check lanh dao du hop
                    if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {

                        if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                            LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], $donViDuHop[$vanBanDenId], $danhSachDonViChuTriIds[$vanBanDenId]);
                        }
                    }
                    //chu tich

                    if (!empty($arrChuTich[$vanBanDenId])) {
                        $quyenGiaHan = 1;
                        $donVi = auth::user()->donVi;

                        //gửi sms
                        $lanhDaoSo = User::role([CHU_TICH])
                            ->whereHas('donVi', function ($query) {
                                return $query->whereNull('cap_xa');
                            })->first();
                        $vanBanDenTY = VanBanDen::where('id',$vanBanDenId)->first();
                        $noidungtn = $vanBanDenTY->so_den . ',' . $vanBanDenTY->trich_yeu . '. Thoi gian:' . $vanBanDenTY->gio_hop . ', ngày:' . formatDMY($vanBanDenTY->ngay_hop) . ', Tại:' . $vanBanDenTY->dia_diem;
                        $conVertTY = vn_to_str($noidungtn);

                        if(auth::user()->roles->pluck('name')[0] == CHU_TICH)
                        {
                            if ($donVi->parent_id == 0) {
                                VanBanDen::guiSMSOnly($conVertTY,$lanhDaoSo->so_dien_thoai);
                            }
                        }


                        $dataXuLyVanBanDen = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $arrChuTich[$vanBanDenId],
                            'noi_dung' => $noiDungChuTich[$vanBanDenId],
                            'tom_tat' => $tomTatVanBan[$vanBanDenId] ?? null,
                            'user_id' => $currentUser->id,
                            'tu_tham_muu' => XuLyVanBanDen::TU_THAM_MUU,
                            'lanh_dao_chi_dao' => $quyenGiaHan,
                            'quyen_gia_han' => $quyenGiaHan
                        ];

                        $checkTonTaiData = XuLyVanBanDen::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_nhan_id' => $arrChuTich[$vanBanDenId]
                        ])
                            ->whereNull('status')
                            ->first();

                        if (empty($checkTonTaiData)) {
                            $xuLyVanBanDen = new XuLyVanBanDen();
                            $xuLyVanBanDen->fill($dataXuLyVanBanDen);
                            $xuLyVanBanDen->save();
                        }


                        // luu vet van ban den
                        $this->luuLogXuLyVanBanDen($dataXuLyVanBanDen);
                        $quyenGiaHan = null;
                    }
                    //pho chu tich
                    if (!empty($arrPhoChuTich[$vanBanDenId])) {

                        if (empty($arrChuTich[$vanBanDenId])) {
                            $quyenGiaHan = 1;
                        }


                        $dataXuLyVanBanDenPCT = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $arrPhoChuTich[$vanBanDenId],
                            'noi_dung' => $noiDungPhoChuTich[$vanBanDenId],
                            'tom_tat' => $tomTatVanBan[$vanBanDenId] ?? null,
                            'user_id' => $currentUser->id,
                            'tu_tham_muu' => XuLyVanBanDen::TU_THAM_MUU,
                            'lanh_dao_chi_dao' => $quyenGiaHan,
                            'quyen_gia_han' => $quyenGiaHan
                        ];

                        $checkTonTaiData = XuLyVanBanDen::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_nhan_id' => $arrPhoChuTich[$vanBanDenId]
                        ])
                            ->whereNull('status')
                            ->first();

                        if (empty($checkTonTaiData)) {
                            $xuLyVanBanDen = new XuLyVanBanDen();
                            $xuLyVanBanDen->fill($dataXuLyVanBanDenPCT);
                            $xuLyVanBanDen->save();
                        }

                        // luu vet van ban den
                        $this->luuLogXuLyVanBanDen($dataXuLyVanBanDenPCT);
                        $quyenGiaHan = null;
                    }

                    //luu can bo xem de biet
                    if (!empty($arrLanhDaoXemDeBiet[$vanBanDenId])) {
                        LanhDaoXemDeBiet::saveLanhDaoXemDeBiet($arrLanhDaoXemDeBiet[$vanBanDenId],
                            $vanBanDenId);
                    }
                    if (!empty($arrLanhDaoChiDao[$vanBanDenId])) {
                        LanhDaoChiDao::saveLanhDaoChiDao($arrLanhDaoChiDao[$vanBanDenId],
                            $vanBanDenId);
                    }
                    if (!empty($giamDocChiDao[$vanBanDenId])) {
                        LanhDaoChiDao::saveGiamDocChiDao($giamDocChiDao[$vanBanDenId],
                            $vanBanDenId);
                    }

                    DonViChuTri::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'parent_don_vi_id' => null,
                        'hoan_thanh' => null
                    ])->delete();

                    if (!empty($danhSachDonViChuTriIds) && !empty($danhSachDonViChuTriIds[$vanBanDenId])) {

                        //gửi sms

//                        VanBanDen::guiSMSOnly($vanBanDenTY->trich_yeu,$nguoiDung->so_dien_thoai);
                        if($vanBanDenTY->loai_van_ban_id == $giayMoi->id)
                        {
                            DonViChuTri::guiSMSchuTri($vanBanDenId, $danhSachDonViChuTriIds);
                        }


                        DonViChuTri::luuDonViXuLyVanBan($vanBanDenId, $textDonViChuTri, $danhSachDonViChuTriIds, $chuyenVanBanXuongDonVi,
                            !empty($vanBanQuanTrongIds[$vanBanDenId]) ? $vanBanQuanTrongIds[$vanBanDenId] : null);
                    }


                    // luu don vi phoi hop
                    DonViPhoiHop::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'chuyen_tiep' => null,
                        'parent_don_vi_id' => null,
                        'hoan_thanh' => null
                    ])->delete();
                    if (isset($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                        //gửi sms
                        if($vanBanDenTY->loai_van_ban_id == $giayMoi->id) {
                            DonViPhoiHop::guiSMSArray($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);
                        }

                        DonViPhoiHop::luuDonViPhoiHop($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);
                    }
                }

                DB::commit();

                return redirect()->back()->with('success', 'Đã gửi thành công.');

            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }

        }

        return redirect()->back()->with('warning', 'Không tìm thấy dữ liệu.');
    }

    public function daPhanLoai(Request $request)
    {
        $user = auth::user();
        $soDenStart = $request->get('so_den_start') ?? null;
        $soDenEnd = $request->get('so_den_end') ?? null;
        $ngayDenStart = !empty($request->get('ngay_den_start')) ? formatYMD($request->get('ngay_den_start')) : null;
        $ngayDenEnd = !empty($request->get('ngay_den_end')) ? formatYMD($request->get('ngay_den_end')) : null;
        $ngayBanHanhStart = !empty($request->get('ngay_ban_hanh_start')) ? formatYMD($request->get('ngay_ban_hanh_start')) : null;
        $ngayBanHanhEnd = !empty($request->get('ngay_ban_hanh_end')) ? formatYMD($request->get('ngay_ban_hanh_end')) : null;
        $soKyHieu = $request->get('so_ky_hieu') ?? null;
//        dd($soKyHieu);
        $nguoiKy = $request->get('nguoi_ky') ?? null;
        $loaiVanBanId = $request->get('loai_van_ban_id') ?? null;
        $soVanBanId = $request->get('so_van_ban_id') ?? null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $tomTat = $request->get('tom_tat') ?? null;
        $coQuanBanHanh = $request->get('co_quan_ban_hanh') ?? null;
        $danhSachDonViXuLy = DonVi::whereNull('deleted_at')->orderBy('thu_tu', 'asc')->get();


        $danhSachDonVi = null;
        $danhSachDonViPhoiHop = null;
        $searchDonVi = $request->get('don_vi_id') ?? null;
        $searchDonViPhoiHop = $request->get('don_vi_phoi_hop_id') ?? null;
        $arrVanBanDenIdChuTri = null;
        $arrVanBanDenIdPhoiHop = null;
        if (!empty($searchDonVi)) {
            $donViChuTri = DonViChuTri::where('don_vi_id', $searchDonVi)
                ->select('id', 'van_ban_den_id')
                ->get();


            $arrVanBanDenIdChuTri = $donViChuTri->pluck('van_ban_den_id')->toArray();

        }
        if (!empty($searchDonViPhoiHop)) {
            $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $searchDonViPhoiHop)
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenIdPhoiHop = $donViPhoiHop->pluck('van_ban_den_id')->toArray();
        }

        $active = null;
//        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;
        $date = $request->get('date') ? formatYMD($request->date) : null;
        $chuTich = User::role(CHU_TICH)->select('id', 'ho_ten', 'don_vi_id', 'cap_xa')
            ->whereNull('cap_xa')
            ->first();
        $donVi = $user->donVi;
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA || $donVi->parent_id != 0) {
            if ($user->hasRole(TRUONG_BAN | TRUONG_PHONG)) {
                $active = VanBanDen::TRUONG_PHONG_NHAN_VB;
            }

            if ($user->hasRole(PHO_TRUONG_BAN | PHO_PHONG)) {
                $active = VanBanDen::PHO_PHONG_NHAN_VB;
            }

            if ($user->hasRole(CHUYEN_VIEN)) {
                $active = VanBanDen::CHUYEN_VIEN_NHAN_VB;
            }
            if ($user->hasRole(CHU_TICH)) {
                $active = VanBanDen::CHU_TICH_XA_NHAN_VB;
            }
            if ($user->hasRole(PHO_CHU_TICH)) {
                $active = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
            }

            $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
                ->select('id')->first();


            $parentDonVi = null;

            if ($user->can(AllPermission::thamMuu()) && (auth::user()->id == 15 || auth::user()->id == 10551)) {

                $donViChuTri = DonViChuTri::where(function ($query) use ($donVi) {
                    return $query->where('don_vi_id', $donVi->parent_id)
                        ->orWhere('parent_don_vi_id', $donVi->parent_id);
                })
                    ->where('can_bo_chuyen_id', $user->id)
                    ->whereNotNull('vao_so_van_ban')
                    ->whereNull('hoan_thanh')
                    ->select('id', 'van_ban_den_id')
                    ->get();

                $donViId = $donVi->parent_id;

                $parentDonVi = DonVi::where('id', $donVi->parent_id)
                    ->select('id', 'type')
                    ->whereNull('deleted_at')->first();

                $view = 'dieuhanhvanbanden::don-vi-cap-xa.lanh-dao.da_phan_loai';

            } else {
                $donViChuTri = DonViChuTri::where(function ($query) use ($user) {
                    return $query->where('don_vi_id', $user->don_vi_id)
                        ->orWhere('parent_don_vi_id', $user->don_vi_id);
                })
                    ->where('can_bo_chuyen_id', $user->id)
                    ->whereNotNull('vao_so_van_ban')
                    ->whereNull('hoan_thanh')
                    ->select('id', 'van_ban_den_id')
                    ->get();

                $donViId = $donVi->id;
                $view = 'dieuhanhvanbanden::don-vi-cap-xa.lanh-dao.da_chi_dao';

                $parentDonVi = DonVi::where('id', $donVi->id)
                    ->select('id', 'type')
                    ->whereNull('deleted_at')->first();
            }

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
            if ($request->type != null) {
                $danhSachVanBanDen = VanBanDen::with(['checkLuuVetVanBanDen',
                    'donViCapXaChuTri',
                    'DonViCapXaPhoiHop' => function ($query) {
                        return $query->select('id', 'don_vi_id', 'van_ban_den_id');
                    }
                ])
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
                    ->orderBy('updated_at', 'DESC')
                    ->paginate(PER_PAGE_10);
            } else {
                $danhSachVanBanDen = VanBanDen::with(['checkLuuVetVanBanDen',
                    'donViCapXaChuTri',
                    'DonViCapXaPhoiHop' => function ($query) {
                        return $query->select('id', 'don_vi_id', 'van_ban_den_id');
                    }
                ])
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
                    ->orderBy('updated_at', 'DESC')
                    ->paginate(PER_PAGE_10);
            }

            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $donViId)
                ->select('id', 'ho_ten')
                ->get();

            $chuTich = User::role(CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $donViId)
                ->select('id', 'ho_ten')
                ->first();
//            dd($donViId);


            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('parent_id', $donViId)
                ->select('id', 'ten_don_vi')
                ->orderBy('thu_tu', 'asc')
                ->get();

            if (!empty($danhSachVanBanDen)) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->giaHanLanhDao = $vanBanDen->getGiaHanLanhDao();
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    $vanBanDen->chuTich = $vanBanDen->getChuyenVienThucHien([$chuTich->id]);
                    $vanBanDen->phoChuTich = $vanBanDen->getChuyenVienThucHien($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lanhDaoXemDeBiet = $vanBanDen->lanhDaoXemDeBiet ?? null;
                    $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]) ?? null;
                    $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonViCapXa();
                }
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            return view($view,
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'danhSachDonVi',
                    'active', 'loaiVanBanGiayMoi', 'order', 'chuTich', 'parentDonVi', 'danhSachDonViXuLy'));

        } else {
            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('don_vi_id', $chuTich->don_vi_id)
                ->select(['id', 'ho_ten'])->get();


            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('parent_id', DonVi::NO_PARENT_ID)
                ->select(['id', 'ten_don_vi'])
                ->orderBy('thu_tu', 'asc')
                ->get();

            if ($user->hasRole(AllPermission::chuTich())) {
                $active = VanBanDen::CHU_TICH_NHAN_VB;
            }

            if ($user->hasRole(AllPermission::phoChuTich())) {
                $active = VanBanDen::PHO_CHU_TICH_NHAN_VB;

                $donViChuTri = DonViChuTri::where('can_bo_chuyen_id', $user->id)
                    ->whereNull('hoan_thanh')
                    ->get();

                $arrIdVanBanDenDonVi = $donViChuTri->pluck('van_ban_den_id')->toArray();
                if ($request->type != null) {
                    $danhSachVanBanDen = VanBanDen::with([
                        'lanhDaoXemDeBiet' => function ($query) {
                            $query->select(['van_ban_den_id', 'lanh_dao_id']);
                        },
                        'checkLuuVetVanBanDen',
                        'checkDonViChuTri' => function ($query) {
                            $query->select('id', 'van_ban_den_id', 'don_vi_id', 'noi_dung', 'han_xu_ly_moi');
                        },
                        'checkDonViPhoiHop' => function ($query) {
                            $query->select(['id', 'van_ban_den_id', 'don_vi_id', 'noi_dung']);
                        },
                        'vanBanDenFile' => function ($query) {
                            return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                        }
                    ])
                        ->where(function ($query) use ($loaiVanBanGiayMoi) {
                            if (!empty($loaiVanBanGiayMoi)) {
                                return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                            }
                        })
                        ->whereIn('id', $arrIdVanBanDenDonVi)
                        ->where(function ($query) use ($trichYeu) {
                            if (!empty($trichYeu)) {
                                return $query->where('trich_yeu', "LIKE", $trichYeu);
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
                        ->orderBy('updated_at', 'DESC')
                        ->paginate(PER_PAGE_10);
                } else {
                    $danhSachVanBanDen = VanBanDen::with([
                        'lanhDaoXemDeBiet' => function ($query) {
                            $query->select(['van_ban_den_id', 'lanh_dao_id']);
                        },
                        'checkLuuVetVanBanDen',
                        'checkDonViChuTri' => function ($query) {
                            $query->select('id', 'van_ban_den_id', 'don_vi_id', 'noi_dung', 'han_xu_ly_moi');
                        },
                        'checkDonViPhoiHop' => function ($query) {
                            $query->select(['id', 'van_ban_den_id', 'don_vi_id', 'noi_dung']);
                        },
                        'vanBanDenFile' => function ($query) {
                            return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                        }
                    ])
                        ->where(function ($query) use ($loaiVanBanGiayMoi) {
                            if (!empty($loaiVanBanGiayMoi)) {
                                return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                            }
                        })
                        ->whereIn('id', $arrIdVanBanDenDonVi)
                        ->where(function ($query) use ($trichYeu) {
                            if (!empty($trichYeu)) {
                                return $query->where('trich_yeu', "LIKE", $trichYeu);
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
                        ->orderBy('updated_at', 'DESC')
                        ->paginate(PER_PAGE_10);


                }


                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->arr_can_bo_nhan = $vanBanDen->getXuLyVanBanDen($type = 'get_id');
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
                    $vanBanDen->chuTich = $vanBanDen->checkCanBoNhan([$chuTich->id]);
                    $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]);
                    $vanBanDen->PhoChuTich = $vanBanDen->checkCanBoNhan($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->vanBanQuanTrong = $vanBanDen->checkVanBanQuanTrong();
                    $vanBanDen->checkQuyenGiaHan = $vanBanDen->checkQuyenGiaHan();
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonVi();
                }

                $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;


                return view('dieuhanhvanbanden::phan-loai-van-ban.da_phan_loai_pct',
                    compact('danhSachVanBanDen', 'order', 'danhSachDonVi', 'danhSachPhoChuTich', 'active', 'loaiVanBanGiayMoi'));

            }

            $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_chuyen_id', $user->id)
                ->select(['id', 'van_ban_den_id'])
                ->whereNull('status')
                ->whereNull('hoan_thanh')
                ->get();

            $donViChuTri = DonViChuTri::where('can_bo_chuyen_id', $user->id)
                ->select(['id', 'van_ban_den_id'])
                ->whereNull('hoan_thanh')
                ->get();

            $idVanBanDonViChuTri = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $idVanBanLanhDaoId = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

            $arrIdVanBanDenDonVi = array_merge($idVanBanDonViChuTri, $idVanBanLanhDaoId);
//            $arrIdVanBanDenDonVi = $idVanBanDonViChuTri;


            if ($request->type != null) {
                $danhSachVanBanDen = VanBanDen::with([
                    'lanhDaoXemDeBiet' => function ($query) {
                        $query->select(['van_ban_den_id', 'lanh_dao_id']);
                    }])
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {
                            return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->whereIn('id', $arrIdVanBanDenDonVi)
                    ->where(function ($query) use ($searchDonVi, $arrVanBanDenIdChuTri) {
                        if (!empty($searchDonVi)) {
                            return $query->whereIn('id', $arrVanBanDenIdChuTri);
                        }
                    })
                    ->where(function ($query) use ($searchDonViPhoiHop, $arrVanBanDenIdPhoiHop) {
                        if (!empty($searchDonViPhoiHop)) {
                            return $query->whereIn('id', $arrVanBanDenIdPhoiHop);
                        }
                    })
                    ->where(function ($query) use ($soDenStart, $soDenEnd) {
                        if (!empty($soDenStart)) {
                            return $query->whereBetween('so_den', [$soDenStart, $soDenEnd]);
                        }
                    })
                    ->where(function ($query) use ($ngayDenStart, $ngayDenEnd) {
                        if (!empty($ngayDenStart)) {
                            return $query->whereBetween('ngay_nhan', [$ngayDenStart, $ngayDenEnd]);
                        }
                    })
                    ->where(function ($query) use ($ngayBanHanhStart, $ngayBanHanhEnd) {
                        if (!empty($ngayBanHanhStart)) {
                            return $query->whereBetween('ngay_ban_hanh', [$ngayBanHanhStart, $ngayBanHanhEnd]);
                        }
                    })
                    ->where(function ($query) use ($soKyHieu) {
                        if (!empty($soKyHieu)) {
                            return $query->where('so_ky_hieu', 'LIKE', "%" . $soKyHieu . "%");
                        }
                    })
                    ->where(function ($query) use ($nguoiKy) {
                        if (!empty($nguoiKy)) {
                            return $query->where('nguoi_ky', 'LIKE', "%" . $nguoiKy . "%");
                        }
                    })
                    ->where(function ($query) use ($loaiVanBanId) {
                        if (!empty($loaiVanBanId)) {
                            return $query->where('loai_van_ban_id', $loaiVanBanId);
                        }
                    })
                    ->where(function ($query) use ($soVanBanId) {
                        if (!empty($soVanBanId)) {
                            return $query->where('so_van_ban_id', $soVanBanId);
                        }
                    })
                    ->where(function ($query) use ($trichYeu) {
                        if (!empty($trichYeu)) {
                            return $query->where('trich_yeu', 'LIKE', "%" . $trichYeu . "%");
                        }
                    })
                    ->where(function ($query) use ($tomTat) {
                        if (!empty($tomTat)) {
                            return $query->where('tom_tat', 'LIKE', "%" . $tomTat . "%");
                        }
                    })
                    ->where(function ($query) use ($coQuanBanHanh) {
                        if (!empty($coQuanBanHanh)) {
                            return $query->where('co_quan_ban_hanh', 'LIKE', "%" . $coQuanBanHanh . "%");
                        }
                    })
                    ->orderBy('updated_at', 'DESC')
//                    ->get();
                    ->paginate(PER_PAGE_10);
//                dd($danhSachVanBanDen);

            } else {

                $danhSachVanBanDen = VanBanDen::with([
                    'lanhDaoXemDeBiet' => function ($query) {
                        $query->select(['van_ban_den_id', 'lanh_dao_id']);
                    }])
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {
                            return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->whereIn('id', $arrIdVanBanDenDonVi)
//                    ->where(function ($query) use ($trichYeu) {
//                        if (!empty($trichYeu)) {
//                            return $query->where('trich_yeu', "LIKE", $trichYeu);
//                        }
//                    })
//                    ->where(function ($query) use ($soDen) {
//                        if (!empty($soDen)) {
//                            return $query->where('so_den', $soDen);
//                        }
//                    })
//                    ->where(function ($query) use ($date) {
//                        if (!empty($date)) {
//                            return $query->where('created_at', "LIKE", "%$date%");
//                        }
//                    })

                    ->where(function ($query) use ($searchDonVi, $arrVanBanDenIdChuTri) {
                        if (!empty($searchDonVi)) {
                            return $query->whereIn('id', $arrVanBanDenIdChuTri);
                        }
                    })
                    ->where(function ($query) use ($searchDonViPhoiHop, $arrVanBanDenIdPhoiHop) {
                        if (!empty($searchDonViPhoiHop)) {
                            return $query->whereIn('id', $arrVanBanDenIdPhoiHop);
                        }
                    })
                    ->where(function ($query) use ($soDenStart, $soDenEnd) {
                        if (!empty($soDenStart)) {
                            return $query->whereBetween('so_den', [$soDenStart, $soDenEnd]);
                        }
                    })
                    ->where(function ($query) use ($ngayDenStart, $ngayDenEnd) {
                        if (!empty($ngayDenStart)) {
                            return $query->whereBetween('ngay_nhan', [$ngayDenStart, $ngayDenEnd]);
                        }
                    })
                    ->where(function ($query) use ($ngayBanHanhStart, $ngayBanHanhEnd) {
                        if (!empty($ngayBanHanhStart)) {
                            return $query->whereBetween('ngay_ban_hanh', [$ngayBanHanhStart, $ngayBanHanhEnd]);
                        }
                    })
                    ->where(function ($query) use ($soKyHieu) {
                        if (!empty($soKyHieu)) {
                            return $query->where('so_ky_hieu', 'LIKE', "%" . $soKyHieu . "%");
                        }
                    })
                    ->where(function ($query) use ($nguoiKy) {
                        if (!empty($nguoiKy)) {
                            return $query->where('nguoi_ky', 'LIKE', "%" . $nguoiKy . "%");
                        }
                    })
                    ->where(function ($query) use ($loaiVanBanId) {
                        if (!empty($loaiVanBanId)) {
                            return $query->where('loai_van_ban_id', $loaiVanBanId);
                        }
                    })
                    ->where(function ($query) use ($soVanBanId) {
                        if (!empty($soVanBanId)) {
                            return $query->where('so_van_ban_id', $soVanBanId);
                        }
                    })
                    ->where(function ($query) use ($trichYeu) {
                        if (!empty($trichYeu)) {
                            return $query->where('trich_yeu', 'LIKE', "%" . $trichYeu . "%");
                        }
                    })
                    ->where(function ($query) use ($tomTat) {
                        if (!empty($tomTat)) {
                            return $query->where('tom_tat', 'LIKE', "%" . $tomTat . "%");
                        }
                    })
                    ->where(function ($query) use ($coQuanBanHanh) {
                        if (!empty($coQuanBanHanh)) {
                            return $query->where('co_quan_ban_hanh', 'LIKE', "%" . $coQuanBanHanh . "%");
                        }
                    })
                    ->orderBy('updated_at', 'DESC')
                    ->paginate(PER_PAGE_10);
            }


            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->arr_can_bo_nhan = $vanBanDen->getXuLyVanBanDen($type = 'get_id');
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                if (empty($active)) {
                    $vanBanDen->chuTich = $vanBanDen->checkCanBoNhan([$chuTich->id]);
                }
                $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]);
                $vanBanDen->PhoChuTich = $vanBanDen->checkCanBoNhan($danhSachPhoChuTich->pluck('id')->toArray());
                $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonVi();
                if ($active == VanBanDen::CHU_TICH_NHAN_VB) {
                    $vanBanDen->vanBanQuanTrong = $vanBanDen->checkVanBanQuanTrong();
                }
//                $vanBanDen->checkQuyenGiaHan = $vanBanDen->checkQuyenGiaHan();
                $vanBanDen->checkLuuVetVanBanDen = $vanBanDen->checkLuuVetVanBanDen ?? null;
                $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;
            $danhSachLoaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
            $danhSachSoVanBan = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
            return view('dieuhanhvanbanden::phan-loai-van-ban.da_phan_loai',
                compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                    'danhSachPhoChuTich', 'chuTich', 'active', 'danhSachDonVi', 'danhSachDonViXuLy', 'danhSachLoaiVanBan', 'danhSachSoVanBan'));
        }
    }

    public function luuLogXuLyVanBanDen($dataXuLyVanBanDen)
    {
        $luuVetVanBanDen = new LogXuLyVanBanDen();
        $luuVetVanBanDen->fill($dataXuLyVanBanDen);
        $luuVetVanBanDen->save();
    }
}
