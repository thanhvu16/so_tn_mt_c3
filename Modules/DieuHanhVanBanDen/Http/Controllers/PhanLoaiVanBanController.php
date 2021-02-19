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
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
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
    public function index()
    {
        canPermission(AllPermission::thamMuu());
        $user = auth::user();

        $danhSachVanBanDen = VanBanDen::where('lanh_dao_tham_muu', $user->id)
            ->with([
                'vanBanDenFile' => function ($query) {
                    return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                }
            ])
            ->whereNull('trinh_tu_nhan_van_ban')
            ->paginate(10);

        if (count($danhSachVanBanDen) > 0) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * 10 + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        $chuTich = User::role('chủ tịch')->select('id', 'ho_ten', 'don_vi_id')->first();

        $danhSachPhoChuTich = User::role('phó chủ tịch')
            ->where('don_vi_id', $chuTich->don_vi_id)
            ->select('id', 'ho_ten')->get();

        $danhSachDonVi = DonVi::whereNull('deleted_at')
            ->where('parent_id', DonVi::NO_PARENT_ID)
            ->select('id', 'ten_don_vi')
            ->get();


        return view('dieuhanhvanbanden::phan-loai-van-ban.index',
            compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                'danhSachPhoChuTich', 'chuTich', 'danhSachDonVi'));
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
        $tomTatVanBan = $data['tom_tat'] ?? null;
        $noiDungChuTich = $data['noi_dung_chu_tich'] ?? null;
        $noiDungPhoChuTich = $data['noi_dung_pho_chu_tich'] ?? null;
        $canBoChiDao = null;
        $type = $request->get('type') ?? null;
        $statusTraiLai = $request->get('van_ban_tra_lai') ?? null;
        $lanhDaoDuHopId = $data['lanh_dao_du_hop_id'] ?? null;
        $danhSachDonViChuTriIds = $data['don_vi_chu_tri_id'] ?? null;
        $danhSachDonViPhoiHopIds = $data['don_vi_phoi_hop_id'] ?? null;
        $textDonViChuTri = $data['don_vi_chu_tri'] ?? null;
        $donViDuHop = $data['don_vi_du_hop'] ?? null;

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        if (isset($vanBanDenIds) && count($vanBanDenIds) > 0) {
            try {
                DB::beginTransaction();

                foreach ($vanBanDenIds as $vanBanDenId) {
                    $checkLogXuLyVanBanDen = LogXuLyVanBanDen::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_chuyen_id' => $currentUser->id
                    ])->orderBy('id', 'DESC')->first();


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
                            $vanBanDen->trinh_tu_nhan_van_ban = 1;
                            $vanBanDen->save();
                        }

                        if (!empty($arrPhoChuTich[$vanBanDenId]) && empty($arrChuTich[$vanBanDenId])) {
                            $vanBanDen->trinh_tu_nhan_van_ban = 2;
                            $vanBanDen->save();
                        }

                        if (empty($arrPhoChuTich[$vanBanDenId]) && empty($arrChuTich[$vanBanDenId])) {
                            $vanBanDen->trinh_tu_nhan_van_ban = 3;
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
                        $luuVetVanBanDen = new LogXuLyVanBanDen();
                        $luuVetVanBanDen->fill($dataXuLyVanBanDen);
                        $luuVetVanBanDen->save();

                        $quyenGiaHan = null;
                    }

                    //pho chu tich
                    if (!empty($arrPhoChuTich[$vanBanDenId])) {

                        if (empty($arrChuTich[$vanBanDenId])) {
                            $quyenGiaHan = 1;
                        }

                        $dataXuLyVanBanDen = [
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
                            $xuLyVanBanDen->fill($dataXuLyVanBanDen);
                            $xuLyVanBanDen->save();
                        }

                        // luu vet van ban den
                        $luuVetVanBanDen = new LogXuLyVanBanDen();
                        $luuVetVanBanDen->fill($dataXuLyVanBanDen);
                        $luuVetVanBanDen->save();
                        $quyenGiaHan = null;
                    }

                    //luu can bo xem de biet
                    if (!empty($arrLanhDaoXemDeBiet[$vanBanDenId])) {
                        LanhDaoXemDeBiet::saveLanhDaoXemDeBiet($arrLanhDaoXemDeBiet[$vanBanDenId],
                            $vanBanDenId);
                    }

                    DonViChuTri::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'parent_don_vi_id' => null,
                        'hoan_thanh' => null
                    ])->delete();

                    if (!empty($danhSachDonViChuTriIds) && !empty($danhSachDonViChuTriIds[$vanBanDenId])){

                        DonViChuTri::luuDonViXuLyVanBan($vanBanDenId, $textDonViChuTri, $danhSachDonViChuTriIds, $chuyenVanBanXuongDonVi);
                    }

                    // luu don vi phoi hop
                    DonViPhoiHop::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'chuyen_tiep' => null,
                        'parent_don_vi_id' => null,
                        'hoan_thanh' => null
                    ])->delete();
                    if (isset($danhSachDonViPhoiHopIds[$vanBanDenId])) {
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

        $active = null;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;
        $date = $request->get('date') ?? null;
        $chuTich = User::role('chủ tịch')->select('id', 'ho_ten')->first();
        $donVi = $user->donVi;
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {

            if ($user->hasRole(TRUONG_BAN)) {
                $active = 3;
            }

            if ($user->hasRole(PHO_TRUONG_BAN)) {
                $active = 4;
            }

            if ($user->hasRole(CHUYEN_VIEN)) {
                $active = 5;
            }
            if ($user->hasRole(CHU_TICH)) {
                $active = 8;
            }
            if ($user->hasRole(PHO_CHUC_TICH)) {
                $active = 9;
            }

            $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
                ->select('id')->first();

            $donViChuTri = DonViChuTri::where(function ($query) use ($user) {
                return $query->where('don_vi_id', $user->don_vi_id)
                            ->orWhere('parent_don_vi_id', $user->don_vi_id);
                })
                ->where('can_bo_chuyen_id', $user->id)
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $danhSachVanBanDen = VanBanDen::with(['checkLuuVetVanBanDen',
                'donViCapXaChuTri',
                'DonViCapXaPhoiHop' => function ($query) {
                    return $query->select('id', 'don_vi_id', 'van_ban_den_id');
                }
            ])
                ->whereIn('id', $arrVanBanDenId)
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
                        return $query->where('updated_at', "LIKE", $date);
                    }
                })
                ->paginate(PER_PAGE_10);

            $danhSachPhoChuTich = User::role(PHO_CHUC_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $user->don_vi_id)
                ->select('id', 'ho_ten')
                ->get();

            $chuTich = User::role(CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $user->don_vi_id)
                ->select('id', 'ho_ten')
                ->first();

            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('parent_id', $user->don_vi_id)
                ->select('id', 'ten_don_vi')
                ->get();


            if (!empty($danhSachVanBanDen)) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->giaHanLanhDao = $vanBanDen->getGiaHanLanhDao();
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    $vanBanDen->phoChuTich = $vanBanDen->getChuyenVienThucHien($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
                    $vanBanDen->lanhDaoXemDeBiet = $vanBanDen->lanhDaoXemDeBiet ?? null;
                    $vanBanDen->vanBanQuanTrong = $vanBanDen->checkVanBanQuanTrong();
                    $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]) ?? null;
                    $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonViCapXa();
                }
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            return view('dieuhanhvanbanden::don-vi-cap-xa.lanh-dao.da_chi_dao',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'danhSachDonVi',
                    'active', 'loaiVanBanGiayMoi', 'order', 'chuTich'));

        } else {

            $danhSachPhoChuTich = User::role(PHO_CHUC_TICH)->select(['id', 'ho_ten'])->get();

            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('parent_id', DonVi::NO_PARENT_ID)
                ->select(['id', 'ten_don_vi'])
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
                    ->paginate(PER_PAGE_10);

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

            $danhSachVanBanDen = VanBanDen::with([
                'lanhDaoXemDeBiet' => function ($query) {
                    $query->select(['van_ban_den_id', 'lanh_dao_id']);
                }])
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
                        return $query->where('created_at', "LIKE", "%$date%");
                    }
                })
                ->paginate(PER_PAGE_10);

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

            return view('dieuhanhvanbanden::phan-loai-van-ban.da_phan_loai',
                compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                    'danhSachPhoChuTich', 'chuTich', 'active', 'danhSachDonVi'));
        }
    }
}
