<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoVanBan extends Model
{
    use SoftDeletes;

    protected $table = 'so_van_ban';
    protected $fillable = [
        'ten_don_vi',
        'ten_viet_tat',
        'mo_ta',
        'loai_so',
        'so_don_vi'
    ];
}
