<?php

namespace Modules\TimKiemVanBan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Exports\thongKeVanBanDenExport;
use Carbon\Carbon;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\VanBanDen\Entities\VanBanDen;
use auth, DB;

class VanBanQuanTrongController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index(Request $request)
    {
        $dact = DonViChuTri::whereNull('cap_do')->count();
        $donVi = auth::user()->donVi;
        $user = auth::user();
        $trichyeu = $request->get('vb_trich_yeu');
        $tuKhoa = $request->get('tu_khoa');
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
        $searchDonVi = auth::user()->don_vi_id;
        $searchDonViPhoiHop = $request->get('don_vi_phoi_hop_id') ?? null;
        $arrVanBanDenId = null;
        $arrVanBanDenId2 = null;


        if (!empty($searchDonViPhoiHop)) {
            $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $searchDonViPhoiHop)
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId2 = $donViPhoiHop->pluck('van_ban_den_id')->toArray();
        }


        $trinhTuNhanVanBan = $request->get('trinh_tu_nhan_van_ban') ?? null;


        $ds_vanBanDen = VanBanDen::query()
            ->whereNull('deleted_at')
            ->where(function ($query) use ($searchDonVi) {
                return $query->whereHas('searchDonViChuTriQuanTrong', function ($q) use ($searchDonVi) {
                    return $q->where('don_vi_id', $searchDonVi);
                });
            })
            ->where(function ($query) use ($searchDonViPhoiHop, $arrVanBanDenId2) {
                if (!empty($searchDonViPhoiHop)) {
                    return $query->whereIn('id', $arrVanBanDenId2);
                }
            })
//                ->where(function ($query) use ($trichyeu) {
//                    if (!empty($trichyeu)) {
//                        return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
//                    }
//                })
            ->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichyeu) . "%");
                }
            })
            ->where(function ($query) use ($so_den, $so_den_end) {
                if ($so_den != '' && $so_den_end != '' && $so_den <= $so_den_end) {

                    return $query->where('so_den', '>=', $so_den)
                        ->where('so_den', '<=', $so_den_end);
                }
                if ($so_den_end == '' && $so_den != '') {
                    return $query->where('so_den', $so_den);

                }
                if ($so_den == '' && $so_den_end != '') {
                    return $query->where('so_den', $so_den_end);

                }
            })
            ->where(function ($query) use ($co_quan_ban_hanh) {
                if (!empty($co_quan_ban_hanh)) {
                    return $query->where(DB::raw('lower(co_quan_ban_hanh)'), 'LIKE', "%" . mb_strtolower($co_quan_ban_hanh) . "%");
                }
            })
//                ->where(function ($query) use ($co_quan_ban_hanh) {
//                    if (!empty($co_quan_ban_hanh)) {
//                        return $query->where('co_quan_ban_hanh', 'LIKE', "%$co_quan_ban_hanh%");
//                    }
//                })
            ->where(function ($query) use ($nguoi_ky) {
                if (!empty($nguoi_ky)) {
                    return $query->where(DB::raw('lower(nguoi_ky)'), 'LIKE', "%" . mb_strtolower($nguoi_ky) . "%");
                }
            })
//                ->where(function ($query) use ($nguoi_ky) {
//                    if (!empty($nguoi_ky)) {
//                        return $query->where('nguoi_ky', 'LIKE', "%$nguoi_ky%");
//                    }
//                })
            ->where(function ($query) use ($so_ky_hieu) {
                if (!empty($so_ky_hieu)) {
                    return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($so_ky_hieu) . "%");
                }
            })
//                ->where(function ($query) use ($so_ky_hieu) {
//                    if (!empty($so_ky_hieu)) {
//                        return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
//                    }
//                })
            ->where(function ($query) use ($loai_van_ban) {
                if (!empty($loai_van_ban)) {
                    return $query->where('loai_van_ban_id', "$loai_van_ban");
                }
            })
            ->where(function ($query) use ($so_van_ban) {
                if (!empty($so_van_ban)) {
                    return $query->where('so_van_ban_id', "$so_van_ban");
                }
            })
            ->where(function ($query) use ($do_khan) {
                if (!empty($do_khan)) {
                    return $query->where('do_khan_cap_id', $do_khan);
                }
            })
            ->where(function ($query) use ($do_mat) {
                if (!empty($do_mat)) {
                    return $query->where('do_bao_mat_id', $do_mat);
                }
            })
            ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                    return $query->where('ngay_nhan', '>=', $ngaybatdau)
                        ->where('ngay_nhan', '<=', $ngayketthuc);
                }
                if ($ngayketthuc == '' && $ngaybatdau != '') {
                    return $query->where('ngay_nhan', $ngaybatdau);

                }
                if ($ngaybatdau == '' && $ngayketthuc != '') {
                    return $query->where('ngay_nhan', $ngayketthuc);

                }
            })
            ->where(function ($query) use ($ngaybanhanhbatdau, $ngaybanhanhketthuc) {
                if ($ngaybanhanhbatdau != '' && $ngaybanhanhketthuc != '' && $ngaybanhanhbatdau <= $ngaybanhanhketthuc) {

                    return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhbatdau)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhketthuc);
                }
                if ($ngaybanhanhketthuc == '' && $ngaybanhanhbatdau != '') {
                    return $query->where('ngay_ban_hanh', $ngaybanhanhbatdau);

                }
                if ($ngaybanhanhbatdau == '' && $ngaybanhanhketthuc != '') {
                    return $query->where('ngay_ban_hanh', $ngaybanhanhketthuc);

                }
            })
            ->where(function ($query) use ($year) {
                if (!empty($year)) {
                    return $query->whereYear('created_at', $year);
                }
            })
            ->where(function ($query) use ($trinhTuNhanVanBan) {
                if (!empty($trinhTuNhanVanBan)) {
                    switch ($trinhTuNhanVanBan) {
                        case 10:
                            return $query->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan);
                        case 2:
                            return $query->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN);
                        case 1:
                            return $query->whereNull('trinh_tu_nhan_van_ban');
                    }

                }
            })
            ->orderBy('so_den', 'desc')->paginate(PER_PAGE, ['*'], 'page', $page);

        $danhSachDonVi = DonVi::where('parent_id', DonVi::NO_PARENT_ID)->whereNull('deleted_at')->orderBy('thu_tu', 'asc')->get();


        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $day = Carbon::now()->format('d');
        if ($request->get('type') == 'excel') {
            $totalRecord = $ds_vanBanDen->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenExport($ds_vanBanDen, $totalRecord,
                $month, $year, $day),
                $fileName);
        }

        if ($request->get('type') == 'word') {
            $fileName = 'van_ban_den_' . date('d_m_Y') . '.doc';
            $headers = array(
                "Content-type" => "text/html",
                "Content-Disposition" => "attachment;Filename=" . $fileName
            );

            $content = view('vanbanden::thong_ke.TK_vb_den_don_vi_chu_tri_word', compact('ds_vanBanDen', 'year', 'day', 'month'));

            return \Response::make($content, 200, $headers);

        }


        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_soVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();


        return view('timkiemvanban::vb_quan_trong',
            compact('ds_vanBanDen', 'ds_soVanBan', 'ds_doKhanCap',
                'ds_mucBaoMat', 'ds_loaiVanBan', 'danhSachDonVi'));
    }

    public function vbDonVi(Request $request)
    {

        $capDoVanBan = $request->get('cap_do') ?? 3;
        $donVi = auth::user()->donVi;
        $user = auth::user();
        $trichyeu = $request->get('vb_trich_yeu');
        $tuKhoa = $request->get('tu_khoa');
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
        $searchDonVi = auth::user()->don_vi_id;
        $searchDonViPhoiHop = $request->get('don_vi_phoi_hop_id') ?? null;
        $arrVanBanDenId = null;
        $arrVanBanDenId2 = null;


        if (!empty($searchDonViPhoiHop)) {
            $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $searchDonViPhoiHop)
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId2 = $donViPhoiHop->pluck('van_ban_den_id')->toArray();
        }


        $trinhTuNhanVanBan = $request->get('trinh_tu_nhan_van_ban') ?? null;


        $ds_vanBanDen = VanBanDen::query()
            ->whereNull('deleted_at')
            ->where(function ($query) use ($searchDonVi,$capDoVanBan) {
                return $query->whereHas('searchDonViChuTriQuanTrongDV', function ($q) use ($searchDonVi,$capDoVanBan) {
                    return $q->where(['don_vi_id'=> $searchDonVi,'cap_do'=>$capDoVanBan]);
                });
            })
            ->where(function ($query) use ($searchDonViPhoiHop, $arrVanBanDenId2) {
                if (!empty($searchDonViPhoiHop)) {
                    return $query->whereIn('id', $arrVanBanDenId2);
                }
            })
//                ->where(function ($query) use ($trichyeu) {
//                    if (!empty($trichyeu)) {
//                        return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
//                    }
//                })
            ->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichyeu) . "%");
                }
            })
            ->where(function ($query) use ($so_den, $so_den_end) {
                if ($so_den != '' && $so_den_end != '' && $so_den <= $so_den_end) {

                    return $query->where('so_den', '>=', $so_den)
                        ->where('so_den', '<=', $so_den_end);
                }
                if ($so_den_end == '' && $so_den != '') {
                    return $query->where('so_den', $so_den);

                }
                if ($so_den == '' && $so_den_end != '') {
                    return $query->where('so_den', $so_den_end);

                }
            })
            ->where(function ($query) use ($co_quan_ban_hanh) {
                if (!empty($co_quan_ban_hanh)) {
                    return $query->where(DB::raw('lower(co_quan_ban_hanh)'), 'LIKE', "%" . mb_strtolower($co_quan_ban_hanh) . "%");
                }
            })
//                ->where(function ($query) use ($co_quan_ban_hanh) {
//                    if (!empty($co_quan_ban_hanh)) {
//                        return $query->where('co_quan_ban_hanh', 'LIKE', "%$co_quan_ban_hanh%");
//                    }
//                })
            ->where(function ($query) use ($nguoi_ky) {
                if (!empty($nguoi_ky)) {
                    return $query->where(DB::raw('lower(nguoi_ky)'), 'LIKE', "%" . mb_strtolower($nguoi_ky) . "%");
                }
            })
//                ->where(function ($query) use ($nguoi_ky) {
//                    if (!empty($nguoi_ky)) {
//                        return $query->where('nguoi_ky', 'LIKE', "%$nguoi_ky%");
//                    }
//                })
            ->where(function ($query) use ($so_ky_hieu) {
                if (!empty($so_ky_hieu)) {
                    return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($so_ky_hieu) . "%");
                }
            })
//                ->where(function ($query) use ($so_ky_hieu) {
//                    if (!empty($so_ky_hieu)) {
//                        return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
//                    }
//                })
            ->where(function ($query) use ($loai_van_ban) {
                if (!empty($loai_van_ban)) {
                    return $query->where('loai_van_ban_id', "$loai_van_ban");
                }
            })
            ->where(function ($query) use ($so_van_ban) {
                if (!empty($so_van_ban)) {
                    return $query->where('so_van_ban_id', "$so_van_ban");
                }
            })
            ->where(function ($query) use ($do_khan) {
                if (!empty($do_khan)) {
                    return $query->where('do_khan_cap_id', $do_khan);
                }
            })
            ->where(function ($query) use ($do_mat) {
                if (!empty($do_mat)) {
                    return $query->where('do_bao_mat_id', $do_mat);
                }
            })
            ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                    return $query->where('ngay_nhan', '>=', $ngaybatdau)
                        ->where('ngay_nhan', '<=', $ngayketthuc);
                }
                if ($ngayketthuc == '' && $ngaybatdau != '') {
                    return $query->where('ngay_nhan', $ngaybatdau);

                }
                if ($ngaybatdau == '' && $ngayketthuc != '') {
                    return $query->where('ngay_nhan', $ngayketthuc);

                }
            })
            ->where(function ($query) use ($ngaybanhanhbatdau, $ngaybanhanhketthuc) {
                if ($ngaybanhanhbatdau != '' && $ngaybanhanhketthuc != '' && $ngaybanhanhbatdau <= $ngaybanhanhketthuc) {

                    return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhbatdau)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhketthuc);
                }
                if ($ngaybanhanhketthuc == '' && $ngaybanhanhbatdau != '') {
                    return $query->where('ngay_ban_hanh', $ngaybanhanhbatdau);

                }
                if ($ngaybanhanhbatdau == '' && $ngaybanhanhketthuc != '') {
                    return $query->where('ngay_ban_hanh', $ngaybanhanhketthuc);

                }
            })
            ->where(function ($query) use ($year) {
                if (!empty($year)) {
                    return $query->whereYear('created_at', $year);
                }
            })
            ->where(function ($query) use ($trinhTuNhanVanBan) {
                if (!empty($trinhTuNhanVanBan)) {
                    switch ($trinhTuNhanVanBan) {
                        case 10:
                            return $query->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan);
                        case 2:
                            return $query->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN);
                        case 1:
                            return $query->whereNull('trinh_tu_nhan_van_ban');
                    }

                }
            })
            ->orderBy('so_den', 'desc')->paginate(PER_PAGE, ['*'], 'page', $page);

        $danhSachDonVi = DonVi::where('parent_id', DonVi::NO_PARENT_ID)->whereNull('deleted_at')->orderBy('thu_tu', 'asc')->get();


        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $day = Carbon::now()->format('d');
        if ($request->get('type') == 'excel') {
            $totalRecord = $ds_vanBanDen->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenExport($ds_vanBanDen, $totalRecord,
                $month, $year, $day),
                $fileName);
        }

        if ($request->get('type') == 'word') {
            $fileName = 'van_ban_den_' . date('d_m_Y') . '.doc';
            $headers = array(
                "Content-type" => "text/html",
                "Content-Disposition" => "attachment;Filename=" . $fileName
            );

            $content = view('vanbanden::thong_ke.TK_vb_den_don_vi_chu_tri_word', compact('ds_vanBanDen', 'year', 'day', 'month'));

            return \Response::make($content, 200, $headers);

        }


        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_soVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();


        return view('timkiemvanban::vb_quan_trong_don_vi',
            compact('ds_vanBanDen', 'ds_soVanBan', 'ds_doKhanCap',
                'ds_mucBaoMat', 'ds_loaiVanBan', 'danhSachDonVi'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('timkiemvanban::create');
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
        return view('timkiemvanban::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('timkiemvanban::edit');
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
