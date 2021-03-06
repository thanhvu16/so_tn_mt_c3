<?php

namespace Modules\VanBanDi\Http\Controllers;

use App\Common\AllPermission;
use App\Jobs\SendEmailFileVanBanDi;
use App\Models\LichCongTac;
use App\Models\UserLogs;
use App\Models\VanBanDiVanBanDen;
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

        if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHANH_VAN_PHONG) || ($user->hasRole(CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA) || ($user->hasRole(PHO_CHU_TICH) && $donVi->cap_xa != DonVi::CAP_XA)) {
            //????y l?? v??n b???n c???a huy???n

            $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();
            $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $lanhDaoSo->don_vi_id])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->where(function ($query) use ($don_vi_van_ban) {
                    if ($don_vi_van_ban == 2) {
                        //v??n b???n huy???n
                        if (!empty($don_vi_van_ban)) {
                            return $query->where('don_vi_soan_thao', UBND_HUYEN)->whereNull('van_ban_huyen_ky');
                        }
                    } else {
                        //v??n b???n ????n v???
                        if (!empty($don_vi_van_ban)) {
                            return $query->where('don_vi_soan_thao', auth::user()->don_vi_id)->where('van_ban_huyen_ky', '!=', '');
                        }
                    }

                    if (!empty($don_vi_van_ban)) {
                        return $query->where('trich_yeu', 'LIKE', "%$don_vi_van_ban%");
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
                ->where(function ($query) use ($chucvu) {
                    if (!empty($chucvu)) {
                        return $query->where(DB::raw('lower(chuc_vu)'), 'LIKE', "%" . mb_strtolower($chucvu) . "%");
                    }
                })
                ->where(function ($query) use ($so_ky_hieu) {
                    if (!empty($so_ky_hieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($so_ky_hieu) . "%");
                    }
                })
//                ->where(function ($query) use ($chucvu) {
//                    if (!empty($chucvu)) {
//                        return $query->where('chuc_vu', 'LIKE', "%$chucvu%");
//                    }
//                })
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
//                ->where(function ($query) use ($so_ky_hieu) {
//                    if (!empty($so_ky_hieu)) {
//                        return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
//                    }
//                })
                ->where(function ($query) use ($so_van_ban) {
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
                ->where(function ($query) use ($phatHanhVanBan) {
                    if (!empty($phatHanhVanBan)) {
                        return $query->where('phat_hanh_van_ban', $phatHanhVanBan);
                    }
                })
                ->orderBy('so_di', 'desc')->paginate(PER_PAGE);
        } else {


            //????y l?? v??n b???n c???a ????n v???


            $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;

            $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $donViId])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->where(function ($query) use ($don_vi_van_ban) {
                    if ($don_vi_van_ban == 2) {
                        //v??n b???n huy???n
                        if (!empty($don_vi_van_ban)) {
                            return $query->where('phong_phat_hanh', auth::user()->donVi->parent_id);
                        }
                    }
                })
                ->where(function ($query) use ($donViId) {
                    if (!empty($donViId)) {
                        return $query->where('so_di', '!=', null)
                            ->orwhere('van_ban_huyen_ky', $donViId);
                    }
                })
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichyeu) . "%");
                    }
                })
                ->where(function ($query) use ($so_ky_hieu) {
                    if (!empty($so_ky_hieu)) {
                        return $query->where(DB::raw('lower(so_ky_hieu)'), 'LIKE', "%" . mb_strtolower($so_ky_hieu) . "%");
                    }
                })
                ->where(function ($query) use ($chucvu) {
                    if (!empty($chucvu)) {
                        return $query->where(DB::raw('lower(chuc_vu)'), 'LIKE', "%" . mb_strtolower($chucvu) . "%");
                    }
                })
//                ->where(function ($query) use ($chucvu) {
//                    if (!empty($chucvu)) {
//                        return $query->where('chuc_vu', 'LIKE', "%$chucvu%");
//                    }
//                })
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
                ->where(function ($query) use ($year) {
                    if (!empty($year)) {
                        return $query->whereYear('created_at', $year);
                    }
                })
                ->orderBy('so_di', 'desc')->paginate(PER_PAGE);
        }


        return view('vanbandi::van_ban_di.index', compact('ds_vanBanDi', 'ds_loaiVanBan', 'ds_soVanBan', 'ds_DonVi', 'ds_nguoiKy'));

    }

    public function guiSMS()
    {
        $arayOffice = array();
        $arayOffice['RQST']['name'] = 'send_sms_list';
        $arayOffice['RQST']['REQID'] = "1234352";
        $arayOffice['RQST']['LABELID'] = "149355";
        $arayOffice['RQST']['CONTRACTTYPEID'] = '1';
        $arayOffice['RQST']['CONTRACTID'] = '13681';
        $arayOffice['RQST']['TEMPLATEID'] = '791767';
//        $arayOffice['RQST']['PARAMS'][0] = array(
//            'NUM' => '1',
//            'CONTENT' => 've viec xin su dung tai nguyen'
//        );
//        $arayOffice['RQST']['PARAMS'][1] = array(
//            'NUM' => '2',
//            'CONTENT' => 've viec xin su dung tai nguyen'
//        );
        $arayOffice['RQST']['SCHEDULETIME'] = '';
        $arayOffice['RQST']['MOBILELIST'] = '84819255456';
        $arayOffice['RQST']['ISTELCOSUB'] = '0';
        $arayOffice['RQST']['AGENTID'] = '244';
        $arayOffice['RQST']['APIUSER'] = 'SOTNMT_HN';
        $arayOffice['RQST']['APIPASS'] = 'aBc123@';
        $arayOffice['RQST']['USERNAME'] = 'SOTNMT_HN';
        $arayOffice['RQST']['DATACODING'] = '0';

        $data = json_encode($arayOffice);
        $url = 'http://113.185.0.35:8888/smsbn/api';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arayOffice));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response . PHP_EOL;

        dd($response, $data);

    }

    public function layNguoiKyChanhVp(Request $request)
    {
        $loaiVanBan = LoaiVanBan::where('id', $request->loai_van_ban)->first();
        $loaiVanBanThongBaoTraLai = LoaiVanBan::where('ten_loai_van_ban', 'LIKE', '%Th??ng b??o - TL h??? s??%')->first();

        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $user = auth::user();
        $donVi = $user->donVi;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    if ($donVi->dieu_hanh == 1) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG])
                            ->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $truongpho = null;
                    }

                    if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                        $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                        if ($chanhVanPhongtp != null) {
                            foreach ($chanhVanPhongtp as $data3) {
                                array_push($dataNguoiKy, $data3);
                            }

                        }
                    }
                    if ($truongpho != null) {
                        foreach ($truongpho as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                    if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                        $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                        if ($chanhVanPhongtp != null) {
                            foreach ($chanhVanPhongtp as $data3) {
                                array_push($dataNguoiKy, $data3);
                            }

                        }
                    }

                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case PHO_PHONG:
                if ($donVi->dieu_hanh == 1) {
                    $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $truongpho = null;
                }

                if ($truongpho != null) {
                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                }
                if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                    $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                    if ($chanhVanPhongtp != null) {
                        foreach ($chanhVanPhongtp as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
//                        $ds_nguoiKy = $lanhDaoSo;
                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                        $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                        if ($chanhVanPhongtp != null) {
                            foreach ($chanhVanPhongtp as $data3) {
                                array_push($dataNguoiKy, $data3);
                            }

                        }
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $ds_nguoiKy1 = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                    foreach ($ds_nguoiKy1 as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                        $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                        if ($chanhVanPhongtp != null) {
                            foreach ($chanhVanPhongtp as $data3) {
                                array_push($dataNguoiKy, $data3);
                            }

                        }
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
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
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }
                if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                    $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                    if ($chanhVanPhongtp != null) {
                        foreach ($chanhVanPhongtp as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = $lanhDaoSo;
                break;

            case TRUONG_BAN:
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }
                if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                    $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                    if ($chanhVanPhongtp != null) {
                        foreach ($chanhVanPhongtp as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                }

                $ds_nguoiKy = $dataNguoiKy;
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
                if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                    $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                    if ($chanhVanPhongtp != null) {
                        foreach ($chanhVanPhongtp as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

        }
        return response()->json(
            [
                'ds_nguoi_Ky' => $ds_nguoiKy
            ]
        );


    }

    public function layNguoiKyChanhVpduyetvanban(Request $request)
    {
        $loaiVanBan = LoaiVanBan::where('id', $request->loai_van_ban)->first();
        $loaiVanBanThongBaoTraLai = LoaiVanBan::where('ten_loai_van_ban', 'LIKE', '%Th??ng b??o - TL h??? s??%')->first();

        $nguoinhan = null;
        $dataNguoiNhan = [];
        $user = auth::user();
        $donVi = $user->donVi;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();
        if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
            switch (auth::user()->roles->pluck('name')[0]) {
                case CHUYEN_VIEN:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();

                    } else {
                        $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    }
                    break;
                case PHO_PHONG:
                    $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    break;
                case TRUONG_PHONG:
                    if ($donVi->parent_id == 0) {
                        foreach ($lanhDaoSo as $data2) {
                            array_push($dataNguoiNhan, $data2);
                        }
                        if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                            $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                            if ($chanhVanPhongtp != null) {
                                foreach ($chanhVanPhongtp as $data3) {
                                    array_push($dataNguoiNhan, $data3);
                                }

                            }
                        }
                        $nguoinhan = $dataNguoiNhan;
                    } else {
                        $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                    }
                    break;
                case PHO_CHU_TICH:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                    }
                    break;
                case CHU_TICH:
                    if ($donVi->cap_xa == 0) {
                        $nguoinhan = null;
                    } else {
                        $nguoinhan1 = User::role([CHU_TICH, PHO_CHU_TICH])->where('cap_xa', null)->whereNull('deleted_at')->get();
                        if ($nguoinhan1) {
                            foreach ($lanhDaoSo as $data2) {
                                array_push($dataNguoiNhan, $data2);
                            }
                        }
                        if ($loaiVanBan->id == $loaiVanBanThongBaoTraLai->id) {
                            $chanhVanPhongtp = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();

                            if ($chanhVanPhongtp != null) {
                                foreach ($chanhVanPhongtp as $data3) {
                                    array_push($dataNguoiNhan, $data3);
                                }

                            }
                        }

                        $nguoinhan = $dataNguoiNhan;
                    }
                    break;
                case CHANH_VAN_PHONG:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = $lanhDaoSo;
                    }
                    break;
                case PHO_CHANH_VAN_PHONG:
                    $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                    break;
                case VAN_THU_DON_VI:
                    $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
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

            return response()->json(
                [
                    'ds_nguoi_Ky' => $nguoinhan
                ]
            );
        }


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
        $ds_DonVi_phatHanh = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->where('dieu_hanh', 1)->get();
        $emailSoBanNganh = MailTrongThanhPho::where('mail_group', 1)->orderBy('ten_don_vi', 'asc')->get();
        $emailQuanHuyen = MailTrongThanhPho::where('mail_group', 2)->orderBy('ten_don_vi', 'asc')->get();
        $emailTrucThuoc = MailTrongThanhPho::where('mail_group', 3)->orderBy('ten_don_vi', 'asc')->get();
//        dd($emailTrucThuoc);
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
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->whereIN('loai_van_ban',[2,3])->orderBy('id', 'asc')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->where('parent_id', 0)->orderBy('id', 'desc')->get();

        $nguoinhan = null;
        $vanThuVanBanDiPiceCharts = [];
        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id ?? null)->first();
        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $chanhVanPhongKy = User::where('id', 106)->get();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();
        $phongThanhTra = User::role([TRUONG_PHONG, PHO_PHONG])
            ->where('don_vi_id', 12)->whereNull('deleted_at')->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    if ($donVi->dieu_hanh == 1) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                            ->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $truongpho = null;
                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                    if ($truongpho != null) {
                        foreach ($truongpho as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }
                    if ($phongThanhTra != null) {
                        foreach ($phongThanhTra as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                    if ($chanhVanPhongKy != null) {
                        foreach ($chanhVanPhongKy as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }

                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case PHO_PHONG:
                if ($donVi->dieu_hanh == 1) {
                    $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $truongpho = null;
                }

                if ($truongpho != null) {
                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                }
                if ($phongThanhTra != null) {
                    foreach ($phongThanhTra as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }
                if ($chanhVanPhongKy != null) {
                    foreach ($chanhVanPhongKy as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    if ($phongThanhTra != null) {
                        foreach ($phongThanhTra as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                    if ($chanhVanPhongKy != null) {
                            foreach ($chanhVanPhongKy as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                }

                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
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
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = $lanhDaoSo;
                break;

            case TRUONG_BAN:
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }

                $ds_nguoiKy = $dataNguoiKy;
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
                if ($donVi->parent_id == 0) {
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();

                } else {
                    $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = $lanhDaoSo;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = null;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = $lanhDaoSo;
                }
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
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

        return view('vanbandi::van_ban_di.create', compact('ds_nguoiKy',
            'ds_soVanBan', 'ds_loaiVanBan', 'ds_doKhanCap', 'ds_DonVi_phatHanh', 'ds_mucBaoMat', 'ds_DonVi', 'nguoinhan', 'ds_DonVi_nhan',
            'emailtrongthanhpho', 'emailngoaithanhpho', 'emailQuanHuyen', 'emailSoBanNganh', 'emailTrucThuoc'));
    }

    public function nhapVanBanDiVanThuSo(Request $request)
    {
        $user = auth::user();
        $donVi = $user->donVi;
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $ds_DonVi_phatHanh = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->where('dieu_hanh', 1)->get();
        $emailSoBanNganh = MailTrongThanhPho::where('mail_group', 1)->orderBy('ten_don_vi', 'asc')->get();
        $emailQuanHuyen = MailTrongThanhPho::where('mail_group', 2)->orderBy('ten_don_vi', 'asc')->get();
        $emailTrucThuoc = MailTrongThanhPho::where('mail_group', 3)->orderBy('ten_don_vi', 'asc')->get();
//        dd($emailTrucThuoc);
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
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->whereIN('loai_van_ban',[2,3])->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->where('parent_id', 0)->orderBy('thu_tu', 'asc')->get();

        $nguoinhan = null;
        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id ?? null)->first();
        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();
        $donViSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();


        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    if ($donVi->dieu_hanh == 1) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                            ->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $truongpho = null;
                    }

                    if ($truongpho != null) {
                        foreach ($truongpho as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case PHO_PHONG:
                if ($donVi->dieu_hanh == 1) {
                    $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $truongpho = null;
                }

                if ($truongpho != null) {
                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = $lanhDaoSo;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
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
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = $lanhDaoSo;
                break;

            case TRUONG_BAN:
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }

                $ds_nguoiKy = $dataNguoiKy;
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
        return view('vanbandi::van_ban_di.van_thu_so_nhap', compact('ds_nguoiKy',
            'ds_soVanBan', 'ds_loaiVanBan', 'ds_doKhanCap', 'ds_DonVi_phatHanh', 'ds_mucBaoMat', 'ds_DonVi', 'nguoinhan', 'ds_DonVi_nhan',
            'emailtrongthanhpho', 'emailngoaithanhpho', 'emailQuanHuyen', 'donViSo', 'emailSoBanNganh', 'emailTrucThuoc'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function laySoDi(Request $request)
    {
//        dd($request->all());
        $donViSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $nam = date("Y");
        $soDivb = null;

        $soVanBan = $request->so_van_ban;
        $loaiVanBan = $request->loai_van_ban;


        $loaiVanBanNhap = LoaiVanBan::where('id', $loaiVanBan)->first();


        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDivb = VanBanDi::where([
//                'don_vi_id' => $donViSo->don_vi_id,
                'so_van_ban_id' => $soVanBan,
                'type' => 1
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_di');
//            ])->whereYear('ngay_ban_hanh', '=', $nam)->get();
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDivb = VanBanDi::where([
                'van_ban_huyen_ky' => auth::user()->donVi->parent_id,
                'so_van_ban_id' => $soVanBan,
                'type' => 2
            ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_di');
        }

        $soDi = $soDivb + 1;
        $stnmt = '-STNMT';
        $loai = $loaiVanBanNhap->ten_viet_tat;
        $donVi = auth::user()->donVi->ten_viet_tat;

        $soKyHieu = "$soDi/$loai$stnmt-$donVi";

        return response()->json(
            [
                'html' => $soKyHieu,
                'soDi' => $soDi
            ]
        );


    }

    public function luuVanBanDiSo(Request $request)
    {
        $tenMailThem = !empty($request['ten_don_vi_them']) ? $request['ten_don_vi_them'] : null;
        $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
        $donvinhanvanbandi = !empty($request['don_vi_nhan_van_ban_di']) ? $request['don_vi_nhan_van_ban_di'] : null;
        $EmailThem = $request->email_them;
        $nguoiky = User::where('id', $request->nguoiky_id)->first();
        $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
        $file = !empty($request['file']) ? $request['file'] : null;
        $dataIdEmailNgoai = [];
        $donViSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        if ($tenMailThem && count($tenMailThem) > 0) {
            foreach ($tenMailThem as $key => $data) {
                if (!empty($data)) {
                    $themDonVi = new MailNgoaiThanhPho();
                    $themDonVi->ten_don_vi = $data;
                    $themDonVi->email = $EmailThem[$key];
                    $themDonVi->save();
                    array_push($dataIdEmailNgoai, $themDonVi->id);
                }
            }
        }


        $user = auth::user();
        $vanbandi = new VanBanDi();
        $vanbandi->trich_yeu = $request->vb_trichyeu;
        $vanbandi->van_ban_den_id = !empty($vanBanDenId) ? explode(',', $vanBanDenId) : null;
        $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
        $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
        $vanbandi->loai_van_ban_id = $request->loaivanban_id;
        $vanbandi->do_khan_cap_id = $request->dokhan_id;
        $vanbandi->so_di = $request->so_di;
        $vanbandi->so_ban = $request->so_ban;
        $vanbandi->phong_phat_hanh = $request->phong_phat_hanh;
        $vanbandi->chuc_vu = $request->chuc_vu;
        $vanbandi->do_bao_mat_id = $request->dobaomat_id;
        if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHU_TICH ||
            $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //????y l?? huy???n k??
        {
            if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) ||
                $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                //????y l?? huy???n so???n th???o v?? huy???n k??
//                    $vanbandi->don_vi_soan_thao = '';
            } else {//????y l?? ????n v??? so???n th???o do huy???n k??
//                    $vanbandi->don_vi_soan_thao = '';
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
            }
            $vanbandi->type = 1;
        } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG || $nguoiky->role_id == QUYEN_VAN_THU_DON_VI) {
            //????y l?? ????n v??? k??
            $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
            $vanbandi->don_vi_soan_thao = $request->donvisoanthao_id;
            $vanbandi->type = 2;
        }

        $vanbandi->so_van_ban_id = $request->sovanban_id;
        $vanbandi->nguoi_ky = $request->nguoiky_id;
        $vanbandi->loai_van_ban_giay_moi = 1;
        $vanbandi->nguoi_tao = auth::user()->id;
        $vanbandi->save();
        if ($file && count($file) > 0) {
            foreach ($file as $key => $getFile) {
                $typeArray = explode('.', $getFile->getClientOriginalName());
                $tenchinhfile = strtolower($typeArray[0]);
                $extFile = $getFile->extension();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                $vanBanDiFile = new FileVanBanDi();
                $getFile->move($uploadPath, $fileName);
                $vanBanDiFile->ten_file = $tenchinhfile;
                $vanBanDiFile->duong_dan = $urlFile;
                $vanBanDiFile->duoi_file = $extFile;
                $vanBanDiFile->van_ban_di_id = $vanbandi->id;
                $vanBanDiFile->file_chinh_gui_di = 2;
                $vanBanDiFile->trang_thai = 2;
                $vanBanDiFile->nguoi_dung_id = auth::user()->id;
                $vanBanDiFile->don_vi_id = $donViSo->don_vi_id;
                $vanBanDiFile->loai_file = FileVanBanDi::LOAI_FILE_DA_KY;
                $vanBanDiFile->save();
            }
        }
        if ($tenMailThem && count($tenMailThem) > 0) {
            foreach ($dataIdEmailNgoai as $key => $ngoai) {
                $mailngoai = new NoiNhanMailNgoai();
                $mailngoai->van_ban_di_id = $vanbandi->id;
                $mailngoai->email = $ngoai;
                $mailngoai->save();
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
        if ($donvinhanvanbandi && count($donvinhanvanbandi) > 0) {
            foreach ($donvinhanvanbandi as $key => $donViId) {
                $donVi = DonVi::where('id', $donViId)->first();

                $donvinhan = new NoiNhanVanBanDi();
                $donvinhan->van_ban_di_id = $vanbandi->id;
                $donvinhan->don_vi_id_nhan = $donViId;
                $donvinhan->dieu_hanh = $donVi->dieu_hanh ?? 0;
                $donvinhan->don_vi_gui = auth::user()->don_vi_id;
                $donvinhan->save();
            }
        }

        return redirect()->route('van-ban-di.index')
            ->with('success', 'Th??m v??n b???n ??i th??nh c??ng !');
    }

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
            $tenMailThem = !empty($request['ten_don_vi_them']) ? $request['ten_don_vi_them'] : null;
            $EmailThem = $request->email_them;
            $user = auth::user();
            $vanBanDenId = $request->get('van_ban_den_id') ?? null;
            $dataIdEmailNgoai = [];

            if ($tenMailThem && count($tenMailThem) > 0) {
                foreach ($tenMailThem as $key => $data) {
                    if (!empty($data)) {
                        $themDonVi = new MailNgoaiThanhPho();
                        $themDonVi->ten_don_vi = $data;
                        $themDonVi->email = $EmailThem[$key];
                        $themDonVi->save();
                        array_push($dataIdEmailNgoai, $themDonVi->id);
                    }
                }
            }
            $vanbandi = new VanBanDi();
            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->van_ban_den_id = !empty($vanBanDenId) ? explode(',', $vanBanDenId) : null;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->phong_phat_hanh = $request->phong_phat_hanh;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->so_ban = $request->so_ban;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHU_TICH ||
                $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //????y l?? huy???n k??
            {
                if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) ||
                    $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                    //????y l?? huy???n so???n th???o v?? huy???n k??
//                    $vanbandi->don_vi_soan_thao = '';
                } else {//????y l?? ????n v??? so???n th???o do huy???n k??
//                    $vanbandi->don_vi_soan_thao = '';
                    $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                }
                $vanbandi->type = 1;
            } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG || $nguoiky->role_id == QUYEN_VAN_THU_DON_VI) {
                //????y l?? ????n v??? k??
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                if ($user->don_vi_id != 12 || $user->don_vi_id != 4) {
                    $vanbandi->don_vi_soan_thao = $request->donvisoanthao_id;

                }
                $vanbandi->type = 2;
            }

            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->loai_van_ban_giay_moi = 1;
            $vanbandi->nguoi_tao = auth::user()->id;
            $vanbandi->save();
            UserLogs::saveUserLogs(' T???o v??n b???n ??i', $vanbandi);

            $canbonhan = new VanBanDiChoDuyet();
            $canbonhan->van_ban_di_id = $vanbandi->id;
            $canbonhan->can_bo_chuyen_id = $vanbandi->nguoi_tao;
            $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
            $canbonhan->save();


            if ($donvinhanvanbandi && count($donvinhanvanbandi) > 0) {
                foreach ($donvinhanvanbandi as $key => $donViId) {
                    $donVi = DonVi::where('id', $donViId)->first();

                    $donvinhan = new NoiNhanVanBanDi();
                    $donvinhan->van_ban_di_id = $vanbandi->id;
                    $donvinhan->don_vi_id_nhan = $donViId;
                    $donvinhan->don_vi_gui = auth::user()->don_vi_id;
                    $donvinhan->dieu_hanh = $donVi->dieu_hanh ?? 0;
                    $donvinhan->save();
                }
            }

            if ($filehoso && count($filehoso) > 0) {
                foreach ($filehoso as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $ten = !empty($tenfilehoso[$key]) ? strSlugFileName(strtolower($tenfilehoso[$key]), '_') . '.' . $extFile : null;
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0777, true, true);
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
                        File::makeDirectory($uploadPath, 0777, true, true);
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
                        File::makeDirectory($uploadPath, 0777, true, true);
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
            if ($tenMailThem && count($tenMailThem) > 0) {
                foreach ($dataIdEmailNgoai as $key => $ngoai) {
                    $mailngoai = new NoiNhanMailNgoai();
                    $mailngoai->van_ban_di_id = $vanbandi->id;
                    $mailngoai->email = $ngoai;
                    $mailngoai->save();
                }
            }
            $isSuccess = true;

            VanBanDi::luuVanBanDiVanBanDen($vanbandi->id, $vanBanDenId);

            DB::commit();
        } catch (Exception $e) {
            $isSuccess = false;
        }
        if ($isSuccess) {
            return redirect()->route('van-ban-di.index')
                ->with('success', 'Th??m v??n b???n ??i th??nh c??ng !');
        } else {
            redirect()->back()
                ->with('failed', 'Th??m v??n b???n th???t b???i, vui l??ng th??? l???i !');
        }
    }

    public function vanBanDiTaoChuaDuyet()
    {
        $ds_vanBanDi = VanBanDi::where(['so_di' => null, 'nguoi_tao' => auth::user()->id])->get();
        return view('vanbandi::van_ban_di.vanBanDiChoDuyet', compact('ds_vanBanDi'));
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
    public function suavbdids(Request $request)
    {

        canPermission(AllPermission::suaVanBanDi());
        $id = $request->get('id');
        $vanbandi = VanBanDi::where('id', $id)->first();
        $donVi = auth::user()->donVi;

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
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->whereIN('loai_van_ban',[2,3])->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->where('parent_id', 0)->orderBy('thu_tu', 'asc')->get();

        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();
        $phongThanhTra = User::role([TRUONG_PHONG, PHO_PHONG])
            ->where('don_vi_id', 12)->whereNull('deleted_at')->get();
        $chanhVanPhongKy = User::where('id', 106)->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    if ($donVi->dieu_hanh == 1) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                            ->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $truongpho = null;
                    }

                    if ($truongpho != null) {
                        foreach ($truongpho as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }
                    if ($phongThanhTra != null) {
                        foreach ($phongThanhTra as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case PHO_PHONG:
                if ($donVi->dieu_hanh == 1) {
                    $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $truongpho = null;
                }

                if ($truongpho != null) {
                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                }
                if ($phongThanhTra != null) {
                    foreach ($phongThanhTra as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    if ($phongThanhTra != null) {
                        foreach ($phongThanhTra as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }
                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }


                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $chiCuc = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
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
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_HUYEN:
                if ($phongThanhTra != null) {

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($phongThanhTra as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }
                if ($chanhVanPhongKy != null) {
                    foreach ($chanhVanPhongKy as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case TRUONG_BAN:
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }

                $ds_nguoiKy = $dataNguoiKy;
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

        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();

        $lay_emailtrongthanhpho = NoiNhanMail::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_emailngoaithanhpho = NoiNhanMailNgoai::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_noi_nhan_van_ban_di = NoiNhanVanBanDi::where(['van_ban_di_id' => $id])->whereIn('trang_thai', [1, 2, 3])->get();
        $vanbandi->listVanBanDen = $vanbandi->getListVanBanDen();

        return view('vanbandi::van_ban_di.edit', compact('vanbandi', 'ds_soVanBan', 'ds_loaiVanBan', 'ds_DonVi', 'ds_doKhanCap',
            'ds_mucBaoMat', 'ds_nguoiKy', 'emailtrongthanhpho', 'emailngoaithanhpho', 'ds_DonVi_nhan', 'lay_emailtrongthanhpho', 'lay_emailngoaithanhpho', 'lay_noi_nhan_van_ban_di'));
    }

    public function suavbdivacapso(Request $request)

    {
        canPermission(AllPermission::suaVanBanDi());
        $id = $request->get('id');
        $vanbandi = VanBanDi::where('id', $id)->first();
        $donVi = auth::user()->donVi;

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
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->where('parent_id', 0)->orderBy('thu_tu', 'asc')->get();

        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $chanhVanPhongKy = User::where('id', 106)->get();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();

        $phongThanhTra = User::role([TRUONG_PHONG, PHO_PHONG])
            ->where('don_vi_id', 12)->whereNull('deleted_at')->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    if ($donVi->dieu_hanh == 1) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                            ->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $truongpho = null;
                    }

                    if ($truongpho != null) {
                        foreach ($truongpho as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }
                    if ($phongThanhTra != null) {
                        foreach ($phongThanhTra as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                    $ds_nguoiKy = $dataNguoiKy;
                }
                break;
            case PHO_PHONG:
                if ($donVi->dieu_hanh == 1) {
                    $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $truongpho = null;
                }

                if ($truongpho != null) {
                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                }
                if ($phongThanhTra != null) {
                    foreach ($phongThanhTra as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = $lanhDaoSo;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                }
                if ($phongThanhTra != null) {
                    foreach ($phongThanhTra as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
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
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_HUYEN:
                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                if ($phongThanhTra != null) {
                    foreach ($phongThanhTra as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }
                if ($chanhVanPhongKy != null) {
                    foreach ($chanhVanPhongKy as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case TRUONG_BAN:
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }

                $ds_nguoiKy = $dataNguoiKy;
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

        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();

        $lay_emailtrongthanhpho = NoiNhanMail::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_emailngoaithanhpho = NoiNhanMailNgoai::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_noi_nhan_van_ban_di = NoiNhanVanBanDi::where(['van_ban_di_id' => $id])->whereIn('trang_thai', [1, 2, 3])->get();
        $vanbandi->listVanBanDen = $vanbandi->getListVanBanDen();

        return view('vanbandi::van_ban_di.editvacapso', compact('vanbandi', 'ds_soVanBan', 'ds_loaiVanBan', 'ds_DonVi', 'ds_doKhanCap',
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

            $arrVanBanDenId = $vanbandi->van_ban_den_id ?? [];
            $vanBanDenId = !empty($request->get('van_ban_den_id')) ? explode(',', $request->get('van_ban_den_id')) : null;

            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->so_di = $request->so_di;
            $vanbandi->van_ban_den_id = !empty($vanBanDenId) ? array_merge($vanBanDenId, $arrVanBanDenId) : null;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHU_TICH ||
                $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //????y l?? huy???n k??
            {
                if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) ||
                    $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                    //????y l?? huy???n so???n th???o v?? huy???n k??
//                    $vanbandi->don_vi_soan_thao = '';
                } else {//????y l?? ????n v??? so???n th???o do huy???n k??
//                    $vanbandi->don_vi_soan_thao = '';
                    $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                }
            } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG || $nguoiky->role_id == QUYEN_VAN_THU_DON_VI) {
                //????y l?? ????n v??? k??
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
            }
//            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->loai_van_ban_giay_moi = 1;
            $vanbandi->nguoi_tao = auth::user()->id;
            $vanbandi->save();
            UserLogs::saveUserLogs(' S???a v??n b???n ??i', $vanbandi);
            $donvinhanvanbandi = !empty($request['don_vi_nhan_van_ban_di']) ? $request['don_vi_nhan_van_ban_di'] : null;
            $noinhanvb = NoiNhanVanBanDi::where(['van_ban_di_id' => $id, 'trang_thai' => 1])->get();
            $idnoinhanvb = $noinhanvb->pluck('don_vi_id_nhan')->toArray();

            if ($donvinhanvanbandi && count($donvinhanvanbandi) > 0) {

                if (array_diff($donvinhanvanbandi, $idnoinhanvb) == null && count($idnoinhanvb) == count($donvinhanvanbandi)) {
                    //????y l?? tr?????ng h???p kh??ng thay ?????i
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
                        $donvinhan->don_vi_gui = auth::user()->don_vi_id;
                        $laydonvimoi->save();
                    }
                }
            }
            $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
            $mailngoaitp = NoiNhanMailNgoai::where(['van_ban_di_id' => $id, 'status' => 1])->get();
            $iddoviphongkhac = $mailngoaitp->pluck('email')->toArray();
            if ($donvinhanmailngoaitp && count($donvinhanmailngoaitp) > 0) {
                if (array_diff($donvinhanmailngoaitp, $iddoviphongkhac) == null && count($iddoviphongkhac) == count($donvinhanmailngoaitp)) {
                    //????y l?? tr?????ng h???p kh??ng thay ?????i
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
            $donVi = auth::user()->donVi;
            $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;
            if ($request->File) {
                $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }
                $typeArray = explode('.', $request->File->getClientOriginalName());
                $tenchinhfile = strtolower($typeArray[0]);
                $extFile = $request->File->extension();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $request->File->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DEN . '/' . $fileName;

                $vanBanDiFile = new FileVanBanDi();
                $request->File->move($uploadPath, $fileName);
                $vanBanDiFile->ten_file = $tenchinhfile;
                $vanBanDiFile->duong_dan = $urlFile;
                $vanBanDiFile->duoi_file = $extFile;
                $vanBanDiFile->van_ban_di_id = $vanbandi->id;
                $vanBanDiFile->file_chinh_gui_di = 2;
                $vanBanDiFile->trang_thai = 2;
                $vanBanDiFile->nguoi_dung_id = auth::user()->id;
                $vanBanDiFile->don_vi_id = $donViId;
                $vanBanDiFile->loai_file = FileVanBanDi::LOAI_FILE_DA_KY;
                $vanBanDiFile->save();
            }


            $isSuccess = true;

            VanBanDi::luuVanBanDiVanBanDen($vanbandi->id, $request->get('van_ban_den_id'));

            DB::commit();
        } catch (Exception $e) {
            $isSuccess = false;
        }
        if ($isSuccess) {

            return redirect()->back()
                ->with('success', 'C???p nh???t th??ng tin v??n b???n th??nh c??ng !');

        } else {
            redirect()->back()
                ->with('failed', 'C???p nh???t th???t b???i, vui l??ng th??? l???i !');
        }
    }

    public function multiple_file_di(Request $request)
    {
        $user = auth::user();
        $type = $request->get('type') ?? null;
        if ($type && $type == 'GM') {
            $uploadPath = UPLOAD_FILE_GIAY_MOI_DI;
        } else {
            $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
        }

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        if (empty($multiFiles) || count($multiFiles) == 0 || (count($multiFiles) > 19)) {
            return redirect()->back()->with('warning', 'B???n ph???i ch???n file ho???c ph???i ch???n s??? l?????ng file nh??? h??n 20 file   !');
        }
        $donViId = null;

        if ($user->hasRole(VAN_THU_HUYEN)) {
            $lanhDaoSo = User::role([CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();

            $donViId = $lanhDaoSo->don_vi_id ?? null;
        } else {
            $donViId = $user->donVi->parent_id;
        }

        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $tenchinhfile = strtolower($typeArray[0]);
            $extFile = $getFile->extension();
            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

            $urlFile = $uploadPath . '/' . $fileName;
            $tachchuoi = explode("-", $tenchinhfile);
            $tenviettatsovb = strtoupper($tachchuoi[0]);
            $tenviettatso = strtoupper($tachchuoi[1]);
            $sodi = (int)$tachchuoi[2];
            $loaivanban = LoaiVanBan::where(['ten_viet_tat' => $tenviettatso])
                ->whereNull('deleted_at')->first();
            $soVanBanden = SoVanBan::where(['ten_viet_tat' => $tenviettatsovb])
                ->whereNull('deleted_at')->first();

            $vanban = null;
//            dd($loaivanban,$soVanBanden);
            if (!empty($loaivanban) && !empty($soVanBanden)) {
                if ($user->hasRole(VAN_THU_HUYEN)) {
                    $vanban = VanBanDi::where(['loai_van_ban_id' => $loaivanban->id, 'so_di' => $sodi, 'so_van_ban_id' => $soVanBanden->id, 'type' => 1])->first();


                } elseif ($user->hasRole(VAN_THU_DON_VI)) {
                    $vanban = VanBanDi::where(['loai_van_ban_id' => $loaivanban->id, 'so_di' => $sodi, 'phong_phat_hanh' => auth::user()->donVi->parent_id])->first();
                }
            }
            if ($vanban) {
                $xoafiletrinhky = FileVanBanDi::where([
                    'trang_thai' => 2,
                    'file_chinh_gui_di' => 2,
                    'van_ban_di_id' => $vanban->id
                ])->first();
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
                $vanBanDiFile->don_vi_id = $donViId;
                $vanBanDiFile->loai_file = FileVanBanDi::LOAI_FILE_DA_KY;
                $vanBanDiFile->save();
                UserLogs::saveUserLogs(' Upload file v??n b???n ??i', $vanBanDiFile);
                //x??a file tr??nh k?? khi ???? k?? s??? l???i

                //g???i v??n b???n ??i ?????n c??c ????n v???
                $noinhan = NoiNhanVanBanDi::where('van_ban_di_id', $vanban->id)->where('trang_thai', 1)->get();
                foreach ($noinhan as $key => $noiNhanVanBanDi) {
                    $noiNhanVanBanDi->trang_thai = 2;
                    $noiNhanVanBanDi->save();

                    // tao lanh dao du hop
                    $this->taoLanhDaoDuHop($noiNhanVanBanDi->don_vi_id_nhan, $vanban);
                    if ($noiNhanVanBanDi->dieu_hanh == 0) {
                        NoiNhanVanBanDi::taoVanBanDenDonViKhongCoDieuHanh($noiNhanVanBanDi, $vanban);
                    }
                }

//                g???i mail ?????n c??c ????n v??? ngo??i
                if (count($vanban->mailngoaitp) > 0) {
                    SendEmailFileVanBanDi::dispatchNow(VanBanDi::LOAI_VAN_BAN_DI, $vanban->id, $donViId);

                } else {
                    foreach ($vanban->vanBanDiFileDaKy as $file) {
                        $file->trang_thai_gui = FileVanBanDi::TRANG_THAI_DA_GUI;
                        $file->save();
                    }
                    $vanban->phat_hanh_van_ban = VanBanDi::DA_PHAT_HANH;
                    $vanban->save();
                }
//                SendEmailFileVanBanDi::dispatch(VanBanDi::LOAI_VAN_BAN_DI, null, $donViId)->delay(now()->addMinutes(3));
            }
        }


        return redirect()->back()->with('success', 'Th??m file th??nh c??ng !');
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

        //t???o lanh dao du hop
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'gi???y m???i')->select('id')->first();
        ThanhPhanDuHop::store($giayMoi, $vanBanDi, [$nguoiDung->id ?? null], ThanhPhanDuHop::TYPE_VB_DI, $nguoiDung->don_vi_id ?? null);
    }

    public function createVanBanDenDonViKhongCoDieuHanh()
    {

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
        UserLogs::saveUserLogs(' X??a v??n b???n ??i', $vanbandi);
        return redirect()->back()
            ->with('success', 'X??a v??n b???n th??nh c??ng !');
    }

    public function ds_van_ban_di_cho_duyet()
    {
        $nguoinhan = null;
        $vanbandichoduyet = null;
        $idnguoiky = null;
        $idcuanguoinhan = null;
        $vanbandichoduyet = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id, 'trang_thai' => 1])->get();
//        dd(auth::user()->id);


        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id ?? null)->first();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();

                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                }
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = $lanhDaoSo;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->cap_xa == 0) {
                    $nguoinhan = null;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('cap_xa', null)->whereNull('deleted_at')->get();
                }
                break;
            case CHANH_VAN_PHONG:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = $lanhDaoSo;
                }
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
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

        if (!empty($nguoinhan)) {
            $idcuanguoinhan = $nguoinhan->pluck('id');
        }

        return view('vanbandi::Duyet_van_ban_di.index', compact('vanbandichoduyet', 'nguoinhan', 'idcuanguoinhan'));
    }

    public function vanbandichoso(Request $request)
    {
        $user = auth::user();

        $trichyeu = $request->get('vb_trichyeu');
        $donvisoanthao = $request->get('donvisoanthao_id');

        $nguoi_ky = $request->get('nguoiky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $phatHanhVanBan = $request->get('phat_hanh_van_ban');
        $year = $request->get('year') ?? null;


        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

        $date = Carbon::now()->format('Y-m-d');
        $user = auth::user();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $vanbandichoso = VanBanDi::where(function ($query) use ($lanhDaoSo) {
                return $query->where('phong_phat_hanh', $lanhDaoSo->don_vi_id);
            })
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichyeu) . "%");
                    }
                })
                ->where(function ($query) use ($nguoi_ky) {
                    if (!empty($nguoi_ky)) {
                        return $query->where('nguoi_ky', $nguoi_ky);
                    }
                })
                ->where(function ($query) use ($donvisoanthao) {
                    if (!empty($donvisoanthao)) {
                        return $query->where('don_vi_soan_thao', $donvisoanthao);
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
                ->whereNull('so_di')
                ->orderBy('created_at', 'desc')
                ->paginate(PER_PAGE);
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $vanbandichoso = VanBanDi::where(function ($query) {
                return $query->where('phong_phat_hanh', auth::user()->donVi->parent_id);
            })
                ->where(function ($query) use ($trichyeu) {
                    if (!empty($trichyeu)) {
                        return $query->where(DB::raw('lower(trich_yeu)'), 'LIKE', "%" . mb_strtolower($trichyeu) . "%");
                    }
                })
                ->where(function ($query) use ($nguoi_ky) {
                    if (!empty($nguoi_ky)) {
                        return $query->where('nguoi_ky', $nguoi_ky);
                    }
                })
                ->where(function ($query) use ($donvisoanthao) {
                    if (!empty($donvisoanthao)) {
                        return $query->where('don_vi_soan_thao', $donvisoanthao);
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
                ->whereNull('so_di')
                ->orderBy('created_at', 'desc')
                ->paginate(PER_PAGE);
        }
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
//        $vanbandichoso = Vanbandichoduyet::where(['cho_cap_so' => 1])->orderBy('created_at', 'desc')->get();
        $emailTrongThanhPho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailNgoaiThanhPho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();


        return view('vanbandi::Du_thao_van_ban_di.vanbandichoso',
            compact('vanbandichoso', 'date', 'emailTrongThanhPho', 'emailNgoaiThanhPho', 'ds_DonVi', 'ds_nguoiKy', 'ds_soVanBan'));
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
                UserLogs::saveUserLogs(' Duy???t v??n b???n ??i', $canbonhan);


                //ch??? s??? v??n b???n ??i
                $vanbandi = VanBanDi::where('id', $nguoicu->van_ban_di_id)->first();
                $vanbandi->cho_cap_so = 2;
                $vanbandi->save();

                // update van ban den
                // bang trung gian 1 van ban den tra loi cho nhieu van ban di
                $this->updateVanBanDen($vanbandi);

                return redirect()->back()->with('success', 'Chuy???n v??n th?? ch??? c???p s??? th??nh c??ng !');

            } elseif ($tralai == 2) {
                $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $request->id_van_ban)->get();
                foreach ($vanbanduthao as $data) {
                    $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                    $vanbanduthao->trang_thai = 0;
                    $vanbanduthao->save();
                }
                $vanbandi = VanBanDi::where('id', $request->id_van_ban)->first();
                $nguoicu = Vanbandichoduyet::where('id', $request->id_vb_cho_duyet)->first();
                $nguoidautiennhan = Vanbandichoduyet::where('van_ban_di_id', $nguoicu->van_ban_di_id)->OrderBy('created_at', 'asc')->first();
                $canbonhan = new Vanbandichoduyet();
                $canbonhan->van_ban_di_id = $vanbandi->id;
                $canbonhan->can_bo_chuyen_id = auth::user()->id;
                $canbonhan->can_bo_nhan_id = $vanbandi->nguoi_tao;
                $canbonhan->y_kien_gop_y = $request->noi_dung;
                $canbonhan->trang_thai = 0;
                $canbonhan->tra_lai = 1;
                $canbonhan->save();
                UserLogs::saveUserLogs(' tr??? l???i v??n b???n ??i', $canbonhan);
                return redirect()->back()->with('success', 'Tr??? l???i th??nh c??ng !');
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

                $vanbandi = VanBanDi::where('id', $nguoicu->van_ban_di_id)->first();
                //????Y L?? v??n b???n c???a huy???n do ????n v??? so???n th???o
                if ($vanbandi->donViPhatHanh->cap_xa == null && $vanbandi->donViSoanThaoVB->dieu_hanh == 0 && $vanbandi->donViSoanThaoVB->parent_id == 0) {
//                    dd(1);
                    if (auth::user()->hasRole(TRUONG_PHONG)) {
                        $vanbandi->truong_phong_ky = 2;
                        $vanbandi->save();
                    }
                }
                //????Y L?? v??n b???n c???a huy???n do chi c???c so???n th???o
                if ($vanbandi->donViPhatHanh->cap_xa == null && $vanbandi->donViSoanThaoVB->parent_id != 0) {
//                    dd(2);
                    $user = auth::user();
                    switch (auth::user()->roles->pluck('name')[0]) {
                        case CHU_TICH:
                            if ($user->cap_xa == 1) {
                                $vanbandi->truong_phong_ky = 2;
                                $vanbandi->save();
                            }
                            break;
                    }
                }
                //????y l?? v??n b???n c???a chi c???c do ph??ng so???n th???o
                if ($vanbandi->donViPhatHanh->cap_xa == 1 && $vanbandi->donViSoanThaoVB->parent_id != 0) {
//                    dd(3);
                    switch (auth::user()->roles->pluck('name')[0]) {
                        case TRUONG_BAN:
                            $vanbandi->truong_phong_ky = 2;
                            $vanbandi->save();
                            break;
                    }
                }


                UserLogs::saveUserLogs(' Duy???t v??n b???n ??i', $canbonhan);
                $nguoicu->trang_thai = 2;
                $nguoicu->save();
                return redirect()->back()->with('success', 'Duy???t th??nh c??ng !');
            } elseif ($tralai == 2) {
                $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $request->id_van_ban)->get();
                $vanbandi = VanBanDi::where('id', $request->id_van_ban)->first();
                foreach ($vanbanduthao as $data) {
                    $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                    $vanbanduthao->trang_thai = 0;
                    $vanbanduthao->save();
                }
                $nguoicu = Vanbandichoduyet::where('id', $request->id_vb_cho_duyet)->first();
                $nguoidautiennhan = Vanbandichoduyet::where('van_ban_di_id', $nguoicu->van_ban_di_id)->OrderBy('van_ban_di_id', 'asc')->first();
                $canbonhan = new Vanbandichoduyet();
                $canbonhan->van_ban_di_id = $vanbandi->id;
                $canbonhan->can_bo_chuyen_id = auth::user()->id;
                $canbonhan->can_bo_nhan_id = $vanbandi->nguoi_tao;
                $canbonhan->y_kien_gop_y = $request->noi_dung;
                $canbonhan->trang_thai = 0;
                $canbonhan->tra_lai = 1;
                $canbonhan->save();

                $vanbandi = VanBanDi::where('id', $nguoicu->van_ban_di_id)->first();
                if (auth::user()->hasRole(CHU_TICH) || auth::user()->hasRole(PHO_CHU_TICH)) {
                    $vanbandi->truong_phong_ky = 1;
                    $vanbandi->save();
                }
                UserLogs::saveUserLogs(' tr??? l???i v??n b???n ??i', $canbonhan);
                return redirect()->back()->with('success', 'Tr??? l???i th??nh c??ng !');
            } elseif ($duyetlai == 3) {
                $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $request->id_van_ban)->get();
                foreach ($vanbanduthao as $data) {
                    $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                    $vanbanduthao->tra_lai = null;
                    $vanbanduthao->save();
                }

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
                            File::makeDirectory($uploadPath, 0777, true, true);
                        }
                        $getFile->move($uploadPath, $fileName);
                        $vbDenFile->ten_file = $ten;
                        $vbDenFile->duong_dan = $urlFile;
                        $vbDenFile->duoi_file = $extFile;
                        $vbDenFile->van_ban_di_id = $request->id_van_ban;
                        $vbDenFile->nguoi_dung_id = auth::user()->id;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->trang_thai = 2;
                        $vbDenFile->save();
                    }
                }
                return redirect()->back()->with('success', 'Chuy???n th??nh c??ng !');


            }
        }
    }

    public function Capsovanbandi(Request $request, $id)
    {
        if ($request->sua_cap_so == 1) {
            $nam_sodi = date('Y', strtotime($request->vb_ngaybanhanh));
            $vanbandi = VanBanDi::where('id', $id)->first();
            $loaiVanBan = LoaiVanBan::where('id', $request->loaivanban_id)->first();
            $soVanBan = SoVanBan::where('id', $request->so_van_ban_id)->first();
//            $this->update($request,$id);


            $user = auth::user();
            $maPhong = null;
            $SoKyHieu = null;
            $donVi = DonVi::where('id', $vanbandi->van_ban_huyen_ky)->first();
            if ($donVi->parent_id != 0) {
                $donVi = DonVi::where('id', $donVi->parent_id)->first();
            }

            switch ($donVi->ten_don_vi) {
                case 'Ph??ng T??i Nguy??n n?????c':
                    $maPhong = 'TNN';
                    break;
                case 'Thanh tra S???':
                    $maPhong = 'TTr';
                    break;
                case 'Ph??ng Kinh T??? ?????t':
                    $maPhong = 'ktd';
                    break;
                case 'Ph??ng ????ng k?? th???ng k?? ?????t ??ai':
                    $maPhong = '??KTK????';
                    break;
                case 'Ph??ng Quy ho???ch-K??? ho???ch s??? d???ng ?????t':
                    $maPhong = 'QKKH-SDD';
                    break;
                case 'Thanh tra S???':
                    $maPhong = 'TTr';
                    break;

                case 'Ph??ng ??o ?????c, B???n ????? v?? Vi???n th??m':
                    $maPhong = '????B??VT';
                    break;
                case 'V??n ph??ng S???':
                    $maPhong = 'VP';
                    break;
                case 'Ph??ng Kh?? t?????ng Th???y v??n v?? Bi???n ?????i kh?? h???u':
                    $maPhong = 'KTTVB??KH';
                    break;
                case 'Ph??ng ????ng k?? th???ng k?? ?????t ??ai':
                    $maPhong = '??KTK';
                    break;
                case 'Ph??ng Quy ho???ch-K??? ho???ch s??? d???ng ?????t':
                    $maPhong = 'QHKHSD??';
                    break;
                case 'Ph??ng Kinh t??? ?????t':
                    $maPhong = 'KT??';
                    break;
                case 'Ph??ng H??nh ch??nh T???ng h???p':
                    $maPhong = 'HCTH';
                    break;
                case 'Ph??ng K??? ho???ch T??i ch??nh':
                    $maPhong = 'KHTC';
                    break;
                case 'Ph??ng kho??ng s???n':
                    $maPhong = 'KS';
                    break;
                case 'Chi c???c B???o v??? m??i tr?????ng H?? N???i':
                    $maPhong = 'CCBVMT';
                    break;
                case 'V??n ph??ng ????ng k?? ?????t ??ai H?? N???i':
                    $maPhong = 'VP??K????';
                    break;
                case 'Trung t??m K??? thu???t T??i Nguy??n v?? m??i tr?????ng H?? N???i':
                    $maPhong = 'TTKTTNMT';
                    break;
                case 'Trung t??m Ph??t tri???n qu??? ?????t H?? N???i':
                    $maPhong = 'TTPTQ??';
                    break;
                case 'Trung t??m c??ng ngh??? th??ng tin T??i Nguy??n m??i tr?????ng H?? N???i':
                    $maPhong = 'TTCNTT';
                    break;
                case 'Ban qu???n l?? d??? ??n HS??C':
                    $maPhong = 'BQLDATTHS??C';
                    break;


            }
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->cho_cap_so = 3;
            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                $soDi = VanBanDi::where([
                    'so_van_ban_id' => $request->so_van_ban_id,
                    'don_vi_soan_thao' => null
                ])->whereNull('deleted_at')->whereYear('ngay_ban_hanh', '=', $nam_sodi)->max('so_di');


            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $soDi = VanBanDi::where([
                    'so_van_ban_id' => $request->so_van_ban_id,
                    'phong_phat_hanh' => auth::user()->donVi->parent_id
                ])->whereNull('deleted_at')->whereYear('ngay_ban_hanh', '=', $nam_sodi)->max('so_di');

            }
            $soDi = $soDi + 1;
            switch ($soVanBan->ten_so_van_ban) {
                case 'C??ng V??n':
                    if ($loaiVanBan->ten_loai_van_ban == 'C??ng v??n') {
                        $SoKyHieu = "$soDi/STNMT-$maPhong";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'K??? ho???ch') {
                        $SoKyHieu = "$soDi/KH-STNMT-$maPhong";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'B??o c??o') {
                        $SoKyHieu = "$soDi/BC-STNMT-$maPhong";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'T??? tr??nh') {
                        $SoKyHieu = "$soDi/TTr-STNMT-$maPhong";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'Phi???u chuy???n') {
                        $SoKyHieu = "$soDi/PC-STNMT-$maPhong";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'Gi???y ???y quy???n') {
                        $SoKyHieu = "$soDi/GUQ-STNMT-$maPhong";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'K???t lu???n Thanh tra') {
                        $SoKyHieu = "$soDi/KLTT-STNMT-$maPhong";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'K???t lu???n ki???m tra') {
                        $SoKyHieu = "$soDi/KLKT-STNMT-$maPhong";
                    }
                    break;
                case 'Quy???t ?????nh':
                    if ($loaiVanBan->ten_loai_van_ban == 'Quy???t ?????nh') {
                        $SoKyHieu = "$soDi/Q??-STNMT";
                    }
                    break;

                case 'Th??ng b??o':
                    if ($loaiVanBan->ten_loai_van_ban == 'Th??ng b??o') {
                        $SoKyHieu = "$soDi/TB-STNMT";
                    }
                    break;
                case 'Gi???y M???i':
                    if ($loaiVanBan->ten_loai_van_ban == 'Gi???y m???i') {
                        $SoKyHieu = "$soDi/GM-STNMT";
                    }
                    break;
                case 'C???p Ph??p':
                    if ($loaiVanBan->ten_loai_van_ban == 'Gi???y ph??p') {
                        $SoKyHieu = "$soDi/GP-STNMT";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'Gi???y x??c nh???n') {
                        $SoKyHieu = "$soDi/GXN-STNMT";
                    }
                    break;
                case 'Thanh Tra':
                    if ($loaiVanBan->ten_loai_van_ban == 'C??ng v??n') {
                        $SoKyHieu = "$soDi/STNMT";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'Quy???t ?????nh') {
                        $SoKyHieu = "$soDi/Q??-XPVPHC";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'K??? ho???ch') {
                        $SoKyHieu = "$soDi/KH";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'Gi???y m???i') {
                        $SoKyHieu = "$soDi/GM-TTR";
                    } elseif ($loaiVanBan->ten_loai_van_ban == 'B??o c??o') {
                        $SoKyHieu = "$soDi/BC-TTr";
                    }
                    break;


            }
            $vanbandi->so_di = $soDi;
            $vanbandi->so_ky_hieu = $SoKyHieu;
            $vanbandi->so_van_ban_id = $request->so_van_ban_id;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->truong_phong_ky = 3;
            $vanbandi->save();

            $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $id)->get();
            foreach ($vanbanduthao as $data) {
                $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                $vanbanduthao->trang_thai = 10;
                $vanbanduthao->save();
            }
            $vanbandiupdate = Vanbandichoduyet::where('van_ban_di_id', $id)->orderBy('created_at', 'desc')->first();
            $vanbandiupdate->cho_cap_so = 1;
            $vanbandiupdate->save();

            $this->updateVanBanDen($vanbandi);

            UserLogs::saveUserLogs(' C???p s??? v??n b???n ??i', $vanbandi);
            return redirect()->route('van-ban-di.index')->with(['capso' => "$soDi"]);
        } else {
            $nam_sodi = date('Y', strtotime($request->ngay_ban_hanh));
            $vanbandi = VanBanDi::where('id', $request->van_ban_di_id)->first();
            $loaiVanBan = LoaiVanBan::where('id', $vanbandi->loai_van_ban_id)->first();
            $soVanBan = SoVanBan::where('id', $request->sovanban_id)->first();


            $user = auth::user();
            $maPhong = null;
            $SoKyHieu = null;
            $donVi = DonVi::where('id', $vanbandi->van_ban_huyen_ky)->first();
            if ($donVi->parent_id != 0) {
                $donVi = DonVi::where('id', $donVi->parent_id)->first();
            }
            switch ($donVi->ten_don_vi) {
                case 'Ph??ng T??i Nguy??n n?????c':
                    $maPhong = 'TNN';
                    break;
                case 'Ph??ng Kinh T??? ?????t':
                    $maPhong = 'ktd';
                    break;
                case 'Ph??ng ????ng k?? th???ng k?? ?????t ??ai':
                    $maPhong = '??KTK????';
                    break;
                case 'Ph??ng Quy ho???ch-K??? ho???ch s??? d???ng ?????t':
                    $maPhong = 'QKKH-SDD';
                    break;
                case 'Thanh tra S???':
                    $maPhong = 'TTr';
                    break;

                case 'Ph??ng ??o ?????c, B???n ????? v?? Vi???n th??m':
                    $maPhong = '????B??VT';
                    break;
                case 'V??n ph??ng S???':
                    $maPhong = 'VP';
                    break;
                case 'Ph??ng Kh?? t?????ng Th???y v??n v?? Bi???n ?????i kh?? h???u':
                    $maPhong = 'KTTVB??KH';
                    break;
                case 'Ph??ng ????ng k?? th???ng k?? ?????t ??ai':
                    $maPhong = '??KTK';
                    break;
                case 'Ph??ng Quy ho???ch-K??? ho???ch s??? d???ng ?????t':
                    $maPhong = 'QHKHSD??';
                    break;
                case 'Ph??ng Kinh t??? ?????t':
                    $maPhong = 'KT??';
                    break;
                case 'Ph??ng H??nh ch??nh T???ng h???p':
                    $maPhong = 'HCTH';
                    break;
                case 'Ph??ng K??? ho???ch T??i ch??nh':
                    $maPhong = 'KHTC';
                    break;
                case 'Ph??ng kho??ng s???n':
                    $maPhong = 'KS';
                    break;
                case 'Chi c???c B???o v??? m??i tr?????ng H?? N???i':
                    $maPhong = 'CCBVMT';
                    break;
                case 'V??n ph??ng ????ng k?? ?????t ??ai H?? N???i':
                    $maPhong = 'VP??K????';
                    break;
                case 'Trung t??m K??? thu???t T??i Nguy??n v?? m??i tr?????ng H?? N???i':
                    $maPhong = 'TTKTTNMT';
                    break;
                case 'Trung t??m Ph??t tri???n qu??? ?????t H?? N???i':
                    $maPhong = 'TTPTQ??';
                    break;
                case 'Trung t??m c??ng ngh??? th??ng tin T??i Nguy??n m??i tr?????ng H?? N???i':
                    $maPhong = 'TTCNTT';
                    break;
                case 'Ban qu???n l?? d??? ??n HS??C':
                    $maPhong = 'BQLDATTHS??C';
                    break;


            }
            $vanbandi->ngay_ban_hanh = $request->ngay_ban_hanh;
            $vanbandi->cho_cap_so = 3;
            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                $soDi = VanBanDi::where([
                    'so_van_ban_id' => $request->sovanban_id,
                    'don_vi_soan_thao' => null
                ])->whereNull('deleted_at')->whereYear('ngay_ban_hanh', '=', $nam_sodi)->max('so_di');


            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $soDi = VanBanDi::where([
                    'so_van_ban_id' => $request->sovanban_id,
                    'phong_phat_hanh' => auth::user()->donVi->parent_id
                ])->whereNull('deleted_at')->whereYear('ngay_ban_hanh', '=', $nam_sodi)->max('so_di');

            }
            $soDi = $soDi + 1;
            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                switch ($soVanBan->ten_so_van_ban) {
                    case 'C??ng V??n':
                        if ($loaiVanBan->ten_loai_van_ban == 'C??ng v??n') {
                            $SoKyHieu = "$soDi/STNMT-$maPhong";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'K??? ho???ch') {
                            $SoKyHieu = "$soDi/KH-STNMT-$maPhong";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'B??o c??o') {
                            $SoKyHieu = "$soDi/BC-STNMT-$maPhong";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'T??? tr??nh') {
                            $SoKyHieu = "$soDi/TTr-STNMT-$maPhong";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'Phi???u chuy???n') {
                            $SoKyHieu = "$soDi/PC-STNMT-$maPhong";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'Gi???y ???y quy???n') {
                            $SoKyHieu = "$soDi/GUQ-STNMT-$maPhong";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'K???t lu???n Thanh tra') {
                            $SoKyHieu = "$soDi/KLTT-STNMT-$maPhong";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'K???t lu???n ki???m tra') {
                            $SoKyHieu = "$soDi/KLKT-STNMT-$maPhong";
                        }
                        break;
                    case 'Quy???t ?????nh':
                        if ($loaiVanBan->ten_loai_van_ban == 'Quy???t ?????nh') {
                            $SoKyHieu = "$soDi/Q??-STNMT";
                        }
                        break;

                    case 'Th??ng b??o':
                        if ($loaiVanBan->ten_loai_van_ban == 'Th??ng b??o') {
                            $SoKyHieu = "$soDi/TB-STNMT";
                        }
                        break;
                    case 'Gi???y M???i':
                        if ($loaiVanBan->ten_loai_van_ban == 'Gi???y m???i') {
                            $SoKyHieu = "$soDi/GM-STNMT";
                        }
                        break;
                    case 'C???p Ph??p':
                        if ($loaiVanBan->ten_loai_van_ban == 'Gi???y ph??p') {
                            $SoKyHieu = "$soDi/GP-STNMT";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'Gi???y x??c nh???n') {
                            $SoKyHieu = "$soDi/GXN-STNMT";
                        }
                        break;
                    case 'Thanh Tra':
                        if ($loaiVanBan->ten_loai_van_ban == 'C??ng v??n') {
                            $SoKyHieu = "$soDi/STNMT";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'Quy???t ?????nh') {
                            $SoKyHieu = "$soDi/Q??-XPVPHC";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'K??? ho???ch') {
                            $SoKyHieu = "$soDi/KH";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'Gi???y m???i') {
                            $SoKyHieu = "$soDi/GM-TTR";
                        } elseif ($loaiVanBan->ten_loai_van_ban == 'B??o c??o') {
                            $SoKyHieu = "$soDi/BC-TTr";
                        }
                        break;


                }
            } else {
                $IDdonVi = auth::user()->donVi->parent_id;
                $donVi = DonVi::where('id', $IDdonVi)->first();
                $SoKyHieu = "$soDi/$donVi->ten_viet_tat";
            }


//        $soKyHieu = "$soDi/$nam_truoc_skh$ma_van_ban$ma_don_vi$ma_phong_ban";
            $vanbandi->so_di = $soDi;
            $vanbandi->so_ky_hieu = $SoKyHieu;
            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->truong_phong_ky = 3;
            $vanbandi->save();

            $vanbanduthao = Vanbandichoduyet::where('van_ban_di_id', $request->van_ban_di_id)->get();
            foreach ($vanbanduthao as $data) {
                $vanbanduthao = Vanbandichoduyet::where('id', $data->id)->first();
                $vanbanduthao->trang_thai = 10;
                $vanbanduthao->save();
            }
            $vanbandiupdate = Vanbandichoduyet::where('van_ban_di_id', $request->van_ban_di_id)->orderBy('created_at', 'desc')->first();
            $vanbandiupdate->cho_cap_so = 1;
            $vanbandiupdate->save();

            $this->updateVanBanDen($vanbandi);

            UserLogs::saveUserLogs(' C???p s??? v??n b???n ??i', $vanbandi);
            return redirect()->back()->with(['capso' => "$soDi"]);
        }

//        SendEmailFileVanBanDi::dispatch(VanBanDi::LOAI_VAN_BAN_DI, $vanbandi->id)->delay(now()->addMinutes(5));

//        return response()->json([
//            'status' => true,
//            'message' => '???? ph??t h??nh v??n b???n.'
//        ], 200);
    }

    public function updateVanBanDen($vanBanDi)
    {
        $vanBanDiVanBanDen = VanBanDiVanBanDen::where('van_ban_di_id', $vanBanDi->id)->get();
        if ($vanBanDiVanBanDen) {
            foreach ($vanBanDiVanBanDen as $vanBanDiDen) {
                if ($vanBanDiDen->van_ban_den_id != null) {
                    VanBanDen::updateHoanThanhVanBanDen([$vanBanDiDen->van_ban_den_id]);

                }
            }
        }

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'gi???y m???i')->select('id')->first();
        //update lich cong tac
        if (!empty($giayMoi) && $vanBanDi && $vanBanDi->loai_van_ban_id == $giayMoi->id) {
            LichCongTac::taoLichCongTac($vanBanDi);
        }
    }

    public function Quytrinhxulyvanbandi($id)
    {
        $laytatcaduthao = null;
        $idduthao = Vanbandichoduyet::where('van_ban_di_id', $id)->orderBy('created_at', 'asc')->first();
        if ($idduthao) {
            $duthaovanban = Duthaovanbandi::where('id', $idduthao->id_du_thao)->first();
            if ($duthaovanban != null) {
                $laytatcaduthao = Duthaovanbandi::where('du_thao_id', $duthaovanban->du_thao_id)->get();
            }
        }


        $quatrinhtruyennhan = Vanbandichoduyet::where('van_ban_di_id', $id)->get();

        $vanbandi = VanBanDi::where('id', $id)->first();

        $vanbandi->listVanBanDen = $vanbandi->getListVanBanDen();


        $file = FileVanBanDi::where('van_ban_di_id', $id)->get();

        return view('vanbandi::Du_thao_van_ban_di.Quytrinhxulyvanbandi', compact('quatrinhtruyennhan', 'file', 'vanbandi', 'laytatcaduthao'));
    }

    public function removeVanBanDen(Request $request)
    {
        $vanBanDiId = $request->get('van_ban_di_id');
        $vanBanDenId = $request->get('van_ban_den_id');

        $vanBanDi = VanBanDiVanBanDen::where('van_ban_di_id', $vanBanDiId)
            ->where('van_ban_den_id', $vanBanDenId)
            ->first();
        if ($vanBanDi) {
            $vanBanDi->delete();

            return response()->json([
                'success' => true,
                'message' => '???? xo?? th??nh c??ng.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kh??ng t??m th???y d??? li???u.'
        ], 500);
    }

    public function xoaFileDi($id)
    {
        $vanBanDi = FileVanBanDi::where('id', $id)->first();
        $vanBanDi->delete();
        return redirect()->back()->with('success', 'X??a file th??nh c??ng !');
    }

}
