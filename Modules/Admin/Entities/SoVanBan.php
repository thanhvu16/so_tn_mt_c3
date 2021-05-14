<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoVanBan extends Model
{
    use SoftDeletes;

    protected $table = 'so_van_ban';

    protected $fillable = [
        'ten_so_van_ban',
        'ten_viet_tat',
        'mo_ta',
        'loai_so',
        'so_don_vi'
    ];
    public function donvi()
    {
        return $this->hasOne(Donvi::class, 'id', 'so_don_vi');
    }
}

