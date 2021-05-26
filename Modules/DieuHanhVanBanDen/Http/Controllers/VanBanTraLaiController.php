<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Auth, DB;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLaiFile;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use mysql_xdevapi\Exception;

class VanBanTraLaiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $currentUser = Auth::user();

        $vanBanTraLai = VanBanTraLai::where('can_bo_nhan_id', $currentUser->id)
            ->whereNull('status')->select('van_ban_den_id', 'id')->get();

        $arrVanBanDenId = $vanBanTraLai->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->orderBy('created_at', 'desc')
            ->paginate(PER_PAGE);

        if ($danhSachVanBanDen) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
            }
        }

        return view('dieuhanhvanbanden::van-ban-tra-lai.index', compact('danhSachVanBanDen'));
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
        $donVi = $currentUser->donVi;
        $active = $request->get('active');
        $vanBanDenId = $request->get('van_ban_den_id');
        $noiDung = $request->get('noi_dung');
        $type = $request->get('type') ?? null;
        $vanBanDen = VanBanDen::findOrFail($vanBanDenId);
        $vanBanDenDonVi = $vanBanDen->hasChild();
        $txtFiles = !empty($request->get('txt_file')) ? $request->get('txt_file') : null;
        $multiFiles = !empty($request->file('ten_file')) ? $request->file('ten_file') : null;

        $chuTich = User::role(CHU_TICH)
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })
            ->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten', 'don_vi_id')
            ->first();

        $vanThuDonVi = User::role(VAN_THU_DON_VI)
            ->whereHas('donVi', function ($query) {
                return $query->where('parent_id', auth::user()->don_vi_id);
            })
            ->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten', 'don_vi_id')
            ->first();

        try {
            DB::beginTransaction();
            if ($vanBanDen) {
                $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $currentUser->id)
                    ->where('van_ban_den_id', $vanBanDenId)
                    ->whereNull('status')
                    ->first();

                $chuyenNhanDonViChuTri = DonViChuTri::where('don_vi_id', $currentUser->don_vi_id)
                    ->where('can_bo_nhan_id', $currentUser->id)
                    ->where('van_ban_den_id', $vanBanDenId)
                    ->whereNotNull('vao_so_van_ban')
                    ->whereNull('hoan_thanh')
                    ->first();

                if ($currentUser->hasRole(VAN_THU_DON_VI)) {
                    // van thu gui tra lai van ban tu van ban cho vao so
                    $chuyenNhanDonViChuTri = DonViChuTri::where('don_vi_id', $currentUser->donVi->parent_id)
//                        ->whereNull('vao_so_van_ban')
                        ->where('van_ban_den_id', $vanBanDenId)
                        ->whereNull('hoan_thanh')
//                        ->whereNull('chuyen_tiep')
                        ->select('id', 'can_bo_chuyen_id', 'can_bo_nhan_id', 'van_ban_den_id')
                        ->first();
                }

                // check van ban tra lai
                $checkVanBanTraLai = VanBanTraLai::where('van_ban_den_id', $vanBanDenId)
                    ->where('can_bo_nhan_id', $currentUser->id)->whereNull('status')->first();
                if ($checkVanBanTraLai) {
                    $checkVanBanTraLai->status = VanBanTraLai::STATUS_GIAI_QUYET;
                    $checkVanBanTraLai->save();
                }

                $canBoNhan = $xuLyVanBanDen->can_bo_chuyen_id ?? null;

                $dataVanBanTraLai = [
                    'van_ban_den_id' => $vanBanDenId,
                    'can_bo_chuyen_id' => $currentUser->id,
                    'can_bo_nhan_id' => $canBoNhan,
                    'noi_dung' => $noiDung,
                    'type' => 1
                ];

                switch ($active) {
                    case VanBanDen::PHO_CHU_TICH_NHAN_VB:
                        //PCT tra lai vb
                        if ($type == 2) {
                            //tra lai chu tich
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_NHAN_VB;
                            $vanBanDen->save();
                        } else {
                            // Tra lai tham muu
                            $xuLyVanBanDen = XuLyVanBanDen::where('van_ban_den_id', $vanBanDenId)
                                ->whereNull('status')
                                ->orderBy('created_at', 'ASC')->first();

                            $canBoNhan = $xuLyVanBanDen->can_bo_chuyen_id;
                            $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                            $vanBanDen->trinh_tu_nhan_van_ban = null;
                            $vanBanDen->save();
                        }
                        break;

                    case VanBanDen::CHUYEN_VIEN_NHAN_VB:
                        // chuyen vien tra lai
                        $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                        $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                        $checkTruongPhong = User::role([TRUONG_PHONG, TRUONG_BAN, CHANH_VAN_PHONG])
                            ->where('trang_thai', ACTIVE)
                            ->where('don_vi_id', $currentUser->don_vi_id)
                            ->select('id', 'ho_ten')
                            ->first();

                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_PHONG_NHAN_VB;
                        $vanBanDen->save();

                        if ($canBoNhan == $checkTruongPhong->id) {
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                            $vanBanDen->save();
                        }
                        break;

                    case VanBanDen::PHO_PHONG_NHAN_VB:
                        $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                        $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                        $vanBanDen->save();
                        break;

                    case VanBanDen::TRUONG_PHONG_NHAN_VB:
                        if ($donVi->cap_xa = DonVi::CAP_XA && $currentUser->hasRole([TRUONG_BAN, TRUONG_PHONG])) {
                            // chuyen van ban len pho chu tich xa chuyen lai van ban
                            $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                            $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                            // neu can bo chuyen la chu tich => active van ban chu tich xa nhan
                            $chuTichXa = User::role(CHU_TICH)->where('trang_thai', ACTIVE)
                                ->where('don_vi_id', $currentUser->donVi->parent_id)
                                ->select('id', 'ho_ten')
                                ->first();
                            if (!empty($chuTichXa) && $chuyenNhanDonViChuTri->can_bo_chuyen_id == $chuTichXa->id) {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                                $vanBanDen->save();
                            } else {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
                                $vanBanDen->save();
                            }
                        } else {
                            if ($currentUser->hasRole([TRUONG_PHONG, TRUONG_BAN])) {
                                // chuyen tra lai van thu neu don vi co dieu hanh
                                if ($chuyenNhanDonViChuTri->don_vi_co_dieu_hanh == DonViChuTri::DON_VI_CO_DIEU_HANH) {

                                    $canBoNhan = $vanThuDonVi->id ?? null;
                                    $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                                    $chuyenNhanDonViChuTri->vao_so_van_ban = null;
                                    $chuyenNhanDonViChuTri->tra_lai = DonViChuTri::TRA_LAI;
                                    $chuyenNhanDonViChuTri->chuyen_tiep = null;
                                    $chuyenNhanDonViChuTri->save();

                                } else {
                                    // chuyen len PCT hoac CT
                                    $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                                    $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;
                                    $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_NHAN_VB;

                                    if ($canBoNhan == $chuTich->id) {
                                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_NHAN_VB;
                                    }
                                    $vanBanDen->save();
                                }

                            } else {
                                // van thu tra lai
                                $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                                $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_NHAN_VB;

                                if ($canBoNhan == $chuTich->id) {
                                    $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_NHAN_VB;
                                }
                                $vanBanDen->save();

                                // xoa van ban den don vi
                                if (!empty($vanBanDenDonVi)) {
                                    $vanBanDenDonVi->delete();

                                    // xoa chuyen vien phoi hop
                                    ChuyenVienPhoiHop::where([
                                        'van_ban_den_id' => $vanBanDen->id,
                                        'don_vi_id' => $currentUser->don_vi_id
                                    ])->delete();

                                    // xoa pho phong xem de biet
                                    LanhDaoXemDeBiet::where([
                                        'van_ban_den_id' => $vanBanDen->id,
                                        'don_vi_id' => $currentUser->don_vi_id
                                    ])->delete();

                                }
                            }
                        }
                        break;

                    case VanBanDen::PHO_CHU_TICH_XA_NHAN_VB:
                        // pho ct xa chuyen lai van ban cho chu tich xa
                        $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;

                        // check can bo nhan la van thu hay chu tich xa
                        if (!empty($vanThuDonVi) && $canBoNhan == $vanThuDonVi->id) {
                            $canBoNhan = $vanThuDonVi->id ?? null;
                            $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                            $chuyenNhanDonViChuTri->vao_so_van_ban = null;
                            $chuyenNhanDonViChuTri->tra_lai = DonViChuTri::TRA_LAI;
                            $chuyenNhanDonViChuTri->chuyen_tiep = null;
                            $chuyenNhanDonViChuTri->save();
                        } else {
                            $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                            $vanBanDen->save();
                        }
                        break;

                    case VanBanDen::CHU_TICH_XA_NHAN_VB:
                        if ($chuyenNhanDonViChuTri->don_vi_co_dieu_hanh == DonViChuTri::DON_VI_CO_DIEU_HANH) {

                            $canBoNhan = $vanThuDonVi->id ?? null;
                            $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                            $chuyenNhanDonViChuTri->vao_so_van_ban = null;
                            $chuyenNhanDonViChuTri->tra_lai = DonViChuTri::TRA_LAI;
                            $chuyenNhanDonViChuTri->chuyen_tiep = null;
                            $chuyenNhanDonViChuTri->save();

                        } else {
                            // chuyen len PCT hoac CT
                            $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                            $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;
                            $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_NHAN_VB;

                            if ($canBoNhan == $chuTich->id) {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_NHAN_VB;
                            }
                            $vanBanDen->save();
                        }
                        break;

                    default:
                        $vanBanDen->trinh_tu_nhan_van_ban = null;
                        $vanBanDen->save();

                        break;

                }

                // luu van ban tra lai
                $vanBanTraLai = new VanBanTraLai();
                $vanBanTraLai->fill($dataVanBanTraLai);
                $vanBanTraLai->save();

                if ($multiFiles && count($multiFiles) > 0) {
                    VanBanTraLaiFile::dinhKemFile($multiFiles, $txtFiles, $vanBanTraLai->id);
                }

                $dataXuLyVanBanDen = [
                    'van_ban_den_id' => $vanBanDenId,
                    'can_bo_chuyen_id' => $currentUser->id,
                    'can_bo_nhan_id' => $canBoNhan,
                    'noi_dung' => $noiDung,
                    'tom_tat' => $xuLyVanBanDen->tom_tat ?? null,
                    'user_id' => $currentUser->id,
                    'status' => XuLyVanBanDen::STATUS_TRA_LAI
                ];

                //luu trinh tu xu ly van ban den
                $xuLyVanBanDen = new XuLyVanBanDen();
                $xuLyVanBanDen->fill($dataXuLyVanBanDen);
                $xuLyVanBanDen->save();

                // luu log xu ly van ban den
                $luuVetVanBanDen = new LogXuLyVanBanDen();
                $luuVetVanBanDen->fill($dataXuLyVanBanDen);
                $luuVetVanBanDen->save();

                //xoa van ban don vi chu tri tu van thu cho vao so gui tra lai
                if ($currentUser->hasRole(VAN_THU_DON_VI)) {
                    $chuyenNhanDonViChuTri->tra_lai = DonViChuTri::TRA_LAI;
                    $chuyenNhanDonViChuTri->save();
                }
                DB::commit();

                return redirect()->back()->with('success', 'Đã  gửi trả lại văn bản');
            }
            return redirect()->back()->with('warning', 'Không tìm thấy dữ liệu');

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

    public function choDuyet(Request $request)
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

        $vanBanTraLai = VanBanTraLai::where('can_bo_chuyen_id', $user->id)
            ->whereNull('status')->select('van_ban_den_id', 'id')->get();

        $arrVanBanDenId = $vanBanTraLai->pluck('van_ban_den_id')->toArray();

        if ($user->hasRole([CHU_TICH, PHO_CHU_TICH])) {
            return $this->lanhDaoTraLai($user, $active, $donVi, $loaiVanBanGiayMoi, $arrVanBanDenId);

        } else {

            if ($user->hasRole([CHUYEN_VIEN, VAN_THU_DON_VI])) {

                if($request->type != null)
                {
                    $danhSachVanBanDen = VanBanDen::with('vanBanTraLaiChoDuyet')
                        ->where(function ($query) use ($loaiVanBanGiayMoi) {
                            if (!empty($loaiVanBanGiayMoi)) {
                                return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                            }
                        })
                        ->whereIn('id', $arrVanBanDenId)
                        ->orderBy('created_at', 'desc')
                        ->paginate(PER_PAGE);
                }else{
                    $danhSachVanBanDen = VanBanDen::with('vanBanTraLaiChoDuyet')
                        ->where(function ($query) use ($loaiVanBanGiayMoi) {
                            if (!empty($loaiVanBanGiayMoi)) {
                                return $query->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id);
                            }
                        })
                        ->whereIn('id', $arrVanBanDenId)
                        ->orderBy('created_at', 'desc')
                        ->paginate(PER_PAGE);
                }

                if ($danhSachVanBanDen) {
                    foreach ($danhSachVanBanDen as $vanBanDen) {
                        $vanBanDen->hasChild = $vanBanDen->hasChild() ?? null;
                    }
                }
                return view('dieuhanhvanbanden::van-ban-tra-lai.cho_duyet', compact('danhSachVanBanDen'));
            } else {
                return $this->donViTraLai($arrVanBanDenId, $user, $loaiVanBanGiayMoi, $donVi);
            }

        }
    }

    public function lanhDaoTraLai($user, $active, $donVi, $loaiVanBanGiayMoi, $arrVanBanDenId)
    {

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
            $danhSachVanBanDen = VanBanDen::with([
                'donViCapXaChuTri',
                'DonViCapXaPhoiHop' => function ($query) {
                    return $query->select('id', 'don_vi_id', 'van_ban_den_id');
                }
            ])
                ->whereIn('id', $arrVanBanDenId)
                ->paginate(PER_PAGE_10);


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
                    $vanBanDen->vanBanTraLai = $vanBanDen->vanBanTraLaiChoDuyet ?? null;
                }
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE_10 + 1;

            return view('dieuhanhvanbanden::don-vi-cap-xa.lanh-dao.tra_lai',
                compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'danhSachDonVi',
                    'loaiVanBanGiayMoi', 'order', 'chuTich', 'active'));

        } else {
            // chu tich huyen nhan van ban
            $vanBanTraLai = VanBanTraLai::where('can_bo_chuyen_id', $user->id)
                ->whereNull('status')->select('van_ban_den_id', 'id')->get();

            $arrVanBanDenId = $vanBanTraLai->pluck('van_ban_den_id')->toArray();

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
                ->whereIn('id', $arrVanBanDenId)
                ->paginate(PER_PAGE_10);

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
                    $vanBanDen->lichCongTacChuTich = $vanBanDen->checkLichCongTac([$chuTich->id]) ?? null;
                    $vanBanDen->PhoChuTich = $vanBanDen->checkCanBoNhan($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->lichCongTacPhoChuTich = $vanBanDen->checkLichCongTac($danhSachPhoChuTich->pluck('id')->toArray());
                    $vanBanDen->vanBanTraLaiChoDuyet = $vanBanDen->vanBanTraLaiChoDuyet ?? null;
                    if ($user->hasRole(PHO_CHU_TICH)) {
                        $vanBanDen->checkVanBanQuaChuTich = $vanBanDen->checkVanBanQuaChuTich();
                    }
                }
            }

            return view('dieuhanhvanbanden::van-ban-tra-lai.lanh_dao',
                compact('danhSachVanBanDen', 'order', 'danhSachDonVi', 'danhSachPhoChuTich', 'active', 'loaiVanBanGiayMoi'));

        }
    }

    public function donViTraLai($arrVanBanDenId, $currentUser, $loaiVanBanGiayMoi, $donVi)
    {
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

        $danhSachVanBanDen = VanBanDen::with(['lanhDaoXemDeBiet' => function($query) {
            return $query->select('id', 'van_ban_den_id', 'lanh_dao_id');
        }])
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

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
                    $vanBanDen->phoPhong = $vanBanDen->getChuyenVienThucHien(count($danhSachPhoPhong) ? $danhSachPhoPhong->pluck('id')->toArray() : [0]);
                    $vanBanDen->chuyenVien = $vanBanDen->getChuyenVienThucHien(count($danhSachChuyenVien) > 0 ? $danhSachChuyenVien->pluck('id')->toArray() : [0]);
                    $vanBanDen->truongPhong = $vanBanDen->getChuyenVienThucHien([$currentUser->id]);
                }
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        return view('dieuhanhvanbanden::van-ban-tra-lai.don-vi.index', compact('danhSachVanBanDen', 'danhSachPhoPhong',
            'danhSachChuyenVien', 'trinhTuNhanVanBan', 'order', 'loaiVanBanGiayMoi', 'donVi'));
    }
}
