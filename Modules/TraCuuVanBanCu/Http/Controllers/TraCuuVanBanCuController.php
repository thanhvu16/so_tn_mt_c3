<?php

namespace Modules\TraCuuVanBanCu\Http\Controllers;

use App\Exports\thongKeVanBanDenExport;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\Admin\Entities\VanBanDenOld;
use Modules\Admin\Entities\VanBanDiOld;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\VanBanDen\Entities\VanBanDen;
use auth,DB;
use Modules\VanBanDi\Entities\VanBanDi;

class TraCuuVanBanCuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $donVi = auth::user()->donVi;
        $user = auth::user();
        $trichyeu = $request->get('vb_trich_yeu');
        $so_ky_hieu = $request->get('vb_so_ky_hieu');
        $co_quan_ban_hanh = $request->get('co_quan_ban_hanh_id');
        $donThu = LoaiVanBan::where('ten_loai_van_ban', 'Like', 'Đơn thư')->first();

        $so_den = $request->get('vb_so_den');
        $so_den_end = $request->get('vb_so_den_end');
        $loai_van_ban = $request->get('loai_van_ban_id');
        $so_van_ban = $request->get('so_van_ban_id');
        $nguoi_ky = $request->get('nguoi_ky_id');
        $do_khan = $request->get('do_khan');
        $do_mat = $request->get('do_mat');

        $ngaybatdau = formatYMD($request->start_date);
        $ngayketthuc = formatYMD($request->end_date);

        $ngaybanhanhbatdau = formatYMD($request->ngay_ban_hanh_date);
        $ngaybanhanhketthuc = formatYMD($request->end_ngay_ban_hanh);
        $year = $request->get('year') ?? null;
        $danhSachDonVi = null;
        $page = $request->get('page');
        $danhSachDonViPhoiHop = null;
        $searchDonVi = $request->get('don_vi_id') ?? null;
        $searchDonViPhoiHop = $request->get('don_vi_phoi_hop_id') ?? null;
        $arrVanBanDenId = null;
        $arrVanBanDenId2 = null;





            $ds_vanBanDen = VanBanDenOld::query()
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where(DB::raw('lower(TrichYeu)'), 'LIKE', "%" . mb_strtolower($trichyeu) . "%");
                    }
                })
                ->where(function ($query) use ($so_den, $so_den_end) {
                    if ($so_den != '' && $so_den_end != '' && $so_den <= $so_den_end) {

                        return $query->where('SoDen', '>=', $so_den)
                            ->where('SoDen', '<=', $so_den_end);
                    }
                    if ($so_den_end == '' && $so_den != '') {
                        return $query->where('SoDen', $so_den);

                    }
                    if ($so_den == '' && $so_den_end != '') {
                        return $query->where('SoDen', $so_den_end);

                    }
                })
                ->where(function ($query) use ($co_quan_ban_hanh) {
                    if (!empty($co_quan_ban_hanh)) {
                        return $query->where(DB::raw('lower(DonViGui)'), 'LIKE', "%" . mb_strtolower($co_quan_ban_hanh) . "%");
                    }
                })
                ->where(function ($query) use ($nguoi_ky) {
                    if (!empty($nguoi_ky)) {
                        return $query->where(DB::raw('lower(NguoiKy)'), 'LIKE', "%" . mb_strtolower($nguoi_ky) . "%");
                    }
                })
                ->where(function ($query) use ($so_ky_hieu) {
                    if (!empty($so_ky_hieu)) {
                        return $query->where(DB::raw('lower(SoKyHieu)'), 'LIKE', "%" . mb_strtolower($so_ky_hieu) . "%");
                    }
                })
//                ->where(function ($query) use ($loai_van_ban) {
//                    if (!empty($loai_van_ban)) {
//                        return $query->where('loai_van_ban_id', "$loai_van_ban");
//                    }
//                })
//                ->where(function ($query) use ($so_van_ban) {
//                    if (!empty($so_van_ban)) {
//                        return $query->where('so_van_ban_id', "$so_van_ban");
//                    }
//                })
//                ->where(function ($query) use ($do_khan) {
//                    if (!empty($do_khan)) {
//                        return $query->where('do_khan_cap_id', $do_khan);
//                    }
//                })
//                ->where(function ($query) use ($do_mat) {
//                    if (!empty($do_mat)) {
//                        return $query->where('do_bao_mat_id', $do_mat);
//                    }
//                })
                ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                    if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                        return $query->where('NgayNhap', '>=', $ngaybatdau)
                            ->where('NgayNhap', '<=', $ngayketthuc);
                    }
                    if ($ngayketthuc == '' && $ngaybatdau != '') {
                        return $query->where('NgayNhap', $ngaybatdau);

                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        return $query->where('NgayNhap', $ngayketthuc);

                    }
                })
                ->where(function ($query) use ($ngaybanhanhbatdau, $ngaybanhanhketthuc) {
                    if ($ngaybanhanhbatdau != '' && $ngaybanhanhketthuc != '' && $ngaybanhanhbatdau <= $ngaybanhanhketthuc) {

                        return $query->where('NgayPhatHanh', '>=', $ngaybanhanhbatdau)
                            ->where('NgayPhatHanh', '<=', $ngaybanhanhketthuc);
                    }
                    if ($ngaybanhanhketthuc == '' && $ngaybanhanhbatdau != '') {
                        return $query->where('NgayPhatHanh', $ngaybanhanhbatdau);

                    }
                    if ($ngaybanhanhbatdau == '' && $ngaybanhanhketthuc != '') {
                        return $query->where('NgayPhatHanh', $ngaybanhanhketthuc);

                    }
                })

                ->orderBy('NgayNhap', 'desc')->paginate(PER_PAGE, ['*'], 'page', $page);

            $danhSachDonVi = DonVi::where('parent_id', DonVi::NO_PARENT_ID)->whereNull('deleted_at')->orderBy('thu_tu', 'asc')->get();


        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_soVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();


        return view('tracuuvanbancu::van-ban-den.index',
            compact('ds_vanBanDen', 'ds_soVanBan', 'ds_doKhanCap',
                'ds_mucBaoMat', 'ds_loaiVanBan', 'danhSachDonVi'));
    }

    public function vanBanDi(Request $request)
    {
        $user = auth::user();

        $donVi = auth::user()->donVi;
        $trichyeu = $request->get('vb_trichyeu');
        $loaivanban = $request->get('loaivanban_id');
        $so_ky_hieu = $request->get('vb_sokyhieu');
        $chucvu = $request->get('chuc_vu');
        $donvisoanthao = $request->get('donvisoanthao_id');
        $so_van_ban = $request->get('sovanban_id');
        $don_vi_van_ban = $request->get('don_vi_van_ban');

        $nguoi_ky = $request->get('nguoiky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $phatHanhVanBan = $request->get('phat_hanh_van_ban');
        $year = $request->get('year') ?? null;
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
//        $ds_vanBanDi = VanBanDi::where('loai_van_ban_giay_moi',1)->whereNull('deleted_at')



            $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();
            $ds_vanBanDi = VanBanDiOld::where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichyeu) . "%");
                    }
                })

                ->where(function ($query) use ($so_ky_hieu) {
                    if (!empty($so_ky_hieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($so_ky_hieu) . "%");
                    }
                })
                ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                    if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                        return $query->where('ngay_phat_hanh', '>=', $ngaybatdau)
                            ->where('ngay_phat_hanh', '<=', $ngayketthuc);
                    }
                    if ($ngayketthuc == '' && $ngaybatdau != '') {
                        return $query->where('ngay_phat_hanh', $ngaybatdau);

                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        return $query->where('ngay_phat_hanh', $ngayketthuc);

                    }
                })
                ->where(function ($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->where(function ($query) use ($phatHanhVanBan) {
                    if (!empty($phatHanhVanBan)) {
                        return $query->where('phat_hanh_van_ban', $phatHanhVanBan);
                    }
                })
                ->orderBy('nam', 'desc')->paginate(PER_PAGE);


        return view('tracuuvanbancu::van-ban-di.index', compact('ds_vanBanDi', 'ds_loaiVanBan', 'ds_soVanBan', 'ds_DonVi', 'ds_nguoiKy'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('tracuuvanbancu::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('tracuuvanbancu::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('tracuuvanbancu::edit');
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
