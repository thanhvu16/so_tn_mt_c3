<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GiaiQuyetVanBan extends Model
{
    protected $table = 'dhvbd_giai_quyet_van_ban';

    protected $fillable = [
        'van_ban_den_id',
        'van_ban_du_thao_id',
        'van_ban_di_id',
        'noi_dung',
        'noi_dung_nhan_xet',
        'user_id',
        'can_bo_duyet_id',
        'ngay_duyet',
        'status',
    ];

    CONST STATUS_DA_DUYET = 1;
    CONST STATUS_TRA_LAI = 2;


    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function giaiQuyetVanBanFile()
    {
        return $this->hasMany(GiaiQuyetVanBanFile::class, 'giai_quyet_van_ban_id', 'id');
    }

    public function canBoDuyet()
    {
        return $this->belongsTo(User::class, 'can_bo_duyet_id', 'id');
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 2:
                return '<span class="label label-danger">Trả lại</span>';
                break;

            case 1:
                return '<span class="label label-success">Đã duyệt</span>';
                break;

            default:
                return '<span class="label label-warning">Chờ duyệt</span>';
                break;
        }
    }
}
