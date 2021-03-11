<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Auth, DB;
use Modules\Admin\Entities\DonVi;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
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

        $chuTich = User::role(CHU_TICH)
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })
            ->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten')
            ->first();

        $vanThuDonVi = User::role(VAN_THU_DON_VI)
            ->where('don_vi_id', $currentUser->don_vi_id)
            ->where('trang_thai', ACTIVE)
            ->select('id', 'ho_ten')
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
                        ->whereNull('vao_so_van_ban')
                        ->whereNull('hoan_thanh')
                        ->whereNull('chuyen_tiep')
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
                    case 2:
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

                    case 5:
                        // chuyen vien tra lai
                        $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                        $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                        $vanBanDen->trinh_tu_nhan_van_ban = 4;
                        $vanBanDen->save();

                        break;

                    case 4:
                        $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                        $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                        $vanBanDen->trinh_tu_nhan_van_ban = 3;
                        $vanBanDen->save();
                        break;

                    case 3:
                        if ($donVi->cap_xa = DonVi::CAP_XA && $currentUser->hasRole(TRUONG_BAN)) {
                            // chuyen van ban len pho chu tich xa chuyen lai van ban
                            $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                            $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                            // neu can bo chuyen la chu tich => active van ban chu tich xa nhan
                            $chuTichXa = User::role(CHU_TICH)->where('trang_thai', ACTIVE)
                                ->where('don_vi_id', $currentUser->donVi->parent_id)
                                ->select('id', 'ho_ten')
                                ->first();
                            if ($chuyenNhanDonViChuTri->can_bo_chuyen_id == $chuTichXa->id) {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                                $vanBanDen->save();
                            } else {
                                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
                                $vanBanDen->save();
                            }
                        } else {
                            if ($currentUser->hasRole(TRUONG_PHONG)) {
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
                                    $vanBanDen->trinh_tu_nhan_van_ban = 2;

                                    if ($canBoNhan == $chuTich->id) {
                                        $vanBanDen->trinh_tu_nhan_van_ban = 1;
                                    }
                                    $vanBanDen->save();
                                }

                            } else {
                                // van thu tra lai
                                $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                                $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;
                                $vanBanDen->trinh_tu_nhan_van_ban = 2;

                                if ($canBoNhan == $chuTich->id) {
                                    $vanBanDen->trinh_tu_nhan_van_ban = 1;
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

                    case 9:
                        // pho ct xa chuyen lai van ban cho chu tich xa
                        $canBoNhan = $chuyenNhanDonViChuTri->can_bo_chuyen_id;
                        $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;
                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                        $vanBanDen->save();
                        break;

                    case 8:
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
                            $vanBanDen->trinh_tu_nhan_van_ban = 2;

                            if ($canBoNhan == $chuTich->id) {
                                $vanBanDen->trinh_tu_nhan_van_ban = 1;
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
}
