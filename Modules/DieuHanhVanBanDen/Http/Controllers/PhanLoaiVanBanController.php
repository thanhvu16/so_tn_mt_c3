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
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
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


        return view('dieuhanhvanbanden::phan-loai-van-ban.index',
            compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                'danhSachPhoChuTich', 'chuTich'));
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
        $statusTraiLai = $request->get('van-ban_tra_lai') ?? null;
        $lanhDaoDuHopId = $data['lanh_dao_du_hop_id'] ?? null;

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

        if ($user->hasRole(AllPermission::chuTich())) {
            $active = 1;
        }

        if ($user->hasRole(AllPermission::phoChuTich())) {
            $active = 2;

            $donViChuTri = DonViChuTri::where('can_bo_chuyen_id', $user->id)
                ->whereNull('hoan_thanh')
                ->get();

            $arrIdVanBanDenDonVi = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $danhSachVanBanDen = VanBanDen::with('lanhDaoXemDeBiet', 'checkLuuVetVanBanDen',
                'checkDonViChuTri', 'checkDonViPhoiHop')
                ->whereIn('id', $arrIdVanBanDenDonVi)
                ->paginate(PER_PAGE);

            $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

            $danhSachDonVi = DonVi::whereNull('deleted_at')
                ->where('id', '!=', $user->don_vi_id)->get();

            return view('dieuhanhvanbanden::phan-loai-van-ban.da_phan_loai_pct',
                compact('danhSachVanBanDen', 'order', 'danhSachDonVi', 'danhSachPhoChuTich', 'active'));

        }

        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_chuyen_id', $user->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->get();

        $arrIdVanBanDenDonVi = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $danhSachVanBanDen = VanBanDen::with('lanhDaoXemDeBiet', 'checkLuuVetVanBanDen')
            ->whereIn('id', $arrIdVanBanDenDonVi)
            ->paginate(PER_PAGE);


        $chuTich = User::role('chủ tịch')->first();

        $order = ($danhSachVanBanDen->currentPage() - 1) * PER_PAGE + 1;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();


        return view('dieuhanhvanbanden::phan-loai-van-ban.da_phan_loai',
            compact('order', 'danhSachVanBanDen', 'loaiVanBanGiayMoi',
                'danhSachPhoChuTich', 'chuTich', 'active'));
    }
}
