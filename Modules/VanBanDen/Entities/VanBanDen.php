<?php

namespace Modules\VanBanDen\Entities;

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
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Auth;
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
        return $this->hasOne(LogXuLyVanBanDen::class, 'van_ban_den_id', 'id')->orderBy('id', 'DESC');
    }

    public function checkCanBoNhan($arrUserId)
    {
        $xuLyVanBanDen = XuLyVanBanDen::where(['van_ban_den_id' => $this->id])
            ->whereIn('can_bo_nhan_id', $arrUserId)
            ->whereNull('status')
            ->first();

        return $xuLyVanBanDen;
    }

    public function lanhDaoXemDeBiet()
    {
        return $this->hasMany(LanhDaoXemDeBiet::class, 'van_ban_den_id', 'id');
    }

    public function checkQuyenGiaHan($userId)
    {
        return XuLyVanBanDen::where('van_ban_den_id', $this->id)
            ->where('can_bo_nhan_id', $userId)
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
        return $this->hasOne(DonViChuTri::class, 'van_ban_den_id', 'id');
    }

    public function checkDonViPhoiHop()
    {
        return $this->hasMany(DonViPhoiHop::class, 'van_ban_den_id', 'id');
    }

    public function vanBanTraLai()
    {
        return $this->hasOne(VanBanTraLai::class, 'van_ban_den_id', 'id')
            ->where('can_bo_nhan_id', auth::user()->id)
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

    public function getChuyenVienThucHien($canBoNhanId)
    {

        return DonViChuTri::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->where('can_bo_nhan_id', $canBoNhanId)
            ->first();
    }

    public function getChuyenVienPhoiHop()
    {
        $danhSachChuyenVien = ChuyenVienPhoiHop::where('van_ban_den_id', $this->id)
            ->where('don_vi_id', auth::user()->don_vi_id)
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
            ->orderBy('id', 'DESC')
            ->first();
    }

}
