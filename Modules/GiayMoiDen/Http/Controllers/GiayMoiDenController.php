<?php

namespace Modules\GiayMoiDen\Http\Controllers;

use App\Common\AllPermission;
use App\Models\UserLogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\NgayNghi;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;
use File, auth, DB;
use Modules\VanBanDen\Entities\VanBanDenDonVi;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;

class GiayMoiDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $donVi = auth::user()->donVi;
        $user = auth::user();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

        //search
        $trichyeu = $request->get('trich_yeu');
        $so_ky_hieu = $request->get('vb_so_ky_hieu');
        $co_quan_ban_hanh = $request->get('co_quan_ban_hanh_id');
        $so_den = $request->get('vb_so_den');
        $so_van_ban = $request->get('so_van_ban_id');
        $nguoi_ky = $request->get('nguoi_ky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $year = $request->get('year') ?? null;
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id', 'ten_loai_van_ban')->first();


        if($user->hasRole(VAN_THU_HUYEN) || ($user->hasRole(CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA) || ( $user->hasRole(PHO_CHU_TICH )&& $donVi->cap_xa != DonVi::CAP_XA)) {
            $ds_vanBanDen = VanBanDen::where([
                'type' => 1,
                'so_van_ban_id' => 100
            ])->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
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
                })->where(function ($query) use ($so_van_ban) {
                    if (!empty($so_van_ban)) {
                        return $query->where('so_van_ban_id', "$so_van_ban");
                    }
                })
                ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                    if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {
                        return $query->where('vb_ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('vb_ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        $ngaybatdau = $ngayketthuc;
                        return $query->where('vb_ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('vb_ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau != '' && $ngayketthuc == '') {
                        $ngayketthuc = $ngaybatdau;
                        return $query->where('vb_ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('vb_ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                })
                ->where(function ($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);

        }
        else{

            $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;
            $ds_vanBanDen = VanBanDen::where([
                'don_vi_id' => $donViId,
                'type' => 2,
                'so_van_ban_id' => 100
            ])->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
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
                })->where(function ($query) use ($so_van_ban) {
                    if (!empty($so_van_ban)) {
                        return $query->where('so_van_ban_id', "$so_van_ban");
                    }
                })
                ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                    if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {
                        return $query->where('vb_ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('vb_ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        $ngaybatdau = $ngayketthuc;
                        return $query->where('vb_ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('vb_ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau != '' && $ngayketthuc == '') {
                        $ngayketthuc = $ngaybatdau;
                        return $query->where('vb_ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('vb_ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                })
                ->where(function ($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        }


        return view('giaymoiden::giay_moi_den.index', compact('ds_vanBanDen'));
    }

    public function layhantruyensangview(Request $request)
    {
        //lấy hạn
        $ngaynhan = $request->get('ngay_ban_hanh');
        $songay = 2;
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
                'html' => $hangiaiquyet
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themGiayMoiDen());
        $user = auth::user();

//        $soDen = VanBanDen::where([
//            'don_vi_id' => auth::user()->don_vi_id,
//            'so_van_ban_id' => 100
//        ])->max('so_den');
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $donVi = auth::user()->donVi;
        $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;
        $nam = date("Y");
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $donViId,
                'so_van_ban_id' => 100,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $sodengiaymoi = $soDenvb + 1;
        $date = Carbon::now()->format('Y-m-d');
//        dd($date);
//        dd($soDen);
//        $sodengiaymoi = $soDen + 1;
        $gioHop = [];

        for ($i = 6; $i <= 20; $i++) {
            if ($i < 10) {
                $i = '0' . $i;
            }
            $gioHop[] = $i . ':00';
            $gioHop[] = $i . ':15';
            $gioHop[] = $i . ':30';
            $gioHop[] = $i . ':45';
        }


        $user = auth::user();
        $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHU_TICH, TRUONG_PHONG, PHO_PHONG])->orderBy('username', 'desc')->get();

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
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();


        $ngaynhan = date('Y-m-d');
        $songay = 8;
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


        return view('giaymoiden::giay_moi_den.create', compact('ds_nguoiKy', 'ds_soVanBan', 'ds_loaiVanBan',
            'ds_doKhanCap', 'ds_mucBaoMat', 'sodengiaymoi',
            'gioHop', 'date', 'nguoi_dung', 'hangiaiquyet'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $nam = date("Y");
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $sodengiaymoi = $soDenvb + 1;
        $gio_hop_chinh_fomart = date('H:i', strtotime($request->gio_hop_chinh));
        $requestData = $request->all();
        $multiFiles = !empty($requestData['ten_file']) ? $requestData['ten_file'] : null;
        $giaymoicom = !empty($requestData['noi_dung_hop_con']) ? $requestData['noi_dung_hop_con'] : null;
        $uploadPath = UPLOAD_FILE_GIAY_MOI_DEN;
        $txtFiles = !empty($requestData['txt_file']) ? $requestData['txt_file'] : null;
        $idvanbanden = [];
        $thamMuuId = $request->lanh_dao_tham_muu ?? null;
        $user = auth::user();
        try {
            DB::beginTransaction();
            $sokyhieu = $request->so_ky_hieu;
            $nguoiky = $request->nguoi_ky_id;
            $coquanbanhanh = $request->co_quan_ban_hanh_id;
            $loaivanban = $request->loai_van_ban_id;
            $trichyeu = $request->vb_trich_yeu;
            //họp chính
            $giohopchinh = $gio_hop_chinh_fomart;
            $ngayhopchinh = $request->ngay_hop_chinh;
            $diadiemchinh = $request->dia_diem_chinh;
            //họp phụ
            $giohopcon = $request->gio_hop_con;
            $ngay_hop_con = $request->ngay_hop_con;
            $dia_diem_con = $request->dia_diem_con;
            $ngaybanhanh = $request->ngay_ban_hanh;
            $chucvu = $request->chuc_vu;

            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                if ($giaymoicom && $giaymoicom[0] != null) {
                    foreach ($giaymoicom as $key => $data) {
                        $vanbandv = new VanBanDen();

                        $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                        $vanbandv->so_den = $sodengiaymoi;
                        $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                        $vanbandv->nguoi_tao = auth::user()->id;
                        $vanbandv->so_ky_hieu = $sokyhieu;
                        $vanbandv->nguoi_ky = $nguoiky;
                        $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                        $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->vb_han_xu_ly;
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
                        $vanbandv->chuc_vu = $chucvu;
                        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                        $vanbandv->trinh_tu_nhan_van_ban = empty($thamMuuId) ? VanBanDen::CHU_TICH_NHAN_VB : null;
                        $vanbandv->save();
                        array_push($idvanbanden, $vanbandv->id);

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
                    }
                } else {
                    $vanbandv = new VanBanDen();
                    $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                    $vanbandv->so_den = $sodengiaymoi;
                    $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->so_ky_hieu = $sokyhieu;
                    $vanbandv->nguoi_ky = $nguoiky;
                    $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                    $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                    $vanbandv->loai_van_ban_id = $loaivanban;
                    $vanbandv->trich_yeu = $trichyeu;
                    $vanbandv->chuc_vu = $chucvu;
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
                    $vanbandv->trinh_tu_nhan_van_ban = empty($thamMuuId) ? VanBanDen::CHU_TICH_NHAN_VB : null;
                    $vanbandv->save();
                    array_push($idvanbanden, $vanbandv->id);

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
                if($request->id_van_ban_di){
                    $layvanbandi = NoiNhanVanBanDi::where('id', $request->id_van_ban_di)->first();
                    if (!empty($layvanbandi)) {
                        $layvanbandi->trang_thai = 3;
                        $layvanbandi->save();

                    }
                    DB::commit();

                    return redirect()->route('vanBanDonViGuiSo')->with('success', 'Thêm văn bản thành công !');
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

                if ($giaymoicom && $giaymoicom[0] != null) {
                    foreach ($giaymoicom as $key => $data) {
                        $vanbandv = new VanBanDen();
                        $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                        $vanbandv->so_den = $sodengiaymoi;
                        $vanbandv->don_vi_id = auth::user()->donVi->parent_id != 0 ? auth::user()->donVi->parent_id : auth::user()->don_vi_id;
                        $vanbandv->nguoi_tao = auth::user()->id;
                        $vanbandv->so_ky_hieu = $sokyhieu;
                        $vanbandv->nguoi_ky = $nguoiky;
                        $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                        $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->vb_han_xu_ly;
                        $vanbandv->loai_van_ban_id = $loaivanban;
                        $vanbandv->trich_yeu = $trichyeu;
                        $vanbandv->type = 2;
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

                        if ($request->don_vi_phoi_hop && $request->don_vi_phoi_hop == 1) {
                            $vanbandv->loai_van_ban_don_vi = 1;
                        }
                        $vanbandv->noi_dung = $data;
                        $vanbandv->ngay_ban_hanh = $ngaybanhanh;
                        $vanbandv->chuc_vu = $chucvu;
                        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                        $vanbandv->trinh_tu_nhan_van_ban = $trinhTuNhanVanBan;
                        $vanbandv->save();
                        array_push($idvanbanden, $vanbandv->id);
                        if ($request->don_vi_phoi_hop) {
                            DonViPhoiHop::saveDonViPhoiHop($vanbandv->id);
                            $vanbandv->loai_van_ban_don_vi = 1;
                        } else {
                            DonViChuTri::saveDonViChuTri($vanbandv->id);
                        }
                    }
                } else {
                    $vanbandv = new VanBanDen();
                    if ($request->don_vi_phoi_hop && $request->don_vi_phoi_hop == 1) {
                        $vanbandv->loai_van_ban_don_vi = 1;
                    }
                    $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                    $vanbandv->so_den = $sodengiaymoi;
                    $vanbandv->don_vi_id = auth::user()->donVi->parent_id != 0 ? auth::user()->donVi->parent_id : auth::user()->don_vi_id;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->so_ky_hieu = $sokyhieu;
                    $vanbandv->nguoi_ky = $nguoiky;
                    $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                    $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                    $vanbandv->loai_van_ban_id = $loaivanban;
                    $vanbandv->trich_yeu = $trichyeu;
                    $vanbandv->chuc_vu = $chucvu;
                    $vanbandv->type = 2;
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
                    $vanbandv->trinh_tu_nhan_van_ban = $trinhTuNhanVanBan;
                    $vanbandv->save();
                    if ($request->don_vi_phoi_hop && $request->don_vi_phoi_hop == 1) {
                        DonViPhoiHop::saveDonViPhoiHop($vanbandv->id);
                    } else {
                        DonViChuTri::saveDonViChuTri($vanbandv->id);
                    }
                    array_push($idvanbanden, $vanbandv->id);
                }
            }
            UserLogs::saveUserLogs('Tạo giấy mời đến ', $vanbandv);

            if ($multiFiles && count($multiFiles) > 0) {
                foreach ($multiFiles as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $ten = strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile;

                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                    $urlFile = UPLOAD_FILE_GIAY_MOI_DEN . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    if (count($idvanbanden) > 1) {
                        $getFile->move($uploadPath, $fileName);
                        foreach ($idvanbanden as $data) {
                            $vbDenFile = new FileVanBanDen();
                            $vbDenFile->ten_file = $ten;
                            $vbDenFile->duong_dan = $urlFile;
                            $vbDenFile->duoi_file = $extFile;
                            $vbDenFile->vb_den_id = $data;
                            $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                            $vbDenFile->don_vi_id = auth::user()->donVi->parent_id != 0 ? auth::user()->donVi->parent_id : auth::user()->don_vi_id;
                            $vbDenFile->save();
                        }

                    } else {
                        $vbDenFile = new FileVanBanDen();
                        $getFile->move($uploadPath, $fileName);
                        $vbDenFile->ten_file = $ten;
                        $vbDenFile->duong_dan = $urlFile;
                        $vbDenFile->duoi_file = $extFile;
                        $vbDenFile->vb_den_id = $vanbandv->id;
                        $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->save();
                    }

                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Thêm văn bản thành công !');

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();


        }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('giaymoiden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        canPermission(AllPermission::suaGiayMoiDen());
        $giayMoi = SoVanBan::where('ten_so_van_ban', "LIKE", 'Giấy mời')->first();
        $vanban = VanBanDen::where('id', $id)->first();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $nam = date("Y");

        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDen = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => $giayMoi->id,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDen = VanBanDen::where([
                'don_vi_id' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $giayMoi->id,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
//        $soDen = VanBanDen::where([
//            'don_vi_id' => auth::user()->don_vi_id,
//            'so_van_ban_id' => 100,
//
//        ])->max('so_den');

        $sodengiaymoi = $soDen + 1;
        $user = auth::user();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

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
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

        return view('giaymoiden::giay_moi_den.edit', compact('vanban', 'sodengiaymoi', 'ds_loaiVanBan', 'ds_nguoiKy', 'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'nguoi_dung'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
//        dd($request->all());
        $giaymoicom = !empty($request['noi_dung_hop_con']) ? $request['noi_dung_hop_con'] : null;
        $ngay_hop_con = !empty($request['ngay_hop_con']) ? $request['ngay_hop_con'] : null;
        $dia_diem_con = !empty($request['dia_diem_con']) ? $request['dia_diem_con'] : null;
        $giohopcon = !empty($request['gio_hop_con']) ? $request['gio_hop_con'] : null;
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        $txtFiles = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $gio_hop = date('H:i', strtotime($request->gio_hop_chinh));
        $giayMoi = SoVanBan::where('ten_so_van_ban', "LIKE", 'Giấy mời')->first();
        $uploadPath = UPLOAD_FILE_GIAY_MOI_DEN;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();


        $nam = date("Y");
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => $giayMoi->id,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $giayMoi->id,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        }
        $vanbandv = VanBanDen::where('id', $id)->first();
        $checktrungsoden = VanBanDen::where(['so_van_ban_id' => $giayMoi->id, 'id' => $vanbandv->id])->first();

        if ($request->vb_so_den != $vanbandv->so_den)
        {
            $vanbandv->so_den = $request->vb_so_den;
        }else{
            if ($checktrungsoden == null) {
                $user = auth::user();
                $nam = date("Y");
                if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                    $soDenvb = VanBanDen::where([
                        'don_vi_id' => $lanhDaoSo->don_vi_id,
                        'so_van_ban_id' => $request->so_van_ban_id,
                        'type' => 1
                    ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
                } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                    $soDenvb = VanBanDen::where([
                        'don_vi_id' => $user->donVi->parent_id,
                        'so_van_ban_id' => $request->so_van_ban_id,
                        'type' => 2
                    ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
                }
                $soDenvb = $soDenvb + 1;
                $vanbandv->so_den = $soDenvb;
            }
        }




        $vanbandv->so_van_ban_id = $request->so_van_ban_id;


        $vanbandv->so_ky_hieu = $request->vb_so_ky_hieu;
        $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh_id;
        $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
        $vanbandv->trich_yeu = $request->trich_yeu;
        $vanbandv->ngay_ban_hanh = $request->vb_ngay_ban_hanh;

        $vanbandv->gio_hop = $gio_hop;
        $vanbandv->ngay_hop = $request->ngay_hop_chinh;
        $vanbandv->dia_diem = $request->dia_diem_chinh;
        if ($giaymoicom && $giaymoicom[0] != null) {
            $gio_hop_phu = date('H:i', strtotime($giohopcon[0]));
            $vanbandv->gio_hop_phu = $gio_hop_phu;
            $vanbandv->ngay_hop_phu = $ngay_hop_con[0];
            $vanbandv->dia_diem_phu = $dia_diem_con[0];
//            $vanbandv->noi_dung_hop = $giaymoicom[0];
        } else {

            $vanbandv->gio_hop_phu = $gio_hop;
            $vanbandv->ngay_hop_phu = $request->ngay_hop_chinh;
            $vanbandv->dia_diem_phu = $request->dia_diem_chinh;
        }
//        họp phụ


        $vanbandv->nguoi_ky = $request->nguoi_ky_id;
        $vanbandv->chuc_vu = $request->chuc_vu;

        $vanbandv->save();
        UserLogs::saveUserLogs('Sửa giấy mời đến ', $vanbandv);
        if ($multiFiles && count($multiFiles) > 0) {
            $vanbandenfile = FileVanBanDen::where('vb_den_id', $vanbandv->id)->get();
            foreach ($vanbandenfile as $filevb) {
                $fileid = FileVanBanDen::where('id', $filevb->id)->first();
                $fileid->delete();
                $fileid->save();
            }
            foreach ($multiFiles as $key => $getFile) {
                $extFile = $getFile->extension();
                $ten = strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile;
                $vbDenFile = new FileVanBanDen();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_GIAY_MOI_DEN . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $vbDenFile->ten_file = $ten;
                $vbDenFile->duong_dan = $urlFile;
                $vbDenFile->duoi_file = $extFile;
                $vbDenFile->vb_den_id = $vanbandv->id;
                $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();

            }

        }
        return redirect()->route('giay-moi-den.index')
            ->with('success', 'Cập nhật giấy mời thành công !');

    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaGiayMoiDen());
        $giaymoi = VanBanDen::where('id', $id)->first();
        $giaymoi->delete();
        UserLogs::saveUserLogs('Xóa giấy mời đến ', $giaymoi);
        return redirect()->back()
            ->with('success', 'Xóa giấy mời thành công !');
    }
}
