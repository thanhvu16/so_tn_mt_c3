<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\VanBanDen\Entities\VanBanDen;

class XuLyVanBanDen extends Model
{
    protected $table = 'dhvbd_xu_ly_van_ban_den';

    protected $fillable = [
        'van_ban_den_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'noi_dung',
        'tom_tat',
        'status',
        'tu_tham_muu',
        'han_xu_ly',
        'lanh_dao_chi_dao',
        'quyen_gia_han',
        'hoan_thanh',
        'van_ban_quan_trong',
        'user_id',
    ];

    const STATUS_TRA_LAI = 1;
    const TU_THAM_MUU = 1;
    const TU_VAN_THU = 2;
    const QUYEN_GIA_HAN = 1;
    const LA_CAN_BO_CHI_DAO = 1;
    const HOAN_THANH_VB = 1;


    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id')->select('id', 'ho_ten');;
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id')->select('id', 'ho_ten', 'chuc_vu_id');
    }

    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
    }

    public static function luuXuLyVanBanDen($dataXuLyVanBanDen)
    {

        $xuLyVanBanDen = new XuLyVanBanDen();
        $xuLyVanBanDen->fill($dataXuLyVanBanDen);
        $xuLyVanBanDen->save();

        // luu vet van ban den
        LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataXuLyVanBanDen);
    }
}
