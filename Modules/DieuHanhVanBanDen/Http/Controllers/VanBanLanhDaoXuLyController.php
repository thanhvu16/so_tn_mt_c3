<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Common\AllPermission;
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
    public function index(Request $request)
    {

        $user = auth::user();
        $active = null;
        $donVi = $user->donVi;
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        $checkThamMuuSo = User::permission(AllPermission::thamMuu())
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa')
                    ->where('parent_id', DonVi::NO_PARENT_ID);
            })->select('id', 'ho_ten', 'don_vi_id')->orderBy('id', 'DESC')->first();

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {

            if ($user->hasRole(CHU_TICH)) {
                $active = VanBanDen::CHU_TICH_XA_NHAN_VB;
            }

            if ($user->hasRole(PHO_CHU_TICH)) {
                $active = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
            }

            if ($user->hasRole([TRUONG_BAN, TRUONG_PHONG])) {
                $active = VanBanDen::TRUONG_PHONG_NHAN_VB;
            }
            // chu tich xa nhan van ban
            $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
//                ->whereNull('chuyen_tiep')
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
//dd($arrVanBanDenId);

            if($request->type != null)
            {
                $danhSachVanBanDen = VanBanDen::with([
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
                    ->where('loai_van_ban_id',$loaiVanBanGiayMoi)
                    ->whereIn('id', $arrVanBanDenId)
                    ->where('trinh_tu_nhan_van_ban', $active)
                    ->paginate(PER_PAGE_10);
            }else{
                $danhSachVanBanDen = VanBanDen::with([
                    'donViCapXaChuTri',
                    'DonViCapXaPhoiHop' => function ($query) {
                        return $query->select('id', 'don_vi_id', 'van_ban_den_id');
                    }
                ])
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {
                            return $query->where('loai_van_ban_id','!=', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->where('loai_van_ban_id',$loaiVanBanGiayMoi)
                    ->whereIn('id', $arrVanBanDenId)
                    ->where('trinh_tu_nhan_van_ban', $active)
                    ->paginate(PER_PAGE_10);
            }


            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $user->don_vi_id)
                ->select('id', 'ho_ten')
                ->get();

            $chuTich = User::role(CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $user->don_vi_id)
                ->select('id', 'ho_ten')
                ->first();

            // sua o day
            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->whereHas('user')
                ->where('parent_id', $user->don_vi_id)
                ->select('id', 'ten_don_vi')
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

            return view('dieuhanhvanbanden::don-vi-cap-xa.lanh-dao.index',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'danhSachDonVi',
                    'loaiVanBanGiayMoi', 'order', 'chuTich', 'active', 'donVi'));

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



            if($request->type != null)
            {
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
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {
                            return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                        }
                    })
                    ->whereIn('id', $arrIdVanBanDenDonVi)
                    ->where('trinh_tu_nhan_van_ban', $active)
                    ->paginate(PER_PAGE_10);
            }else{

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
                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
                        if (!empty($loaiVanBanGiayMoi)) {
                            return $query->where('loai_van_ban_id','!=' ,$loaiVanBanGiayMoi->id);
                        }
                    })
                    ->whereIn('id', $arrIdVanBanDenDonVi)
                    ->where('trinh_tu_nhan_van_ban', $active)
                    ->paginate(PER_PAGE_10);
            }

            $chuTich = User::role(CHU_TICH)->where('trang_thai', ACTIVE)
                ->select('id', 'ho_ten', 'don_vi_id')
                ->whereNull('cap_xa')
                ->first();

            $danhSachPhoChuTich = User::role(PHO_CHU_TICH)
                ->where('trang_thai', ACTIVE)
                ->where('don_vi_id', $chuTich->don_vi_id)
                ->whereNull('cap_xa')
                ->select('id', 'ho_ten')
                ->get();

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('parent_id', DonVi::NO_PARENT_ID)
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
                    if ($user->hasRole(PHO_CHU_TICH)) {
                        $vanBanDen->checkVanBanQuaChuTich = $vanBanDen->checkVanBanQuaChuTich();
                    }
                }
            }

            if ($active == VanBanDen::PHO_CHU_TICH_NHAN_VB) {
                return view('dieuhanhvanbanden::van-ban-lanh-dao-xu-ly.pho_chu_tich',
                    compact('danhSachVanBanDen', 'order', 'danhSachDonVi', 'danhSachPhoChuTich', 'active',
                        'loaiVanBanGiayMoi', 'checkThamMuuSo'));
            }


//            if (!empty($checkThamMuuSo)) {

                return view('dieuhanhvanbanden::van-ban-lanh-dao-xu-ly.index',
                    compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'chuTich', 'loaiVanBanGiayMoi',
                        'order', 'active', 'danhSachDonVi', 'checkThamMuuSo'));

//            } else {
//
//                return view('dieuhanhvanbanden::van-ban-lanh-dao-xu-ly.phan_loai_van_ban',
//                    compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'chuTich', 'loaiVanBanGiayMoi',
//                        'order', 'active', 'danhSachDonVi'));
//            }
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
                        VanBanTraLai::updateStatusVanBanTraLai($vanBanTraLai);
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
                    // van ban quan trong
                    VanBanQuanTrong::where([
                        'user_id' => $currentUser->id,
                        'van_ban_den_id' => $vanBanDenId
                    ])->delete();

                    if(!empty($dataVanBanQuanTrong[$vanBanDenId])) {
                        VanBanQuanTrong::saveVanBanQuanTrong($vanBanDenId, $dataVanBanQuanTrong[$vanBanDenId]);
                    }
                    // check lanh dao du hop
                    if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {
                        if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                            LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], $donViDuHop[$vanBanDenId], $danhSachDonViChuTriIds[$vanBanDenId]);
                        }
                    }
                    // chu tich
                    if ($currentUser->hasRole(CHU_TICH)) {

                        if (!empty($arrPhoChuTich[$vanBanDenId])) {

                            $dataXuLyVanBanDen = [
                                'van_ban_den_id' => $vanBanDenId,
                                'can_bo_chuyen_id' => $currentUser->id,
                                'can_bo_nhan_id' => $arrPhoChuTich[$vanBanDenId],
                                'noi_dung' => $noiDungPhoChuTich[$vanBanDenId],
                                'tom_tat' => $checkXuLyVanBanDen->tom_tat ?? null,
                                'user_id' => $currentUser->id,
                                'han_xu_ly' => $dataHanXuLy[$vanBanDenId] ? formatYMD($dataHanXuLy[$vanBanDenId]) : null
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
                            LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataXuLyVanBanDen);
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
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_NHAN_VB;
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
                            LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataLuuDonViPhoiHop);
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

            $danhSachDonViChutri = DonVi::whereNotIn('id', json_decode($id))
                ->where('parent_id', DonVi::NO_PARENT_ID)
                ->whereNull('deleted_at')
                ->get();

            if ($donVi->cap_xa == DonVi::CAP_XA) {

                $danhSachDonViChutri = DonVi::whereNotIn('id', json_decode($id))
                    ->where('parent_id', $donVi->id)
                    ->whereNull('deleted_at')
                    ->get();
            }

            if ($donVi->parent_id != 0) {
                $danhSachDonViChutri = DonVi::whereNotIn('id', json_decode($id))
                    ->where('parent_id', $donVi->parent_id)
                    ->whereNull('deleted_at')
                    ->get();
            }

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
        /** van ban da gui tra lai cho lanh dao cho duyet,
         * lanh dao chua duyet nhung van ci dao tiep van ban
         **/
        $chiDaoVanBanTraLaiChoDuyet = $data['chi_dao_tu_van_ban_tra_lai_cho_duyet'] ?? null;

        if (count($vanBanDenDonViIds) > 0) {
            try {
                DB::beginTranSaction();

                foreach ($vanBanDenDonViIds as $vanBanDenId) {
                    $donVi = DonVi::where('id', $danhSachDonViChuTriIds[$vanBanDenId])->first();
                    $vanBanDen = VanBanDen::findOrFail($vanBanDenId);

                    if (isset($donVi)) {
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

                        // check lanh dao du hop
                        if (!empty($giayMoi) && $vanBanDen->loai_van_ban_id == $giayMoi->id) {

                            if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                                LichCongTac::taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId[$vanBanDenId], $donViDuHop[$vanBanDenId], $danhSachDonViChuTriIds[$vanBanDenId]);
                            }
                        }

                        $dataLuuDonViChuTri = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $nguoiDung->id ?? null,
                            'noi_dung' => $textDonViChuTri[$vanBanDenId],
                            'don_vi_id' => $danhSachDonViChuTriIds[$vanBanDenId],
                            'user_id' => $currentUser->id,
                            'han_xu_ly_cu' => $vanBanDen->han_xu_ly ?? null,
                            'han_xu_ly_moi' => isset($dataHanXuLy[$vanBanDenId]) ? formatYMD($dataHanXuLy[$vanBanDenId]) : null,
                            'don_vi_co_dieu_hanh' => $donVi->dieu_hanh ?? null,
                            'vao_so_van_ban' => !empty($donVi) && $donVi->dieu_hanh == 0 ? 1 : null,
                            'da_chuyen_xuong_don_vi' => $vanBanDen->trinh_tu_nhan_van_ban == VanBanDen::TRUONG_PHONG_NHAN_VB || $vanBanDen->trinh_tu_nhan_van_ban == VanBanDen::CHU_TICH_XA_NHAN_VB ? 1 : null
                        ];

                        // luu don vi chu tri
                        DonViChuTri::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'parent_don_vi_id' => null,
                            'hoan_thanh' => null
                        ])->delete();

                        if (!empty($danhSachDonViChuTriIds) && !empty($danhSachDonViChuTriIds[$vanBanDenId])) {
                            $donViChuTri = new DonViChuTri();
                            $donViChuTri->fill($dataLuuDonViChuTri);
                            $donViChuTri->save();
                            // luu vet van ban den
                            LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataLuuDonViChuTri);
                            // save thành phần dự họp
                            ThanhPhanDuHop::store($giayMoi, $vanBanDen, [$nguoiDung->id ?? null], null, $nguoiDung->don_vi_id ?? null);
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
                            'parent_don_vi_id' => null,
                            'hoan_thanh' => null
                        ])->delete();

                        if (isset($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                            DonViPhoiHop::luuDonViPhoiHop($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);
                            // luu vet van ban den
                            LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataLuuDonViPhoiHop);
                        }
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
