<?php

namespace Modules\VanBanDen\Entities;

use App\Models\LichCongTac;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
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

    const CHU_TICH_NHAN_VB = 1;
    const PHO_CHU_TICH_NHAN_VB = 2;
    const TRUONG_PHONG_NHAN_VB = 3;
    const PHO_PHONG_NHAN_VB = 4;
    const CHUYEN_VIEN_NHAN_VB = 5;
    const HOAN_THANH_CHO_DUYET = 6;
    const HOAN_THANH_VAN_BAN = 7;
    const VB_TRA_LOI = 1;
    const DON_VI_DU_HOP = 1;

    const TYPE_VB_HUYEN = 1;
    const TYPE_VB_DON_VI = 2;

    const LOAI_VAN_BAN_DON_VI_PHOI_HOP = 1;

    protected $fillable = [];

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_tao', 'id');
    }

    public function vanBanDenFile()
    {
        return $this->hasMany(FileVanBanDen::class, 'vb_den_id', 'id')->whereNull('deleted_at');
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
            ->select('id', 'noi_dung', 'can_bo_nhan_id')
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
            ->select('id', 'van_ban_den_id', 'don_vi_id', 'noi_dung');
    }

    public function checkDonViPhoiHop()
    {
        return $this->hasMany(DonViPhoiHop::class, 'van_ban_den_id', 'id');
    }

    public function vanBanTraLai()
    {
        return $this->hasOne(VanBanTraLai::class, 'van_ban_den_id', 'id')
            ->where('can_bo_nhan_id', auth::user()->id)
            ->select('id', 'van_ban_den_id', 'noi_dung', 'can_bo_chuyen_id', 'created_at')
            ->whereNull('status')
            ->orderBy('id','DESC');
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

    public function donViPhoiHop()
    {
        return $this->hasMany(DonViPhoiHop::class, 'van_ban_den_id', 'id');
    }

    public function getChuyenVienThucHien($canBoNhanId = null)
    {

        return DonViChuTri::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->where(function ($query) use ($canBoNhanId) {
                if (!empty($canBoNhanId)) {
                    return $query->whereIn('can_bo_nhan_id', $canBoNhanId);
                }
            })
            ->select(['id', 'van_ban_den_id', 'noi_dung', 'can_bo_nhan_id'])
            ->first();
    }

    public function donViPhoiHopVanBan($canBoNhanId)
    {

        return DonViPhoiHop::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
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
        return $this->hasOne(PhoiHopGiaiQuyet::class, 'van_ban_den_id', 'id')
            ->where('status', PhoiHopGiaiQuyet::GIAI_QUYET_DON_VI_PHOI_HOP)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->select('id', 'van_ban_den_id', 'noi_dung');
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
        return  DonViPhoiHop::where([
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
        $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();

        if ($vanBanDen) {
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

    public function vanBanDi()
    {
        return $this->hasOne(VanBanDi::class, 'van_ban_den_id', 'id')
            ->orderBy('id', 'DESC');
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

    // lay van ban den don vi
    public function hasChild($type = null)
    {
        return VanBanDen::where('parent_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->where('type', VanBanDen::TYPE_VB_DON_VI)
            ->where(function ($query) use ($type) {
                return $query->where('loai_van_ban_don_vi', $type);
            })
            ->orderBy('id', 'DESC')->first();

    }

    // lay van ban goc
    public function getParent()
    {
        return VanBanDen::where('id', $this->parent_id)->orderBy('id', 'DESC')->first();
    }

    // lay ds vb den , giay moi den
    public static function getListVanBanDen($giayMoi=null, $type, $condition=null, $month, $year, $donViId = null)
    {
        return VanBanDen::where(function ($query) use ($giayMoi, $condition) {
            if (!empty($giayMoi)) {

                return $query ->where('so_van_ban_id', $condition, $giayMoi->id);
                }
            })
            ->where(function($query) use ($month) {
                if (!empty($month)) {
                    return $query->whereMonth('created_at', $month);
                }
            })
            ->where(function($query) use ($year) {
                if (!empty($year)) {
                    return $query->whereYear('created_at', $year);
                }
            })
            ->where(function($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->where('type', $type)
            ->whereNull('deleted_at')
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
}
