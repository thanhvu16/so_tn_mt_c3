<?php

namespace Modules\VanBanDi\Http\Controllers;

use App\Common\AllPermission;
use App\Jobs\SendEmailFileVanBanDi;
use App\Models\LichCongTac;
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
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\MailTrongThanhPho;
use Modules\Admin\Entities\NhomDonVi;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\LayVanBanTuEmail\Entities\EmailDonVi;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\Fileduthao;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanMail;
use Modules\VanBanDi\Entities\NoiNhanMailNgoai;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use Modules\VanBanDi\Entities\VanBanDi;
use auth, File, DB;
use Modules\VanBanDi\Entities\VanBanDiChoDuyet;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanDiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $user = auth::user();


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
        $year = $request->get('year') ?? null;
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
//        $ds_vanBanDi = VanBanDi::where('loai_van_ban_giay_moi',1)->whereNull('deleted_at')

        if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHUC_TICH)) {
            //đây là văn bản của huyện
            $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'don_vi_soan_thao' => null])->where('so_di', '!=', null)->whereNull('deleted_at')
                ->where(function ($query) use ($don_vi_van_ban) {
                    if ($don_vi_van_ban == 2) {
                        //văn bản huyện
                        if (!empty($don_vi_van_ban)) {
                            return $query->where('don_vi_soan_thao', UBND_HUYEN)->whereNull('van_ban_huyen_ky');
                        }
                    } else {
                        //văn bản đơn vị
                        if (!empty($don_vi_van_ban)) {
                            return $query->where('don_vi_soan_thao', auth::user()->don_vi_id)->where('van_ban_huyen_ky', '!=', '');
                        }
                    }

                    if (!empty($don_vi_van_ban)) {
                        return $query->where('trich_yeu', 'LIKE', "%$don_vi_van_ban%");
                    }
                })
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
                    }
                })
                ->where(function ($query) use ($chucvu) {
                    if (!empty($chucvu)) {
                        return $query->where('chuc_vu', 'LIKE', "%$chucvu%");
                    }
                })
                ->where(function ($query) use ($nguoi_ky) {
                    if (!empty($nguoi_ky)) {
                        return $query->where('nguoi_ky', $nguoi_ky);
                    }
                })->where(function ($query) use ($loaivanban) {
                    if (!empty($loaivanban)) {
                        return $query->where('loai_van_ban_id', $loaivanban);
                    }
                })->where(function ($query) use ($donvisoanthao) {
                    if (!empty($donvisoanthao)) {
                        return $query->where('don_vi_soan_thao', $donvisoanthao);
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
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        } elseif ($user->hasRole(CHUYEN_VIEN) || $user->hasRole(PHO_PHONG) ||
            $user->hasRole(TRUONG_PHONG) || $user->hasRole(VAN_THU_DON_VI) ||
            $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
            //đây là văn bản của đơn vị
            $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'van_ban_huyen_ky' => auth::user()->don_vi_id])->where('so_di', '!=', null)->whereNull('deleted_at')
                ->where(function ($query) use ($don_vi_van_ban) {
                    if ($don_vi_van_ban == 2) {
                        //văn bản huyện
                        if (!empty($don_vi_van_ban)) {
                            return $query->where('don_vi_soan_thao', UBND_HUYEN)->where('don_vi_soan_thao', '!=', auth::user()->don_vi_id);
                        }
                    } else {
                        //văn bản đơn vị
                        if (!empty($don_vi_van_ban)) {
                            return $query->where('don_vi_soan_thao', auth::user()->don_vi_id)->where('don_vi_soan_thao', '!=', UBND_HUYEN);
                        }
                    }

                    if (!empty($don_vi_van_ban)) {
                        return $query->where('trich_yeu', 'LIKE', "%$don_vi_van_ban%");
                    }
                })
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
                    }
                })
                ->where(function ($query) use ($chucvu) {
                    if (!empty($chucvu)) {
                        return $query->where('chuc_vu', 'LIKE', "%$chucvu%");
                    }
                })
                ->where(function ($query) use ($nguoi_ky) {
                    if (!empty($nguoi_ky)) {
                        return $query->where('nguoi_ky', $nguoi_ky);
                    }
                })->where(function ($query) use ($loaivanban) {
                    if (!empty($loaivanban)) {
                        return $query->where('loai_van_ban_id', $loaivanban);
                    }
                })->where(function ($query) use ($donvisoanthao) {
                    if (!empty($donvisoanthao)) {
                        return $query->where('don_vi_soan_thao', $donvisoanthao);
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
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        }


        return view('vanbandi::van_ban_di.index', compact('ds_vanBanDi', 'ds_loaiVanBan', 'ds_soVanBan', 'ds_DonVi', 'ds_nguoiKy'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themVanBanDi());
        $user = auth::user();
        $donVi = $user->donVi;
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [2, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 2])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->where('dieu_hanh', 1)->get();
        $nguoinhan = null;
        $vanThuVanBanDiPiceCharts = [];
        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi','LIKE',LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi',$nhomDonVi->id)->first();
        $ds_nguoiKy = null;

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if (empty($donVi->cap_xa)) {
                    $truongpho = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    foreach ($truongpho as $data2) {
                        array_push($vanThuVanBanDiPiceCharts, $data2);
                    }
                    $chanvanphong = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                    foreach ($chanvanphong as $data) {
                        array_push($vanThuVanBanDiPiceCharts, $data);
                    }
                    $ds_nguoiKy = $vanThuVanBanDiPiceCharts;
                } else {
                    $ds_nguoiKy = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $truongpho = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($truongpho as $data2) {
                    array_push($vanThuVanBanDiPiceCharts, $data2);
                }
                $chanvanphong = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                foreach ($chanvanphong as $data) {
                    array_push($vanThuVanBanDiPiceCharts, $data);
                }
                $ds_nguoiKy = $vanThuVanBanDiPiceCharts;
                break;
            case TRUONG_PHONG:
                $ds_nguoiKy = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case PHO_CHUC_TICH:
                if (empty($donVi->cap_xa)) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $ds_nguoiKy = null;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHUC_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                    $ds_nguoiKy = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donViCapHuyen->id)->get();
                break;
            case PHO_CHANH_VAN_PHONG:
                $ds_nguoiKy = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case TRUONG_BAN:
                $ds_nguoiKy = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                break;
            case PHO_TRUONG_BAN:
                $ds_nguoiKy = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

        }
        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case PHO_CHUC_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = null;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHUC_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donViCapHuyen->id)->get();
                }
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:

                $nguoinhan = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case TRUONG_BAN:
                $nguoinhan = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                break;
            case PHO_TRUONG_BAN:
                $nguoinhan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

        }
        return view('vanbandi::van_ban_di.create', compact('ds_nguoiKy',
            'ds_soVanBan', 'ds_loaiVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'ds_DonVi', 'nguoinhan', 'ds_DonVi_nhan', 'emailtrongthanhpho', 'emailngoaithanhpho'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
            $tenfilehoso = !empty($request['txt_file']) ? $request['txt_file'] : null;
            $filehoso = !empty($request['file_name']) ? $request['file_name'] : null;
            $filephieutrinh = !empty($request['file_phieu_trinh']) ? $request['file_phieu_trinh'] : null;
            $filetrinhky = !empty($request['file_trinh_ky']) ? $request['file_trinh_ky'] : null;
            $donvinhanmailtrongtp = !empty($request['don_vi_nhan_trong_thanh_php']) ? $request['don_vi_nhan_trong_thanh_php'] : null;
            $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
            $donvinhanvanbandi = !empty($request['don_vi_nhan_van_ban_di']) ? $request['don_vi_nhan_van_ban_di'] : null;
            $nguoiky = User::where('id', $request->nguoiky_id)->first();
            $user = auth::user();

            $vanbandi = new VanBanDi();
            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHUC_TICH ||
                $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //đây là huyện ký
            {
                if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHUC_TICH) ||
                    $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                    //đây là huyện soạn thảo và huyện ký
//                    $vanbandi->don_vi_soan_thao = '';
                } else {//đây là đơn vị soạn thảo do huyện ký
//                    $vanbandi->don_vi_soan_thao = '';
                    $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                }
                $vanbandi->type = 1;
            } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG || $nguoiky->role_id == QUYEN_VAN_THU_DON_VI) {
                //đây là đơn vị ký
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                $vanbandi->don_vi_soan_thao = $request->donvisoanthao_id;
                $vanbandi->type = 2;
            }

            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->loai_van_ban_giay_moi = 1;
            $vanbandi->nguoi_tao = auth::user()->id;
            $vanbandi->save();
            UserLogs::saveUserLogs(' Tạo văn bản đi', $vanbandi);

            $canbonhan = new VanBanDiChoDuyet();
            $canbonhan->van_ban_di_id = $vanbandi->id;
            $canbonhan->can_bo_chuyen_id = $vanbandi->nguoi_tao;
            $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
            $canbonhan->save();


            if ($donvinhanvanbandi && count($donvinhanvanbandi) > 0) {
                foreach ($donvinhanvanbandi as $key => $donvi) {
                    $donvinhan = new NoiNhanVanBanDi();
                    $donvinhan->van_ban_di_id = $vanbandi->id;
                    $donvinhan->don_vi_id_nhan = $donvi;
                    $donvinhan->save();
                }
            }

            if ($filehoso && count($filehoso) > 0) {
                foreach ($filehoso as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $ten = strSlugFileName(strtolower($tenfilehoso[$key]), '_') . '.' . $extFile;
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);

                    $vbDiFile->ten_file = isset($ten) ? $ten : $fileName;
                    $vbDiFile->duong_dan = $urlFile;
                    $vbDiFile->duoi_file = $extFile;
                    $vbDiFile->van_ban_di_id = $vanbandi->id;
                    $vbDiFile->nguoi_dung_id = auth::user()->id;
                    $vbDiFile->don_vi_id = auth::user()->donvi_id;
                    $vbDiFile->trang_thai = 3;
                    $vbDiFile->save();

                }

            }
            if ($filephieutrinh && count($filephieutrinh) > 0) {
                foreach ($filephieutrinh as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);

                    $vbDiFile->ten_file = $fileName;
                    $vbDiFile->duong_dan = $urlFile;
                    $vbDiFile->duoi_file = $extFile;
                    $vbDiFile->van_ban_di_id = $vanbandi->id;
                    $vbDiFile->nguoi_dung_id = auth::user()->id;
                    $vbDiFile->don_vi_id = auth::user()->donvi_id;
                    $vbDiFile->trang_thai = 1;
                    $vbDiFile->save();

                }

            }
            if ($filetrinhky && count($filetrinhky) > 0) {
                foreach ($filetrinhky as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);

                    $vbDiFile->ten_file = $fileName;
                    $vbDiFile->duong_dan = $urlFile;
                    $vbDiFile->duoi_file = $extFile;
                    $vbDiFile->van_ban_di_id = $vanbandi->id;
                    $vbDiFile->nguoi_dung_id = auth::user()->id;
                    $vbDiFile->don_vi_id = auth::user()->donvi_id;
                    $vbDiFile->trang_thai = 2;
                    $vbDiFile->save();

                }
            }
            if ($donvinhanmailngoaitp && count($donvinhanmailngoaitp) > 0) {
                foreach ($donvinhanmailngoaitp as $key => $ngoai) {
                    $mailngoai = new NoiNhanMailNgoai();
                    $mailngoai->van_ban_di_id = $vanbandi->id;
                    $mailngoai->email = $ngoai;
                    $mailngoai->save();
                }
            }


            $isSuccess = true;

            DB::commit();
        } catch (Exception $e) {
            $isSuccess = false;
        }
        if ($isSuccess) {
            return redirect()->route('van-ban-di.index')
                ->with('success', 'Thêm văn bản đi thành công !');
        } else {
            redirect()->back()
                ->with('failed', 'Thêm văn bản thất bại, vui lòng thử lại !');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('vanbandi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id, Request $request)
    {

        canPermission(AllPermission::suaVanBanDi());
        $vanbandi = VanBanDi::where('id', $id)->first();
        $user = auth::user();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [2, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 2])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->where('dieu_hanh', 1)->get();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();

        $lay_emailtrongthanhpho = NoiNhanMail::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_emailngoaithanhpho = NoiNhanMailNgoai::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_noi_nhan_van_ban_di = NoiNhanVanBanDi::where(['van_ban_di_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        return view('vanbandi::van_ban_di.edit', compact('vanbandi', 'ds_soVanBan', 'ds_loaiVanBan', 'ds_DonVi', 'ds_doKhanCap',
            'ds_mucBaoMat', 'ds_nguoiKy', 'emailtrongthanhpho', 'emailngoaithanhpho', 'ds_DonVi_nhan', 'lay_emailtrongthanhpho', 'lay_emailngoaithanhpho', 'lay_noi_nhan_van_ban_di'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $nguoiky = User::where('id', $request->nguoiky_id)->first();
        $user = auth::user();
        try {
            $vanbandi = VanBanDi::where('id', $id)->first();
            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHUC_TICH ||
                $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //đây là huyện ký
            {
                if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHUC_TICH) ||
                    $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                    //đây là huyện soạn thảo và huyện ký
//                    $vanbandi->don_vi_soan_thao = '';
                } else {//đây là đơn vị soạn thảo do huyện ký
//                    $vanbandi->don_vi_soan_thao = '';
                    $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                }
            } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG || $nguoiky->role_id == QUYEN_VAN_THU_DON_VI) {
                //đây là đơn vị ký
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
            }
            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->loai_van_ban_giay_moi = 1;
            $vanbandi->nguoi_tao = auth::user()->id;
            $vanbandi->save();
            UserLogs::saveUserLogs(' Sửa văn bản đi', $vanbandi);
            $donvinhanvanbandi = !empty($request['don_vi_nhan_van_ban_di']) ? $request['don_vi_nhan_van_ban_di'] : null;
            $noinhanvb = NoiNhanVanBanDi::where(['van_ban_di_id' => $id, 'trang_thai' => 1])->get();
            $idnoinhanvb = $noinhanvb->pluck('don_vi_id_nhan')->toArray();

            if ($donvinhanvanbandi && count($donvinhanvanbandi) > 0) {

                if (array_diff($donvinhanvanbandi, $idnoinhanvb) == null && count($idnoinhanvb) == count($donvinhanvanbandi)) {
                    //đây là trường hợp không thay đổi
                } else {
                    $noinhanvb = NoiNhanVanBanDi::where(['van_ban_di_id' => $id])->get();
                    if (count($noinhanvb) > 0) {
                        foreach ($noinhanvb as $key => $data) {
                            $noinhanvbdi = NoiNhanVanBanDi::where(['id' => $data->id])->first();
                            $noinhanvbdi->delete();
                        }
                    }
                    foreach ($donvinhanvanbandi as $key => $trong) {
                        $laydonvimoi = new NoiNhanVanBanDi();
                        $laydonvimoi->van_ban_di_id = $vanbandi->id;
                        $laydonvimoi->don_vi_id_nhan = $trong;
                        $laydonvimoi->save();
                    }
                }
            }
            $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
            $mailngoaitp = NoiNhanMailNgoai::where(['van_ban_di_id' => $id, 'status' => 1])->get();
            $iddoviphongkhac = $mailngoaitp->pluck('email')->toArray();
            if ($donvinhanmailngoaitp && count($donvinhanmailngoaitp) > 0) {
                if (array_diff($donvinhanmailngoaitp, $iddoviphongkhac) == null && count($iddoviphongkhac) == count($donvinhanmailngoaitp)) {
                    //đây là trường hợp không thay đổi
                } else {
                    $mailngoai = NoiNhanMailNgoai::where('van_ban_di_id', $id)->get();
                    if (count($mailngoai) > 0) {
                        foreach ($mailngoai as $key => $xoahetngoai) {
                            $mailngoaixoa = NoiNhanMailNgoai::where('id', $xoahetngoai->id)->first();
                            $mailngoaixoa->status = 0;
                            $mailngoaixoa->save();
                        }
                    }
                    foreach ($donvinhanmailngoaitp as $key => $ngoai) {
                        $mailngoaimoi = new NoiNhanMailNgoai();
                        $mailngoaimoi->van_ban_di_id = $vanbandi->id;
                        $mailngoaimoi->email = $ngoai;
                        $mailngoaimoi->save();
                    }
                }

            }


            $isSuccess = true;
            DB::commit();
        } catch (Exception $e) {
            $isSuccess = false;
        }
        if ($isSuccess) {

            return redirect()->back()
                ->with('success', 'Cập nhật thông tin văn bản thành công !');

        } else {
            redirect()->back()
                ->with('failed', 'Cập nhật thất bại, vui lòng thử lại !');
        }
    }

    public function multiple_file_di(Request $request)
    {
        $user = auth::user();
        $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        if (empty($multiFiles) || count($multiFiles) == 0 || (count($multiFiles) > 19)) {
            return redirect()->back()->with('warning', 'Bạn phải chọn file hoặc phải chọn số lượng file nhỏ hơn 20 file   !');
        }
        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $tenchinhfile = strtolower($typeArray[0]);
            $extFile = $getFile->extension();
            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
            $tachchuoi = explode("-", $tenchinhfile);
            $tenviettatso = strtoupper($tachchuoi[0]);
            $sodi = (int)$tachchuoi[1];
            $loaivanban = LoaiVanBan::where(['ten_viet_tat' => $tenviettatso])->whereNull('deleted_at')->first();

            $vanban = null;
            if (!empty($loaivanban)) {
                if ($user->hasRole(VAN_THU_HUYEN)) {
                    $vanban = VanBanDi::where(['loai_van_ban_id' => $loaivanban->id, 'so_di' => $sodi, 'type' => 1])->first();


                } elseif ($user->hasRole(VAN_THU_DON_VI)) {
                    $vanban = VanBanDi::where(['loai_van_ban_id' => $loaivanban->id, 'so_di' => $sodi, 'don_vi_soan_thao' => auth::user()->don_vi_id])->first();
                }
            }
            if ($vanban) {

                $xoafiletrinhky = FileVanBanDi::where(['trang_thai' => 2, 'file_chinh_gui_di' => 2])->first();
                if ($xoafiletrinhky) {
                    $xoafiletrinhky->delete();
                }
                $vanBanDiFile = new FileVanBanDi();
                $getFile->move($uploadPath, $fileName);
                $vanBanDiFile->ten_file = $tenchinhfile;
                $vanBanDiFile->duong_dan = $urlFile;
                $vanBanDiFile->duoi_file = $extFile;
                $vanBanDiFile->van_ban_di_id = $vanban->id;
                $vanBanDiFile->file_chinh_gui_di = 2;
                $vanBanDiFile->trang_thai = 2;
                $vanBanDiFile->nguoi_dung_id = auth::user()->id;
                $vanBanDiFile->don_vi_id = auth::user()->don_vi_id;
                $vanBanDiFile->loai_file = FileVanBanDi::LOAI_FILE_DA_KY;
                $vanBanDiFile->save();
                UserLogs::saveUserLogs(' Upload file văn bản đi', $vanBanDiFile);
                //xóa file trình ký khi đã ký số lỗi

                //gửi văn bản đi đến các đơn vị
                $noinhan = NoiNhanVanBanDi::where('van_ban_di_id', $vanban->id)->get();
                foreach ($noinhan as $key => $noi_nhan) {
                    $guidennoinhan = NoiNhanVanBanDi::where('id', $noi_nhan->id)->first();
                    $guidennoinhan->trang_thai = 2;
                    $guidennoinhan->save();

                    // tao lanh dao du hop
                    $this->taoLanhDaoDuHop($noi_nhan->don_vi_id_nhan, $vanban);
                }

//                gửi mail đến các đơn vị ngoài
                SendEmailFileVanBanDi::dispatch(VanBanDi::LOAI_VAN_BAN_DI, null)->delay(now()->addMinutes(5));
            }
        }


        return redirect()->back()->with('success', 'Thêm file thành công !');
    }

    public function taoLanhDaoDuHop($donViId, $vanBanDi)
    {
        $role = [TRUONG_PHONG, CHANH_VAN_PHONG];

        $nguoiDung = User::where('don_vi_id', $donViId)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->first();

        $donVi = DonVi::where('id', $donViId)->first();

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
            $nguoiDung = User::role(CHU_TICH)
                ->where('don_vi_id', $donViId)
                ->where('trang_thai', ACTIVE)
                ->whereNull('deleted_at')->first();
        }

        //tạo lanh dao du hop
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        ThanhPhanDuHop::store($giayMoi, $vanBanDi, [$nguoiDung->id], ThanhPhanDuHop::TYPE_VB_DI, $nguoiDung->don_vi_id ?? null);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaVanBanDi());
        $vanbandi = VanBanDi::where('id', $id)->first();
        $vanbandi->delete();
        UserLogs::saveUserLogs(' Xóa văn bản đi', $vanbandi);
        return redirect()->back()
            ->with('success', 'Xóa văn bản thành công !');
    }

    public function ds_van_ban_di_cho_duyet()
    {
        $nguoinhan = null;
        $vanbandichoduyet = null;
        $idnguoiky = null;
        $idcuanguoinhan = null;
        $vanbandichoduyet = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id, 'trang_thai' => 1])->get();


        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi','LIKE',LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi',$nhomDonVi->id)->first();


        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case PHO_CHUC_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = null;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHUC_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donViCapHuyen->id)->get();
                }
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:

                $nguoinhan = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case TRUONG_BAN:
                $nguoinhan = User::role([PHO_CHUC_TICH, CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                break;
            case PHO_TRUONG_BAN:
                $nguoinhan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

        }

        if (!empty($nguoinhan)) {
            $idcuanguoinhan = $nguoinhan->pluck('id');
        }

        return view('vanbandi::Duyet_van_ban_di.index', compact('vanbandichoduyet', 'nguoinhan', 'idcuanguoinhan'));
    }

    public function vanbandichoso()
    {
        $date = Carbon::now()->format('Y-m-d');

        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $vanbandichoso = VanBanDi::where(['cho_cap_so' => 2, 'don_vi_soan_thao' => null])->orderBy('created_at', 'desc')->get();
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $vanbandichoso = VanBanDi::where(['cho_cap_so' => 2, 'van_ban_huyen_ky' => auth::user()->don_vi_id , 'don_vi_soan_thao'=>auth::user()->don_vi_id])->orderBy('created_at', 'desc')->get();
        }
//        $vanbandichoso = Vanbandichoduyet::where(['cho_cap_so' => 1])->orderBy('created_at', 'desc')->get();
        $emailTrongThanhPho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailNgoaiThanhPho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();


        return view('vanbandi::Du_thao_van_ban_di.vanbandichoso',
            compact('vanbandichoso', 'date', 'emailTrongThanhPho', 'emailNgoaiThanhPho'));
    }
//    public function vanbandichosothanhpho()
//    {
//        $date = Carbon::now()->format('Y-m-d');
//        $vanbandichoso = Vanbandichoduyet::where(['cho_cap_so' => 1])->orderBy('created_at', 'desc')->get();
//        $emailTrongThanhPho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
//        $emailNgoaiThanhPho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
//
//
//        return view('vanbandi::Du_thao_van_ban_di.vanbandichoso',
//            compact('vanbandichoso', 'date', 'emailTrongThanhPho', 'emailNgoaiThanhPho'));
//    }

    public function duyetvbditoken(Request $request)
    {
        $duyet = !empty($request['submit_Duyet']) ? $request['submit_Duyet'] : null;
        $tralai = !empty($request['submit_tralai']) ? $request['submit_tralai'] : null;
        $duyetlai = !empty($request['submit_Duyet_lai']) ? $request['submit_Duyet_lai'] : null;
        if ($request->vb_cho_so == 1) {
            if ($duyet == 1) {
                $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $request->id_van_ban)->get();
                foreach ($vanbanduthao as $data) {
                    $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                    $vanbanduthao->trang_thai = 10;
                    $vanbanduthao->save();
                }
                $nguoicu = Vanbandichoduyet::where('id', $request->id_vb_cho_duyet)->first();
                $canbonhan = new Vanbandichoduyet();
                $canbonhan->van_ban_di_id = $nguoicu->van_ban_di_id;
                $canbonhan->can_bo_chuyen_id = auth::user()->id;
                $canbonhan->y_kien_gop_y = $request->noi_dung;
                $canbonhan->trang_thai = 10;
                $canbonhan->cho_cap_so = 1;
                $canbonhan->save();
                UserLogs::saveUserLogs(' Duyệt văn bản đi', $canbonhan);


                //chờ số văn bản đi
                $vanbandi = VanBanDi::where('id', $nguoicu->van_ban_di_id)->first();
                $vanbandi->cho_cap_so = 2;
                $vanbandi->save();

                // update van ban den
                VanBanDen::updateHoanThanhVanBanDen($vanbandi->van_ban_den_id);

                $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
//                //update lich cong tac
                if (!empty($giayMoi) && $vanbandi && $vanbandi->loai_van_ban_id == $giayMoi->id) {
                    LichCongTac::taoLichCongTac($vanbandi);
                }
//                    $tuan = date('W', strtotime($vanbandi->ngay_hop));
//
//                    $lanhDaoDuHop = DieuHanhVanBanDenLichCongTac::checkLanhDaoDuHop($vanbandi->nguoiky_id);
//                    $noiDungMoiHop = null;
//
//                    if (!empty($lanhDaoDuHop)) {
//
//                        $noiDungMoiHop = 'Kính mời ' . $lanhDaoDuHop->chucVu->ten_chuc_vu . ' ' . $lanhDaoDuHop->ho_ten . ' dự họp';
//                    }
//
//                    $dataLichCongTac = array(
//                        'van_ban_den_don_vi_id' => $vanbandi->id,
//                        'lanh_dao_id' => $lanhDaoDuHop->id,
//                        'ngay' => $vanbandi->ngay_hop,
//                        'gio' => $vanbandi->gio_hop,
//                        'tuan' => $tuan,
//                        'buoi' => ($vanbandi->gio_hop <= '12:00') ? 1 : 2,
//                        'noi_dung' => $noiDungMoiHop,
//                        'user_id' => auth::user()->id,
//                        'type' => DieuHanhVanBanDenLichCongTac::TYPE_VB_DI
//                    );
//                    //check lich cong tac
//                    $lichCongTac = DieuHanhVanBanDenLichCongTac::where('van_ban_den_don_vi_id', $vanbandi->id)->first();
//
//                    if (empty($lichCongTac)) {
//                        $lichCongTac = new DieuHanhVanBanDenLichCongTac();
//                    }
//                    $lichCongTac->fill($dataLichCongTac);
//                    $lichCongTac->save();
//                }

                return redirect()->back()->with('success', 'Chuyển văn thư chờ cấp số thành công !');

            } elseif ($tralai == 2) {
                $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $request->id_van_ban)->get();
                foreach ($vanbanduthao as $data) {
                    $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                    $vanbanduthao->trang_thai = 0;
                    $vanbanduthao->save();
                }
                $nguoicu = Vanbandichoduyet::where('id', $request->id_vb_cho_duyet)->first();
                $nguoidautiennhan = Vanbandichoduyet::where('van_ban_di_id', $nguoicu->van_ban_di_id)->OrderBy('created_at', 'asc')->first();
                $canbonhan = new Vanbandichoduyet();
                $canbonhan->van_ban_di_id = $nguoidautiennhan->van_ban_di_id;
                $canbonhan->can_bo_chuyen_id = auth::user()->id;
                $canbonhan->can_bo_nhan_id = $nguoidautiennhan->can_bo_chuyen_id;
                $canbonhan->y_kien_gop_y = $request->noi_dung;
                $canbonhan->trang_thai = 0;
                $canbonhan->tra_lai = 1;
                $canbonhan->save();
                UserLogs::saveUserLogs(' trả lại văn bản đi', $canbonhan);
                return redirect()->back()->with('success', 'Trả lại thành công !');
            }

        } else {
            if ($duyet == 1) {
                $nguoicu = Vanbandichoduyet::where('id', $request->id_vb_cho_duyet)->first();
                $canbonhan = new Vanbandichoduyet();
                $canbonhan->van_ban_di_id = $nguoicu->van_ban_di_id;
                $canbonhan->can_bo_chuyen_id = auth::user()->id;
                $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
                $canbonhan->y_kien_gop_y = $request->noi_dung;
                $canbonhan->save();
                UserLogs::saveUserLogs(' Duyệt văn bản đi', $canbonhan);
                $nguoicu->trang_thai = 2;
                $nguoicu->save();
                return redirect()->back()->with('success', 'Duyệt thành công !');
            } elseif ($tralai == 2) {
                $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $request->id_van_ban)->get();
                foreach ($vanbanduthao as $data) {
                    $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                    $vanbanduthao->trang_thai = 0;
                    $vanbanduthao->save();
                }
                $nguoicu = Vanbandichoduyet::where('id', $request->id_vb_cho_duyet)->first();
                $nguoidautiennhan = Vanbandichoduyet::where('van_ban_di_id', $nguoicu->van_ban_di_id)->OrderBy('van_ban_di_id', 'asc')->first();
                $canbonhan = new Vanbandichoduyet();
                $canbonhan->van_ban_di_id = $nguoidautiennhan->van_ban_di_id;
                $canbonhan->can_bo_chuyen_id = auth::user()->id;
                $canbonhan->can_bo_nhan_id = $nguoidautiennhan->can_bo_chuyen_id;
                $canbonhan->y_kien_gop_y = $request->noi_dung;
                $canbonhan->trang_thai = 0;
                $canbonhan->tra_lai = 1;
                $canbonhan->save();
                UserLogs::saveUserLogs(' trả lại văn bản đi', $canbonhan);
                return redirect()->back()->with('success', 'Trả lại thành công !');
            } elseif ($duyetlai == 3) {
                $file = FileVanBanDi::where('van_ban_di_id', $request->id_van_ban)->get();
                foreach ($file as $data) {
                    $file1 = FileVanBanDi::where('id', $data->id)->first();
                    $file1->trang_thai = 0;
                    $file1->save();
                }
                $nguoicu = Vanbandichoduyet::where('id', $request->id_vb_cho_duyet)->first();
                $canbonhan = new Vanbandichoduyet();
                $canbonhan->van_ban_di_id = $nguoicu->van_ban_di_id;
                $canbonhan->can_bo_chuyen_id = auth::user()->id;
                $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
                $canbonhan->y_kien_gop_y = $request->noi_dung;
                $canbonhan->save();
                $nguoicu->trang_thai = 5;
                $nguoicu->save();

                $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
                $txtFiles = !empty($request['txt_file']) ? $request['txt_file'] : null;
                $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
                if ($multiFiles && count($multiFiles) > 0) {
                    foreach ($multiFiles as $key => $getFile) {
                        $extFile = $getFile->extension();
                        $ten = strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile;
                        $vbDenFile = new FileVanBanDi();
                        $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                        $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                        if (!File::exists($uploadPath)) {
                            File::makeDirectory($uploadPath, 0775, true, true);
                        }
                        $getFile->move($uploadPath, $fileName);
                        $vbDenFile->ten_file = $ten;
                        $vbDenFile->duong_dan = $urlFile;
                        $vbDenFile->duoi_file = $extFile;
                        $vbDenFile->van_ban_di_id = $request->vb_di_id;
                        $vbDenFile->nguoi_dung_id = auth::user()->id;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->save();
                    }
                }
                return redirect()->back()->with('success', 'Chuyển thành công !');


            }
        }
    }

    public function Capsovanbandi(Request $request)
    {

        $nam_sodi = date('Y', strtotime($request->ngay_ban_hanh));
        $vanbandiduyet = Vanbandichoduyet::where(['van_ban_di_id' => $request->van_ban_di_id, 'cho_cap_so' => 1])->first();
        $vanbandiduyet->cho_cap_so = 3;
        $vanbandiduyet->save();
        $vanbandi = VanBanDi::where('id', $request->van_ban_di_id)->first();

        $vanbandi->ngay_ban_hanh = $request->ngay_ban_hanh;
        $vanbandi->cho_cap_so = 3;


        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDi = VanBanDi::where([
                'loai_van_ban_id' => $vanbandi->loai_van_ban_id,
                'don_vi_soan_thao' => null
            ])->whereNull('deleted_at')->whereYear('ngay_ban_hanh', '=', $nam_sodi)->max('so_di');


        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDi = VanBanDi::where([
                'loai_van_ban_id' => $vanbandi->loai_van_ban_id,
                'don_vi_soan_thao' => auth::user()->don_vi_id
            ])->whereNull('deleted_at')->whereYear('ngay_ban_hanh', '=', $nam_sodi)->max('so_di');

        }

        $soDi = $soDi + 1;
        $vanbandi->so_di = $soDi;
        $vanbandi->save();
        UserLogs::saveUserLogs(' Cấp số văn bản đi', $vanbandi);
        return redirect()->back()->with('success', 'Cấp số thành công thành công !');
//        SendEmailFileVanBanDi::dispatch(VanBanDi::LOAI_VAN_BAN_DI, $vanbandi->id)->delay(now()->addMinutes(5));

//        return response()->json([
//            'status' => true,
//            'message' => 'Đã phát hành văn bản.'
//        ], 200);
    }

    public function Quytrinhxulyvanbandi($id)
    {
        $laytatcaduthao = null;
        $idduthao = Vanbandichoduyet::where('van_ban_di_id', $id)->orderBy('created_at', 'asc')->first();
        $duthaovanban = Duthaovanbandi::where('id', $idduthao->id_du_thao)->first();
        if ($duthaovanban != null) {
            $laytatcaduthao = Duthaovanbandi::where('du_thao_id', $duthaovanban->du_thao_id)->get();
        }


        $quatrinhtruyennhan = Vanbandichoduyet::where('van_ban_di_id', $id)->get();

        $vanbandi = VanBanDi::with('vanBanDenDonVi')
            ->where('id', $id)->first();

        $file = FileVanBanDi::where('van_ban_di_id', $id)->get();

        return view('vanbandi::Du_thao_van_ban_di.Quytrinhxulyvanbandi', compact('quatrinhtruyennhan', 'file', 'vanbandi', 'laytatcaduthao'));
    }
}
