<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LichCongTac;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
use Modules\VanBanDen\Entities\VanBanDen;
use Auth, DB;

class VanBanLanhDaoXuLyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = auth::user();
        $active = null;
        $donVi = $user->donVi;
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();
        $trinhTuNhanVanBan = null;

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {

            if ($user->hasRole(CHU_TICH)) {
                $trinhTuNhanVanBan = 8;
            }

            if ($user->hasRole(PHO_CHUC_TICH)) {
                $trinhTuNhanVanBan = 9;
            }

            if ($user->hasRole(TRUONG_BAN)) {
                $trinhTuNhanVanBan = 3;
            }
            // chu tich xa nhan van ban
            $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->select('id', 'van_ban_den_id')
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $danhSachVanBanDen = VanBanDen::whereIn('id', $arrVanBanDenId)
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->paginate(10);


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

            $truongBan = User::role(TRUONG_BAN)
                ->where('don_vi_id', $user->don_vi_id)
                ->select('id', 'ho_ten')
                ->where('trang_thai', ACTIVE)
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')->first();

            $danhSachPhoPhong = User::role(PHO_TRUONG_BAN)
                ->where('don_vi_id', $user->don_vi_id)
                ->select('id', 'ho_ten')
                ->where('trang_thai', ACTIVE)
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')->get();


            $danhSachChuyenVien = User::role(CHUYEN_VIEN)
                ->where('don_vi_id', $user->don_vi_id)
                ->where('trang_thai', ACTIVE)
                ->select('id', 'ho_ten')
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')->get();

            if (!empty($danhSachVanBanDen)) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
                    $vanBanDen->chuTich = $vanBanDen->getChuyenVienThucHien([$chuTich->id]);
                    $vanBanDen->phoChuTich = $vanBanDen->getChuyenVienThucHien($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonVi();
                    $vanBanDen->truongPhong = $vanBanDen->getChuyenVienThucHien([$truongBan->id]);
                    $vanBanDen->phoPhong = $vanBanDen->getChuyenVienThucHien($danhSachPhoPhong->pluck('id')->toArray());
                    $vanBanDen->chuyenVien = $vanBanDen->getChuyenVienThucHien($danhSachChuyenVien->pluck('id')->toArray());
                    $vanBanDen->getChuyenVienPhoiHop = $vanBanDen->getChuyenVienPhoiHop() ?? null;
                    $vanBanDen->lanhDaoXemDeBiet = $vanBanDen->lanhDaoXemDeBiet ?? null;
//
                }
            }

            return view('dieuhanhvanbanden::don-vi-cap-xa.index',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'truongBan',
                    'danhSachPhoPhong', 'danhSachChuyenVien', 'trinhTuNhanVanBan', 'loaiVanBanGiayMoi'));

        } else {
            // chu tich huyen nhan van ban
            if ($user->hasRole(CHU_TICH)) {
                $active = VanBanDen::CHU_TICH_NHAN_VB;
            } else {
                $active = VanBanDen::PHO_CHU_TICH_NHAN_VB;
            }
            $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $user->id)
                ->whereNull('status')
                ->whereNull('hoan_thanh')
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrIdVanBanDenDonVi = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

            $danhSachVanBanDen = VanBanDen::with([
                'lanhDaoXemDeBiet' => function ($query) {
                    $query->select(['van_ban_den_id', 'lanh_dao_id']);
                },
                'checkDonViPhoiHop' => function ($query) {
                    $query->select(['id', 'van_ban_den_id', 'don_vi_id']);
                },
                'checkLuuVetVanBanDen',
                'vanBanTraLai',
                'vanBanDenFile' => function ($query) {
                    return $query->select('id', 'vb_den_id', 'ten_file', 'duong_dan');
                }
                ])
                ->whereIn('id', $arrIdVanBanDenDonVi)
                ->where('trinh_tu_nhan_van_ban', $active)
                ->paginate(PER_PAGE_10);

            $chuTich = User::role(CHU_TICH)->where('trang_thai', ACTIVE)
                ->select('id', 'ho_ten')
                ->first();

            $danhSachPhoChuTich = User::role(PHO_CHUC_TICH)
                ->where('trang_thai', ACTIVE)
                ->select('id', 'ho_ten')
                ->get();

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('id', '!=', $user->don_vi_id)
                ->select('id', 'ten_don_vi')
                ->get();

            if (count($danhSachVanBanDen) > 0) {
                foreach ($danhSachVanBanDen as $vanBanDen) {
                    $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    $vanBanDen->giaHanXuLy = $vanBanDen->getGiaHanXuLy() ?? null;
                    $vanBanDen->vanBanTraLai = $vanBanDen->vanBanTraLai ?? null;
                    $vanBanDen->checkDonViChuTri = $vanBanDen->checkDonViChuTri ?? null;
                    $vanBanDen->lichCongTacDonVi = $vanBanDen->checkLichCongTacDonVi();
//                    $vanBanDen->checkQuyenGiaHan = $vanBanDen->checkQuyenGiaHan();
//                    $vanBanDen->chuTich = $vanBanDen->checkCanBoNhan([$chuTich->id]) ?? null;
                    $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]) ?? null;
                    $vanBanDen->PhoChuTich = $vanBanDen->checkCanBoNhan($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                    if ($user->hasRole(PHO_CHUC_TICH)) {
                        $vanBanDen->checkVanBanQuaChuTich = $vanBanDen->checkVanBanQuaChuTich();
                    }
                }
            }

            if ($active == 2) {
                return view('dieuhanhvanbanden::van-ban-lanh-dao-xu-ly.pho_chu_tich',
                    compact('danhSachVanBanDen', 'order', 'danhSachDonVi', 'danhSachPhoChuTich', 'active', 'loaiVanBanGiayMoi'));
            }

            return view('dieuhanhvanbanden::van-ban-lanh-dao-xu-ly.index',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'chuTich', 'loaiVanBanGiayMoi',
                    'order', 'active', 'danhSachDonVi'));
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
        $arrPhoChuTich = $data['pho_chu_tich_id'] ?? null;
        $arrLanhDaoXemDeBiet = $data['lanh_dao_xem_de_biet'] ?? null;
        $noiDungPhoChuTich = $data['noi_dung_pho_chu_tich'] ?? null;
        $type = $request->get('type') ?? null;
        $statusTraiLai = $request->get('van-ban_tra_lai') ?? null;
        $lanhDaoDuHopId = $data['lanh_dao_du_hop_id'] ?? null;
        $dataHanXuLy = $data['han_xu_ly'] ?? null;
        $dataVanBanQuanTrong = $data['van_ban_quan_trong'] ?? null;
        $danhSachDonViChuTriIds = $data['don_vi_chu_tri_id'] ?? null;
        $danhSachDonViPhoiHopIds = $data['don_vi_phoi_hop_id'] ?? null;
        $textDonViChuTri = $data['don_vi_chu_tri'] ?? null;
        $textDonViPhoiHop = $data['don_vi_phoi_hop'] ?? null;
        $donViDuHop = $data['don_vi_du_hop'] ?? null;

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        try {
            DB::beginTransaction();
            if (isset($vanBanDenIds) && count($vanBanDenIds) > 0) {
                foreach ($vanBanDenIds as $vanBanDenId) {

                    $vanBanDen = VanBanDen::findOrFail($vanBanDenId);

                    $checkLogXuLyVanBanDen = LogXuLyVanBanDen::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_chuyen_id' => $currentUser->id
                    ])->orderBy('id', 'DESC')->first();

                    if (isset($type) && $type == 'update' && empty($checkLogXuLyVanBanDen)) {

                        return redirect()->back()->with('danger', 'Văn bản này đang xử lý, không thể cập nhật.');
                    }

                    //check van ban tra lai
                    $vanBanTraLai = VanBanTraLai::where('van_ban_den_id', $vanBanDenId)
                        ->where('can_bo_nhan_id', $currentUser->id)
                        ->whereNull('status')->first();

                    if ($vanBanTraLai) {
                        $vanBanTraLai->status = VanBanTraLai::STATUS_GIAI_QUYET;
                        $vanBanTraLai->save();
                    }

                    //check xu ly van ban den
                    $checkXuLyVanBanDen = XuLyVanBanDen::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_nhan_id' => $currentUser->id
                    ])
                        ->whereNull('status')
                        ->first();

                    if ($checkXuLyVanBanDen) {
                        XuLyVanBanDen::where('van_ban_den_id', $vanBanDenId)
                            ->whereNull('status')->where('id', '>', $checkXuLyVanBanDen->id)->delete();
                    }

                    //han xu ly
//                    if (isset($dataHanXuLy[$vanBanDenId]) && $vanBanDen->han_xu_ly != $dataHanXuLy[$vanBanDenId]) {
//                        $checkXuLyVanBanDen->han_xu_ly = $dataHanXuLy[$vanBanDenId];
//                        $checkXuLyVanBanDen->save();
//                        $vanBanDen->han_xu_ly = $dataHanXuLy[$vanBanDenId];
//                        $vanBanDen->save();
//                    }

                    // van ban quan trong
                    VanBanQuanTrong::where([
                        'user_id' => $currentUser->id,
                        'van_ban_den_id' => $vanBanDenId
                    ])->delete();

                    if (isset($dataVanBanQuanTrong[$vanBanDenId]) && !empty($dataVanBanQuanTrong[$vanBanDenId])) {
                        $dataVanBanQuanTrong = [
                            'van_ban_den_id' => $vanBanDenId,
                            'user_id' => $currentUser->id
                        ];

                        $vanBanQuanTrong = VanBanQuanTrong::where([
                            'user_id' => $currentUser->id,
                            'van_ban_den_id' => $vanBanDenId
                        ])->first();

                        if (empty($vanBanQuanTrong)) {
                            $vanBanQuanTrong = new VanBanQuanTrong();
                            $vanBanQuanTrong->fill($dataVanBanQuanTrong);
                            $vanBanQuanTrong->save();
                        }
                    }

                    // check lanh dao du hop
                    if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {
                        if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                            LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], $donViDuHop[$vanBanDenId], $danhSachDonViChuTriIds[$vanBanDenId]);
                        }
                    }

                    // chu tich
                    if ($currentUser->hasRole('chủ tịch')) {

                        if (!empty($arrPhoChuTich[$vanBanDenId])) {

                            $dataXuLyVanBanDen = [
                                'van_ban_den_id' => $vanBanDenId,
                                'can_bo_chuyen_id' => $currentUser->id,
                                'can_bo_nhan_id' => $arrPhoChuTich[$vanBanDenId],
                                'noi_dung' => $noiDungPhoChuTich[$vanBanDenId],
                                'tom_tat' => $checkXuLyVanBanDen->tom_tat ?? null,
                                'user_id' => $currentUser->id,
                                'han_xu_ly' => $dataHanXuLy[$vanBanDenId] ?? null
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
                        LanhDaoXemDeBiet::where('van_ban_den_id', $vanBanDenId)
                            ->whereNull('don_vi_id')
                            ->delete();

                        if (!empty($arrLanhDaoXemDeBiet[$vanBanDenId])) {
                            LanhDaoXemDeBiet::saveLanhDaoXemDeBiet($arrLanhDaoXemDeBiet[$vanBanDenId],
                                $vanBanDenId, $type = null);
                        }

                        // active trinh tu nhan van ban
                        if (!empty($arrPhoChuTich[$vanBanDenId])) {
                            $vanBanDen->trinh_tu_nhan_van_ban = 2;
                            $vanBanDen->save();
                        }

                        $vanBanChuyenXuongDonVi = null;

                        if (empty($arrPhoChuTich[$vanBanDenId]) && !empty($danhSachDonViChuTriIds[$vanBanDenId])) {
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                            $vanBanDen->save();
                            $vanBanChuyenXuongDonVi = DonViChuTri::VB_DA_CHUYEN_XUONG_DON_VI;
                        }

                        DonViChuTri::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'hoan_thanh' => null
                        ])->delete();

                        // don vi chu tri
                        if (!empty($danhSachDonViChuTriIds) && !empty($danhSachDonViChuTriIds[$vanBanDenId])) {

                            DonViChuTri::luuDonViXuLyVanBan($vanBanDenId, $textDonViChuTri, $danhSachDonViChuTriIds, $vanBanChuyenXuongDonVi);
                            // lưu phòng chuẩn bị
                        }

                        // luu don vi phoi hop
                        DonViPhoiHop::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'chuyen_tiep' => null,
                            'hoan_thanh' => null
                        ])->delete();

                        if (isset($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                            DonViPhoiHop::luuDonViPhoiHop($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);

                            // luu vet van ban den
                            $dataLuuDonViPhoiHop = [
                                'van_ban_den_id' => $vanBanDenId,
                                'can_bo_chuyen_id' => $currentUser->id,
                                'can_bo_nhan_id' => $nguoiDung->id ?? null,
                                'noi_dung' => $textDonViPhoiHop[$vanBanDenId],
                                'don_vi_phoi_hop_id' => isset($danhSachDonViPhoiHopIds[$vanBanDenId]) ? \GuzzleHttp\json_encode($danhSachDonViPhoiHopIds[$vanBanDenId]) : null,
                                'user_id' => $currentUser->id
                            ];
                            $luuVetVanBanDen = new LogXuLyVanBanDen();
                            $luuVetVanBanDen->fill($dataLuuDonViPhoiHop);
                            $luuVetVanBanDen->save();
                        }
                    }

                }

                DB::commit();

                return redirect()->back()->with('success', 'Đã gửi thành công.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

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

    public function getListDonVi($id, Request $request)
    {
        if ($request->ajax()) {

            $currentUser = auth::user();

            $donVi = $currentUser->donVi;

            $danhSachDonViChutri = DonVi::where('id', '!=', $currentUser->don_vi_id)
                ->whereNotIn('id', json_decode($id))
                ->whereNull('deleted_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $danhSachDonViChutri
            ]);
        }
    }

    public function saveDonViChuTri(Request $request)
    {
        $data = $request->all();
        $currentUser = auth::user();

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();

        $vanBanDenDonViIds = json_decode($data['van_ban_den_id']);
        $danhSachDonViChuTriIds = $data['don_vi_chu_tri_id'] ?? null;
        $danhSachDonViPhoiHopIds = $data['don_vi_phoi_hop_id'] ?? null;
        $textDonViChuTri = $data['don_vi_chu_tri'] ?? null;
        $textDonViPhoiHop = $data['don_vi_phoi_hop'] ?? null;
        $dataVanBanQuanTrong = $data['van_ban_quan_trong'] ?? null;
        $lanhDaoDuHopId = $data['lanh_dao_du_hop_id'] ?? null;
        $dataHanXuLy = $data['han_xu_ly'] ?? null;
        $donViDuHop = $data['don_vi_du_hop'] ?? null;

        if (count($vanBanDenDonViIds) > 0) {
            try {
                DB::beginTranSaction();

                foreach ($vanBanDenDonViIds as $vanBanDenId) {

                    $donVi = DonVi::where('id', $danhSachDonViChuTriIds[$vanBanDenId])->first();
                    $vanBanDen = VanBanDen::findOrFail($vanBanDenId);

                    if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                        // chu tich cap xa nhan van ban
                        $role = [CHU_TICH];

                        $nguoiDung = User::where('trang_thai', ACTIVE)
                            ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                            ->whereHas('roles', function ($query) use ($role) {
                                return $query->whereIn('name', $role);
                            })
                            ->select('id', 'don_vi_id')
                            ->orderBy('id', 'DESC')
                            ->whereNull('deleted_at')->first();

                        //update trinh tu nhan van ban
                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                        $vanBanDen->save();

                    } else {
                        // luu don vi chu tri
                        $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];

                        $nguoiDung = User::where('trang_thai', ACTIVE)
                            ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                            ->whereHas('roles', function ($query) use ($roles) {
                                return $query->whereIn('name', $roles);
                            })
                            ->select('id', 'don_vi_id')
                            ->orderBy('id', 'DESC')
                            ->whereNull('deleted_at')->first();

                        //update trinh tu nhan van ban
                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                        $vanBanDen->save();
                    }

                    $checkLogXuLyVanBanDen = LogXuLyVanBanDen::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_chuyen_id' => $currentUser->id
                    ])->orderBy('id', 'DESC')->first();

                    if (isset($type) && $type == 'update' && empty($checkLogXuLyVanBanDen)) {

                        return redirect()->back()->with('danger', 'Văn bản này đang xử lý, không thể cập nhật.');
                    }

                    // van ban quan trong
                    VanBanQuanTrong::where([
                        'user_id' => $currentUser->id,
                        'van_ban_den_id' => $vanBanDenId
                    ])->delete();

                    if (isset($dataVanBanQuanTrong[$vanBanDenId]) && !empty($dataVanBanQuanTrong[$vanBanDenId])) {
                        $dataVanBanQuanTrong = [
                            'van_ban_den_id' => $vanBanDenId,
                            'user_id' => $currentUser->id
                        ];

                        $vanBanQuanTrong = VanBanQuanTrong::where([
                            'user_id' => $currentUser->id,
                            'van_ban_den_id' => $vanBanDenId
                        ])->first();

                        if (empty($vanBanQuanTrong)) {
                            $vanBanQuanTrong = new VanBanQuanTrong();
                            $vanBanQuanTrong->fill($dataVanBanQuanTrong);
                            $vanBanQuanTrong->save();
                        }
                    }
                    // check lanh dao du hop
                    if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {

                        if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                            LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], $donViDuHop[$vanBanDenId], $danhSachDonViChuTriIds[$vanBanDenId]);
                        }
                    }

                    //han xu ly
//                    if (isset($dataHanXuLy[$vanBanDenId]) && $vanBanDen->han_xu_ly != $dataHanXuLy[$vanBanDenId]) {
//                        $vanBanDen->han_xu_ly = $dataHanXuLy[$vanBanDenId];
//                        $vanBanDen->save();
//                    }

                    $dataLuuDonViChuTri = [
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_chuyen_id' => $currentUser->id,
                        'can_bo_nhan_id' => $nguoiDung->id ?? null,
                        'noi_dung' => $textDonViChuTri[$vanBanDenId],
                        'don_vi_id' => $danhSachDonViChuTriIds[$vanBanDenId],
                        'user_id' => $currentUser->id,
                        'han_xu_ly_cu' => $vanBanDen->han_xu_ly ?? null,
                        'han_xu_ly_moi' => isset($dataHanXuLy[$vanBanDenId]) ? $dataHanXuLy[$vanBanDenId] : null,
                        'don_vi_co_dieu_hanh' => $donVi->dieu_hanh ?? null,
                        'vao_so_van_ban' => $donVi->dieu_hanh == 0 ? 1 : null,
                        'da_chuyen_xuong_don_vi' => $vanBanDen->trinh_tu_nhan_van_ban == 3 || $vanBanDen->trinh_tu_nhan_van_ban == 8 ? 1 : null
                    ];

                    // luu don vi chu tri
                    //chuyen nhan van ban don vi
                    DonViChuTri::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'hoan_thanh' => null
                    ])->delete();

                    if (!empty($danhSachDonViChuTriIds) && !empty($danhSachDonViChuTriIds[$vanBanDenId])) {
                        $donViChuTri = new DonViChuTri();
                        $donViChuTri->fill($dataLuuDonViChuTri);
                        $donViChuTri->save();

                        // luu vet van ban den
                        $luuVetVanBanDen = new LogXuLyVanBanDen();
                        $luuVetVanBanDen->fill($dataLuuDonViChuTri);
                        $luuVetVanBanDen->save();
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
                    ])->delete();

                    if (isset($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                        DonViPhoiHop::luuDonViPhoiHop($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);

                        // luu vet van ban den
                        $luuVetVanBanDen = new LogXuLyVanBanDen();
                        $luuVetVanBanDen->fill($dataLuuDonViPhoiHop);
                        $luuVetVanBanDen->save();
                    }

                }

                DB::commit();
                return redirect()->back()->with('success', 'Đã gửi thành công.');

            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
}
