<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;

class LogXuLyVanBanDen extends Model
{
    protected $table = 'dhvbd_log_xu_ly_van_ban_den';

    protected $fillable = [
        'van_ban_den_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'noi_dung',
        'don_vi_id',
        'don_vi_phoi_hop_id',
        'status',
        'tu_tham_muu',
        'user_id'
    ];
}
