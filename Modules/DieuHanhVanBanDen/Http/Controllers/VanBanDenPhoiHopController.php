<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\PhoiHopGiaiQuyet;
use Modules\DieuHanhVanBanDen\Entities\PhoiHopGiaiQuyetFile;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Auth, DB;

class VanBanDenPhoiHopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = auth::user();
        $donVi = $currentUser->donVi;
        $trichYeu = $request->get('trich_yeu') ?? null;
        $soDen = $request->get('so_den') ?? null;
        $date = $request->get('date') ?? null;

        $trinhTuNhanVanBan = null;

        $chuyenTiep = $request->get('chuyen_tiep') ?? null;

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($currentUser->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }

        $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $currentUser->don_vi_id)
            ->where('can_bo_nhan_id', $currentUser->id)
            ->where(function ($query) use ($chuyenTiep) {
                if (!empty($chuyenTiep)) {
                    return $query->where('chuyen_tiep', $chuyenTiep);
                } else {
                    return $query->whereNull('chuyen_tiep');
                }
            })
            ->whereNull('hoan_thanh')
            ->whereNotNull('vao_so_van_ban')
            ->select('id', 'van_ban_den_id')
            ->get();


        $arrVanBanDenId = $donViPhoiHop->pluck('van_ban_den_id')->toArray();

        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN];
        $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
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

        $danhSachPhoChuTich = User::role(PHO_CHUC_TICH)
            ->where('trang_thai', ACTIVE)
            ->where('don_vi_id', $currentUser->don_vi_id)
            ->select('id', 'ho_ten')
            ->get();

        $truongPhong = User::role(TRUONG_BAN)
            ->where('don_vi_id', $currentUser->don_vi_id)
            ->select('id', 'ho_ten')
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->first();

        $danhSachVanBanDen = VanBanDen::with([
                'xuLyVanBanDen' => function ($query) {
                    return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
                },
                'donViChuTri' => function ($query) {
                    return $query->select('van_ban_den_id', 'can_bo_nhan_id');
                }
            ])
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
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

        if (!empty($danhSachVanBanDen)) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild(VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP) ?? null;

                // chu tich
                $vanBanDen->phoChuTich = $vanBanDen->donViPhoiHopVanBan($danhSachPhoChuTich->pluck('id')->toArray());
                $vanBanDen->lanhDaoXemDeBiet = $vanBanDen->lanhDaoXemDeBiet ?? null;

                if ($currentUser->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {
                    $vanBanDen->phoPhong = $vanBanDen->donViPhoiHopVanBan($danhSachPhoPhong->pluck('id')->toArray());
                    $vanBanDen->chuyenVien = $vanBanDen->donViPhoiHopVanBan($danhSachChuyenVien->pluck('id')->toArray());
                    $vanBanDen->truongPhong = $vanBanDen->donViPhoiHopVanBan([$currentUser->id]);
                    $vanBanDen->getChuyenVienPhoiHop = $vanBanDen->getChuyenVienPhoiHop() ?? null;
                }

            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        if ($currentUser->hasrole(CHUYEN_VIEN)) {

            return view('dieuhanhvanbanden::don-vi-phoi-hop.chuyen-vien',
                compact('danhSachVanBanDen', 'order'));

        }

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {

            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('parent_id', $currentUser->don_vi_id)
                ->select('id', 'ten_don_vi')
                ->get();

            $chuTich = User::role(CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $currentUser->don_vi_id)
                ->select('id', 'ho_ten')
                ->first();

            // view da chi dao
            if (!empty($chuyenTiep)) {
                return view('dieuhanhvanbanden::don-vi-phoi-hop.cap_xa.da_chi_dao', compact('danhSachVanBanDen',
                    'danhSachPhoPhong', 'danhSachPhoChuTich', 'truongPhong', 'donVi',
                    'danhSachChuyenVien', 'order', 'trinhTuNhanVanBan', 'chuTich', 'danhSachDonVi'));
            }

            return view('dieuhanhvanbanden::don-vi-phoi-hop.cap_xa.index', compact('danhSachVanBanDen',
                'danhSachPhoPhong', 'danhSachPhoChuTich', 'truongPhong', 'donVi',
                'danhSachChuyenVien', 'order', 'trinhTuNhanVanBan', 'chuTich', 'danhSachDonVi'));
        }

        //view da chi dao
        if (!empty($chuyenTiep)) {
            return view('dieuhanhvanbanden::don-vi-phoi-hop.da_chi_dao', compact('danhSachVanBanDen',
                'danhSachPhoPhong', 'danhSachPhoChuTich', 'truongPhong',
                'danhSachChuyenVien', 'order', 'trinhTuNhanVanBan'));
        }

        return view('dieuhanhvanbanden::don-vi-phoi-hop.index', compact('danhSachVanBanDen',
            'danhSachPhoPhong', 'danhSachPhoChuTich', 'truongPhong',
            'danhSachChuyenVien', 'order', 'trinhTuNhanVanBan'));
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

        $vanBanDenDonViIds = json_decode($data['van_ban_den_id']);
        $danhSachPhoChuTichIds = $data['pho_chu_tich_id'] ?? null;
        $danhSachTruongPhongIds = $data['truong_phong_id'] ?? null;
        $danhSachPhoPhongIds = $data['pho_phong_id'] ?? null;
        $danhSachChuyenVienIds = $data['chuyen_vien_id'] ?? null;
        $textnoidungPhoChuTich = $data['noi_dung_pho_chu_tich'] ?? null;
        $textnoidungTruongPhong = $data['noi_dung_truong_phong'] ?? null;
        $textnoidungPhoPhong = $data['noi_dung_pho_phong'] ?? null;
        $textNoiDungChuyenVien = $data['noi_dung_chuyen_vien'] ?? null;
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        // them moi
        $textDonViPhoiHop = $data['don_vi_phoi_hop'] ?? null;
        $danhSachDonViPhoiHopIds = $data['don_vi_phoi_hop_id'] ?? null;
        $arrLanhDaoXemDeBiet = $data['lanh_dao_xem_de_biet'] ?? null;
        $arrChuyenVienPhoiHopIds = $data['chuyen_vien_phoi_hop_id'] ?? null;

        if (isset($vanBanDenDonViIds) && count($vanBanDenDonViIds) > 0) {
            try {
                DB::beginTransaction();

                foreach ($vanBanDenDonViIds as $vanBanDenId) {

                    $donViPhoiHop = DonViPhoiHop::where('van_ban_den_id', $vanBanDenId)
                        ->where('can_bo_nhan_id', $currentUser->id)
                        ->whereNull('hoan_thanh')->first();

                    if ($donViPhoiHop) {
                        $donViPhoiHop->chuyen_tiep = DonViPhoiHop::CHUYEN_TIEP;
                        $donViPhoiHop->save();

                        DonViPhoiHop::where('van_ban_den_id', $vanBanDenId)
                            ->where('id', '>', $donViPhoiHop->id)
                            ->whereNull('hoan_thanh')
                            ->delete();
                    }
                    $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();

                    if (isset($danhSachPhoChuTichIds) && !empty($danhSachPhoChuTichIds[$vanBanDenId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoChuTichIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'noi_dung' => $textnoidungPhoChuTich[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanPhoChuTich = new DonViPhoiHop();
                        $chuyenNhanVanBanPhoChuTich->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanPhoChuTich->save();
                    }

                    // luu don vi chu tri tu cap xa
                    if ($currentUser->hasRole([CHU_TICH, PHO_CHUC_TICH])) {
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
                        ])->where('parent_don_vi_id', auth::user()->don_vi_id)->delete();

                        if (!empty($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                            DonViPhoiHop::luuDonViPhoiHopCapXa($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);

                            // luu vet van ban den
                            $this->luuLogXuLyVanBanDen($dataLuuDonViPhoiHop);
                        }

                        //luu can bo xem de biet
                        LanhDaoXemDeBiet::where('van_ban_den_id', $vanBanDenId)
                            ->where('don_vi_id', auth::user()->don_vi_id)
                            ->delete();

                        if (!empty($arrLanhDaoXemDeBiet[$vanBanDenId])) {
                            LanhDaoXemDeBiet::saveLanhDaoXemDeBiet($arrLanhDaoXemDeBiet[$vanBanDenId], $vanBanDenId, $type = 1);
                        }
                    }


                    if (isset($danhSachTruongPhongIds) && !empty($danhSachTruongPhongIds[$vanBanDenId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachTruongPhongIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'parent_don_vi_id' => $donViPhoiHop->parent_don_vi_id ?? null,
                            'noi_dung' => $textnoidungTruongPhong[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanTruongPhong = new DonViPhoiHop();
                        $chuyenNhanVanBanTruongPhong->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanTruongPhong->save();

                        // luu vet van ban den
                        $this->luuLogXuLyVanBanDen($dataChuyenNhanVanBanDonVi);
                    }

                    //chuyen nhan van ban don vi
                    if (isset($danhSachPhoPhongIds) && !empty($danhSachPhoPhongIds[$vanBanDenId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoPhongIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'parent_don_vi_id' => $donViPhoiHop->parent_don_vi_id ?? null,
                            'noi_dung' => $textnoidungPhoPhong[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanPhoPhong = new DonViPhoiHop();
                        $chuyenNhanVanBanPhoPhong->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanPhoPhong->save();

                        // luu log dh van ban den truong phong
                        // luu vet van ban den
                        $this->luuLogXuLyVanBanDen($dataChuyenNhanVanBanDonVi);
                    }

                    if (isset($danhSachChuyenVienIds) && !empty($danhSachChuyenVienIds[$vanBanDenId])) {
                        //save chuyen vien thuc hien
                        $dataChuyenNhanVanBanChuyenVien = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachChuyenVienIds[$vanBanDenId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'parent_don_vi_id' => $donViPhoiHop->parent_don_vi_id ?? null,
                            'noi_dung' => $textNoiDungChuyenVien[$vanBanDenId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanChuyenVienDonVi = new DonViPhoiHop();
                        $chuyenNhanVanBanChuyenVienDonVi->fill($dataChuyenNhanVanBanChuyenVien);
                        $chuyenNhanVanBanChuyenVienDonVi->save();

                        // luu vet van ban den
                        $this->luuLogXuLyVanBanDen($dataChuyenNhanVanBanChuyenVien);
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

                    // save thanh phan du hop
                    ThanhPhanDuHop::store($giayMoi, $vanBanDen, [$danhSachPhoChuTichIds[$vanBanDenId], $danhSachTruongPhongIds[$vanBanDenId],
                        $danhSachPhoPhongIds[$vanBanDenId], $danhSachChuyenVienIds[$vanBanDenId]], null, auth::user()->don_vi_id);

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

    public function donViPhoiHopDaXuLy(Request $request)
    {
        $currentUser = auth::user();
        $donViPhoiHop = DonViPhoiHop::where('can_bo_nhan_id', $currentUser->id)
            ->where('hoan_thanh', DonViPhoiHop::HOAN_THANH_VB)
            ->select('id', 'van_ban_den_id')
            ->get();

        $arrVanBanDenId = $donViPhoiHop->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with([
            'xuLyVanBanDen' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
            },
            'donViChuTri' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_nhan_id');
            }
        ])
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

        if (!empty($danhSachVanBanDen)) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild(VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP) ?? null;
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        return view('dieuhanhvanbanden::don-vi-phoi-hop.hoan_thanh', compact('order', 'danhSachVanBanDen'));
    }

    public function chuyenVienPhoiHop(Request $request)
    {
        $currentUser = auth::user();
        $status = $request->get('status') ?? null;

        $chuyenVienPhoiHop = ChuyenVienPhoiHop::where('can_bo_nhan_id', $currentUser->id)
            ->where(function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->select('id', 'van_ban_den_id')
            ->get();

        $arrIdVanBanDen = $chuyenVienPhoiHop->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with([
            'xuLyVanBanDen' => function ($query) {
                return $query->select('id', 'van_ban_den_id', 'can_bo_nhan_id');
            },
            'donViChuTri' => function ($query) {
                return $query->select('van_ban_den_id', 'can_bo_nhan_id');
            }
        ])
            ->whereIn('id', $arrIdVanBanDen)
            ->orderBy('updated_at', 'DESC')
            ->paginate(PER_PAGE);


        if (!empty($danhSachVanBanDen)) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        return view('dieuhanhvanbanden::chuyen-vien-phoi-hop.index',
            compact('order', 'danhSachVanBanDen'));
    }

    public function phoiHopGiaiQuyet(Request $request)
    {
        $data = $request->all();
        $donVi = auth::user()->donVi;
        $data['user_id'] = auth::user()->id;
        $data['don_vi_id'] = auth::user()->don_vi_id;
        $data['parent_don_vi_id'] = $donVi->parent_id ?? $donVi->id;
        $type = $request->get('type');

        if (!empty($type)) {
            $data['status'] = PhoiHopGiaiQuyet::GIAI_QUYET_CHUYEN_VIEN_PHOI_HOP;
        }

        //tao giai quyet vb don vi
        $phoiHopGiaiQuyet = new PhoiHopGiaiQuyet();
        $phoiHopGiaiQuyet->fill($data);
        $phoiHopGiaiQuyet->save();

        if (empty($type)) {
            //update chuyen nhan van ban don vi phoi hop
            $donViPhoiHop = DonViPhoiHop::where([
                'van_ban_den_id' => $data['van_ban_den_id'],
                'can_bo_nhan_id' => auth::user()->id
            ])->first();

            if ($donViPhoiHop) {
                $donViPhoiHop->hoan_thanh = DonViPhoiHop::HOAN_THANH_VB;
                $donViPhoiHop->save();
            }

            // truong phong hoac pho phong giai quyet
            if (auth::user()->hasRole(TRUONG_PHONG) || auth::user()->hasrole(PHO_PHONG)) {
                DonViPhoiHop::where('van_ban_den_id', $data['van_ban_den_id'])
                    ->where('don_vi_id', auth::user()->don_vi_id)
                    ->where('id', '>', $donViPhoiHop->id)->delete();
            }

            // don vi phoi hop
            DonViPhoiHop::where('van_ban_den_id', $data['van_ban_den_id'])
                ->where('don_vi_id', auth::user()->don_vi_id)
                ->update(['hoan_thanh' => DonViPhoiHop::HOAN_THANH_VB]);

            //update chuyen nhan van ban don vi co don vi cha
            $parentDonViId = auth::user()->donVi->parent_id;
            DonViPhoiHop::where('van_ban_den_id', $data['van_ban_den_id'])
                ->where('don_vi_id', $parentDonViId)
                ->update(['hoan_thanh' => DonViPhoiHop::HOAN_THANH_VB]);
        }

        if (!empty($type)) {
            $chuyenVienPhoiHop = ChuyenVienPhoiHop::where([
                'van_ban_den_id' => $data['van_ban_den_id'],
                'can_bo_nhan_id' => auth::user()->id,
            ])->first();

            if ($chuyenVienPhoiHop) {
                $chuyenVienPhoiHop->status = ChuyenVienPhoiHop::CHUYEN_VIEN_GIAI_QUYET;
                $chuyenVienPhoiHop->save();
            }
        }

        //upload file
        $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
        $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

        if ($multiFiles && count($multiFiles) > 0) {

            PhoiHopGiaiQuyetFile::dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $phoiHopGiaiQuyet->id);
        }

        if (!empty($type)) {

            return redirect()->route('van_ban_den_chuyen_vien.da_xu_ly', 'status=1')->with('success', 'Đã phối hợp giải quyết.');
        }

        return redirect()->route('van-ban-den-phoi-hop.da-xu-ly')->with('success', 'Đã phối hợp giải quyết.');
    }

    public function luuLogXuLyVanBanDen($dataChuyenNhanVanBanDonVi)
    {
        // luu log dh van ban den truong phong
        $luuVetVanBanDen = new LogXuLyVanBanDen();
        $luuVetVanBanDen->fill($dataChuyenNhanVanBanDonVi);
        $luuVetVanBanDen->save();
    }
}
