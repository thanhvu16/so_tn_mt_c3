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
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
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
}
