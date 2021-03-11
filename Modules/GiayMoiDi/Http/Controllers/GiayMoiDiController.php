<?php

namespace Modules\GiayMoiDi\Http\Controllers;

use App\Common\AllPermission;
use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File , auth;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\MailTrongThanhPho;
use Modules\Admin\Entities\NhomDonVi;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\NoiNhanMail;
use Modules\VanBanDi\Entities\NoiNhanMailNgoai;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use Modules\VanBanDi\Entities\VanBanDi;
use Modules\VanBanDi\Entities\VanBanDiChoDuyet;

class GiayMoiDiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user= auth::user();
        $trichyeu = $request->get('vb_trichyeu');
        $so_ky_hieu = $request->get('vb_sokyhieu');
        $chucvu = $request->get('chuc_vu');
        $donvisoanthao = $request->get('donvisoanthao_id');
        $so_van_ban = $request->get('sovanban_id');
        $giohop = $request->get('gio_hop');
        $nguoi_ky = $request->get('nguoiky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $ngaybanhanhstart = $request->get('vb_ngaybanhanh_start');
        $ngaybanhanhend = $request->get('vb_ngaybanhanh_end');
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $year = $request->get('year') ?? null;
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id', 'ten_loai_van_ban')->first();

        if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) || $user->hasRole(CHANH_VAN_PHONG) || $user->hasRole(PHO_CHANH_VAN_PHONG)) {
            $ds_nguoiKy = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, CHU_TICH, PHO_CHU_TICH ])->orderBy('username', 'desc')->get();
        } else {
            $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG,CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, CHU_TICH, PHO_CHU_TICH])->orderBy('username', 'desc')->get();
        }
        if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH)) {
            //đây là văn bản của huyện
            $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 2, 'loai_van_ban_id' => $giayMoi->id ?? null , 'don_vi_soan_thao' => null])->where('so_di', '!=', '')->whereNull('deleted_at')
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
                    }
                })
                ->where(function ($query) use ($giohop) {
                    if (!empty($giohop)) {
                        return $query->where('gio_hop', $giohop);
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
                        return $query->where('ngay_hop', '>=', $ngaybatdau)
                            ->where('ngay_hop', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        $ngaybatdau = $ngayketthuc;
                        return $query->where('ngay_hop', '>=', $ngaybatdau)
                            ->where('ngay_hop', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau != '' && $ngayketthuc == '') {
                        $ngayketthuc = $ngaybatdau;
                        return $query->where('ngay_hop', '>=', $ngaybatdau)
                            ->where('ngay_hop', '<=', $ngayketthuc);
                    }
                })
                ->where(function ($query) use ($ngaybanhanhstart, $ngaybanhanhend) {
                    if ($ngaybanhanhstart != '' && $ngaybanhanhend != '' && $ngaybanhanhstart <= $ngaybanhanhend) {
                        return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                            ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                    }
                    if ($ngaybanhanhstart == '' && $ngaybanhanhend != '') {
                        $ngaybatdau = $ngaybanhanhend;
                        return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                    }
                    if ($ngaybanhanhstart != '' && $ngaybanhanhend == '') {
                        $ngaybanhanhend = $ngaybanhanhstart;
                        return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                            ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                    }
                })
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);

        } else
            //đây là văn bản của đơn vị
            $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 2, 'loai_van_ban_id' => $giayMoi->id ?? null, 'van_ban_huyen_ky' => auth::user()->don_vi_id])->where('so_di', '!=', '')->whereNull('deleted_at')
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
                    }
                })
                ->where(function ($query) use ($giohop) {
                    if (!empty($giohop)) {
                        return $query->where('gio_hop', $giohop);
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
                        return $query->where('ngay_hop', '>=', $ngaybatdau)
                            ->where('ngay_hop', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        $ngaybatdau = $ngayketthuc;
                        return $query->where('ngay_hop', '>=', $ngaybatdau)
                            ->where('ngay_hop', '<=', $ngayketthuc);
                    }
                    if ($ngaybatdau != '' && $ngayketthuc == '') {
                        $ngayketthuc = $ngaybatdau;
                        return $query->where('ngay_hop', '>=', $ngaybatdau)
                            ->where('ngay_hop', '<=', $ngayketthuc);
                    }
                })
                ->where(function ($query) use ($ngaybanhanhstart, $ngaybanhanhend) {
                    if ($ngaybanhanhstart != '' && $ngaybanhanhend != '' && $ngaybanhanhstart <= $ngaybanhanhend) {
                        return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                            ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                    }
                    if ($ngaybanhanhstart == '' && $ngaybanhanhend != '') {
                        $ngaybatdau = $ngaybanhanhend;
                        return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                    }
                    if ($ngaybanhanhstart != '' && $ngaybanhanhend == '') {
                        $ngaybanhanhend = $ngaybanhanhstart;
                        return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                            ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                    }
                })
                ->where(function($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->orderBy('created_at', 'desc')->paginate(PER_PAGE);

        return view('giaymoidi::giay_moi_di.index', compact('ds_vanBanDi', 'ds_DonVi', 'ds_nguoiKy','ds_soVanBan'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themGiayMoiDi());
        $user= auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id ?? null)->first();
        $emailSoBanNganh = MailTrongThanhPho::where('mail_group',1)->orderBy('ten_don_vi', 'asc')->get();
        $emailQuanHuyen = MailTrongThanhPho::where('mail_group',2)->orderBy('ten_don_vi', 'asc')->get();
        $emailTrucThuoc = MailTrongThanhPho::where('mail_group',3)->orderBy('ten_don_vi', 'asc')->get();
        $ds_DonVi_phatHanh= DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->where('dieu_hanh', 1)->get();


        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if (empty($donVi->cap_xa)) {
                    $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();

                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $ds_nguoiKy = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($truongpho as $data2) {
                    array_push($dataNguoiKy, $data2);
                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if (empty($donVi->cap_xa)) {
                    $ds_nguoiKy = $lanhDaoSo;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case PHO_CHU_TICH:
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
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                $ds_nguoiKy = $lanhDaoSo;
                break;
            case PHO_CHANH_VAN_PHONG:
                $chanhVanPhong = User::role([CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->first();
                foreach ($chanhVanPhong as $data) {
                    array_push($dataNguoiKy, $data);
                }
                foreach ($lanhDaoSo as $item) {
                    array_push($dataNguoiKy, $item);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_DON_VI:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })
                    ->get();
                break;

            case TRUONG_BAN:
                $ds_nguoiKy = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                break;

            case PHO_TRUONG_BAN:
                $truongBan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->first();
                array_push($dataNguoiKy, $truongBan);

                $danhSachLanhDaoPhongBan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                if ($danhSachLanhDaoPhongBan) {
                    foreach ($danhSachLanhDaoPhongBan as $lanhDaoPhongBan) {
                        array_push($dataNguoiKy, $lanhDaoPhongBan);
                    }
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

        }

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();

                } else {
                    $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = $lanhDaoSo;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = null;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = $lanhDaoSo;
                }
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })->get();
                break;
            case TRUONG_BAN:
                $nguoinhan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();;
                break;
            case PHO_TRUONG_BAN:
                $nguoinhan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

        }



        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->where('dieu_hanh',1)->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id', 'ten_loai_van_ban')->first();


        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [2, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id,'type'=>2])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_loaiVanBan =LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        return view('giaymoidi::giay_moi_di.create',compact('ds_mucBaoMat','nguoinhan','ds_doKhanCap','ds_DonVi_nhan','ds_loaiVanBan','ds_soVanBan',
            'ds_nguoiKy','emailngoaithanhpho','emailtrongthanhpho','ds_DonVi', 'giayMoi','emailTrucThuoc','emailSoBanNganh','emailQuanHuyen','ds_DonVi_phatHanh'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $nguoiky = User::where('id', $request->nguoiky_id)->first();
        $user=auth::user();
        $donvinhanmailtrongtp = !empty($request['don_vi_nhan_trong_thanh_php']) ? $request['don_vi_nhan_trong_thanh_php'] : null;
        $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
        $donvinhanvanbandi = !empty($request['don_vi_nhan_van_ban_di']) ? $request['don_vi_nhan_van_ban_di'] : null;
        $tenMailThem = !empty($request['ten_don_vi_them']) ? $request['ten_don_vi_them'] : null;
        $EmailThem = $request->email_them;
        $gio_hop= date ('H:i',strtotime($request->gio_hop));
        $vanBanDenId = $request->get('van_ban_den_id') ?? null;


        if ($tenMailThem && count($tenMailThem) > 0) {
            foreach ($tenMailThem as $key => $data) {
                $themDonVi = new MailNgoaiThanhPho();
                $themDonVi -> ten_don_vi= $data;
                $themDonVi -> email= $EmailThem[$key];
                $themDonVi -> save();
            }
        }
        $vanbandi = new VanBanDi();
        $vanbandi->trich_yeu = $request->vb_trichyeu;
        $vanbandi->van_ban_den_id = !empty($vanBanDenId) ? explode(',', $vanBanDenId) : null;
        $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
        $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
        $vanbandi->phong_phat_hanh = $request->phong_phat_hanh;
        $vanbandi->loai_van_ban_id = $request->loaivanban_id;
        $vanbandi->do_khan_cap_id = $request->dokhan_id;
        $vanbandi->chuc_vu = $request->chuc_vu;
        $vanbandi->do_bao_mat_id = $request->dobaomat_id;

        if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHU_TICH ||
            $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //đây là huyện ký
        {
            if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) ||
                $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                //đây là huyện soạn thảo và huyện ký
//                $vanbandi->don_vi_soan_thao = '';
            } else {//đây là đơn vị soạn thảo do huyện ký
//                $vanbandi->don_vi_soan_thao = '';
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
        $vanbandi->gio_hop = $gio_hop;
        $vanbandi->ngay_hop = $request->ngay_hop;
        $vanbandi->dia_diem = $request->dia_diem;
        $vanbandi->user_id = $request->nguoi_nhan;
        $vanbandi->loai_van_ban_giay_moi = 2;
        $vanbandi->nguoi_tao = auth::user()->id;
        $vanbandi->save();
        UserLogs::saveUserLogs('Tạo giấy mời đi ', $vanbandi);
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
        if ($donvinhanmailngoaitp && count($donvinhanmailngoaitp) > 0) {
            foreach ($donvinhanmailngoaitp as $key => $ngoai) {
                $mailngoai = new NoiNhanMailNgoai();
                $mailngoai->van_ban_di_id = $vanbandi->id;
                $mailngoai->email = $ngoai;
                $mailngoai->save();
            }
        }
        return redirect()->route('giay-moi-di.index')->with('success', 'Thêm giấy mời thành công ! ');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('giaymoidi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        canPermission(AllPermission::suaGiayMoiDi());
        $user= auth::user();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->where('dieu_hanh',1)->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id', 'ten_loai_van_ban')->first();
        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi','LIKE',LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi',$nhomDonVi->id)->first();

        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if (empty($donVi->cap_xa)) {
                    $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();

                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $ds_nguoiKy = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                foreach ($truongpho as $data2) {
                    array_push($dataNguoiKy, $data2);
                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if (empty($donVi->cap_xa)) {
                    $ds_nguoiKy = $lanhDaoSo;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case PHO_CHU_TICH:
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
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                $ds_nguoiKy = $lanhDaoSo;
                break;
            case PHO_CHANH_VAN_PHONG:
                $chanhVanPhong = User::role([CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->first();
                foreach ($chanhVanPhong as $data) {
                    array_push($dataNguoiKy, $data);
                }
                foreach ($lanhDaoSo as $item) {
                    array_push($dataNguoiKy, $item);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_DON_VI:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })
                    ->get();
                break;

            case TRUONG_BAN:
                $ds_nguoiKy = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                break;

            case PHO_TRUONG_BAN:
                $truongBan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->first();
                array_push($dataNguoiKy, $truongBan);

                $danhSachLanhDaoPhongBan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                if ($danhSachLanhDaoPhongBan) {
                    foreach ($danhSachLanhDaoPhongBan as $lanhDaoPhongBan) {
                        array_push($dataNguoiKy, $lanhDaoPhongBan);
                    }
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

        }

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();

                } else {
                    $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = $lanhDaoSo;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = null;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = $lanhDaoSo;
                }
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })->get();
                break;
            case TRUONG_BAN:
                $nguoinhan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();;
                break;
            case PHO_TRUONG_BAN:
                $nguoinhan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

        }


        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [2, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id,'type'=>2])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_loaiVanBan =LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();


        $giaymoidi = VanBanDi::where('id',$id)->first();
        $giaymoidi->listVanBanDen = $giaymoidi->getListVanBanDen();
        $lay_emailtrongthanhpho = NoiNhanMail::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_emailngoaithanhpho = NoiNhanMailNgoai::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_noi_nhan_van_ban_di = NoiNhanVanBanDi::where(['van_ban_di_id' => $id])->whereIn('trang_thai', [1, 2])->get();

        return view('giaymoidi::giay_moi_di.edit',compact('ds_mucBaoMat','nguoinhan','ds_doKhanCap','ds_loaiVanBan','ds_soVanBan',
            'ds_nguoiKy','emailngoaithanhpho','emailtrongthanhpho','ds_DonVi','ds_DonVi_nhan','giaymoidi',
            'lay_emailngoaithanhpho','lay_emailtrongthanhpho', 'lay_noi_nhan_van_ban_di', 'giayMoi'));

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
        $user=auth::user();
        $gio_hop= date ('H:i',strtotime($request->gio_hop));

        $vanbandi = VanBanDi::where('id', $id)->first();

        $arrVanBanDenId = $vanbandi->van_ban_den_id ?? [];
        $vanBanDenId = !empty($request->get('van_ban_den_id')) ?  explode(',', $request->get('van_ban_den_id')) : null;

        $vanbandi->trich_yeu = $request->vb_trichyeu;
        $vanbandi->van_ban_den_id = !empty($vanBanDenId) ? array_merge($vanBanDenId, $arrVanBanDenId) : null;
        $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
        $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
        $vanbandi->loai_van_ban_id = $request->loaivanban_id;
        $vanbandi->do_khan_cap_id = $request->dokhan_id;
        $vanbandi->chuc_vu = $request->chuc_vu;
        $vanbandi->do_bao_mat_id = $request->dobaomat_id;
        if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHU_TICH ||
            $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //đây là huyện ký
        {
            if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) ||
                $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                //đây là huyện soạn thảo và huyện ký
                $vanbandi->don_vi_soan_thao = '';
            } else {//đây là đơn vị soạn thảo do huyện ký
                $vanbandi->don_vi_soan_thao = '';
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
            }
        } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG || $nguoiky->role_id == QUYEN_VAN_THU_DON_VI) {
            //đây là đơn vị ký
            $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
        }
        $vanbandi->so_van_ban_id = $request->sovanban_id;
        $vanbandi->nguoi_ky = $request->nguoiky_id;
        $vanbandi->gio_hop = $gio_hop;
        $vanbandi->ngay_hop = $request->ngay_hop;
        $vanbandi->dia_diem = $request->dia_diem;
        $vanbandi->user_id = $request->nguoi_nhan;
        $vanbandi->save();
        UserLogs::saveUserLogs('Sửa giấy mời đi ', $vanbandi);
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
        return redirect()->back()
            ->with('failed', 'Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaGiayMoiDi());
        $giaymoidi = VanBanDi::where('id',$id)->first();
        $giaymoidi ->delete();
        UserLogs::saveUserLogs('Xóa giấy mời đi ', $giaymoidi);
        return redirect()->back()->with('xóa giấy mời thành công!');
    }
}
