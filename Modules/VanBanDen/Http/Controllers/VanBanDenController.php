<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Http\Controllers\Controller;
use App\Models\QlvbVbDenDonVi as VbDenDonVi;
use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\NgayNghi;
use Modules\Admin\Entities\SoVanBan;
use File, auth, DB;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\LayVanBanTuEmail\Entities\GetEmail;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\TieuChuanVanBan;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDen\Entities\VanBanDenDonVi;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use function GuzzleHttp\Promise\all;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;

class VanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
//        $arayEcabinet=;
//        $data = json_decode($arayEcabinet);
//        dd($data);
        $donVi = auth::user()->donVi;
        $user = auth::user();
        $trichyeu = $request->get('vb_trich_yeu');
        $so_ky_hieu = $request->get('vb_so_ky_hieu');
        $co_quan_ban_hanh = $request->get('co_quan_ban_hanh_id');

        $so_den = $request->get('vb_so_den');
        $loai_van_ban = $request->get('loai_van_ban_id');
        $so_van_ban = $request->get('so_van_ban_id');
        $nguoi_ky = $request->get('nguoi_ky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $year = $request->get('year') ?? null;

        if ($user->hasRole(VAN_THU_HUYEN) || ($user->hasRole(CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA) ||
            ($user->hasRole(PHO_CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA)) {

            $ds_vanBanDen = VanBanDen::where([
                'type' => 1])->where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where('vb_trich_yeu', 'LIKE', "%$trichyeu%");
                    }
                })->where(function ($query) use ($so_den) {
                    if (!empty($so_den)) {
                        return $query->where('so_den', 'LIKE', "%$so_den%");
                    }
                })
                ->where(function ($query) use ($co_quan_ban_hanh) {
                    if (!empty($co_quan_ban_hanh)) {
                        return $query->where('co_quan_ban_hanh', 'LIKE', "%$co_quan_ban_hanh%");
                    }
                })
                ->where(function ($query) use ($nguoi_ky) {
                    if (!empty($nguoi_ky)) {
                        return $query->where('nguoi_ky', 'LIKE', "%$nguoi_ky%");
                    }
                })
                ->where(function ($query) use ($so_ky_hieu) {
                    if (!empty($so_ky_hieu)) {
                        return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                    }
                })->where(function ($query) use ($loai_van_ban) {
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
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);

        } else {
            $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;
            $ds_vanBanDen = VanBanDen::
            where([
                'don_vi_id' => $donViId,
                'type' => VanBanDen::TYPE_VB_DON_VI])
                ->where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where('vb_trich_yeu', 'LIKE', "%$trichyeu%");
                    }
                })->where(function ($query) use ($so_den) {
                    if (!empty($so_den)) {
                        return $query->where('so_den', 'LIKE', "%$so_den%");
                    }
                })
                ->where(function ($query) use ($co_quan_ban_hanh) {
                    if (!empty($co_quan_ban_hanh)) {
                        return $query->where('co_quan_ban_hanh', 'LIKE', "%$co_quan_ban_hanh%");
                    }
                })
                ->where(function ($query) use ($nguoi_ky) {
                    if (!empty($nguoi_ky)) {
                        return $query->where('nguoi_ky', 'LIKE', "%$nguoi_ky%");
                    }
                })
                ->where(function ($query) use ($so_ky_hieu) {
                    if (!empty($so_ky_hieu)) {
                        return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                    }
                })->where(function ($query) use ($loai_van_ban) {
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
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        }

        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_soVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();

        return view('vanbanden::van_ban_den.index',
            compact('ds_vanBanDen', 'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'ds_loaiVanBan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themVanBanDen());

        $user = auth::user();
        $user->can(VAN_THU_DON_VI);
        $domat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $tieuChuan = TieuChuanVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $date = date("d/m/Y");

        $nam = date("Y");
        $soDenvb = null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $soVanBan = SoVanBan::where('ten_so_van_ban', "LIKE", 'công văn')->first();

        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $soDen = $soDenvb + 1;

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

        $users = User::permission(AllPermission::thamMuu())->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }

        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);

        return view('vanbanden::van_ban_den.create', compact('domat', 'dokhan','date', 'loaivanban', 'soDen','sovanban', 'tieuChuan', 'users', 'hangiaiquyet'));
    }

    public function layhantruyensangview(Request $request)
    {
        //lấy hạn
        $ngaynhan = $request->get('ngay_ban_hanh');
        $tieuChuan = $request->get('tieu_chuan');
        $tieuChuandata = TieuChuanVanBan::where('id', $tieuChuan)->first();
        $songay = $tieuChuandata->so_ngay ?? null;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }

        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        return response()->json(
            [
                'html' => formatDMY($hangiaiquyet)
            ]
        );
    }

    public function laysoden(Request $request)
    {
        $nam = date("Y");
        $soDenvb = null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();

        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => $request->soVanBanId,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $request->soVanBanId,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $soDen = $soDenvb + 1;
//        dd($soDen);
        return response()->json(
            [
                'html' => $soDen

            ]
        );
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
        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($request['noi_dung']) ? $request['noi_dung'] : null;
        $thamMuuId = !empty($request->lanh_dao_tham_muu) ?? null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();

        try {
            DB::beginTransaction();

//            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
//                $soDenvb = VanBanDen::where([
//                    'don_vi_id' => $lanhDaoSo->don_vi_id,
//                    'so_van_ban_id' => $request->so_van_ban,
//                    'type' => 1
//                ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
//            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
//                $soDenvb = VanBanDen::where([
//                    'don_vi_id' => $user->donVi->parent_id,
//                    'so_van_ban_id' => $request->so_van_ban,
//                    'type' => 2
//                ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
//            }
//            $soDenvb = $soDenvb + 1;
            if($request->chu_tri_phoi_hop == null)
            {
                $request->chu_tri_phoi_hop = 0;
            }

            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                if ($noi_dung && $noi_dung[0] != null) {
                    foreach ($noi_dung as $key => $data) {
                        $vanbandv = new VanBanDen();
                        $vanbandv->loai_van_ban_id = $request->loai_van_ban;
                        $vanbandv->so_van_ban_id = $request->so_van_ban;
                        $vanbandv->so_den = $request->so_den;
                        $vanbandv->so_ky_hieu = $request->so_ky_hieu;
                        $vanbandv->tieu_chuan = $request->tieu_chuan;
                        $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
                        $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
                        $vanbandv->trich_yeu = $request->trich_yeu;
                        $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                        $vanbandv->nguoi_ky = $request->nguoi_ky;
                        $vanbandv->do_khan_cap_id = $request->do_khan;
                        $vanbandv->do_bao_mat_id = $request->do_mat;
                        $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
                        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                        $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                        $vanbandv->nguoi_tao = auth::user()->id;
                        $vanbandv->type = 1;
                        $vanbandv->noi_dung = $data;
                        if ($request->han_giai_quyet[$key] == null) {
                            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                            //$vanbandv->han_giai_quyet = $request->han_xu_ly;
                        } else {
                            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                            $vanbandv->han_giai_quyet = $han_gq[$key];
                        }
                        $vanbandv->trinh_tu_nhan_van_ban = empty($thamMuuId) ? VanBanDen::CHU_TICH_NHAN_VB : null;
                        $vanbandv->save();

                        // nếu empty tham mưu thì chuyển thẳng giám đốc (chủ tịch)
                        if (empty($thamMuuId)) {
                            $chuTich = User::role(CHU_TICH)
                                ->whereHas('donVi', function ($query) {
                                    return $query->whereNull('cap_xa');
                                })->select('id', 'ho_ten', 'don_vi_id')->first();

                            $dataXuLyVanBanDen = [
                                'van_ban_den_id' => $vanbandv->id,
                                'can_bo_chuyen_id' => $user->id,
                                'can_bo_nhan_id' => $chuTich->id,
                                'noi_dung' => 'Kính chuyển giám đốc ' . $chuTich->ho_ten . ' chỉ đạo',
                                'tom_tat' => $vanbandv->trich_yeu ?? null,
                                'user_id' => $user->id,
                                'tu_tham_muu' => XuLyVanBanDen::TU_VAN_THU,
                                'lanh_dao_chi_dao' => 1,
                                'quyen_gia_han' => 1
                            ];

                            $checkTonTaiData = XuLyVanBanDen::where([
                                'van_ban_den_id' => $vanbandv->id,
                                'can_bo_nhan_id' => $chuTich->id
                            ])
                                ->whereNull('status')
                                ->first();

                            if (empty($checkTonTaiData)) {
                                XuLyVanBanDen::luuXuLyVanBanDen($dataXuLyVanBanDen);
                            }
                        }

                        UserLogs::saveUserLogs('Tạo văn bản đến', $vanbandv);
                    }
                } else {
                    $vanbandv = new VanBanDen();
                    $vanbandv->loai_van_ban_id = $request->loai_van_ban;
                    $vanbandv->so_van_ban_id = $request->so_van_ban;
                    $vanbandv->so_den = $request->so_den;
                    $vanbandv->so_ky_hieu = $request->so_ky_hieu;
                    $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
                    $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                    $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
                    $vanbandv->trich_yeu = $request->trich_yeu;
                    $vanbandv->nguoi_ky = $request->nguoi_ky;
                    $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
                    $vanbandv->do_khan_cap_id = $request->do_khan;
                    $vanbandv->do_bao_mat_id = $request->do_mat;
                    $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                    //$vanbandv->han_giai_quyet = $request->han_xu_ly;
                    $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                    $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                    $vanbandv->type = 1;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->trinh_tu_nhan_van_ban = empty($thamMuuId) ? VanBanDen::CHU_TICH_NHAN_VB : null;
                    $vanbandv->save();

                    // nếu empty tham mưu thì chuyển thẳng giám đốc (chủ tịch)
                    if (empty($thamMuuId)) {
                        $chuTich = User::role(CHU_TICH)
                            ->whereHas('donVi', function ($query) {
                                return $query->whereNull('cap_xa');
                            })->select('id', 'ho_ten', 'don_vi_id')->first();

                        $dataXuLyVanBanDen = [
                            'van_ban_den_id' => $vanbandv->id,
                            'can_bo_chuyen_id' => $user->id,
                            'can_bo_nhan_id' => $chuTich->id,
                            'noi_dung' => 'Kính chuyển giám đốc ' . $chuTich->ho_ten . ' chỉ đạo',
                            'tom_tat' => $vanbandv->trich_yeu ?? null,
                            'user_id' => $user->id,
                            'tu_tham_muu' => XuLyVanBanDen::TU_VAN_THU,
                            'lanh_dao_chi_dao' => 1,
                            'quyen_gia_han' => 1
                        ];

                        $checkTonTaiData = XuLyVanBanDen::where([
                            'van_ban_den_id' => $vanbandv->id,
                            'can_bo_nhan_id' => $chuTich->id
                        ])
                            ->whereNull('status')
                            ->first();

                        if (empty($checkTonTaiData)) {
                            XuLyVanBanDen::luuXuLyVanBanDen($dataXuLyVanBanDen);
                        }
                    }

                    UserLogs::saveUserLogs('Tạo văn bản đến', $vanbandv);
                }

                if ($request->id_file) {
                    $file = FileVanBanDi::where('id', $request->id_file)->first();
                    if ($file) {
                        $vbDenFile = new FileVanBanDen();
                        $vbDenFile->ten_file = $file->ten_file;
                        $vbDenFile->duong_dan = $file->duong_dan;
                        $vbDenFile->duoi_file = $file->duoi_file;
                        $vbDenFile->vb_den_id = $vanbandv->id;
                        $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->save();
                    }

                }
                if ($request->id_van_ban_di) {
                    $layvanbandi = NoiNhanVanBanDi::where('id', $request->id_van_ban_di)->first();
                    if (!empty($layvanbandi)) {
                        $layvanbandi->trang_thai = 3;
                        $layvanbandi->save();
                        DB::commit();
                        return redirect()->route('vanBanDonViGuiSo')->with('success', 'Thêm văn bản thành công !');

                    }
                }
            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
                if (auth::user()->donVi->parent_id != 0) {
                    $thamMuuChiCuc = User::permission(AllPermission::thamMuu())
                        ->whereHas('donVi', function ($query) {
                            return $query->where('parent_id', auth::user()->donVi->parent_id);
                        })->orderBy('id', 'DESC')->first();

                    $trinhTuNhanVanBan = VanBanDen::CHU_TICH_XA_NHAN_VB;
                    if ($thamMuuChiCuc && $user->donVi->parent_id != 0) {
                        $trinhTuNhanVanBan = VanBanDen::THAM_MUU_CHI_CUC_NHAN_VB;
                    }
                }
                if ($noi_dung && $noi_dung[0] != null) {
                    foreach ($noi_dung as $key => $data) {
                        $vanbandv = new VanBanDen();
                        $vanbandv->loai_van_ban_id = $request->loai_van_ban;
                        $vanbandv->so_van_ban_id = $request->so_van_ban;
                        $vanbandv->so_den = $request->so_den;
                        $vanbandv->so_ky_hieu = $request->so_ky_hieu;
                        $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
                        $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                        $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
                        $vanbandv->trich_yeu = $request->trich_yeu;
                        $vanbandv->nguoi_ky = $request->nguoi_ky;
                        $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
                        $vanbandv->do_khan_cap_id = $request->do_khan;
                        $vanbandv->do_bao_mat_id = $request->do_mat;
                        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                        $vanbandv->don_vi_id = auth::user()->donVi->parent_id != 0 ? auth::user()->donVi->parent_id : auth::user()->don_vi_id;
                        $vanbandv->nguoi_tao = auth::user()->id;
                        $vanbandv->type = 2;
                        $vanbandv->noi_dung = $data;
                        if ($request->han_giai_quyet[$key] == null) {
                            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                            //$vanbandv->han_giai_quyet = $request->han_xu_ly;
                        } else {
                            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                            $vanbandv->han_giai_quyet = $han_gq[$key];
                        }
                        $vanbandv->trinh_tu_nhan_van_ban = $trinhTuNhanVanBan;
                        if ($request->don_vi_phoi_hop && $request->don_vi_phoi_hop == 1) {
                            $vanbandv->loai_van_ban_don_vi = 1;
                        }
                        $vanbandv->save();

                        UserLogs::saveUserLogs('Tạo văn bản đến', $vanbandv);

                        if ($request->don_vi_phoi_hop && $request->don_vi_phoi_hop == 1) {
                            DonViPhoiHop::saveDonViPhoiHop($vanbandv->id);
                        } else {
                            //save chuyen don vi chu tri
                            DonViChuTri::saveDonViChuTri($vanbandv->id);
                        }
                    }
                } else {
                    $vanbandv = new VanBanDen();
                    $vanbandv->loai_van_ban_id = $request->loai_van_ban;
                    $vanbandv->so_van_ban_id = $request->so_van_ban;
                    $vanbandv->so_den = $request->so_den;
                    $vanbandv->so_ky_hieu = $request->so_ky_hieu;
                    $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
                    $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                    $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
                    $vanbandv->trich_yeu = $request->trich_yeu;
                    $vanbandv->nguoi_ky = $request->nguoi_ky;
                    $vanbandv->do_khan_cap_id = $request->do_khan;
                    $vanbandv->do_bao_mat_id = $request->do_mat;
                    $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                    $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
                    //$vanbandv->han_giai_quyet = $request->han_xu_ly;
                    $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                    $vanbandv->don_vi_id = auth::user()->donVi->parent_id != 0 ? auth::user()->donVi->parent_id : auth::user()->don_vi_id;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->type = 2;
                    $vanbandv->trinh_tu_nhan_van_ban = $trinhTuNhanVanBan;
                    if ($request->don_vi_phoi_hop && $request->don_vi_phoi_hop == 1) {
                        $vanbandv->loai_van_ban_don_vi = 1;
                    }
                    $vanbandv->save();

                    UserLogs::saveUserLogs('Tạo văn bản đến', $vanbandv);

                    if ($request->don_vi_phoi_hop && $request->don_vi_phoi_hop == 1) {
                        DonViPhoiHop::saveDonViPhoiHop($vanbandv->id);
                    } else {
                        //save chuyen don vi chu tri
                        DonViChuTri::saveDonViChuTri($vanbandv->id);
                    }
                }

            }
            $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
            if($request->File)
            {
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
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

    public function hanXuLyvb(Request $request)
    {
//        dd($request->all());
        $ngaynhan =  formatYMD($request->ngay_nhan);
//        dd($ngaynhan);

        $tieuChuandata = TieuChuanVanBan::where('id', $request->tieu_chuan)->first();
        $songay = $tieuChuandata->so_ngay ?? null;
//        dd($songay);
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }

        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        return response()->json(
            [
                'html' => formatDMY($hangiaiquyet)
            ]
        );
    }

    public function multiple_file(Request $request)
    {
        $user = auth::user();
        $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }
        $txtFiles = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        if (empty($multiFiles) || count($multiFiles) == 0 || (count($multiFiles) > 19)) {
            return redirect()->back()->with('warning', 'Bạn phải chọn file hoặc phải chọn số lượng file nhỏ hơn 20 file   !');
        }
        $soVanBan = SoVanBan::where('ten_so_van_ban', "LIKE", 'công văn')->first();
        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $tenchinhfile = strtolower($typeArray[0]);
            $extFile = $getFile->extension();
            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $urlFile = UPLOAD_FILE_VAN_BAN_DEN . '/' . $fileName;
            $tachchuoi = explode("-", $tenchinhfile);
//            $tenviettatso = strtoupper($tachchuoi[0]);
            $soden = isset($tachchuoi[0]) ? (int)$tachchuoi[0] : null;
            $yearsfile = isset($tachchuoi[2]) ? (int)$tachchuoi[2] : null;

//            dd($soVanBan);

//            $sovanban = SoVanBan::where('ten_viet_tat', 'LIKE', "%$tenviettatso%")->whereNull('deleted_at')->first();
            if ($soVanBan != null) {
                if ($user->hasRole(VAN_THU_HUYEN)) {
                    $vanban = VanBanDen::where(['so_van_ban_id' => $soVanBan->id, 'so_den' => $soden, 'type' => 1])->get();

                } elseif ($user->hasRole(VAN_THU_DON_VI)) {
                    $vanban = VanBanDen::where(['so_van_ban_id' => $soVanBan->id, 'so_den' => $soden, 'don_vi_id' => auth::user()->donVi->parent_id])->whereYear('ngay_ban_hanh', '=', $yearsfile)->get();

                }
                if ($vanban) {
                    $getFile->move($uploadPath, $fileName);

                    foreach ($vanban as $data3) {
                        $vbDenFile = new FileVanBanDen();
                        $vbDenFile->ten_file = $tenchinhfile;
                        $vbDenFile->duong_dan = $urlFile;
                        $vbDenFile->duoi_file = $extFile;
                        $vbDenFile->vb_den_id = $data3->id;
                        $vbDenFile->nguoi_dung_id = auth::user()->id;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->save();
                        UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);
                    }

                }
            }


        }

        return redirect()->back()->with('success', 'Thêm file thành công !');
    }

    public function xoaFileDen($id)
    {
        $vanBanDi = FileVanBanDen::where('id', $id)->first();
            $vanBanDi->delete();
        return redirect()->back()->with('success', 'Xóa file thành công !');
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

    public function chi_tiet_van_ban_den($id)
    {
        canPermission(AllPermission::suaVanBanDen());
        $user = auth::user();
        $van_ban_den = VanBanDen::where('id', $id)->WhereNull('deleted_at')->first();
        $domat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
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

        $users = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
        return view('vanbanden::van_ban_den.edit', compact('van_ban_den', 'domat', 'dokhan', 'loaivanban', 'sovanban', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $noi_dung = !empty($request['noi_dung']) ? $request['noi_dung'] : null;
        $han_giai_quyet = !empty($request['han_giai_quyet']) ? $request['han_giai_quyet'] : null;

        $vanbandv = VanBanDen::where('id', $id)->first();
        $checktrungsoden = VanBanDen::where(['so_van_ban_id' => $request->so_van_ban, 'id' => $vanbandv->id])->first();
        $vanbandv->loai_van_ban_id = $request->loai_van_ban;
        $vanbandv->so_van_ban_id = $request->so_van_ban;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();


        if ($request->so_den != $vanbandv->so_den) {
            $vanbandv->so_den = $request->so_den;
        }
//        } else {
//            if ($checktrungsoden == null) {
//                $user = auth::user();
//                $nam = date("Y");
//                if (auth::user()->hasRole(VAN_THU_HUYEN)) {
//                    $soDenvb = VanBanDen::where([
//                        'don_vi_id' => $lanhDaoSo->don_vi_id,
//                        'so_van_ban_id' => $request->so_van_ban,
//                        'type' => 1
//                    ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
//                } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
//                    $soDenvb = VanBanDen::where([
//                        'don_vi_id' => $user->don_vi_id,
//                        'so_van_ban_id' => $request->so_van_ban,
//                        'type' => 2
//                    ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
//                }
//                $soDenvb = $soDenvb + 1;
//                $vanbandv->so_den = $soDenvb;
//            }
//
//        }

        $vanbandv->so_ky_hieu = $request->so_ky_hieu;
        $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
        $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
        $vanbandv->trich_yeu = $request->trich_yeu;
        $vanbandv->nguoi_ky = $request->nguoi_ky;
        $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
        $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
        $vanbandv->do_khan_cap_id = $request->do_khan;
        $vanbandv->do_bao_mat_id = $request->do_mat;
        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;;
//        $vanbandv->han_giai_quyet = $request->han_giai_quyet;
//        $vanbandv->don_vi_id = auth::user()->don_vi_id;;

        $vanbandv->noi_dung = $noi_dung[0];
        if ($request->han_giai_quyet[0] == null) {
            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
            //$vanbandv->han_giai_quyet = $request->han_xu_ly;
        } else {
            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
            $vanbandv->han_giai_quyet = $han_giai_quyet[0];
        }
//        $vanbandv->han_giai_quyet = $han_giai_quyet[0];
        $vanbandv->save();

        $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
        if ($request->File)
        {
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0775, true, true);
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

        UserLogs::saveUserLogs('Cập nhật văn bản đến', $vanbandv);


        return redirect()->route('van-ban-den.index')->with('success', 'Cập nhật dữ liệu thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function delete_vb_den(Request $request)
    {
        canPermission(AllPermission::xoaVanBanDen());
        $vanbanden = VanBanDen::where('id', $request->id_vb)->first();
        $vanbanden->delete();
        UserLogs::saveUserLogs('Xóa văn bản đến', $vanbanden);
        return redirect()->route('van-ban-den.index')->with('success', 'Xóa thành công !');
    }


    public function dsvanbandentumail(Request $request)
    {
        canPermission(AllPermission::homThuCong());
        $noiguimail = $request->get('noiguimail') ?? null;
        $tinhtrang = $request->get('tinhtrang') ?? 1;
        $getEmail = GetEmail::where(['mail_active' => $tinhtrang])
            ->where(function ($query) use ($noiguimail) {
                if (!empty($noiguimail)) {
                    return $query->where('noigui', $noiguimail);
                }
            })->orderBy('mail_date', 'desc')->paginate(20);
        return view('vanbanden::van_ban_den.dsvanbandentumail', compact('getEmail'));
    }

    public function deleteEmail($id)
    {
        $idEmail = GetEmail::find($id);
        $idEmail->mail_active = 3;
        $idEmail->save();
        return redirect()->back()->with('success', 'Xoá thành công!');

    }

    public function taovbdentumail(Request $request)
    {
        $file_xml = $request->get('xml');
        $id = $request->get('id');
        $file_pdf = $request->get('pdf');
        $file_doc = $request->get('doc');
        $file_xls = $request->get('xls');
        $email = GetEmail::where('id', $id)->first();
        $url_file = 'emailFile_' . substr($email->mail_date, 0, 4) . '/';
        $url_pdf = $url_file . $file_pdf;
        if (isset($file_doc))
            $url_doc = $url_file . $file_doc;
        else
            $url_doc = '';
        if (isset($file_xls))
            $url_xls = $url_file . $file_xls;
        else
            $url_xls = '';
        if (!empty($file_xml)) {
            $conten_xml = file_get_contents($url_file . $file_xml);
        }
        if (!empty($file_xml) && $conten_xml != '') {
            $string = preg_replace('/[\x00-\x1F\x7F]/u', '', $conten_xml);
            if (empty($string)) {
                $string = iconv('UTF-16LE', 'UTF-8', $conten_xml);
            }
            $data_xml = simplexml_load_string($string);

            $data_xml->STRNGAYKY = @date('Y-m-d', strtotime(str_replace('/', '-', $data_xml->STRNGAYKY)));
            if (isset($data_xml->STRNGAYHOP))
                $data_xml->STRNGAYHOP = date('Y-m-d', strtotime(str_replace('/', '-', $data_xml->STRNGAYHOP)));
            else

                $data_xml->STRNGAYHOP = '';
//            dd($data_xml->STRLOAIVANBAN);
            $loaivb_email = LoaiVanBan::where('ten_loai_van_ban', strtolower($data_xml->STRLOAIVANBAN))->first();
            $vb_so_den = null;
//            if (!empty($loaivb_email) && $loaivb_email == 100) {
//
//                $vb_so_den = QlvbVbDenDonVi::where(['so_van_ban_id' => 100, 'trang_thai' => 1])->orderBy('vb_so_den', 'desc')->first()->vb_so_den;
//                if (!empty($vb_so_den)) $vb_so_den = $vb_so_den + 1;
//                else $vb_so_den = 1;
//            } else {
//                $soDen = VbDenDonVi::where(['so_van_ban_id' => $loaivb_email, 'trang_thai' => 1])->max('vb_so_den');
//                dd($loaivb_email);
//                $soDen = empty($soDen) ? 1 : $soDen + 1;
//
//                $vb_so_den = $soDen;
//            }

            //check trung van ban
            if (!empty($data_xml->STRNGAYHOP)) {
                $data_xml->STRNGAYHOP = date('Y-m-d', strtotime(str_replace('/', '-', $data_xml->STRNGAYHOP)));

                $data_trung = VanBanDen::where(['so_ky_hieu' => strtolower($data_xml->STRKYHIEU), 'ngay_hop' => strtolower($data_xml->STRNGAYHOP), 'nguoi_ky' => strtolower($data_xml->STRNGUOIKY)])->first();
            } else {
                $data_trung = VanBanDen::where(['so_ky_hieu' => strtolower($data_xml->STRKYHIEU), 'ngay_ban_hanh' => strtolower($data_xml->STRNGAYKY), 'nguoi_ky' => strtolower($data_xml->STRNGUOIKY)])->first();
            }
        } else {
            $data_xml = null;
            $loaivb_email = null;
            $data_trung = null;
            $vb_so_den = null;
        }

        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')
            ->orderBy('ten_loai_van_ban', 'asc')->get();
        $user = auth::user();
        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [1, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 1])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $user = auth::user();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
        $type = 2;

        //lấy hạn
        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;
        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }
        }
        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        $nam = date("Y");
        $soDenvb = null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $soVanBan = SoVanBan::where('ten_so_van_ban', "LIKE", 'công văn')->first();

        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $soDen = $soDenvb + 1;
        $tieuChuan = TieuChuanVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission(AllPermission::thamMuu())->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
        $date = date("d/m/Y");

        return view('vanbanden::van_ban_den.tao_vb_tu_mail', compact('data_xml', 'ds_loaiVanBan', 'users','tieuChuan',
            'soDen','ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'type', 'email', 'loaivb_email', 'hangiaiquyet', 'date',
            'url_pdf', 'url_doc', 'url_xls', 'id', 'data_trung', 'vb_so_den', 'nguoi_dung'));
    }

    public function hanmail(Request $request)
    {

        $ngaynhan = $request->get('ngay_hop_chinh');
        $songay = 2;
        $ngaynghi = NgayNghi::where('ngayNghi', '>', date('Y-m-d'))->where('trangthai', 1)->orderBy('id', 'desc')->get();
        $i = 0;
        foreach ($ngaynghi as $key => $value) {
            if ($value['ngayNghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngayNghi'] && $value['ngayNghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }
        }
        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        return response()->json(
            [
                'html' => $hangiaiquyet
            ]
        );
    }


    /**
     * Lưu văn bản đến từ mail
     * @param Request $request
     * @return Response
     */
    public function luuvanbantumail(Request $request)
    {
        $requestData = $request->all();
        $user = auth::user();
        $nam = date("Y");
        //vb tu truc
        if (!empty($request->get('type_van_ban'))) {
            $docEmail = DocEmails::where('id', $request->id_vanban_tumail)->first();
            if ($docEmail) {
                $docEmail->status = 1;
                $docEmail->save();
            }
        } else {
            //vb tu mail
            $tbl_email = GetEmail::find($request->id_vanban_tumail);
            $tbl_email->mail_active = 2;
            $tbl_email->save();
        }

        $nam = date("Y");
        $soDenvb = null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $soVanBan = SoVanBan::where('ten_so_van_ban', "LIKE", 'công văn')->first();

        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $soDenvb = $soDenvb + 1;
        if($request->chu_tri_phoi_hop == null)
        {
            $request->chu_tri_phoi_hop = 0;
        }


        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($requestData['noi_dung']) ? $requestData['noi_dung'] : null;
        if ($noi_dung && $noi_dung[0] != null) {
            foreach ($noi_dung as $key => $data) {
                $vanbandv = new VanBanDen();
                $vanbandv->loai_van_ban_id = $request->loai_van_ban;
                $vanbandv->so_van_ban_id = $request->so_van_ban;
                $vanbandv->so_den = $soDenvb;
                $vanbandv->so_ky_hieu = $request->so_ky_hieu;
                $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
                $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
                $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
                $vanbandv->trich_yeu = $request->trich_yeu;
                $vanbandv->nguoi_ky = $request->nguoi_ky;
                $vanbandv->do_khan_cap_id = $request->do_khan;
                $vanbandv->do_bao_mat_id = $request->do_mat;
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                $vanbandv->nguoi_tao = auth::user()->id;
                if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                    $vanbandv->type = 1;
                } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                    $vanbandv->type = 2;
                    $vanbandv->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                    DonViChuTri::saveDonViChuTri($vanbandv->id);
                }
                $vanbandv->noi_dung = $data;
                if ($request->han_giai_quyet[$key] == null) {
                    $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                    //$vanbandv->han_giai_quyet = $request->han_xu_ly;
                } else {
                    $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                    $vanbandv->han_giai_quyet = $han_gq[$key];
                }
                $vanbandv->save();

                if (!empty($request->get('file_pdf'))) {
                    foreach ($requestData['file_pdf'] as $file) {
                        $vbDenFile = new FileVanBanDen();
                        $vbDenFile->ten_file = str_replace('/', '_', $request->vb_so_ky_hieu) . $this->filename_extension($file);
                        $vbDenFile->duong_dan = $file;
                        $vbDenFile->duoi_file = $this->filename_extension($file);
                        $vbDenFile->vb_den_id = $vanbandv->id;
                        $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->save();
                    }
                }
            }
        } else {
            $vanbandv = new VanBanDen();
            $vanbandv->loai_van_ban_id = $request->loai_van_ban;
            $vanbandv->so_van_ban_id = $request->so_van_ban;
            $vanbandv->so_den = $request->so_den;
            $vanbandv->so_ky_hieu = $request->so_ky_hieu;
            $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
            $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
            $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
            $vanbandv->trich_yeu = $request->trich_yeu;
            $vanbandv->nguoi_ky = $request->nguoi_ky;
            $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
            $vanbandv->do_khan_cap_id = $request->do_khan;
            $vanbandv->do_bao_mat_id = $request->do_mat;
            $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
            //$vanbandv->han_giai_quyet = $request->han_xu_ly;
            $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
            $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                $vanbandv->type = 1;
            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $vanbandv->type = 2;
                $vanbandv->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                DonViChuTri::saveDonViChuTri($vanbandv->id);
            }
            $vanbandv->type = 1;
            $vanbandv->nguoi_tao = auth::user()->id;
            $vanbandv->save();
            //upload file
            if (!empty($request->get('file_pdf'))) {
                foreach ($requestData['file_pdf'] as $file) {
                    $vbDenFile = new FileVanBanDen();
                    $vbDenFile->ten_file = str_replace('/', '_', $request->vb_so_ky_hieu) . $this->filename_extension($file);
                    $vbDenFile->duong_dan = $file;
                    $vbDenFile->duoi_file = $this->filename_extension($file);
                    $vbDenFile->vb_den_id = $vanbandv->id;
                    $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                    $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                    $vbDenFile->save();
                }
            }

        }

        return redirect()->route('dsvanbandentumail')->with('success', 'Thêm văn bản thành công ! !');

    }

    public function luuGiayMoiMail(Request $request)
    {
        $requestData = $request->all();
        $idvanbanden = [];
        $user = auth::user();
        $nam = date("Y");
        //vb tu truc
        if (!empty($request->get('type_van_ban'))) {
            $docEmail = DocEmails::where('id', $request->id_vanban_tumail)->first();
            if ($docEmail) {
                $docEmail->status = 1;
                $docEmail->save();
            }
        } else {
            //vb tu mail
            $tbl_email = GetEmail::find($request->id_vanban_tumail);
            $tbl_email->mail_active = 2;
            $tbl_email->save();
        }
        $nam = date("Y");
        $soDenvb = null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $soVanBan = SoVanBan::where('ten_so_van_ban', "LIKE", 'công văn')->first();

        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $soVanBan->id,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $soDenvb = $soDenvb + 1;
        $han_gq = $request->han_giai_quyet;
        $gio_hop_chinh_fomart = date('H:i', strtotime($request->gio_hop_chinh));
        $giaymoicom = !empty($requestData['noi_dung_hop_con']) ? $requestData['noi_dung_hop_con'] : null;
        if($request->chu_tri_phoi_hop == null)
        {
            $request->chu_tri_phoi_hop = 0;
        }
        try {
            DB::beginTransaction();
            $sokyhieu = $request->so_ky_hieu;
            $nguoiky = $request->nguoi_ky;
            $coquanbanhanh = $request->co_quan_ban_hanh;
            $loaivanban = $request->loai_van_ban;
            $trichyeu = $request->trich_yeu;
            //họp chính
            $giohopchinh = $gio_hop_chinh_fomart;
            $ngayhopchinh = $request->ngay_hop_chinh;
            $diadiemchinh = $request->dia_diem_chinh;
            //họp phụ
            $giohopcon = $request->gio_hop_con;
            $ngay_hop_con = $request->ngay_hop_con;
            $dia_diem_con = $request->dia_diem_con;
            $ngaybanhanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
            $chucvu = $request->chuc_vu;

            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                if ($giaymoicom && $giaymoicom[0] != null) {
                    foreach ($giaymoicom as $key => $data) {
                        $vanbandv = new VanBanDen();

                        $vanbandv->so_van_ban_id = $request->so_van_ban;
                        $vanbandv->so_den = $soDenvb;
                        $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                        $vanbandv->nguoi_tao = auth::user()->id;
                        $vanbandv->so_ky_hieu = $sokyhieu;
                        $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
                        $vanbandv->nguoi_ky = $nguoiky;
                        $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                        $vanbandv->han_xu_ly = $request->han_xu_ly ? formatYMD($request->han_xu_ly) : null;
                        $vanbandv->ngay_nhan = $request->ngay_nhan ? formatYMD($request->ngay_nhan) : null;
                        //$vanbandv->han_giai_quyet = $request->han_xu_ly;
                        $vanbandv->loai_van_ban_id = $loaivanban;
                        $vanbandv->type = 1;
                        $vanbandv->trich_yeu = $trichyeu;
                        //họp chính
                        $vanbandv->gio_hop = $giohopchinh;
                        $vanbandv->ngay_hop = $ngayhopchinh;
                        $vanbandv->dia_diem = $diadiemchinh;
                        //họp con
                        if ($request->gio_hop_con[$key] == null) {
                            $vanbandv->gio_hop_phu = $gio_hop_chinh_fomart;
                        } else {
                            $gio_hop_phu = date('H:i', strtotime($giohopcon[$key]));

                            $vanbandv->gio_hop_phu = $gio_hop_phu;
                        }
                        if ($request->dia_diem_con[$key] == null) {
                            $vanbandv->dia_diem_phu = $diadiemchinh;
                        } else {
                            $vanbandv->dia_diem_phu = $dia_diem_con[$key];
                        }
                        if ($request->ngay_hop_con[$key] == null) {
                            $vanbandv->ngay_hop_phu = $ngayhopchinh;
                        } else {
                            $vanbandv->ngay_hop_phu = $ngay_hop_con[$key];
                        }

                        $vanbandv->noi_dung = $data;
                        $vanbandv->ngay_ban_hanh = $ngaybanhanh;
//                        $vanbandv->chuc_vu = $chucvu;
                        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                        $vanbandv->save();
                        array_push($idvanbanden, $vanbandv->id);
                    }
                } else {
                    $vanbandv = new VanBanDen();
                    $vanbandv->so_van_ban_id = $request->so_van_ban;
                    $vanbandv->so_den = $soDenvb;
                    $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->so_ky_hieu = $sokyhieu;
                    $vanbandv->nguoi_ky = $nguoiky;
                    $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                    $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                    $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                    $vanbandv->loai_van_ban_id = $loaivanban;
                    $vanbandv->trich_yeu = $trichyeu;
//                    $vanbandv->chuc_vu = $chucvu;
                    $vanbandv->type = 1;
                    //họp chính
                    $vanbandv->gio_hop = $gio_hop_chinh_fomart;
                    $vanbandv->ngay_hop = $ngayhopchinh;
                    $vanbandv->dia_diem = $diadiemchinh;
                    //nếu không tách nhỏ thì họp con sẽ là họp chính
                    $vanbandv->gio_hop_phu = $gio_hop_chinh_fomart;
                    $vanbandv->ngay_hop_phu = $ngayhopchinh;
                    $vanbandv->dia_diem_phu = $diadiemchinh;
                    $vanbandv->ngay_ban_hanh = $ngaybanhanh;
                    $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                    $vanbandv->save();
                    array_push($idvanbanden, $vanbandv->id);
                }

                if (!empty($request->get('file_pdf'))) {
                    foreach ($requestData['file_pdf'] as $file) {
                        $vbDenFile = new FileVanBanDen();
                        $vbDenFile->ten_file = str_replace('/', '_', $request->vb_so_ky_hieu) . $this->filename_extension($file);
                        $vbDenFile->duong_dan = $file;
                        $vbDenFile->duoi_file = $this->filename_extension($file);
                        $vbDenFile->vb_den_id = $vanbandv->id;
                        $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->save();
                    }
                }
            }
            UserLogs::saveUserLogs('Tạo giấy mời đến ', $vanbandv);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Thêm văn bản thành công !');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);


        }
    }

    public function kiemTraTrichYeu(Request $request)
    {
        $user = auth::user();
        $so_ky_hieu = $request->input('so_ky_hieu');
        $ngay_ban_hanh = $request->input('ngay_ban_hanh');
        $data = VanBanDen::where(['so_ky_hieu' => $so_ky_hieu, 'ngay_ban_hanh' => $ngay_ban_hanh])->orderBy('id', 'desc')->take(5)->get();
        $ds_nguoiDung = User::orderBy('created_at', 'desc')->get(['id', 'ho_ten'])->toArray();
        $ds_nguoiDung = array_column($ds_nguoiDung, 'ho_ten', 'id');
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get(['id', 'ho_ten'])->toArray();
        $ds_nguoiKy = array_column($ds_nguoiKy, 'ho_ten', 'id');
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get(['id', 'ten_loai_van_ban'])->toArray();
        $ds_loaiVanBan = array_column($ds_loaiVanBan, 'ten_loai_van_ban', 'id');
        $returnHTML = $data->isNotEmpty() ? view('vanbanden::van_ban_den.check_trung_van_ban',
            compact('data', 'ds_nguoiDung', 'ds_nguoiKy', 'ds_loaiVanBan'))->render() : '';
        return response()->json(
            [
                'is_relate' => $data->isNotEmpty() ? true : false,
                'html' => $returnHTML
            ]
        );
    }

    function filename_extension($filename)
    {
        $pos = strrpos($filename, '.');
        if ($pos === false) {
            return false;
        } else {
            return substr($filename, $pos + 1);
        }
    }

    public function createXuLyVanBanDenLanhDao($vanBanDenId)
    {

    }

}










