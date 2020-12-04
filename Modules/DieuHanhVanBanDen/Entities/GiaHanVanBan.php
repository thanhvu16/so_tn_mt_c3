<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\VanBanDen\Entities\VanBanDen;

class GiaHanVanBan extends Model
{
    protected $table = 'dhvbd_gia_han_van_ban';

    protected $fillable = [
        'van_ban_den_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'parent_id',
        'noi_dung',
        'thoi_han_de_xuat',
        'thoi_han_cu',
        'status',
        'lanh_dao_duyet'
    ];

    const STATUS_CHO_DUYET = 1;
    const STATUS_TRA_LAI = 2;
    const STATUS_DA_DUYET = 3;

    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
    }

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 1:
                return '<span class="label label-warning">Chờ duyệt</span>';
                break;
            case 2:
                return '<span class="label label-danger">Trả lại</span>';
                break;

            case 3:
                return '<span class="label label-success">Đã duyệt</span>';
                break;
        }
    }
}
