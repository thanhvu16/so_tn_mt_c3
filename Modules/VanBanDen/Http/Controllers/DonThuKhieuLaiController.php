<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Exports\thongKeVanBanDenExport;
use App\Models\UserLogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use auth, DB, File;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\TieuChuanVanBan;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;

class DonThuKhieuLaiController extends Controller
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

        $so_den = $request->get('vb_so_den');
        $so_den_end = $request->get('vb_so_den_end');
        $loai_van_ban = $request->get('loai_van_ban_id');
        $so_van_ban = $request->get('so_van_ban_id');
        $nguoi_ky = $request->get('nguoi_ky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $year = $request->get('year') ?? null;
        $danhSachDonVi = null;
        $page = $request->get('page');
        $danhSachDonViPhoiHop = null;
        $searchDonVi = $request->get('don_vi_id') ?? null;
        $searchDonViPhoiHop = $request->get('don_vi_phoi_hop_id') ?? null;
        $arrVanBanDenId = null;
        $arrVanBanDenId2 = null;
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();


        if (!empty($searchDonVi)) {
            $donViChuTri = DonViChuTri::where('don_vi_id', $searchDonVi)
                ->select('id', 'van_ban_den_id')
                ->distinct('van_ban_den_id')
                ->get('van_ban_den_id');

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        }
        if (!empty($searchDonViPhoiHop)) {
            $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $searchDonViPhoiHop)
                ->select('id', 'van_ban_den_id')
                ->get();

            $arrVanBanDenId2 = $donViPhoiHop->pluck('van_ban_den_id')->toArray();
        }


        $trinhTuNhanVanBan = $request->get('trinh_tu_nhan_van_ban') ?? null;

        $ds_vanBanDen = VanBanDen::query()->where(['type' => 1])
            ->where('so_van_ban_id', '!=', 100)
            ->where('loai_van_ban_id', $donThu->id)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($searchDonVi, $arrVanBanDenId) {
                if (!empty($searchDonVi)) {
                    return $query->whereIn('id', $arrVanBanDenId);
                }
            })
            ->where(function ($query) use ($searchDonViPhoiHop, $arrVanBanDenId2) {
                if (!empty($searchDonViPhoiHop)) {
                    return $query->whereIn('id', $arrVanBanDenId2);
                }
            })
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
            ->where(function ($query) use ($nguoi_ky) {
                if (!empty($nguoi_ky)) {
                    return $query->where(DB::raw('lower(nguoi_ky)'), 'LIKE', "%" . mb_strtolower($nguoi_ky) . "%");
                }
            })
            ->where(function ($query) use ($so_ky_hieu) {
                if (!empty($so_ky_hieu)) {
                    return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($so_ky_hieu) . "%");
                }
            })
            ->where(function ($query) use ($loai_van_ban) {
                if (!empty($loai_van_ban)) {
                    return $query->where('loai_van_ban_id', "$loai_van_ban");
                }
            })->where(function ($query) use ($so_van_ban) {
                if (!empty($so_van_ban)) {
                    return $query->where('so_van_ban_id', "$so_van_ban");
                }
            })
            ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                    return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                        ->where('ngay_ban_hanh', '<=', $ngayketthuc);
                }
                if ($ngayketthuc == '' && $ngaybatdau != '') {
                    return $query->where('ngay_ban_hanh', $ngaybatdau);

                }
                if ($ngaybatdau == '' && $ngayketthuc != '') {
                    return $query->where('ngay_ban_hanh', $ngayketthuc);

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

        return view('vanbanden::don-thu.index',
            compact('ds_vanBanDen', 'ds_soVanBan', 'ds_doKhanCap',
                'ds_mucBaoMat', 'ds_loaiVanBan', 'danhSachDonVi'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $user = auth::user();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [1, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 1])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $sovanban = $laysovanban;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $nam = date("Y");
        $soVanBan = SoVanBan::where('ten_so_van_ban', "LIKE", 'công văn')->first();
        $soDenvb = VanBanDen::where([
            'don_vi_id' => $lanhDaoSo->don_vi_id,
            'so_van_ban_id' => $soVanBan->id,
            'type' => 1
        ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        $soDen = $soDenvb + 1;
        $date = date("d/m/Y");
        $tieuChuan = TieuChuanVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission(AllPermission::thamMuu())->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->orderBy('id', 'DESC')->get();
        return view('vanbanden::don-thu.create', compact('sovanban', 'loaivanban', 'soDen', 'date', 'tieuChuan', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $user = auth::user();
        $nam = date("Y");
        $thamMuuId = !empty($request->lanh_dao_tham_muu) ?? null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        try {
            DB::beginTransaction();

            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                $soDenvb = VanBanDen::where([
                    'don_vi_id' => $lanhDaoSo->don_vi_id,
                    'so_van_ban_id' => $request->so_van_ban,
                    'type' => 1
                ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $soDenvb = VanBanDen::where([
                    'don_vi_id' => $user->donVi->parent_id,
                    'so_van_ban_id' => $request->so_van_ban,
                    'type' => 2
                ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
            }
            $soDenvb = $soDenvb + 1;
            if ($request->chu_tri_phoi_hop == null) {
                $request->chu_tri_phoi_hop = 0;
            }

            $vanbandv = new VanBanDen();
            $vanbandv->loai_van_ban_id = $request->loai_van_ban;
            $vanbandv->so_van_ban_id = $request->so_van_ban;
            $vanbandv->so_den = $soDenvb;
            $vanbandv->so_ky_hieu = $request->so_ky_hieu;
            $vanbandv->thong_tin_cong_dan = $request->thong_tin_cong_dan;
            $vanbandv->dia_diem_khieu_nai = $request->dia_diem_khieu_nai;
            $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
            $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
            $vanbandv->trich_yeu = $request->trich_yeu;
            $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
            $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
            $vanbandv->don_vi_id = auth::user()->don_vi_id;
            $vanbandv->type = 1;
            $vanbandv->nguoi_tao = auth::user()->id;
            $vanbandv->trinh_tu_nhan_van_ban = empty($thamMuuId) ? VanBanDen::CHU_TICH_NHAN_VB : null;
            $vanbandv->save();

            // nếu empty tham mưu thì chuyển thẳng giám đốc (chủ tịch)
            $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
            if ($request->File) {
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }
                $typeArray = explode('.', $request->File->getClientOriginalName());
                $tenchinhfile = strtolower($typeArray[0]);
                $extFile = $request->File->extension();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $request->File->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DEN . '/' . $fileName;
                $request->File->move($uploadPath, $fileName);
                $vbDenFile = new FileVanBanDen();
                $vbDenFile->ten_file = $tenchinhfile;
                $vbDenFile->duong_dan = $urlFile;
                $vbDenFile->duoi_file = $extFile;
                $vbDenFile->vb_den_id = $vanbandv->id;
                $vbDenFile->nguoi_dung_id = auth::user()->id;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();
            }


            DB::commit();
            return redirect()->back()->with('success', 'Thêm văn bản thành công !');

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
        return view('vanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('vanbanden::edit');
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
