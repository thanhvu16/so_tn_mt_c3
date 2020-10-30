<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;

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
}
