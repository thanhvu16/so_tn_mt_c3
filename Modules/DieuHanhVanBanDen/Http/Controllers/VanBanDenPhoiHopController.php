<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\PhoiHopGiaiQuyet;
use Modules\DieuHanhVanBanDen\Entities\PhoiHopGiaiQuyetFile;
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

        $trinhTuNhanVanBan = null;

        $chuyenTiep = $request->get('chuyen_tiep') ?? null;

        if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = 3;
        }

        if ($currentUser->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = 4;
        }

        if ($currentUser->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = 5;
        }

        $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $currentUser->don_vi_id)
            ->where('can_bo_nhan_id', $currentUser->id)
            ->where(function ($query) use ($chuyenTiep) {
                if (!empty($chuyenTiep)) {
                    return $query->where('chuyen_tiep', $chuyenTiep);
                }
                else {
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
            ->whereIn('id', $arrVanBanDenId)
            ->paginate(PER_PAGE);

        if (!empty($danhSachVanBanDen)) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->hasChild = $vanBanDen->hasChild(VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP) ?? null;
                if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                    $vanBanDen->phoPhong = $vanBanDen->donViPhoiHopVanBan($danhSachPhoPhong->pluck('id')->toArray());
                    $vanBanDen->chuyenVien = $vanBanDen->donViPhoiHopVanBan($danhSachChuyenVien->pluck('id')->toArray());
                    $vanBanDen->truongPhong = $vanBanDen->donViPhoiHopVanBan([$truongPhong->id]);
                    $vanBanDen->phoChuTich = $vanBanDen->donViPhoiHopVanBan($danhSachPhoChuTich->pluck('id')->toArray());

                } else {
                    if ($currentUser->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])) {
                        $vanBanDen->phoPhong = $vanBanDen->donViPhoiHopVanBan($danhSachPhoPhong->pluck('id')->toArray());
                        $vanBanDen->chuyenVien = $vanBanDen->donViPhoiHopVanBan($danhSachChuyenVien->pluck('id')->toArray());
                        $vanBanDen->truongPhong = $vanBanDen->donViPhoiHopVanBan([$currentUser->id]);
                    }
                }
            }
        }

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        if ($currentUser->hasrole(CHUYEN_VIEN)) {

            return view('dieuhanhvanbanden::don-vi-phoi-hop.chuyen-vien',
                compact('danhSachVanBanDen', 'order'));

        }

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
            return view('dieuhanhvanbanden::don-vi-phoi-hop.cap_xa.index', compact('danhSachVanBanDen',
                'danhSachPhoPhong', 'danhSachPhoChuTich', 'truongPhong', 'donVi',
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

        if (isset($vanBanDenDonViIds) && count($vanBanDenDonViIds) > 0) {
            try {
                DB::beginTransaction();

                foreach ($vanBanDenDonViIds as $vanBanDenDonViId) {

                    $donViPhoiHop = DonViPhoiHop::where('van_ban_den_id', $vanBanDenDonViId)
                        ->where('can_bo_nhan_id', $currentUser->id)
                        ->whereNull('hoan_thanh')->first();

                    $vanBanDen = VanBanDen::where('id', $vanBanDenDonViId)->first();

                    if ($donViPhoiHop) {
                        $donViPhoiHop->chuyen_tiep = DonViPhoiHop::CHUYEN_TIEP;
                        $donViPhoiHop->save();

                        DonViPhoiHop::where('van_ban_den_id', $vanBanDenDonViId)
                            ->where('id', '>', $donViPhoiHop->id)
                            ->whereNull('hoan_thanh')
                            ->delete();
                    }

                    if (isset($danhSachPhoChuTichIds) && !empty($danhSachPhoChuTichIds[$vanBanDenDonViId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenDonViId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoChuTichIds[$vanBanDenDonViId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'noi_dung' => $textnoidungPhoChuTich[$vanBanDenDonViId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanPhoChuTich = new DonViPhoiHop();
                        $chuyenNhanVanBanPhoChuTich->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanPhoChuTich->save();
                    }

                    if (isset($danhSachTruongPhongIds) && !empty($danhSachTruongPhongIds[$vanBanDenDonViId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenDonViId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachTruongPhongIds[$vanBanDenDonViId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'noi_dung' => $textnoidungTruongPhong[$vanBanDenDonViId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanTruongPhong = new DonViPhoiHop();
                        $chuyenNhanVanBanTruongPhong->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanTruongPhong->save();
                    }

                    //chuyen nhan van ban don vi
                    if (isset($danhSachPhoPhongIds) && !empty($danhSachPhoPhongIds[$vanBanDenDonViId])) {
                        $dataChuyenNhanVanBanDonVi = [
                            'van_ban_den_id' => $vanBanDenDonViId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoPhongIds[$vanBanDenDonViId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'noi_dung' => $textnoidungPhoPhong[$vanBanDenDonViId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanPhoPhong = new DonViPhoiHop();
                        $chuyenNhanVanBanPhoPhong->fill($dataChuyenNhanVanBanDonVi);
                        $chuyenNhanVanBanPhoPhong->save();
                    }

                    if (isset($danhSachChuyenVienIds) && !empty($danhSachChuyenVienIds[$vanBanDenDonViId])) {
                        //save chuyen vien thuc hien
                        $dataChuyenNhanVanBanChuyenVien = [
                            'van_ban_den_id' => $vanBanDenDonViId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachChuyenVienIds[$vanBanDenDonViId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'parent_id' => $donViPhoiHop ? $donViPhoiHop->id : null,
                            'noi_dung' => $textNoiDungChuyenVien[$vanBanDenDonViId],
                            'don_vi_co_dieu_hanh' => $donViPhoiHop->don_vi_co_dieu_hanh,
                            'vao_so_van_ban' => $donViPhoiHop->vao_so_van_ban,
                            'user_id' => $currentUser->id
                        ];

                        $chuyenNhanVanBanChuyenVienDonVi = new DonViPhoiHop();
                        $chuyenNhanVanBanChuyenVienDonVi->fill($dataChuyenNhanVanBanChuyenVien);
                        $chuyenNhanVanBanChuyenVienDonVi->save();
                    }
                    // save thanh phan du hop
                    ThanhPhanDuHop::store($giayMoi, $vanBanDen, [$danhSachPhoChuTichIds[$vanBanDenDonViId], $danhSachTruongPhongIds[$vanBanDenDonViId],
                        $danhSachPhoPhongIds[$vanBanDenDonViId], $danhSachChuyenVienIds[$vanBanDenDonViId]], null, auth::user()->don_vi_id);

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
        $data['user_id'] = auth::user()->id;
        $data['don_vi_id'] = auth::user()->don_vi_id;
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
}
