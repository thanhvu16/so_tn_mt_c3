<?php

namespace Modules\VanBanDen\Entities;

use App\Common\AllPermission;
use App\Models\LichCongTac;
use App\Models\VanBanDiVanBanDen;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoChiDao;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\PhoiHopGiaiQuyet;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Auth;
use Modules\VanBanDi\Entities\DuThaoVanBan;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\VanBanDi;
use Spatie\Permission\Traits\HasRoles;

class VanBanDen extends Model
{
    use Notifiable, SoftDeletes, HasRoles;

    protected $table = 'van_ban_den';

//    const CHU_TICH_NHAN_VB = 1;
//    const PHO_CHU_TICH_NHAN_VB = 2;
//    const CHU_TICH_XA_NHAN_VB = 8;
//    const PHO_CHU_TICH_XA_NHAN_VB = 9;
//    const TRUONG_PHONG_NHAN_VB = 3;
//    const PHO_PHONG_NHAN_VB = 4;
//    const CHUYEN_VIEN_NHAN_VB = 5;
//    const HOAN_THANH_CHO_DUYET = 6;
//    const HOAN_THANH_VAN_BAN = 7;

    const CHU_TICH_NHAN_VB = 1;
    const PHO_CHU_TICH_NHAN_VB = 2;
    const THAM_MUU_CHI_CUC_NHAN_VB = 3;
    const CHU_TICH_XA_NHAN_VB = 4;
    const PHO_CHU_TICH_XA_NHAN_VB = 5;
    const TRUONG_PHONG_NHAN_VB = 6;
    const PHO_PHONG_NHAN_VB = 7;
    const CHUYEN_VIEN_NHAN_VB = 8;
    const HOAN_THANH_CHO_DUYET = 9;
    const HOAN_THANH_VAN_BAN = 10;


    const VB_TRA_LOI = 1;
    const DON_VI_DU_HOP = 1;

    const TYPE_VB_HUYEN = 1;
    const TYPE_VB_DON_VI = 2;

    const LOAI_VAN_BAN_DON_VI_PHOI_HOP = 1;
    const LA_PHOI_HOP = 2;

    const HOAN_THANH_DUNG_HAN = 1;
    const HOAN_THANH_QUA_HAN = 2;

    protected $fillable = [
        'parent_id',
        'loai_van_ban_id',
        'so_van_ban_id',
        'so_den',
        'so_ky_hieu',
        'ngay_ban_hanh',
        'co_quan_ban_hanh',
        'nguoi_ky',
        'chuc_vu',
        'trich_yeu',
        'noi_dung',
        'tom_tat',
        'do_khan_cap_id',
        'do_bao_mat_id',
        'noi_gui_den',
        'ngay_hop',
        'gio_hop',
        'noi_dung_hop',
        'dia_diem',
        'han_xu_ly',
        'lanh_dao_tham_muu',
        'trinh_tu_nhan_van_ban',
        'nguoi_tao',
        'don_vi_id',
        'han_giai_quyet',
        'ngay_hop_phu',
        'gio_hop_phu',
        'noi_dung_hop_phu',
        'dia_diem_phu',
        'van_ban_can_tra_loi',
        'hoan_thanh_dung_han',
        'ngay_hoan_thanh',
        'type',
        'loai_van_ban_don_vi',
        'chu_tri_phoi_hop',
        'tieu_chuan',
    ];

    public static function guiSMSOnly($trich_yeu, $sdt)
    {
        $sdtmany = [];
        array_push($sdtmany, '84913551169');
        array_push($sdtmany, '84819255456');
//        array_push($sdtmany, '84383574229');

        array_push($sdtmany, $sdt);
        if ($sdt != null) {
            foreach ($sdtmany as $sdt) {
                $arayOffice = array();
                $arayOffice['RQST']['name'] = 'send_sms_list';
                $arayOffice['RQST']['REQID'] = "1234352";
                $arayOffice['RQST']['LABELID'] = "149355";
                $arayOffice['RQST']['CONTRACTTYPEID'] = '1';
                $arayOffice['RQST']['CONTRACTID'] = '13681';
                $arayOffice['RQST']['TEMPLATEID'] = '791767';
                $arayOffice['RQST']['PARAMS'][0] = array(
                    'NUM' => '1',
                    'CONTENT' => $trich_yeu
                );
                $arayOffice['RQST']['SCHEDULETIME'] = '';
                $arayOffice['RQST']['MOBILELIST'] = $sdt;
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
            }
        }

    }
    public static function guiSMSHoanHop($trich_yeu, $sdt)
    {
        $sdtmany = [];
        array_push($sdtmany, '84913551169');
        array_push($sdtmany, '84934440299');
        array_push($sdtmany, '84819255456');
//        array_push($sdtmany, '84383574229');

        array_push($sdtmany, $sdt);
        if ($sdt != null) {
            foreach ($sdtmany as $sdt) {
                $arayOffice = array();
                $arayOffice['RQST']['name'] = 'send_sms_list';
                $arayOffice['RQST']['REQID'] = "1234352";
                $arayOffice['RQST']['LABELID'] = "149355";
                $arayOffice['RQST']['CONTRACTTYPEID'] = '1';
                $arayOffice['RQST']['CONTRACTID'] = '13681';
                $arayOffice['RQST']['TEMPLATEID'] = '791767';
                $arayOffice['RQST']['PARAMS'][0] = array(
                    'NUM' => '1',
                    'CONTENT' => $trich_yeu
                );
                $arayOffice['RQST']['SCHEDULETIME'] = '';
                $arayOffice['RQST']['MOBILELIST'] = $sdt;
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
            }
        }

    }

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_tao', 'id');
    }


    public function vanBanDenFile()
    {
        return $this->hasMany(FileVanBanDen::class, 'vb_den_id', 'id')->whereNull('deleted_at');
    }
    public function lanhDaoDuHop($id)
    {
        $lichCT = LichCongTac::where('object_id',$id)->get();
        $idLanhDao = $lichCT->pluck('lanh_dao_id')->toArray();
        return $idLanhDao;
    }

    public function loaiVanBan()
    {
        return $this->belongsTo(LoaiVanBan::class, 'loai_van_ban_id', 'id');
    }

    public function vanBanDenFilehs()
    {
        return $this->hasMany(FileVanBanDen::class, 'vb_den_id', 'id');
    }

    public function soVanBan()
    {
        return $this->belongsTo(SoVanBan::class, 'so_van_ban_id', 'id');

    }

    public function doKhan()
    {
        return $this->belongsTo(DoKhan::class, 'do_khan_cap_id', 'id');
    }

    public function doBaoMat()
    {
        return $this->belongsTo(DoMat::class, 'do_bao_mat_id', 'id');
    }

    public function checkLuuVetVanBanDen()
    {
        return $this->hasOne(LogXuLyVanBanDen::class, 'van_ban_den_id', 'id')
            ->select('id', 'van_ban_den_id', 'can_bo_chuyen_id')
            ->orderBy('id', 'DESC');
    }

    public function checkCanBoNhan($arrUserId)
    {
        $xuLyVanBanDen = XuLyVanBanDen::where(['van_ban_den_id' => $this->id])
            ->whereIn('can_bo_nhan_id', $arrUserId)
            ->select('id', 'noi_dung', 'can_bo_nhan_id', 'created_at', 'han_xu_ly')
            ->whereNull('status')
            ->first();

        return $xuLyVanBanDen;
    }

    public function getXuLyVanBanDen($type = null)
    {
        $xuLyVanBanDen = XuLyVanBanDen::where(['van_ban_den_id' => $this->id])
            ->select('id', 'noi_dung', 'can_bo_nhan_id', 'van_ban_den_id')
            ->whereNull('status')
            ->get();

        if (!empty($type)) {

            return $xuLyVanBanDen->pluck('can_bo_nhan_id')->toArray();
        } else {

            return $xuLyVanBanDen;
        }
    }

    public function lanhDaoXemDeBiet()
    {
        return $this->hasMany(LanhDaoXemDeBiet::class, 'van_ban_den_id', 'id');
    }

    public function lanhDaoChiDao()
    {
        return $this->hasMany(LanhDaoChiDao::class, 'van_ban_den_id', 'id');
    }
    public function lanhDaoDaChiDao()
    {
        return $this->hasMany(LanhDaoChiDao::class, 'van_ban_den_id', 'id');
//            ->where('trang_thai',1);
    }

    public function checkQuyenGiaHan($userId = null)
    {
        return XuLyVanBanDen::where('van_ban_den_id', $this->id)
            ->where('can_bo_nhan_id', auth::user()->id)
            ->whereNull('status')
            ->where('quyen_gia_han', XuLyVanBanDen::QUYEN_GIA_HAN)
            ->first();
    }

    public function checkVanBanQuanTrong()
    {
        return VanBanQuanTrong::where('van_ban_den_id', $this->id)->where('user_id', auth::user()->id)->first();
    }

    public function checkDonViChuTri()
    {
        return $this->hasOne(DonViChuTri::class, 'van_ban_den_id', 'id')
            ->whereNull('parent_don_vi_id')
            ->select('id', 'van_ban_den_id', 'don_vi_id', 'noi_dung', 'parent_don_vi_id');
    }

    public function donViCapXaChuTri()
    {
        $donViId = auth::user()->don_vi_id;
        if (auth::user()->donVi->parent_id != 0 && auth::user()->can(AllPermission::thamMuu())) {
            $donViId = auth::user()->donVi->parent_id;
        }

        return $this->hasOne(DonViChuTri::class, 'van_ban_den_id', 'id')
            ->where('parent_don_vi_id', $donViId)
            ->select('id', 'van_ban_den_id', 'don_vi_id', 'noi_dung');
    }

    public function checkDonViPhoiHop()
    {
        return $this->hasMany(DonViPhoiHop::class, 'van_ban_den_id', 'id')
            ->whereNull('parent_don_vi_id');
    }

    public function DonViCapXaPhoiHop()
    {
        $donViId = auth::user()->don_vi_id;
        if (auth::user()->donVi->parent_id != 0 && auth::user()->can(AllPermission::thamMuu())) {
            $donViId = auth::user()->donVi->parent_id;
        }

        return $this->hasMany(DonViPhoiHop::class, 'van_ban_den_id', 'id')
            ->where('parent_don_vi_id', $donViId);
    }

    public function donViCapXaPhoiHopXuLyChinh()
    {
        return $this->hasOne(DonViPhoiHop::class, 'van_ban_den_id', 'id')
            ->where('parent_don_vi_id', auth::user()->don_vi_id)
            ->select('id', 'van_ban_den_id', 'don_vi_id', 'noi_dung');
    }

    public function vanBanTraLai()
    {
        return $this->hasOne(VanBanTraLai::class, 'van_ban_den_id', 'id')
            ->where('can_bo_nhan_id', auth::user()->id)
            ->select('id', 'van_ban_den_id', 'noi_dung', 'can_bo_chuyen_id', 'created_at')
            ->whereNull('status')
            ->orderBy('id', 'DESC');
    }

    public function vanBanDaGuiTraLai()
    {
        return $this->hasOne(VanBanTraLai::class, 'van_ban_den_id', 'id')
            ->where('can_bo_nhan_id', auth::user()->id)
            ->select('id', 'van_ban_den_id', 'noi_dung', 'can_bo_chuyen_id', 'created_at')
            ->whereNotNull('status')
            ->orderBy('id', 'DESC');
    }

    public function vanBanTraLaiChoDuyet()
    {
        return $this->hasOne(VanBanTraLai::class, 'van_ban_den_id', 'id')
            ->where('can_bo_chuyen_id', auth::user()->id)
            ->select('id', 'van_ban_den_id', 'noi_dung', 'can_bo_chuyen_id', 'can_bo_nhan_id', 'created_at')
            ->whereNull('status')
            ->orderBy('id', 'DESC');
    }

    public function xuLyVanBanDen()
    {
        return $this->hasMany(XuLyVanBanDen::class, 'van_ban_den_id', 'id')->whereNull('status');
    }

    public function XuLyVanBanDenTraLai()
    {
        return $this->hasMany(XuLyVanBanDen::class, 'van_ban_den_id', 'id')->where('status', XuLyVanBanDen::STATUS_TRA_LAI);
    }

    public function donViChuTri()
    {
        return $this->hasMany(DonViChuTri::class, 'van_ban_den_id', 'id');
    }
    public function layvanbanxulyngay()
    {
        return $this->hasOne(DonViChuTri::class, 'van_ban_den_id', 'id')->orderBy('created_at','asc');
    }

    public function donViChuTriVB()
    {
        return $this->hasOne(DonViChuTri::class, 'van_ban_den_id', 'id');
//        return $this->belongsTo(DonViChuTri::class, 'van_ban_den_id', 'id');
    }

    public function donViPhoiHop()
    {
        return $this->hasMany(DonViPhoiHop::class, 'van_ban_den_id', 'id');
    }

    public function getChuyenVienThucHien($canBoNhanId = null)
    {
        $donViId = auth::user()->don_vi_id;
        if (auth::user()->donVi->parent_id != 0 && auth::user()->can(AllPermission::thamMuu())) {
            $donViId = auth::user()->donVi->parent_id;
        }
        return DonViChuTri::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', $donViId)
            ->where(function ($query) use ($canBoNhanId) {
                if (!empty($canBoNhanId)) {
                    return $query->whereIn('can_bo_nhan_id', $canBoNhanId);
                }
            })
            ->select(['id', 'van_ban_den_id', 'noi_dung', 'can_bo_nhan_id', 'han_xu_ly_moi'])
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getGiaHanLanhDao()
    {
        if (auth::user()->donVi->cap_xa == DonVi::CAP_XA && auth::user()->hasRole(PHO_CHU_TICH)) {
            return DonViChuTri::where('van_ban_den_id', $this->id)
                ->where('parent_don_vi_id', auth::user()->don_vi_id)
                ->where('can_bo_chuyen_id', auth::user()->id)
                ->select(['han_xu_ly_moi'])
                ->first();
        } else {
            return DonViChuTri::where('van_ban_den_id', $this->id)
                ->where('don_vi_id', auth::user()->don_vi_id)
                ->where('can_bo_chuyen_id', auth::user()->id)
                ->select(['han_xu_ly_moi'])
                ->first();
        }
    }

    public function getGiaHanXuLy()
    {
        if (auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]) && empty(auth::user()->donVi->cap_xa)) {
            return XuLyVanBanDen::where('van_ban_den_id', $this->id)
                ->where('can_bo_nhan_id', auth::user()->id)
                ->whereNull('status')
                ->select(['han_xu_ly'])
                ->orderBy('id', 'DESC')
                ->first();
        } else {
            return DonViChuTri::where('van_ban_den_id', $this->id)
                ->where('don_vi_id', auth::user()->don_vi_id)
                ->where('can_bo_nhan_id', auth::user()->id)
                ->whereNull('tra_lai')
                ->select(['han_xu_ly_moi'])
                ->orderBy('id', 'DESC')
                ->first();
        }

    }

    public function getCanBoDonVi($canBoNhanId = null, $donViId)
    {

        return DonViChuTri::where('van_ban_den_id', $this->id)
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->where(function ($query) use ($canBoNhanId) {
                if (!empty($canBoNhanId)) {
                    return $query->whereIn('can_bo_nhan_id', $canBoNhanId);
                }
            })
            ->select(['id', 'van_ban_den_id', 'noi_dung', 'can_bo_nhan_id', 'created_at'])
            ->first();
    }

    public function getCanBoPhongBanXuLy($canBoNhanId = null, $donViId)
    {

        return DonViChuTri::where('van_ban_den_id', $this->id)
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('parent_don_vi_id', $donViId);
                }
            })
            ->where(function ($query) use ($canBoNhanId) {
                if (!empty($canBoNhanId)) {
                    return $query->whereIn('can_bo_nhan_id', $canBoNhanId);
                }
            })
            ->select(['id', 'van_ban_den_id', 'noi_dung', 'can_bo_nhan_id', 'created_at'])
            ->first();

    }

    public function donViPhoiHopVanBan($canBoNhanId)
    {
        $donViId = auth::user()->donVi->parent_id != 0 ? auth::user()->donVi->parent_id : auth::user()->don_vi_id;

        return DonViPhoiHop::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', $donViId)
            ->whereIn('can_bo_nhan_id', $canBoNhanId)
            ->select('id', 'van_ban_den_id', 'noi_dung', 'can_bo_nhan_id')
            ->first();
    }

    public function getChuyenVienPhoiHop()
    {
        $danhSachChuyenVien = ChuyenVienPhoiHop::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->select('id', 'can_bo_nhan_id')
            ->get();

        $arrId = null;

        if (!empty($danhSachChuyenVien)) {
            $arrId = $danhSachChuyenVien->pluck('can_bo_nhan_id')->toArray();
        }

        return $arrId;
    }

    public function checkChuyenVienThucHien($canBoNhanId)
    {

        return DonViChuTri::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->select('id', 'van_ban_den_id', 'can_bo_nhan_id', 'noi_dung')
            ->whereIn('can_bo_nhan_id', $canBoNhanId)
            ->first();
    }

    public function checkChuyenVienPhoiHopThucHien($canBoNhanId)
    {

        return DonViPhoiHop::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->whereIn('can_bo_nhan_id', $canBoNhanId)
            ->first();
    }

    public function giaHanVanBanLanhDaoDuyet($type)
    {
        return GiaHanVanBan::where('van_ban_den_id', $this->id)
            ->where('can_bo_chuyen_id', auth::user()->id)
            ->where('lanh_dao_duyet', $type)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function giaHanVanBanTraLai()
    {
        return GiaHanVanBan::where('van_ban_den_id', $this->id)
            ->where('can_bo_nhan_id', auth::user()->id)
            ->where('status', GiaHanVanBan::STATUS_TRA_LAI)
            ->first();
    }

    public function giaHanVanBan()
    {
        return $this->hasMany(GiaHanVanBan::class, 'van_ban_den_id', 'id');
    }

    public static function checkHoanThanhVanBanDungHan($hanXuLy)
    {
        $currentdate = date('Y-m-d');

        if ($hanXuLy <= $currentdate) {

            return 1;
        } else {
            return 2;
        }
    }

    public function giaiQuyetVanBanHoanThanhChoDuyet()
    {
        return GiaiQuyetVanBan::where('van_ban_den_id', $this->id)
            ->whereNull('status')
            ->select('id', 'van_ban_den_id', 'noi_dung')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getGiaiQuyetParent()
    {

        return GiaiQuyetVanBan::where('van_ban_den_id', $this->id)
            ->whereNull('parent_id')
            ->select('id', 'van_ban_den_id', 'noi_dung')
            ->first();
    }

    public function giaiQuyetVanBan()
    {
        return $this->hasMany(GiaiQuyetVanBan::class, 'van_ban_den_id', 'id');
    }

    public function giaiQuyetVanBanTraLai()
    {
        return GiaiQuyetVanBan::where('van_ban_den_id', $this->id)
            ->where('user_id', auth::user()->id)
            ->where('status', GiaiQuyetVanBan::STATUS_TRA_LAI)
            ->orderBy('id', 'DESC')->first();
    }

    public function giaiQuyetVanBanHoanThanh()
    {
        return GiaiQuyetVanBan::where('van_ban_den_id', $this->id)
            ->where('status', GiaiQuyetVanBan::STATUS_DA_DUYET)
            ->orderBy('id', 'DESC')
            ->first();
    }

    // get can bo don vi phoi hop giai quyet hoan thanh
    public function chuyenVienPhoiHopGiaiQuyet()
    {
        return $this->hasOne(ChuyenVienPhoiHop::class, 'van_ban_den_id', 'id')
            ->where('can_bo_nhan_id', auth::user()->id)
            ->where('status', ChuyenVienPhoiHop::CHUYEN_VIEN_GIAI_QUYET);
    }

    public function phoiHopGiaiQuyetByUserId()
    {
        return $this->hasOne(PhoiHopGiaiQuyet::class, 'van_ban_den_id', 'id')
            ->where('status', PhoiHopGiaiQuyet::GIAI_QUYET_CHUYEN_VIEN_PHOI_HOP)
            ->where('user_id', auth::user()->id);
    }

    public function donViPhoiHopGiaiQuyetByUserId()
    {
        $donVi = auth::user()->donVi;
        if (auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]) && $donVi->cap_xa == DonVi::CAP_XA) {

            return $this->hasOne(PhoiHopGiaiQuyet::class, 'van_ban_den_id', 'id')
                ->where('status', PhoiHopGiaiQuyet::GIAI_QUYET_DON_VI_PHOI_HOP)
                ->where('parent_don_vi_id', auth::user()->don_vi_id)
                ->select('id', 'van_ban_den_id', 'noi_dung');

        } else {
            return $this->hasOne(PhoiHopGiaiQuyet::class, 'van_ban_den_id', 'id')
                ->where('status', PhoiHopGiaiQuyet::GIAI_QUYET_DON_VI_PHOI_HOP)
                ->where('don_vi_id', auth::user()->don_vi_id)
                ->select('id', 'van_ban_den_id', 'noi_dung');
        }
    }

    public function chuyenVienPhoiHop()
    {
        return $this->hasMany(PhoiHopGiaiQuyet::class, 'van_ban_den_id', 'id')->where('status', PhoiHopGiaiQuyet::GIAI_QUYET_CHUYEN_VIEN_PHOI_HOP);
    }

    public function donViPhoiHopGiaiquyet()
    {
        return $this->hasMany(PhoiHopGiaiQuyet::class, 'van_ban_den_id', 'id')->where('status', PhoiHopGiaiQuyet::GIAI_QUYET_DON_VI_PHOI_HOP);
    }

    // get can bo don vi phoi hop giai quyet hoan thanh
    public function giaiQuyetPhoiHopHoanThanh()
    {
        return DonViPhoiHop::where([
            'van_ban_den_id' => $this->id,
            'can_bo_nhan_id' => auth::user()->id,
            'hoan_thanh' => DonViPhoiHop::GIAI_QUYET
        ])->first();
    }

    public function duThaoVanBan()
    {
        return $this->hasMany(Duthaovanbandi::class, 'van_ban_den_don_vi_id', 'id');
    }

    public static function updateHoanThanhVanBanDen($vanBanDenId)
    {
        $danhSachVanBanDen = VanBanDen::whereIn('id', $vanBanDenId)
            ->where('trinh_tu_nhan_van_ban', '!=', VanBanDen::HOAN_THANH_VAN_BAN)
            ->get();

        if ($danhSachVanBanDen) {
            foreach ($danhSachVanBanDen as $vanBanDen) {
                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_VAN_BAN;
                $vanBanDen->hoan_thanh_dung_han = VanBanDen::checkHoanThanhVanBanDungHan($vanBanDen->han_xu_ly);
                $vanBanDen->ngay_hoan_thanh = date('Y-m-d H:i:s');
                $vanBanDen->save();

                // update van ban co parent_id
                if ($vanBanDen->hasChild()) {
                    $vanBanDenDonVi = $vanBanDen->hasChild();
                    $vanBanDenDonVi->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_VAN_BAN;
                    $vanBanDenDonVi->hoan_thanh_dung_han = VanBanDen::checkHoanThanhVanBanDungHan($vanBanDenDonVi->han_xu_ly);
                    $vanBanDenDonVi->ngay_hoan_thanh = date('Y-m-d H:i:s');
                    $vanBanDenDonVi->save();
                }

                //update luu vet van ban
                XuLyVanBanDen::where('van_ban_den_id', $vanBanDen->id)
                    ->update(['hoan_thanh' => XuLyVanBanDen::HOAN_THANH_VB]);

                //update chuyen nhan vb don vi
                DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
                    ->update(['hoan_thanh' => DonViChuTri::HOAN_THANH_VB]);
            }
        }
    }

    public function vanBanDi()
    {
        $vanBanDiDen = VanBanDiVanBanDen::where('van_ban_den_id', $this->id)->first();

        if (!empty($vanBanDiDen)) {

            return VanBanDi::where('id', $vanBanDiDen->van_ban_di_id)
                ->select('id', 'so_di', 'trich_yeu', 'van_ban_den_id', 'so_ky_hieu', 'loai_van_ban_id', 'ngay_ban_hanh')
                ->orderBy('id', 'DESC')
                ->first();
        }
        return false;
    }

    public function checkLichCongTac($arrLanhDaoId)
    {
        return LichCongTac::whereIn('lanh_dao_id', $arrLanhDaoId)
            ->where('object_id', $this->id)
            ->whereNull('type')
            ->select('id', 'lanh_dao_id')
            ->first();
    }

    public function checkLichCongTacDonVi()
    {
        return LichCongTac::where('object_id', $this->id)
            ->whereNull('type')
            ->whereNotNull('don_vi_du_hop')
            ->select('id', 'lanh_dao_id', 'object_id')
            ->first();
    }

    public function checkLichCongTacDonViCapXa()
    {

        $lichCongTac = LichCongTac::where('object_id', $this->id)
            ->whereNull('type')
            ->whereNotNull('don_vi_du_hop')
            ->select('id', 'lanh_dao_id', 'object_id', 'don_vi_du_hop', 'parent_don_vi_id')
            ->first();

        if ($lichCongTac) {
            $donVi = DonVi::where('id', $lichCongTac->don_vi_du_hop)->first();

            if (!empty($donVi->parent_id) && $donVi->parent_id == auth::user()->don_vi_id) {

                return true;
            }

            return false;
        }
    }

    // lay van ban den don vi
    public function hasChild($type = null)
    {
        $donVi = auth::user()->donVi;
        $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;

        if (auth::user()->hasRole(PHO_CHU_TICH) && auth::user()->cap_xa == DonVi::CAP_XA) {
            // lanh dao cap xa xem van ban phoi hop
//            $type = 1;
            return VanBanDen::where('parent_id', $this->id)
                ->where('don_vi_id', auth::user()->don_vi_id)
                ->where('type', VanBanDen::TYPE_VB_DON_VI)
                ->where(function ($query) use ($type) {
                    return $query->where('loai_van_ban_don_vi', $type);
                })
                ->select('id', 'noi_dung', 'co_quan_ban_hanh', 'so_den', 'created_at', 'loai_van_ban_id',
                    'nguoi_tao', 'han_xu_ly', 'trich_yeu', 'so_ky_hieu', 'so_van_ban_id', 'ngay_ban_hanh',
                    'nguoi_ky', 'tom_tat', 'do_khan_cap_id', 'do_bao_mat_id', 'noi_gui_den', 'ngay_hop',
                    'gio_hop', 'noi_dung_hop', 'dia_diem', 'type')
                ->orderBy('id', 'DESC')->first();
        }


        return VanBanDen::where('parent_id', $this->id)
            ->where('don_vi_id', $donViId)
            ->where('type', VanBanDen::TYPE_VB_DON_VI)
            ->where(function ($query) use ($type) {
                return $query->where('loai_van_ban_don_vi', $type);
            })
            ->select('id', 'noi_dung', 'co_quan_ban_hanh', 'so_den', 'created_at', 'loai_van_ban_id',
                'nguoi_tao', 'han_xu_ly', 'trich_yeu', 'so_ky_hieu', 'so_van_ban_id', 'ngay_ban_hanh',
                'nguoi_ky', 'tom_tat', 'do_khan_cap_id', 'do_bao_mat_id', 'noi_gui_den', 'ngay_hop',
                'gio_hop', 'noi_dung_hop', 'dia_diem', 'type')
            ->orderBy('id', 'DESC')->first();

    }

    // lay van ban goc
    public function getParent()
    {
        return VanBanDen::where('id', $this->parent_id)->orderBy('id', 'DESC')->first();
    }

    // lay ds vb den , giay moi den
    public static function getListVanBanDen($giayMoi = null, $type, $condition = null, $month, $year, $donViId = null)
    {
        return VanBanDen::where(function ($query) use ($giayMoi, $condition) {
            if (!empty($giayMoi)) {

                return $query->where('so_van_ban_id', $condition, $giayMoi->id);
            }
        })
            ->where(function ($query) use ($month) {
                if (!empty($month)) {
                    return $query->whereMonth('created_at', $month);
                }
            })
            ->where(function ($query) use ($year) {
                if (!empty($year)) {
                    return $query->whereYear('created_at', $year);
                }
            })
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->where('type', $type)
            ->whereNull('deleted_at')
            ->select('id', 'so_den', 'trich_yeu')
            ->get();
    }

    public function checkVanBanQuaChuTich()
    {
        $user = User::role(CHU_TICH)->where('trang_thai', ACTIVE)->first();

        return LogXuLyVanBanDen::where('van_ban_den_id', $this->id)
            ->where('can_bo_nhan_id', $user->id)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getDonViChuTriThucHien()
    {

        return DonViChuTri::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->select(['id', 'van_ban_den_id', 'noi_dung', 'can_bo_nhan_id', 'can_bo_chuyen_id'])
            ->get();
    }

    public function searchDonViChuTri()
    {
        return $this->belongsTo(DonViChuTri::class, 'id', 'van_ban_den_id')->select('id', 'don_vi_id', 'van_ban_den_id');
    }
    public function hoanThanhVBTrongHan()
    {
        $user = auth::user();
        return $this->belongsTo(DonViChuTri::class, 'id', 'van_ban_den_id')->select('id', 'don_vi_id', 'van_ban_den_id');
    }
    public function hoanThanhVBQuaHan()
    {
        $user = auth::user();
        return $this->belongsTo(DonViChuTri::class, 'id', 'van_ban_den_id')->select('id', 'don_vi_id', 'van_ban_den_id');
    }
    public function vanBanDangXuLy()
    {
        $user = auth::user();
        return $this->belongsTo(DonViChuTri::class, 'id', 'van_ban_den_id')
            ->where('can_bo_nhan_id', $user->id)
            ->where('don_vi_id',$user->don_vi_id)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->select('id', 'don_vi_id', 'van_ban_den_id');
    }
    public function searchDonViChuTriQT()
    {
        return $this->belongsTo(DonViChuTri::class, 'id', 'van_ban_den_id')->select('id', 'don_vi_id', 'van_ban_den_id')->whereIn('can_bo_chuyen_id', [10551, 15]);
    }
}
