<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class VanBanTraLai extends Model
{
    protected $table = 'dhvbd_van_ban_tra_lai';

    protected $fillable = [
        'van_ban_den_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'noi_dung',
        'type',
        'status'
    ];

    const TYPE_LANH_DAO = 1;
    const TYPE_DON_VI = 2;
    const STATUS_GIAI_QUYET = 1;

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }
}
