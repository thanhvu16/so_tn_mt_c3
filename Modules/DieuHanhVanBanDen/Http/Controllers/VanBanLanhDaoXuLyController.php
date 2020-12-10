<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Models\LichCongTac;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
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
        if ($user->hasRole(CHU_TICH)) {
            $active = 1;
        } else {
            $active = 2;
        }
        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->get();

        $arrIdVanBanDenDonVi = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with('lanhDaoXemDeBiet', 'checkLuuVetVanBanDen', 'nguoiDung', 'vanBanTraLai')
            ->whereIn('id', $arrIdVanBanDenDonVi)
            ->where('trinh_tu_nhan_van_ban', $active)
            ->paginate(PER_PAGE);

        $chuTich = User::role(CHU_TICH)->where('trang_thai', ACTIVE)->first();
        $danhSachPhoChuTich = User::role(PHO_CHUC_TICH)
            ->where('trang_thai', ACTIVE)
            ->get();

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

        $danhSachDonVi = DonVi::whereNull('deleted_at')
            ->where('id', '!=', $user->don_vi_id)->get();

        if ($active == 2) {

            return view('dieuhanhvanbanden::van-ban-lanh-dao-xu-ly.pho_chu_tich',
                compact('danhSachVanBanDen', 'order', 'danhSachDonVi', 'danhSachPhoChuTich', 'active'));
        }

        return view('dieuhanhvanbanden::van-ban-lanh-dao-xu-ly.index',
            compact('danhSachVanBanDen', 'danhSachPhoChuTich', 'chuTich', 'loaiVanBanGiayMoi',
                'order', 'active', 'danhSachDonVi'));
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

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

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
                    if (isset($dataHanXuLy[$vanBanDenId]) && $vanBanDen->hhan_xu_ly != $dataHanXuLy[$vanBanDenId]) {
                        $checkXuLyVanBanDen->han_xu_ly = $dataHanXuLy[$vanBanDenId];
                        $checkXuLyVanBanDen->save();
                        $vanBanDen->han_xu_ly = $dataHanXuLy[$vanBanDenId];
                        $vanBanDen->save();
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
                    if (!empty($giayMoi) && $vanBanDen->so_van_ban_id == $giayMoi->id) {
                        if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                            $tuan = date('W',strtotime($vanBanDen->ngay_hop_chinh));

                            $lanhDaoDuHop = LichCongTac::checkLanhDaoDuHop($lanhDaoDuHopId[$vanBanDenId]);
                            $noiDungMoiHop = null;

                            if (!empty($lanhDaoDuHop)) {

                                $noiDungMoiHop = 'Kính mời '.$lanhDaoDuHop->chucVu->ten_chuc_vu. ' '. $lanhDaoDuHop->ho_ten .' dự họp';
                            }

                            $dataLichCongTac = array(
                                'object_id' => $vanBanDen->id,
                                'lanh_dao_id' => $lanhDaoDuHopId[$vanBanDenId],
                                'ngay' => $vanBanDen->ngay_hop,
                                'gio' => $vanBanDen->gio_hop,
                                'tuan' => $tuan,
                                'buoi' => ($vanBanDen->gio_hop <= '12:00') ? 1 : 2,
                                'noi_dung' => !empty($vanBanDen->noi_dung_hop) ? $vanBanDen->noi_dung_hop : $noiDungMoiHop,
                                'dia_diem' => !empty($vanBanDen->dia_diem) ? $vanBanDen->dia_diem : null,
                                'user_id' => $currentUser->id,
                            );
                            //check lich cong tac
                            $lichCongTac = LichCongTac::where('object_id', $vanBanDenId)->whereNull('type')->first();

                            if (empty($lichCongTac)) {
                                $lichCongTac = new LichCongTac();
                            }
                            $lichCongTac->fill($dataLichCongTac);
                            $lichCongTac->save();
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
                                'user_id' => $currentUser->id
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

                        // luu don vi chu tri
                        $nguoiDung = User::role(TRUONG_PHONG)
                            ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                            ->where('trang_thai', ACTIVE)
                            ->whereNull('deleted_at')->first();

                        $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];

                        $nguoiDung = User::where('trang_thai', ACTIVE)
                            ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                            ->whereHas('roles', function ($query) use ($roles) {
                                return $query->whereIn('name', $roles);
                            })
                            ->whereNull('deleted_at')->first();

                        $donVi = DonVi::where('id', $danhSachDonViChuTriIds[$vanBanDenId])->first();

                        $dataLuuDonViChuTri = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $nguoiDung->id ?? null,
                            'noi_dung' => $textDonViChuTri[$vanBanDenId],
                            'don_vi_id' => $danhSachDonViChuTriIds[$vanBanDenId],
                            'user_id' => $currentUser->id,
                            'don_vi_co_dieu_hanh' => $donVi->dieu_hanh ?? null,
                            'vao_so_van_ban' => !empty($donVi) && $donVi->dieu_hanh == 0 ? 1 : null
                        ];

                        DonViChuTri::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'hoan_thanh'  => null
                        ])->delete();

                        $donViChuTri = new DonViChuTri();
                        $donViChuTri->fill($dataLuuDonViChuTri);
                        $donViChuTri->save();

                        // luu vet van ban den
                        $luuVetVanBanDen = new LogXuLyVanBanDen();
                        $luuVetVanBanDen->fill($dataLuuDonViChuTri);
                        $luuVetVanBanDen->save();

                        //data don vi phoi hop
                        $dataLuuDonViPhoiHop = [
                            'van_ban_den_id' => $vanBanDenId,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $nguoiDung->id ?? null,
                            'noi_dung' => $textDonViPhoiHop[$vanBanDenId],
                            'don_vi_phoi_hop_id' => isset($danhSachDonViPhoiHopIds[$vanBanDenId]) ? \GuzzleHttp\json_encode($danhSachDonViPhoiHopIds[$vanBanDenId]) : null,
                            'user_id' => $currentUser->id
                        ];

                        // luu vet van ban den
                        $luuVetVanBanDen = new LogXuLyVanBanDen();
                        $luuVetVanBanDen->fill($dataLuuDonViPhoiHop);
                        $luuVetVanBanDen->save();

                        // luu don vi phoi hop
                        DonViPhoiHop::where([
                            'van_ban_den_id' => $vanBanDenId,
                            'chuyen_tiep'  => null,
                            'hoan_thanh'  => null
                        ])->delete();
                        if (isset($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                            DonViPhoiHop::luuDonViPhoiHop($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);
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


        $vanBanDenDonViIds = json_decode($data['van_ban_den_id']);
        $danhSachDonViChuTriIds = $data['don_vi_chu_tri_id'] ?? null;
        $danhSachDonViPhoiHopIds = $data['don_vi_phoi_hop_id'] ?? null;
        $textDonViChuTri = $data['don_vi_chu_tri'] ?? null;
        $textDonViPhoiHop = $data['don_vi_phoi_hop'] ?? null;
        $dataVanBanQuanTrong = $data['van_ban_quan_trong'] ?? null;
        $lanhDaoDuHopId = $data['lanh_dao_du_hop_id'] ?? null;
        $dataHanXuLy = $data['han_xu_ly'] ?? null;

        $chucVuTP = ChucVu::where('ten_chuc_vu', 'like', 'trưởng phòng')->first();

        if (count($vanBanDenDonViIds) > 0) {
            try {
                DB::beginTranSaction();

                foreach ($vanBanDenDonViIds as $vanBanDenId) {

                    $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];
                    $nguoiDung = User::where('trang_thai', ACTIVE)
                        ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                        ->whereHas('roles', function ($query) use ($roles) {
                            return $query->whereIn('name', $roles);
                        })
                        ->whereNull('deleted_at')->first();

                    $vanBanDen = VanBanDen::findOrFail($vanBanDenId);

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

                    //han xu ly
                    if (isset($dataHanXuLy[$vanBanDenId]) && $vanBanDen->hhan_xu_ly != $dataHanXuLy[$vanBanDenId]) {
                        $vanBanDen->han_xu_ly = $dataHanXuLy[$vanBanDenId];
                        $vanBanDen->save();
                    }

                    $donVi = DonVi::where('id', $danhSachDonViChuTriIds[$vanBanDenId])->first();

                    $dataLuuDonViChuTri = [
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_chuyen_id' => $currentUser->id,
                        'can_bo_nhan_id' => $nguoiDung->id ?? null,
                        'noi_dung' => $textDonViChuTri[$vanBanDenId],
                        'don_vi_id' => $danhSachDonViChuTriIds[$vanBanDenId],
                        'user_id' => $currentUser->id,
                        'don_vi_co_dieu_hanh' => $donVi->dieu_hanh ?? null,
                        'vao_so_van_ban' => $donVi->dieu_hanh == 0 ? 1 : null
                    ];

                    // luu don vi chu tri
                    //chuyen nhan van ban don vi
                    DonViChuTri::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'hoan_thanh'  => null
                    ])->delete();

                    $donViChuTri = new DonViChuTri();
                    $donViChuTri->fill($dataLuuDonViChuTri);
                    $donViChuTri->save();

                    // luu vet van ban den
                    $luuVetVanBanDen = new LogXuLyVanBanDen();
                    $luuVetVanBanDen->fill($dataLuuDonViChuTri);
                    $luuVetVanBanDen->save();

                    //data don vi phoi hop
                    $dataLuuDonViPhoiHop = [
                        'van_ban_den_id' => $vanBanDenId,
                        'can_bo_chuyen_id' => $currentUser->id,
                        'can_bo_nhan_id' => $nguoiDung->id ?? null,
                        'noi_dung' => $textDonViPhoiHop[$vanBanDenId],
                        'don_vi_phoi_hop_id' => isset($danhSachDonViPhoiHopIds[$vanBanDenId]) ? \GuzzleHttp\json_encode($danhSachDonViPhoiHopIds[$vanBanDenId]) : null,
                        'user_id' => $currentUser->id
                    ];

                    // luu vet van ban den
                    $luuVetVanBanDen = new LogXuLyVanBanDen();
                    $luuVetVanBanDen->fill($dataLuuDonViPhoiHop);
                    $luuVetVanBanDen->save();

                    // luu don vi phoi hop
                    DonViPhoiHop::where([
                        'van_ban_den_id' => $vanBanDenId,
                        'chuyen_tiep'  => null,
                        'hoan_thanh'  => null
                    ])->delete();
                    if (isset($danhSachDonViPhoiHopIds[$vanBanDenId])) {
                        DonViPhoiHop::luuDonViPhoiHop($danhSachDonViPhoiHopIds[$vanBanDenId], $vanBanDenId);
                    }

                    //update trinh tu nhan van ban
                    $vanBanDen->trinh_tu_nhan_van_ban = 3;
                    $vanBanDen->save();

                }

                DB::commit();
                return redirect()->back()->with('success', 'Đã gửi thành công.');

            } catch (\Exception $e)
            {
                DB::rollback();
                dd($e);
            }
        }
    }
}
