<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoaiVanBan extends Model
{
    use SoftDeletes;

    protected $table = 'loai_van_ban';

    protected $fillable = [
        'ten_loai_van_ban',
        'ten_viet_tat',
        'mo_ta',
        'loai_van_ban',
        'loai_don_vi'
    ];
    public function donvi()
    {
        return $this->hasOne(Donvi::class, 'id', 'loai_don_vi');
    }
}

