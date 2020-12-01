<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\VanBanDen\Entities\VanBanDen;

class DonViChuTri extends Model
{
    protected $table = 'dhvbd_don_vi_chu_tri';

    protected $fillable = [
        'van_ban_den_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'don_vi_id',
        'parent_id',
        'noi_dung',
        'don_vi_co_dieu_hanh',
        'vao_so_van_ban',
        'chuyen_tiep',
        'hoan_thanh'
    ];

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }

    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
    }
}
