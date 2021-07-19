<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\Models\LichCongTac;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;
use DB, auth;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\VanBanDi;

class CapNhatQuyTrinhGiamDocController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
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
        try {
            DB::beginTransaction();
            $this->XoaThongTinCu($request);
            $this->ThemLaiThongTin($request);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }


    public function ThemLaiThongTin($request)
    {
        $currentUser = auth::user();
        $data = $request->all();
        $vanBanDenIds = json_decode($data['van_ban_den_id']);

        $arrChuTich = $data['chu_tich_id'] ?? null;
        $arrPhoChuTich = $data['pho_chu_tich_id'] ?? null;
        $arrLanhDaoXemDeBiet = $data['lanh_dao_xem_de_biet'] ?? null;
        $tomTatVanBan = $data['don_vi_chu_tri'] ?? null;
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


            foreach ($vanBanDenIds as $vanBanDenId) {
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

//                    if (!empty($arrChuTich[$vanBanDenId])) {
//                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_NHAN_VB;
//                        $vanBanDen->save();
//                    }
//
//                    if (!empty($arrPhoChuTich[$vanBanDenId]) && empty($arrChuTich[$vanBanDenId])) {
//                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::PHO_CHU_TICH_NHAN_VB;
//                        $vanBanDen->save();
//                    }
//
//                    if (empty($arrPhoChuTich[$vanBanDenId]) && empty($arrChuTich[$vanBanDenId])) {
                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                        $vanBanDen->save();
                        $chuyenVanBanXuongDonVi = DonViChuTri::VB_DA_CHUYEN_XUONG_DON_VI;
//                    }
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
                    if (!empty($vanBanQuanTrongIds[$vanBanDenId])) {
                        $vbquantrong = 1;
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
                        'van_ban_quan_trong' => $vbquantrong,
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
                    if (!empty($vanBanQuanTrongIds[$vanBanDenId])) {
                        $vbquantrong = 1;
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
                        'van_ban_quan_trong' => $vbquantrong,
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
                if (!empty($vanBanQuanTrongIds[$vanBanDenId])) {
                    $vbquantrong = 1;
                }
                DonViChuTri::where([
                    'van_ban_den_id' => $vanBanDenId,
                    'parent_don_vi_id' => null,
                    'hoan_thanh' => null
                ])->delete();

                if (!empty($danhSachDonViChuTriIds) && !empty($danhSachDonViChuTriIds[$vanBanDenId])) {

                    DonViChuTri::luuDonViXuLyVanBan($vanBanDenId, $textDonViChuTri, $danhSachDonViChuTriIds, $chuyenVanBanXuongDonVi, $vbquantrong);
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

    public function XoaThongTinCu($request)
    {
        $data = $request->all();
        $vanBanDenIds = json_decode($data['van_ban_den_id']);
        if (isset($vanBanDenIds) && count($vanBanDenIds) > 0) {
            foreach ($vanBanDenIds as $vanBanDenId) {
                //Xóa log Xử lý văn bản đến
                LogXuLyVanBanDen::where(['van_ban_den_id' => $vanBanDenId,])->delete();
                //Xóa đơn vị chủ trì
                DonViChuTri::where('van_ban_den_id', $vanBanDenId)->delete();
                //Xóa đơn vị phối hợp
                DonViPhoiHop::where('van_ban_den_id', $vanBanDenId)->delete();
                //Xóa xử lý văn bản đến
                XuLyVanBanDen::where('van_ban_den_id', $vanBanDenId)->delete();
                //xóa văn bản trả lại
                VanBanTraLai::where('van_ban_den_id', $vanBanDenId)->delete();
                //Xóa lãnh đạo xem để biết
                LanhDaoXemDeBiet::where('van_ban_den_id', $vanBanDenId)->delete();
                //Xóa chuyên viên phối hợp
                ChuyenVienPhoiHop::where('van_ban_den_id', $vanBanDenId)->delete();
                //Xóa gia hạn văn bản
                GiaHanVanBan::where('van_ban_den_id', $vanBanDenId)->delete();
                //Xóa giải quyết văn bản
                GiaiQuyetVanBan::where('van_ban_den_id', $vanBanDenId)->delete();
                //Xóa DỰ thảo và văn bản đi
                $duthao = Duthaovanbandi::where('van_ban_den_don_vi_id', $vanBanDenId)->get();
                if (count($duthao)) {
                    foreach ($duthao as $data) {
                        VanBanDi::where('du_thao_van_ban_di_id', $data->id)->delete();
                        CanBoPhongDuThao::where('du_thao_vb_id', $data->id)->forceDelete();
                        CanBoPhongDuThaoKhac::where('du_thao_vb_id', $data->id)->forceDelete();
                        Duthaovanbandi::where('id', $data->id)->delete();
                    }
                }

                //Xóa văn bản đến của cấp 2
                VanBanDen::where('parent_id', $vanBanDenId)->forceDelete();
            }
        }
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
