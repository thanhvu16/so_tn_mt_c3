<?php

namespace App\Models;

use App\User;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Illuminate\Database\Eloquent\Model;
use Modules\VanBanDi\Entities\VanBanDi;

class LichCongTac extends Model
{
    protected $table = 'dhvbd_lich_cong_tac';

    protected $fillable = [
        'object_id',
        'type',
        'lanh_dao_id',
        'ngay',
        'gio',
        'tuan',
        'noi_dung',
        'don_vi_id',
        'buoi',
        'dia_diem',
        'trang_thai_lich',
        'ghi chu',
        'user_id'
    ];

    const TYPE_VB_DI = 1;
    const TYPE_NHAP_TRUC_TIEP = 2;

    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'object_id', 'id')->whereNull('type');
    }

    public function lanhDao()
    {
        return $this->belongsTo(User::class, 'lanh_dao_id', 'id');
    }

    public static function checkLanhDaoDuHop($lanhDaoId)
    {
        return User::where('id', $lanhDaoId)->where('trang_thai', ACTIVE)->whereNull('deleted_at')->first();
    }

    public function chuanBiTruocCuocHop()
    {
        $xuLyVanBanDen = XuLyVanBanDen::where('van_ban_den_id', $this->object_id)
            ->where('can_bo_chuyen_id', $this->lanh_dao_id)->first();

        return $xuLyVanBanDen->id ?? null;
    }

    public function donViChuTri()
    {
        return DonViChuTri::where('van_ban_den_id', $this->object_id)->get();
    }

    public function vanBanDi()
    {
        return $this->belongsTo(VanBanDi::class, 'van_ban_den_id', 'id');
    }

//    public function congViecDonVi()
//    {
//        return $this->hasOne(CongViecDonVi::class, 'lich_cong_tac_id', 'id');
//    }
}
