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
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
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
            ->whereNull('trinh_tu_nhan_van_ban')
            ->paginate(PER_PAGE);

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

        $chuTich = User::role('chủ tịch')->first();
        $danhSachPhoChuTich = User::role('phó chủ tịch')->get();
        $danhSachDonVi = DonVi::whereNull('deleted_at')
            ->where('id', '!=', $user->don_vi_id)->get();


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
        $textDonViPhoiHop = $data['don_vi_phoi_hop'] ?? null;
        $donViDuHop = $data['don_vi_du_hop'] ?? null;
        $chucVuTP = ChucVu::where('ten_chuc_vu', 'like', 'trưởng phòng')->first();

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

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
                    }

                    // check quyen gia han van ban
                    $quyenGiaHan = null;

                    // luu don vi chu tri
                    $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];

                    $nguoiDung = User::where('trang_thai', ACTIVE)
                        ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                        ->whereHas('roles', function ($query) use ($roles) {
                            return $query->whereIn('name', $roles);
                        })
                        ->whereNull('deleted_at')->first();

                    $donVi = DonVi::where('id', $danhSachDonViChuTriIds[$vanBanDenId])->first();

                    // check lanh dao du hop
                    if (!empty($giayMoi) && $vanBanDen->so_van_ban_id == $giayMoi->id) {
                        if (!empty($lanhDaoDuHopId[$vanBanDenId])) {
                            $lanhDaoId = $lanhDaoDuHopId[$vanBanDenId];
                            $tuan = date('W',strtotime($vanBanDen->ngay_hop_chinh));

                            $lanhDaoDuHop = LichCongTac::checkLanhDaoDuHop($lanhDaoDuHopId[$vanBanDenId]);
                            $noiDungMoiHop = null;

                            if (!empty($lanhDaoDuHop)) {

                                $noiDungMoiHop = 'Kính mời '.$lanhDaoDuHop->chucVu->ten_chuc_vu. ' '. $lanhDaoDuHop->ho_ten .' dự họp';
                            }

                            // don vi du hop
                            if ($donViDuHop[$vanBanDenId] == VanBanDen::DON_VI_DU_HOP) {
                                $lanhDaoId = $nguoiDung->id ?? null;
                            }

                            $dataLichCongTac = array(
                                'object_id' => $vanBanDen->id,
                                'lanh_dao_id' => $lanhDaoId,
                                'ngay' => $vanBanDen->ngay_hop,
                                'gio' => $vanBanDen->gio_hop,
                                'tuan' => $tuan,
                                'buoi' => ($vanBanDen->gio_hop <= '12:00') ? 1 : 2,
                                'noi_dung' => !empty($vanBanDen->noi_dung_hop) ? $vanBanDen->noi_dung_hop : $noiDungMoiHop,
                                'dia_diem' => !empty($vanBanDen->dia_diem) ? $vanBanDen->dia_diem : null,
                                'user_id' => $currentUser->id,
                                'don_vi_du_hop' => !empty($donViDuHop[$vanBanDenId]) ? LichCongTac::DON_VI_DU_HOP : null
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

        $danhSachPhoChuTich = User::role('phó chủ tịch')->get();

        $danhSachDonVi = DonVi::whereNull('deleted_at')
            ->where('id', '!=', $user->don_vi_id)->get();

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
                'checkLuuVetVanBanDen' => function ($query) {
                    $query->select(['can_bo_chuyen_id']);
                },
                'checkDonViChuTri' => function ($query) {
                    $query->select('don_vi_id');
                },
                'checkDonViPhoiHop' => function ($query) {
                    $query->select(['id', 'don_vi_id']);
                }
                ])
                ->whereIn('id', $arrIdVanBanDenDonVi)
                ->paginate(PER_PAGE);

            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->arr_can_bo_nhan = $vanBanDen->getXuLyVanBanDen();
            }

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

            return view('dieuhanhvanbanden::phan-loai-van-ban.da_phan_loai_pct',
                compact('danhSachVanBanDen', 'order', 'danhSachDonVi', 'danhSachPhoChuTich', 'active'));

        }

        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_chuyen_id', $user->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->get();

        $donViChuTri = DonViChuTri::where('can_bo_chuyen_id', $user->id)
            ->whereNull('hoan_thanh')
            ->get();

        $idVanBanDonViChuTri = $donViChuTri->pluck('van_ban_den_id')->toArray();

        $idVanBanLanhDaoId = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $arrIdVanBanDenDonVi = array_merge($idVanBanDonViChuTri, $idVanBanLanhDaoId);

        $danhSachVanBanDen = VanBanDen::with([
            'lanhDaoXemDeBiet' => function ($query) {
                $query->select(['van_ban_den_id', 'lanh_dao_id']);
            },
            'checkLuuVetVanBanDen' => function ($query) {
                $query->select(['can_bo_chuyen_id']);
            }])
            ->whereIn('id', $arrIdVanBanDenDonVi)
            ->paginate(PER_PAGE);

        foreach ($danhSachVanBanDen as $vanBanDen) {
            $vanBanDen->arr_can_bo_nhan = $vanBanDen->getXuLyVanBanDen();
        }

        $chuTich = User::role('chủ tịch')->first();

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();


        return view('dieuhanhvanbanden::phan-loai-van-ban.da_phan_loai',
            compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                'danhSachPhoChuTich', 'chuTich', 'active', 'danhSachDonVi'));
    }
}
